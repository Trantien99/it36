<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderStatusHistory;
use App\Models\Settings;
use App\Models\Shipping;
use App\User;
use PDF;
use Notification;
use Helper;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use App\Notifications\StatusNotification;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $orders = Order::with('shipping')
            ->orderBy('id', 'DESC')
            ->paginate(10);

        $statusSummary = Order::query()
            ->selectRaw('status, COUNT(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        $paymentSummary = Order::query()
            ->selectRaw('payment_method, COUNT(*) as total')
            ->groupBy('payment_method')
            ->pluck('total', 'payment_method');

        $totalOrders = (int) $statusSummary->sum();
        $pendingOrders = (int) Order::whereIn('status', ['pending_confirmation', 'preparing', 'ready', 'shipping', 'delivery_failed', 'returning'])->count();
        $deliveredOrders = (int) Order::whereIn('status', ['delivery_success', 'completed'])->count();
        $cancelledOrders = (int) Order::whereIn('status', ['cancelled', 'returned', 'ended'])->count();
        $deliveredRevenue = (float) Order::query()
            ->whereIn('status', ['delivery_success', 'completed'])
            ->sum('total_amount');
        $averageOrderValue = $totalOrders > 0
            ? (float) Order::query()->avg('total_amount')
            : 0;
        $todayOrders = (int) Order::query()
            ->whereDate('created_at', now()->toDateString())
            ->count();
        $latestOrder = Order::with('shipping')
            ->latest('created_at')
            ->first();
        $fulfillmentRate = $totalOrders > 0
            ? round(($deliveredOrders / $totalOrders) * 100, 1)
            : 0;

        return view('backend.order.index', compact(
            'orders',
            'statusSummary',
            'paymentSummary',
            'totalOrders',
            'pendingOrders',
            'deliveredOrders',
            'cancelledOrders',
            'deliveredRevenue',
            'averageOrderValue',
            'todayOrders',
            'latestOrder',
            'fulfillmentRate'
        ));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $rules = [
            'first_name'=>'string|required',
            'last_name'=>'string|required',
            'address1'=>'string|required',
            'address2'=>'string|nullable',
            'country'=>'string|nullable',
            'coupon'=>'nullable|numeric',
            'phone'=>'numeric|required',
            'post_code'=>'string|nullable',
            'email'=>'string|required',
            'payment_method'=>'required|in:cod,paypal,momo'
        ];

        if (Shipping::count() > 0) {
            $rules['shipping'] = 'required|exists:shippings,id';
        }

        $this->validate($request, $rules);
        // return $request->all();

        if(empty(Cart::where('user_id',auth()->user()->id)->where('order_id',null)->first())){
            request()->session()->flash('error','Cart is Empty !');
            return back();
        }
        // $cart=Cart::get();
        // // return $cart;
        // $cart_index='ORD-'.strtoupper(uniqid());
        // $sub_total=0;
        // foreach($cart as $cart_item){
        //     $sub_total+=$cart_item['amount'];
        //     $data=array(
        //         'cart_id'=>$cart_index,
        //         'user_id'=>$request->user()->id,
        //         'product_id'=>$cart_item['id'],
        //         'quantity'=>$cart_item['quantity'],
        //         'amount'=>$cart_item['amount'],
        //         'status'=>'pending_confirmation',
        //         'price'=>$cart_item['price'],
        //     );

        //     $cart=new Cart();
        //     $cart->fill($data);
        //     $cart->save();
        // }

        // $total_prod=0;
        // if(session('cart')){
        //         foreach(session('cart') as $cart_items){
        //             $total_prod+=$cart_items['quantity'];
        //         }
        // }

        $order=new Order();
        $order_data=$request->all();
        $order_data['order_number']='ORD-'.strtoupper(Str::random(10));
        $order_data['user_id']=$request->user()->id;
        $order_data['shipping_id']=$request->shipping;
        $shipping=Shipping::where('id',$order_data['shipping_id'])->pluck('price');
        // return session('coupon')['value'];
        $order_data['sub_total']=Helper::totalCartPrice();
        $order_data['quantity']=Helper::cartCount();
        if(session('coupon')){
            $order_data['coupon']=session('coupon')['value'];
        }
        if($request->shipping){
            if(session('coupon')){
                $order_data['total_amount']=Helper::totalCartPrice()+$shipping[0]-session('coupon')['value'];
            }
            else{
                $order_data['total_amount']=Helper::totalCartPrice()+$shipping[0];
            }
        }
        else{
            if(session('coupon')){
                $order_data['total_amount']=Helper::totalCartPrice()-session('coupon')['value'];
            }
            else{
                $order_data['total_amount']=Helper::totalCartPrice();
            }
        }
        // return $order_data['total_amount'];
        $order_data['status'] = 'pending_confirmation';
        if(request('payment_method')=='paypal'){
            $order_data['payment_method']='paypal';
            $order_data['payment_status']='pending';
        }
        elseif(request('payment_method')=='momo'){
            $order_data['payment_method']='momo';
            $order_data['payment_status']='pending';
        }
        else{
            $order_data['payment_method']='cod';
            $order_data['payment_status']='unpaid';
        }
        $order->fill($order_data);
        $status=$order->save();
        if(!$status){
            request()->session()->flash('error','Could not create order. Please try again.');
            return back();
        }

        OrderStatusHistory::create([
            'order_id' => $order->id,
            'status' => $order->status,
            'payment_status' => $order->payment_status,
            'changed_by' => auth()->id(),
        ]);

        $request->user()->update([
            'checkout_first_name' => $request->first_name,
            'checkout_last_name' => $request->last_name,
            'checkout_phone' => $request->phone,
            'checkout_country' => $request->country,
            'checkout_address1' => $request->address1,
            'checkout_address2' => $request->address2,
            'checkout_post_code' => $request->post_code,
        ]);

        $users=User::where('role','admin')->first();
        $details=[
            'title'=>'Có đơn hàng mới',
            'actionURL'=>route('order.show',$order->id),
            'fas'=>'fa-file-alt'
        ];
        if($users){
            Notification::send($users, new StatusNotification($details));
        }
        if(request('payment_method')=='paypal'){
            return redirect()->route('payment')->with(['id'=>$order->id]);
        }
        elseif(request('payment_method')=='momo'){
            Cart::where('user_id', auth()->user()->id)->where('order_id', null)->update(['order_id' => $order->id]);
            return redirect()->route('momo.payment', $order->id);
        }
        else{
            session()->forget('cart');
            session()->forget('coupon');
        }
        Cart::where('user_id', auth()->user()->id)->where('order_id', null)->update(['order_id' => $order->id]);

        // dd($users);
        request()->session()->flash('success','Bạn đã đặt hàng thành công');
        return redirect()->route('order.success', $order->id);
    }

    /**
     * Display the order success screen.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function success($id)
    {
        $order = Order::where('id', $id)
            ->where('user_id', auth()->user()->id)
            ->firstOrFail();

        return view('frontend.pages.order-success', compact('order'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $order = Order::with(['shipping', 'cart_info.product'])->findOrFail($id);
        $settings = Settings::first();

        return view('backend.order.show', compact('order', 'settings'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $order = Order::with(['shipping', 'cart.product', 'statusHistory'])->findOrFail($id);
        return view('backend.order.edit')->with('order', $order);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $order = Order::with('cart.product')->findOrFail($id);
        $allowedStatuses = array_keys(Order::ORDER_STATUS_LABELS);
        $this->validate($request,[
            'status'=>'required|in:'.implode(',', $allowedStatuses),
            'payment_status'=>'nullable|in:'.implode(',', array_keys(Order::PAYMENT_STATUS_LABELS)),
        ]);
        $data = $request->only(['status', 'payment_status']);
        $previousStatus = trim((string) $order->status);
        $nextStatus = trim((string) $request->status);

        if (!in_array($nextStatus, Order::ORDER_STATUS_TRANSITIONS[$previousStatus] ?? [$previousStatus], true)) {
            request()->session()->flash('error', 'Trạng thái mới không hợp lệ với tiến trình hiện tại.');
            return redirect()->back()->withInput();
        }

        if ($nextStatus === 'completed' && $order->payment_status !== 'paid' && $request->input('payment_status') !== 'paid') {
            request()->session()->flash('error', 'Chỉ có thể hoàn thành đơn sau khi đã thanh toán hoặc đối soát.');
            return redirect()->back()->withInput();
        }

        if (in_array($nextStatus, ['cancelled', 'returned'], true) && $order->payment_status === 'paid') {
            $data['payment_status'] = 'refunded';
        }

        $status = DB::transaction(function () use ($order, $data, $previousStatus, $nextStatus) {
            if(in_array($nextStatus, Order::STOCK_DECREMENT_STATUSES, true) && !in_array($previousStatus, Order::STOCK_DECREMENT_STATUSES, true)){
                foreach($order->cart as $cart){
                    $product = $cart->product;
                    if (!$product) {
                        continue;
                    }

                    $product->stock -= $cart->quantity;
                    $product->save();
                }
            }

            $saved = $order->fill($data)->save();

            if ($saved && $previousStatus !== $nextStatus) {
                OrderStatusHistory::create([
                    'order_id' => $order->id,
                    'status' => $nextStatus,
                    'payment_status' => $order->payment_status,
                    'changed_by' => auth()->id(),
                ]);
            }

            return $saved;
        });
        if($status){
            request()->session()->flash('success','Cập nhật đơn hàng thành công');
        }
        else{
            request()->session()->flash('error','Có lỗi khi cập nhật đơn hàng');
        }
        return redirect()->route('order.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $order=Order::find($id);
        if($order){
            $status=$order->delete();
            if($status){
                request()->session()->flash('success','Xóa đơn hàng thành công');
            }
            else{
                request()->session()->flash('error','Đơn hàng không thể xóa');
            }
            return redirect()->route('order.index');
        }
        else{
            request()->session()->flash('error','Không tìm thấy đơn hàng');
            return redirect()->back();
        }
    }

    public function orderTrack(){
        return view('frontend.pages.order-track');
    }

    public function productTrackOrder(Request $request){
        $request->validate([
            'order_number' => 'required|string',
        ]);

        $order = Order::where('order_number', trim((string) $request->order_number))->first();

        if (!$order) {
            request()->session()->flash('error', 'Mã đơn hàng không hợp lệ, vui lòng thử lại.');
            return back();
        }

        if (auth()->user()->role !== 'admin' && $order->user_id !== auth()->id()) {
            request()->session()->flash('error', 'Mã đơn hàng không thuộc tài khoản của bạn.');
            return back();
        }

        $statusMap = [
            'pending_confirmation' => 'Đơn hàng đang chờ shop xác nhận.',
            'preparing' => 'Shop đang chuẩn bị hàng cho bạn.',
            'ready' => 'Đơn hàng đã sẵn sàng để bàn giao vận chuyển.',
            'shipping' => 'Đơn hàng đang được giao đến bạn.',
            'delivery_failed' => 'Giao hàng chưa thành công, đơn đang được xử lý tiếp.',
            'returning' => 'Đơn hàng đang được hoàn về shop.',
            'returned' => 'Shop đã nhận lại hàng hoàn.',
            'delivery_success' => 'Đơn hàng đã giao thành công.',
            'completed' => 'Đơn hàng đã hoàn thành.',
            'cancelled' => 'Đơn hàng đã bị hủy.',
            'ended' => 'Đơn hàng đã kết thúc.',
        ];

        $statusText = $statusMap[$order->status] ?? 'Đơn hàng đang được cập nhật trạng thái.';

        return view('frontend.pages.order-track', [
            'order' => $order,
            'statusText' => $statusText,
            'searched' => true,
        ]);
    }

    // PDF generate
    public function pdf(Request $request){
        if (!auth()->check()) {
            abort(403);
        }

        $query = Order::with('cart_info');

        if (auth()->user()->role !== 'admin') {
            $query->where('user_id', auth()->id());
        }

        $order = $query->findOrFail($request->id);
        $file_name=$order->order_number.'-'.$order->first_name.'.pdf';
        $pdf=PDF::loadview('backend.order.pdf',compact('order'));
        return $pdf->download($file_name);
    }
    // Income chart
    public function incomeChart(Request $request){
        $year=now()->year;
        $items=$this->incomeOrdersQuery()
            ->whereYear('created_at',$year)
            ->get()
            ->groupBy(function($d){
                return \Carbon\Carbon::parse($d->created_at)->format('m');
            });
        $result=$this->buildIncomeTotals($items);
        $data=[];
        for($i=1; $i <=12; $i++){
            $monthName=date('F', mktime(0,0,0,$i,1));
            $data[$monthName] = (!empty($result[$i]))? number_format((float)($result[$i]), 2, '.', '') : 0.0;
        }
        return $data;
    }

    // Income chart quarterly
    public function incomeChartQuarterly(Request $request){
        $year=now()->year;
        $items=$this->incomeOrdersQuery()
            ->whereYear('created_at',$year)
            ->get()
            ->groupBy(function($d){
                return \Carbon\Carbon::parse($d->created_at)->quarter;
            });
        $result=$this->buildIncomeTotals($items);
        $data=[];
        for($i=1; $i <=4; $i++){
            $data['Q'.$i] = (!empty($result[$i]))? number_format((float)($result[$i]), 2, '.', '') : 0.0;
        }
        return $data;
    }

    protected function incomeOrdersQuery()
    {
        $query = Order::with(['cart_info'])
            ->whereIn('status', ['delivery_success', 'completed']);

        if (auth()->user()->role !== 'admin') {
            $query->where('user_id', auth()->id());
        }

        return $query;
    }

    protected function buildIncomeTotals($groupedOrders)
    {
        $result = [];

        foreach ($groupedOrders as $bucket => $itemCollections) {
            foreach ($itemCollections as $item) {
                $amount = $item->cart_info->sum('amount');
                $bucketKey = (int) $bucket;
                isset($result[$bucketKey]) ? $result[$bucketKey] += $amount : $result[$bucketKey] = $amount;
            }
        }

        return $result;
    }
}
