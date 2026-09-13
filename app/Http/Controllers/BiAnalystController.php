<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Coupon;
use App\Models\Order;
use App\Models\Shipping;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class BiAnalystController extends Controller
{
    public function index(Request $request)
    {
        $range = $this->resolveRange($request);
        $endDate = Carbon::now()->endOfDay();
        $startDate = Carbon::now()->subDays($range - 1)->startOfDay();

        $deliveredOrders = Order::query()
            ->where('status', 'delivered')
            ->whereBetween('created_at', [$startDate, $endDate]);

        $deliveredOrderCount = (int) (clone $deliveredOrders)->count();
        $deliveredRevenue = (float) (clone $deliveredOrders)->sum('total_amount');
        $averageUnitsPerOrder = $deliveredOrderCount > 0
            ? (float) (clone $deliveredOrders)->avg('quantity')
            : 0;
        $multiItemOrders = (int) (clone $deliveredOrders)
            ->where('quantity', '>=', 2)
            ->count();

        $paymentMixRaw = (clone $deliveredOrders)
            ->select('payment_method')
            ->selectRaw('COUNT(*) as order_count')
            ->selectRaw('SUM(total_amount) as revenue')
            ->selectRaw('AVG(total_amount) as average_order_value')
            ->groupBy('payment_method')
            ->orderByDesc('revenue')
            ->get()
            ->keyBy('payment_method');

        $paymentMix = collect([
            ['key' => 'cod', 'label' => 'COD'],
            ['key' => 'paypal', 'label' => 'PayPal'],
            ['key' => 'momo', 'label' => 'MoMo'],
        ])->map(function (array $payment) use ($paymentMixRaw, $deliveredOrderCount, $deliveredRevenue) {
            $row = $paymentMixRaw->get($payment['key']);
            $orderCount = $row ? (int) $row->order_count : 0;
            $revenue = $row ? round((float) $row->revenue, 2) : 0;

            return [
                'key' => $payment['key'],
                'label' => $payment['label'],
                'order_count' => $orderCount,
                'revenue' => $revenue,
                'average_order_value' => $row ? round((float) $row->average_order_value, 2) : 0,
                'order_share' => $deliveredOrderCount > 0 ? round(($orderCount / $deliveredOrderCount) * 100, 1) : 0,
                'revenue_share' => $deliveredRevenue > 0 ? round(($revenue / $deliveredRevenue) * 100, 1) : 0,
            ];
        })->values();

        $couponMixRaw = (clone $deliveredOrders)
            ->selectRaw("CASE WHEN COALESCE(coupon, 0) > 0 THEN 'coupon' ELSE 'full_price' END as bucket")
            ->selectRaw('COUNT(*) as order_count')
            ->selectRaw('SUM(total_amount) as revenue')
            ->selectRaw('AVG(total_amount) as average_order_value')
            ->selectRaw('AVG(quantity) as average_units')
            ->groupBy('bucket')
            ->get()
            ->keyBy('bucket');

        $couponMix = collect([
            ['key' => 'coupon', 'label' => 'Dùng coupon'],
            ['key' => 'full_price', 'label' => 'Không dùng coupon'],
        ])->map(function (array $coupon) use ($couponMixRaw, $deliveredOrderCount, $deliveredRevenue) {
            $row = $couponMixRaw->get($coupon['key']);
            $orderCount = $row ? (int) $row->order_count : 0;
            $revenue = $row ? round((float) $row->revenue, 2) : 0;

            return [
                'key' => $coupon['key'],
                'label' => $coupon['label'],
                'order_count' => $orderCount,
                'revenue' => $revenue,
                'average_order_value' => $row ? round((float) $row->average_order_value, 2) : 0,
                'average_units' => $row ? round((float) $row->average_units, 1) : 0,
                'order_share' => $deliveredOrderCount > 0 ? round(($orderCount / $deliveredOrderCount) * 100, 1) : 0,
                'revenue_share' => $deliveredRevenue > 0 ? round(($revenue / $deliveredRevenue) * 100, 1) : 0,
            ];
        })->values();

        $basketMixRaw = (clone $deliveredOrders)
            ->selectRaw("
                CASE
                    WHEN quantity = 1 THEN 'single'
                    WHEN quantity = 2 THEN 'double'
                    WHEN quantity BETWEEN 3 AND 4 THEN 'bundle'
                    ELSE 'bulk'
                END as basket_band
            ")
            ->selectRaw('COUNT(*) as order_count')
            ->selectRaw('SUM(total_amount) as revenue')
            ->groupBy('basket_band')
            ->get()
            ->keyBy('basket_band');

        $basketMix = collect([
            ['key' => 'single', 'label' => '1 sản phẩm'],
            ['key' => 'double', 'label' => '2 sản phẩm'],
            ['key' => 'bundle', 'label' => '3-4 sản phẩm'],
            ['key' => 'bulk', 'label' => 'Từ 5 sản phẩm'],
        ])->map(function (array $band) use ($basketMixRaw, $deliveredOrderCount, $deliveredRevenue) {
            $row = $basketMixRaw->get($band['key']);
            $orderCount = $row ? (int) $row->order_count : 0;
            $revenue = $row ? round((float) $row->revenue, 2) : 0;

            return [
                'key' => $band['key'],
                'label' => $band['label'],
                'order_count' => $orderCount,
                'revenue' => $revenue,
                'order_share' => $deliveredOrderCount > 0 ? round(($orderCount / $deliveredOrderCount) * 100, 1) : 0,
                'revenue_share' => $deliveredRevenue > 0 ? round(($revenue / $deliveredRevenue) * 100, 1) : 0,
            ];
        })->values();

        $firstDeliveredOrders = Order::query()
            ->where('status', 'delivered')
            ->whereNotNull('user_id')
            ->selectRaw('user_id, MIN(created_at) as first_delivered_at')
            ->groupBy('user_id');

        $segmentExpression = "CASE WHEN first_delivered_orders.first_delivered_at >= '".$startDate->format('Y-m-d H:i:s')."' THEN 'new' ELSE 'returning' END";
        $customerSegmentsRaw = Order::query()
            ->joinSub($firstDeliveredOrders, 'first_delivered_orders', function ($join) {
                $join->on('orders.user_id', '=', 'first_delivered_orders.user_id');
            })
            ->where('orders.status', 'delivered')
            ->whereBetween('orders.created_at', [$startDate, $endDate])
            ->selectRaw($segmentExpression.' as segment')
            ->selectRaw('COUNT(DISTINCT orders.user_id) as customer_count')
            ->selectRaw('COUNT(*) as order_count')
            ->selectRaw('SUM(orders.total_amount) as revenue')
            ->groupBy('segment')
            ->get()
            ->keyBy('segment');

        $customerSegments = collect([
            ['key' => 'new', 'label' => 'Khách mới'],
            ['key' => 'returning', 'label' => 'Khách quay lại'],
        ])->map(function (array $segment) use ($customerSegmentsRaw, $deliveredRevenue) {
            $row = $customerSegmentsRaw->get($segment['key']);
            $revenue = $row ? round((float) $row->revenue, 2) : 0;
            $customerCount = $row ? (int) $row->customer_count : 0;
            $orderCount = $row ? (int) $row->order_count : 0;

            return [
                'key' => $segment['key'],
                'label' => $segment['label'],
                'customer_count' => $customerCount,
                'order_count' => $orderCount,
                'revenue' => $revenue,
                'average_order_value' => $orderCount > 0 ? round($revenue / $orderCount, 2) : 0,
                'revenue_share' => $deliveredRevenue > 0 ? round(($revenue / $deliveredRevenue) * 100, 1) : 0,
            ];
        })->values();

        $returningSegment = $this->findRow($customerSegments, 'key', 'returning');
        $couponOrders = $this->findRow($couponMix, 'key', 'coupon');
        $nonCouponOrders = $this->findRow($couponMix, 'key', 'full_price');
        $codMix = $this->findRow($paymentMix, 'key', 'cod');
        $singleBasket = $this->findRow($basketMix, 'key', 'single');

        $summaryCards = [
            [
                'label' => 'Tỷ trọng doanh thu khách quay lại',
                'value' => $returningSegment['revenue_share'],
                'format' => 'percent',
                'icon' => 'fa-user-clock',
                'note' => 'Tỷ trọng doanh thu đến từ khách đã từng mua trong '.$range.' ngày.',
            ],
            [
                'label' => 'Tỷ lệ dùng coupon',
                'value' => $couponOrders['order_share'],
                'format' => 'percent',
                'icon' => 'fa-ticket-alt',
                'note' => 'Tỷ lệ đơn giao thành công có sử dụng coupon.',
            ],
            [
                'label' => 'Đơn nhiều sản phẩm',
                'value' => $deliveredOrderCount > 0 ? round(($multiItemOrders / $deliveredOrderCount) * 100, 1) : 0,
                'format' => 'percent',
                'icon' => 'fa-box-open',
                'note' => 'Tỷ lệ đơn có từ 2 sản phẩm trở lên để đánh giá cơ hội combo.',
            ],
            [
                'label' => 'Sản phẩm trung bình/đơn',
                'value' => round($averageUnitsPerOrder, 1),
                'format' => 'number',
                'icon' => 'fa-balance-scale',
                'note' => 'Số sản phẩm trung bình trên mỗi đơn đã giao.',
            ],
        ];

        $commercialSignals = [];
        if ($returningSegment['revenue_share'] < 45) {
            $commercialSignals[] = [
                'title' => 'Retention đang mở',
                'description' => 'Doanh thu từ khách quay lại đang dưới 45%, nên ưu tiên chương trình kéo khách cũ quay lại mua thêm.',
            ];
        }

        if ($couponOrders['average_order_value'] > 0 && $nonCouponOrders['average_order_value'] > 0 && $couponOrders['average_order_value'] < ($nonCouponOrders['average_order_value'] * 0.9)) {
            $commercialSignals[] = [
                'title' => 'Coupon cần đặt ngưỡng',
                'description' => 'AOV của nhóm dùng coupon thấp hơn nhóm không dùng coupon trên 10%, nên xem lại ngưỡng tối thiểu để giữ biên giá trị đơn.',
            ];
        }

        if ($codMix['order_share'] > 70) {
            $commercialSignals[] = [
                'title' => 'Phụ thuộc COD cao',
                'description' => 'Hơn 70% đơn giao thành công đến từ COD, cần mở rộng ưu đãi cho thanh toán trước để giảm áp lực vận hành.',
            ];
        }

        if ($singleBasket['order_share'] > 55) {
            $commercialSignals[] = [
                'title' => 'Giỏ hàng nghiêng về 1 sản phẩm',
                'description' => 'Đơn 1 sản phẩm đang chiếm tỷ trọng lớn, có thể đẩy combo theo phụ kiện hoặc phân khúc cao hơn.',
            ];
        }

        if (empty($commercialSignals)) {
            $commercialSignals[] = [
                'title' => 'Mix kinh doanh khá cân bằng',
                'description' => 'Chưa thấy dấu hiệu lệch lớn về coupon, thanh toán hay cỡ giỏ hàng trong '.$range.' ngày gần đây.',
            ];
        }

        $activeCoupons = Coupon::query()
            ->where('status', 'active')
            ->orderBy('code')
            ->get(['id', 'code', 'type', 'value']);

        $simulatorInput = [
            'coupon_code' => (string) $request->query('scenario_coupon_code', optional($activeCoupons->first())->code),
            'coupon_reach' => $this->boundPercent((int) $request->query('scenario_coupon_reach', 25)),
            'free_ship_threshold' => max(0, (float) $request->query('scenario_free_ship_threshold', 2000000)),
            'cod_shift' => $this->boundPercent((int) $request->query('scenario_cod_shift', 15)),
        ];

        $selectedCoupon = $activeCoupons->firstWhere('code', $simulatorInput['coupon_code']);
        $scenarioOrders = Order::query()
            ->with('shipping')
            ->where('status', 'delivered')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->get();

        $simulatedRevenue = 0;
        $couponBudget = 0;
        $freeShipBudget = 0;
        $impactedOrderCount = 0;
        $freeShipEligibleCount = 0;
        $couponReachFactor = $simulatorInput['coupon_reach'] / 100;

        foreach ($scenarioOrders as $order) {
            $shippingCost = $order->shipping ? (float) $order->shipping->price : 0;
            $couponDiscount = $selectedCoupon
                ? min((float) $order->sub_total, (float) $selectedCoupon->discount((float) $order->sub_total)) * $couponReachFactor
                : 0;
            $freeShipDiscount = (float) $order->sub_total >= $simulatorInput['free_ship_threshold'] ? $shippingCost : 0;

            if ($freeShipDiscount > 0) {
                $freeShipEligibleCount++;
            }

            if ($couponDiscount > 0 || $freeShipDiscount > 0) {
                $impactedOrderCount++;
            }

            $couponBudget += $couponDiscount;
            $freeShipBudget += $freeShipDiscount;
            $simulatedRevenue += max(0, (float) $order->total_amount - $couponDiscount - $freeShipDiscount);
        }

        $codOrders = (clone $deliveredOrders)->where('payment_method', 'cod');
        $codRevenue = (float) (clone $codOrders)->sum('total_amount');
        $codOrderCount = (int) (clone $codOrders)->count();
        $shiftFactor = $simulatorInput['cod_shift'] / 100;
        $shiftedRevenue = round($codRevenue * $shiftFactor, 2);
        $shiftedOrders = (int) round($codOrderCount * $shiftFactor);
        $remainingCodRevenue = max(0, $codRevenue - $shiftedRevenue);

        $scenarioSummary = [
            [
                'label' => 'Doanh thu sau mô phỏng',
                'value' => $simulatedRevenue,
                'format' => 'currency',
                'note' => 'Doanh thu sau khi trừ ngân sách coupon và freeship.',
            ],
            [
                'label' => 'AOV sau mô phỏng',
                'value' => $deliveredOrderCount > 0 ? $simulatedRevenue / $deliveredOrderCount : 0,
                'format' => 'currency',
                'note' => 'Giá trị đơn trung bình của tập đơn hiện tại dưới kịch bản mới.',
            ],
            [
                'label' => 'Đơn chịu tác động',
                'value' => $impactedOrderCount,
                'format' => 'number',
                'note' => 'Số đơn được ước tính chạm coupon hoặc ngưỡng freeship.',
            ],
            [
                'label' => 'Tỷ trọng COD còn lại',
                'value' => $deliveredRevenue > 0 ? ($remainingCodRevenue / $deliveredRevenue) * 100 : 0,
                'format' => 'percent',
                'note' => 'Phần doanh thu vẫn còn ở COD sau khi giả định dịch chuyển thanh toán.',
            ],
        ];

        $scenarioAssumptions = [
            'selected_coupon' => $selectedCoupon
                ? $selectedCoupon->code.' ('.($selectedCoupon->type === 'percent' ? number_format($selectedCoupon->value, 0, ',', '.').'%' : number_format($selectedCoupon->value, 0, ',', '.').'đ').')'
                : 'Không áp coupon mới',
            'coupon_budget' => round($couponBudget, 2),
            'free_ship_budget' => round($freeShipBudget, 2),
            'free_ship_eligible_count' => $freeShipEligibleCount,
            'shifted_cod_orders' => $shiftedOrders,
            'shifted_revenue' => $shiftedRevenue,
        ];

        $winBackCustomers = Order::query()
            ->join('users', 'users.id', '=', 'orders.user_id')
            ->where('orders.status', 'delivered')
            ->select('users.id as user_id', 'users.name', 'users.email')
            ->selectRaw('COUNT(*) as order_count')
            ->selectRaw('SUM(orders.total_amount) as revenue')
            ->selectRaw('MAX(orders.created_at) as last_order_at')
            ->groupBy('users.id', 'users.name', 'users.email')
            ->havingRaw('COUNT(*) >= 2')
            ->havingRaw('MAX(orders.created_at) < ?', [Carbon::now()->subDays(60)->format('Y-m-d H:i:s')])
            ->orderByDesc('revenue')
            ->limit(6)
            ->get()
            ->map(function ($row) {
                $daysSinceLastOrder = Carbon::parse($row->last_order_at)->diffInDays(Carbon::now());

                return [
                    'user_id' => $row->user_id,
                    'name' => $row->name,
                    'email' => $row->email,
                    'order_count' => (int) $row->order_count,
                    'revenue' => round((float) $row->revenue, 2),
                    'days_since_last_order' => $daysSinceLastOrder,
                    'playbook' => $daysSinceLastOrder >= 120 ? 'Ưu tiên win-back bằng ưu đãi mạnh' : 'Nhắc mua lại bằng combo hoặc coupon nhẹ',
                ];
            })
            ->values();

        $singleItemUpsellRows = Cart::query()
            ->join('orders', 'orders.id', '=', 'carts.order_id')
            ->join('products', 'products.id', '=', 'carts.product_id')
            ->where('orders.status', 'delivered')
            ->whereBetween('orders.created_at', [$startDate, $endDate])
            ->where('orders.quantity', 1)
            ->select('products.id', 'products.title', 'products.price', 'products.stock', 'products.condition')
            ->selectRaw('COUNT(DISTINCT orders.id) as single_order_count')
            ->selectRaw('SUM(carts.amount) as revenue')
            ->groupBy('products.id', 'products.title', 'products.price', 'products.stock', 'products.condition')
            ->orderByDesc('single_order_count')
            ->limit(6)
            ->get()
            ->map(function ($row) {
                return [
                    'product_id' => $row->id,
                    'title' => $row->title,
                    'price' => (float) $row->price,
                    'stock' => (int) $row->stock,
                    'single_order_count' => (int) $row->single_order_count,
                    'revenue' => round((float) $row->revenue, 2),
                    'upsell_note' => $row->price >= 2000000
                        ? 'Gợi ý phụ kiện hoặc gói bảo hành để tăng giá trị đơn.'
                        : 'Phù hợp gợi ý mua kèm phụ kiện hoặc phiên bản cao hơn.',
                ];
            })
            ->values();

        $bundleOpportunities = collect($this->buildBundlePairs($startDate, $endDate))
            ->take(6)
            ->values();

        $chartData = [
            'paymentMix' => [
                'labels' => $paymentMix->pluck('label')->values(),
                'revenue' => $paymentMix->pluck('revenue')->values(),
                'orders' => $paymentMix->pluck('order_count')->values(),
            ],
            'customerSegments' => [
                'labels' => $customerSegments->pluck('label')->values(),
                'revenue' => $customerSegments->pluck('revenue')->values(),
                'orders' => $customerSegments->pluck('order_count')->values(),
            ],
            'couponMix' => [
                'labels' => $couponMix->pluck('label')->values(),
                'orders' => $couponMix->pluck('order_count')->values(),
                'revenue' => $couponMix->pluck('revenue')->values(),
            ],
            'basketMix' => [
                'labels' => $basketMix->pluck('label')->values(),
                'orders' => $basketMix->pluck('order_count')->values(),
                'revenue' => $basketMix->pluck('revenue')->values(),
            ],
        ];

        return view('backend.bi-analyst.index', compact(
            'range',
            'startDate',
            'endDate',
            'summaryCards',
            'paymentMix',
            'couponMix',
            'basketMix',
            'customerSegments',
            'commercialSignals',
            'chartData',
            'deliveredOrderCount',
            'deliveredRevenue',
            'activeCoupons',
            'simulatorInput',
            'scenarioSummary',
            'scenarioAssumptions',
            'winBackCustomers',
            'singleItemUpsellRows',
            'bundleOpportunities'
        ));
    }

    protected function resolveRange(Request $request)
    {
        $allowedRanges = [30, 90, 180];
        $range = (int) $request->query('range', 90);

        return in_array($range, $allowedRanges, true) ? $range : 90;
    }

    protected function findRow(Collection $rows, $field, $value)
    {
        return $rows->firstWhere($field, $value) ?: [
            'order_count' => 0,
            'revenue' => 0,
            'average_order_value' => 0,
            'order_share' => 0,
            'revenue_share' => 0,
            'customer_count' => 0,
        ];
    }

    protected function boundPercent($value)
    {
        return max(0, min(100, (int) $value));
    }

    protected function buildBundlePairs(Carbon $startDate, Carbon $endDate)
    {
        $orderItems = Cart::query()
            ->join('orders', 'orders.id', '=', 'carts.order_id')
            ->join('products', 'products.id', '=', 'carts.product_id')
            ->where('orders.status', 'delivered')
            ->whereBetween('orders.created_at', [$startDate, $endDate])
            ->select('orders.id as order_id', 'products.title')
            ->selectRaw('SUM(carts.amount) as line_revenue')
            ->groupBy('orders.id', 'products.id', 'products.title')
            ->orderBy('orders.id')
            ->get()
            ->groupBy('order_id');

        $pairs = [];
        foreach ($orderItems as $items) {
            $items = $items->values();
            $count = $items->count();

            for ($leftIndex = 0; $leftIndex < $count; $leftIndex++) {
                for ($rightIndex = $leftIndex + 1; $rightIndex < $count; $rightIndex++) {
                    $leftItem = $items[$leftIndex];
                    $rightItem = $items[$rightIndex];
                    $names = [$leftItem->title, $rightItem->title];

                    sort($names, SORT_NATURAL | SORT_FLAG_CASE);
                    $pairKey = implode('|||', $names);

                    if (!isset($pairs[$pairKey])) {
                        $pairs[$pairKey] = [
                            'label' => $names[0].' + '.$names[1],
                            'product_titles' => $names,
                            'bundle_count' => 0,
                            'bundle_revenue' => 0,
                        ];
                    }

                    $pairs[$pairKey]['bundle_count']++;
                    $pairs[$pairKey]['bundle_revenue'] += ((float) $leftItem->line_revenue) + ((float) $rightItem->line_revenue);
                }
            }
        }

        $pairs = array_values($pairs);
        usort($pairs, function ($left, $right) {
            if ($left['bundle_count'] === $right['bundle_count']) {
                return $right['bundle_revenue'] <=> $left['bundle_revenue'];
            }

            return $right['bundle_count'] <=> $left['bundle_count'];
        });

        return array_slice($pairs, 0, 6);
    }
}
