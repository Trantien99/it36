<?php

namespace App\Http\Controllers;

use App\Models\Order;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class MomoController extends Controller
{
    public function payment(Order $order)
    {
        if (!auth()->check() || (int) $order->user_id !== (int) auth()->id()) {
            abort(403);
        }

        if (strtolower((string) $order->payment_method) !== 'momo') {
            request()->session()->flash('error', 'Đơn hàng này không dùng phương thức thanh toán MoMo.');
            return redirect()->route('user.order.show', $order->id);
        }

        if (strtolower((string) $order->payment_status) === 'paid') {
            request()->session()->flash('success', 'Đơn hàng này đã được thanh toán trước đó.');
            return redirect()->route('user.order.show', $order->id);
        }

        if (in_array((string) $order->status, ['cancelled', 'returned', 'ended'], true)) {
            request()->session()->flash('error', 'Đơn hàng đã kết thúc hoặc bị hủy, không thể thanh toán lại.');
            return redirect()->route('user.order.show', $order->id);
        }

        $momoConfig = $this->getMomoConfig();
        if ($this->hasMissingConfig($momoConfig)) {
            request()->session()->flash('error', 'Thiếu cấu hình MoMo test. Vui lòng cập nhật MOMO_PARTNER_CODE, MOMO_ACCESS_KEY và MOMO_SECRET_KEY trong file .env.');
            return redirect()->route('user.order.show', $order->id);
        }

        $payload = $this->buildCreatePayload($order, $momoConfig);
        $payload['signature'] = $this->signCreatePayload($payload, $momoConfig['access_key'], $momoConfig['secret_key']);

        try {
            $client = new Client([
                'timeout' => 15,
            ]);

            $response = $client->post($momoConfig['endpoint'], [
                'headers' => [
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json',
                ],
                'json' => $payload,
            ]);

            $responseData = json_decode((string) $response->getBody(), true);
        } catch (GuzzleException $exception) {
            Log::warning('MoMo create payment request failed', [
                'order_id' => $order->id,
                'message' => $exception->getMessage(),
            ]);

            request()->session()->flash('error', 'Không thể kết nối tới MoMo test lúc này. Bạn có thể thử lại từ trang chi tiết đơn hàng.');
            return redirect()->route('user.order.show', $order->id);
        }

        if (!is_array($responseData) || (int) data_get($responseData, 'resultCode', -1) !== 0 || empty($responseData['payUrl'])) {
            Log::warning('MoMo create payment returned unexpected response', [
                'order_id' => $order->id,
                'response' => $responseData,
            ]);

            $message = (string) data_get($responseData, 'message', 'MoMo chưa tạo được link thanh toán.');
            request()->session()->flash('error', $message);
            return redirect()->route('user.order.show', $order->id);
        }

        return redirect()->away($responseData['payUrl']);
    }

    public function returnUrl(Request $request)
    {
        $order = Order::where('order_number', (string) $request->input('orderId'))->first();
        if (!$order) {
            request()->session()->flash('error', 'Không tìm thấy đơn hàng MoMo tương ứng.');
            return redirect()->route('home');
        }

        $momoConfig = $this->getMomoConfig();
        if ($this->hasMissingConfig($momoConfig)) {
            request()->session()->flash('error', 'Không thể xác minh thanh toán MoMo vì thiếu cấu hình hệ thống.');
            return $this->redirectToOrder($order);
        }

        if (!$this->verifyCallbackSignature($request->all(), $momoConfig['access_key'], $momoConfig['secret_key'])) {
            Log::warning('MoMo return URL signature mismatch', [
                'order_id' => $order->id,
                'payload' => $request->all(),
            ]);

            request()->session()->flash('error', 'Không thể xác minh kết quả thanh toán MoMo. Vui lòng kiểm tra lại trạng thái đơn hàng.');
            return $this->redirectToOrder($order);
        }

        if (!$this->matchesOrderAmount($order, $request->input('amount'))) {
            Log::warning('MoMo return URL amount mismatch', [
                'order_id' => $order->id,
                'payload' => $request->all(),
                'expected_amount' => $order->total_amount,
            ]);

            request()->session()->flash('error', 'Số tiền MoMo trả về không khớp với đơn hàng.');
            return $this->redirectToOrder($order);
        }

        if ((int) $request->input('resultCode') === 0) {
            $this->markOrderAsPaid($order);
            session()->forget('cart');
            session()->forget('coupon');
            request()->session()->flash('success', 'Thanh toán MoMo test thành công.');
            return redirect()->route('order.success', $order->id);
        } else {
            $message = (string) $request->input('message', 'Thanh toán MoMo chưa hoàn tất hoặc đã bị hủy.');
            request()->session()->flash('error', $message);
        }

        return $this->redirectToOrder($order);
    }

    public function ipn(Request $request)
    {
        $order = Order::where('order_number', (string) $request->input('orderId'))->first();
        if (!$order) {
            Log::warning('MoMo IPN order not found', [
                'payload' => $request->all(),
            ]);

            return response()->json(['message' => 'Order not found'], 404);
        }

        $momoConfig = $this->getMomoConfig();
        if ($this->hasMissingConfig($momoConfig) || !$this->verifyCallbackSignature($request->all(), $momoConfig['access_key'], $momoConfig['secret_key'])) {
            Log::warning('MoMo IPN signature mismatch', [
                'order_id' => $order->id,
                'payload' => $request->all(),
            ]);

            return response()->json(['message' => 'Invalid signature'], 400);
        }

        if (!$this->matchesOrderAmount($order, $request->input('amount'))) {
            Log::warning('MoMo IPN amount mismatch', [
                'order_id' => $order->id,
                'payload' => $request->all(),
                'expected_amount' => $order->total_amount,
            ]);

            return response()->json(['message' => 'Amount mismatch'], 400);
        }

        if ((int) $request->input('resultCode') === 0) {
            $this->markOrderAsPaid($order);
        }

        return response()->noContent();
    }

    protected function getMomoConfig()
    {
        return [
            'partner_code' => trim((string) config('services.momo.partner_code')),
            'access_key' => trim((string) config('services.momo.access_key')),
            'secret_key' => trim((string) config('services.momo.secret_key')),
            'endpoint' => trim((string) config('services.momo.endpoint')),
            'store_name' => trim((string) config('services.momo.store_name')),
            'store_id' => trim((string) config('services.momo.store_id')),
            'lang' => trim((string) config('services.momo.lang', 'vi')),
            'request_type' => trim((string) config('services.momo.request_type', 'captureWallet')),
        ];
    }

    protected function hasMissingConfig(array $momoConfig)
    {
        return empty($momoConfig['partner_code']) || empty($momoConfig['access_key']) || empty($momoConfig['secret_key']) || empty($momoConfig['endpoint']);
    }

    protected function buildCreatePayload(Order $order, array $momoConfig)
    {
        $requestId = 'MOMO'.$order->id.time();
        $amount = (int) round((float) $order->total_amount);
        $extraData = base64_encode(json_encode([
            'order_id' => $order->id,
            'user_id' => $order->user_id,
        ], JSON_UNESCAPED_UNICODE));

        return [
            'partnerCode' => $momoConfig['partner_code'],
            'storeName' => $momoConfig['store_name'],
            'storeId' => $momoConfig['store_id'],
            'requestId' => $requestId,
            'amount' => (string) $amount,
            'orderId' => $order->order_number,
            'orderInfo' => 'Thanh toan don hang '.$order->order_number,
            'redirectUrl' => route('momo.return'),
            'ipnUrl' => route('momo.ipn'),
            'lang' => $momoConfig['lang'] ?: 'vi',
            'requestType' => $momoConfig['request_type'] ?: 'captureWallet',
            'autoCapture' => true,
            'extraData' => $extraData,
        ];
    }

    protected function signCreatePayload(array $payload, $accessKey, $secretKey)
    {
        $rawHash = 'accessKey='.$accessKey
            .'&amount='.$payload['amount']
            .'&extraData='.$payload['extraData']
            .'&ipnUrl='.$payload['ipnUrl']
            .'&orderId='.$payload['orderId']
            .'&orderInfo='.$payload['orderInfo']
            .'&partnerCode='.$payload['partnerCode']
            .'&redirectUrl='.$payload['redirectUrl']
            .'&requestId='.$payload['requestId']
            .'&requestType='.$payload['requestType'];

        return hash_hmac('sha256', $rawHash, $secretKey);
    }

    protected function verifyCallbackSignature(array $payload, $accessKey, $secretKey)
    {
        if (empty($payload['signature'])) {
            return false;
        }

        $rawHash = 'accessKey='.$accessKey
            .'&amount='.data_get($payload, 'amount', '')
            .'&extraData='.data_get($payload, 'extraData', '')
            .'&message='.data_get($payload, 'message', '')
            .'&orderId='.data_get($payload, 'orderId', '')
            .'&orderInfo='.data_get($payload, 'orderInfo', '')
            .'&orderType='.data_get($payload, 'orderType', '')
            .'&partnerCode='.data_get($payload, 'partnerCode', '')
            .'&payType='.data_get($payload, 'payType', '')
            .'&requestId='.data_get($payload, 'requestId', '')
            .'&responseTime='.data_get($payload, 'responseTime', '')
            .'&resultCode='.data_get($payload, 'resultCode', '')
            .'&transId='.data_get($payload, 'transId', '');

        return hash_equals($payload['signature'], hash_hmac('sha256', $rawHash, $secretKey));
    }

    protected function markOrderAsPaid(Order $order)
    {
        if (strtolower((string) $order->payment_status) === 'paid') {
            return;
        }

        $order->payment_method = 'momo';
        $order->payment_status = 'paid';
        $order->save();
    }

    protected function matchesOrderAmount(Order $order, $amount)
    {
        return (int) round((float) $order->total_amount) === (int) $amount;
    }

    protected function redirectToOrder(Order $order)
    {
        if (auth()->check() && (int) auth()->id() === (int) $order->user_id) {
            return redirect()->route('user.order.show', $order->id);
        }

        return redirect()->route('home');
    }
}
