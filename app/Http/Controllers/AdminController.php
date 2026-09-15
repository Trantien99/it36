<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Category;
use App\Models\Order;
use App\Models\Post;
use App\Models\Product;
use App\Models\Settings;
use App\Rules\MatchOldPassword;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    public function index(Request $request)
    {
        $range = $this->resolveRange($request);

        if ((string) $request->query('range') !== (string) $range) {
            return redirect()->route('admin', array_merge(
                $request->except('range'),
                ['range' => $range]
            ));
        }

        $now = Carbon::now();
        $startDate = $now->copy()->subDays($range - 1)->startOfDay();
        $endDate = $now->copy()->endOfDay();
        $previousStartDate = $startDate->copy()->subDays($range);
        $previousEndDate = $startDate->copy()->subSecond();

        $statusCounts = Order::query()
            ->select('status', DB::raw('COUNT(*) as total'))
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy('status')
            ->pluck('total', 'status');

        $previousStatusCounts = Order::query()
            ->select('status', DB::raw('COUNT(*) as total'))
            ->whereBetween('created_at', [$previousStartDate, $previousEndDate])
            ->groupBy('status')
            ->pluck('total', 'status');

        $totalOrders = (int) $statusCounts->sum();
        $deliveredOrders = (int) $statusCounts->get('delivery_success', 0) + (int) $statusCounts->get('completed', 0);
        $newOrders = (int) $statusCounts->get('pending_confirmation', 0);
        $processingOrders = (int) $statusCounts->get('preparing', 0) + (int) $statusCounts->get('ready', 0) + (int) $statusCounts->get('shipping', 0);
        $cancelledOrders = (int) $statusCounts->get('cancelled', 0);
        $previousDeliveredOrders = (int) $previousStatusCounts->get('delivery_success', 0) + (int) $previousStatusCounts->get('completed', 0);
        $previousTotalOrders = (int) $previousStatusCounts->sum();

        $revenue = (float) Order::query()
            ->whereIn('status', ['delivery_success', 'completed'])
            ->whereBetween('created_at', [$startDate, $endDate])
            ->sum('total_amount');

        $previousRevenue = (float) Order::query()
            ->whereIn('status', ['delivery_success', 'completed'])
            ->whereBetween('created_at', [$previousStartDate, $previousEndDate])
            ->sum('total_amount');

        $newCustomers = (int) User::query()
            ->where('role', 'user')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->count();

        $previousNewCustomers = (int) User::query()
            ->where('role', 'user')
            ->whereBetween('created_at', [$previousStartDate, $previousEndDate])
            ->count();

        $averageOrderValue = $deliveredOrders > 0 ? $revenue / $deliveredOrders : 0;
        $previousAverageOrderValue = $previousDeliveredOrders > 0 ? $previousRevenue / $previousDeliveredOrders : 0;
        $fulfillmentRate = $totalOrders > 0 ? ($deliveredOrders / $totalOrders) * 100 : 0;
        $previousFulfillmentRate = $previousTotalOrders > 0 ? ($previousDeliveredOrders / $previousTotalOrders) * 100 : 0;

        $lowStockThreshold = 5;
        $lowStockCount = (int) Product::query()
            ->where('status', 'active')
            ->where('stock', '<=', $lowStockThreshold)
            ->count();

        $trendData = $this->buildOrderTrend($startDate, $endDate, $range);
        $customerTrend = $this->buildCustomerTrend($startDate, $endDate, $range);
        $hourlyDemandProfile = $this->buildHourlyDemandProfile($startDate, $endDate);
        $customerOrderCorrelation = $this->buildCustomerOrderCorrelation($startDate, $endDate, $range);
        $weekdaySeasonality = $this->buildWeekdaySeasonality($startDate, $endDate);
        $forwardForecast = $this->buildForwardForecast($endDate, $range, $trendData, $weekdaySeasonality);
        $inventoryForecast = $this->buildInventoryForecast($endDate, $range);

        $topProducts = Cart::query()
            ->join('orders', 'orders.id', '=', 'carts.order_id')
            ->join('products', 'products.id', '=', 'carts.product_id')
            ->select('products.title')
            ->selectRaw('SUM(carts.quantity) as units_sold')
            ->selectRaw('SUM(carts.amount) as revenue')
            ->whereIn('orders.status', ['delivery_success', 'completed'])
            ->whereBetween('orders.created_at', [$startDate, $endDate])
            ->groupBy('products.id', 'products.title')
            ->orderByDesc(DB::raw('SUM(carts.quantity)'))
            ->limit(5)
            ->get();

        $categoryRevenue = Cart::query()
            ->join('orders', 'orders.id', '=', 'carts.order_id')
            ->join('products', 'products.id', '=', 'carts.product_id')
            ->join('categories', 'categories.id', '=', 'products.cat_id')
            ->select('categories.title')
            ->selectRaw('SUM(carts.amount) as revenue')
            ->whereIn('orders.status', ['delivery_success', 'completed'])
            ->whereBetween('orders.created_at', [$startDate, $endDate])
            ->groupBy('categories.id', 'categories.title')
            ->orderByDesc(DB::raw('SUM(carts.amount)'))
            ->limit(5)
            ->get();

        $lowStockProducts = Product::query()
            ->select('title', 'stock', 'price')
            ->where('status', 'active')
            ->where('stock', '<=', $lowStockThreshold)
            ->orderBy('stock')
            ->limit(5)
            ->get();

        $bestRevenueLabel = 'Chưa có doanh thu giao thành công';
        $bestRevenueValue = 0;
        foreach ($trendData['revenue'] as $index => $amount) {
            if ($amount > $bestRevenueValue) {
                $bestRevenueValue = $amount;
                $bestRevenueLabel = $trendData['labels'][$index];
            }
        }

        $topProduct = $topProducts->first();
        $topCategory = $categoryRevenue->first();

        $metrics = [
            [
                'label' => 'Doanh thu đã giao',
                'value' => $revenue,
                'format' => 'currency',
                'icon' => 'fa-coins',
                'tone' => 'primary',
                'description' => 'Chỉ tính doanh thu từ các đơn đã giao',
                'change' => $this->buildChange($revenue, $previousRevenue),
            ],
            [
                'label' => 'Đơn hàng phát sinh',
                'value' => $totalOrders,
                'format' => 'number',
                'icon' => 'fa-shopping-bag',
                'tone' => 'success',
                'description' => 'Tổng số đơn phát sinh trong khoảng thời gian chọn',
                'change' => $this->buildChange($totalOrders, $previousTotalOrders),
            ],
            [
                'label' => 'Tỷ lệ hoàn tất',
                'value' => $fulfillmentRate,
                'format' => 'percent',
                'icon' => 'fa-shipping-fast',
                'tone' => 'info',
                'description' => 'Tỷ lệ đơn đã giao trên tổng số đơn',
                'change' => $this->buildChange($fulfillmentRate, $previousFulfillmentRate),
            ],
            [
                'label' => 'Giá trị đơn trung bình',
                'value' => $averageOrderValue,
                'format' => 'currency',
                'icon' => 'fa-receipt',
                'tone' => 'warning',
                'description' => 'Giá trị trung bình của mỗi đơn đã giao',
                'change' => $this->buildChange($averageOrderValue, $previousAverageOrderValue),
            ],
            [
                'label' => 'Khách hàng mới',
                'value' => $newCustomers,
                'format' => 'number',
                'icon' => 'fa-user-plus',
                'tone' => 'secondary',
                'description' => 'Số tài khoản khách hàng đăng ký trong kỳ',
                'change' => $this->buildChange($newCustomers, $previousNewCustomers),
            ],
            [
                'label' => 'Cảnh báo tồn kho thấp',
                'value' => $lowStockCount,
                'format' => 'number',
                'icon' => 'fa-exclamation-triangle',
                'tone' => 'danger',
                'description' => 'Sản phẩm đang hoạt động có tồn kho nhỏ hơn hoặc bằng '.$lowStockThreshold,
                'change' => null,
            ],
        ];

        $operations = [
            [
                'label' => 'Danh mục đang hoạt động',
                'value' => Category::query()->where('status', 'active')->count(),
                'icon' => 'fa-sitemap',
            ],
            [
                'label' => 'Sản phẩm đang bán',
                'value' => Product::query()->where('status', 'active')->count(),
                'icon' => 'fa-cubes',
            ],
            [
                'label' => 'Đơn đang xử lý',
                'value' => Order::query()->whereIn('status', ['pending_confirmation', 'preparing', 'ready', 'shipping'])->count(),
                'icon' => 'fa-hourglass-half',
            ],
            [
                'label' => 'Bài viết hiển thị',
                'value' => Post::query()->where('status', 'active')->count(),
                'icon' => 'fa-newspaper',
            ],
        ];

        $insights = [
            [
                'title' => 'Giai đoạn doanh thu cao nhất',
                'value' => $bestRevenueLabel,
                'description' => $bestRevenueValue > 0
                    ? 'Doanh thu giao thành công đạt đỉnh tại '.number_format($bestRevenueValue, 0, '.', ',').'đ.'
                    : 'Chưa có đơn giao thành công trong khoảng thời gian này.',
                'icon' => 'fa-chart-line',
            ],
            [
                'title' => 'Sản phẩm nổi bật',
                'value' => $topProduct ? $topProduct->title : 'Chưa có dữ liệu sản phẩm',
                'description' => $topProduct
                    ? 'Đã bán '.number_format($topProduct->units_sold, 0, '.', ',').' sản phẩm và tạo '.number_format($topProduct->revenue, 0, '.', ',').'đ doanh thu.'
                    : 'Chưa có dữ liệu bán hàng giao thành công trong kỳ.',
                'icon' => 'fa-star',
            ],
            [
                'title' => 'Danh mục dẫn đầu',
                'value' => $topCategory ? $topCategory->title : 'Chưa có dữ liệu danh mục',
                'description' => $topCategory
                    ? 'Danh mục có doanh thu cao nhất với '.number_format($topCategory->revenue, 0, '.', ',').'đ.'
                    : 'Chưa có dữ liệu đóng góp doanh thu theo danh mục.',
                'icon' => 'fa-layer-group',
            ],
        ];

        $predictionActions = $this->buildPredictionActions($forwardForecast, $inventoryForecast, $weekdaySeasonality);

        $chartData = [
            'trend' => $trendData,
            'forwardForecast' => $forwardForecast['chart'],
            'customerTrend' => $customerTrend,
            'statusBreakdown' => [
                'labels' => ['Mới', 'Đang xử lý', 'Đã giao', 'Đã hủy'],
                'values' => [$newOrders, $processingOrders, $deliveredOrders, $cancelledOrders],
            ],
            'topProducts' => [
                'labels' => $topProducts->pluck('title')->values(),
                'values' => $topProducts->pluck('units_sold')->map(function ($value) {
                    return (int) $value;
                })->values(),
            ],
            'categoryRevenue' => [
                'labels' => $categoryRevenue->pluck('title')->values(),
                'values' => $categoryRevenue->pluck('revenue')->map(function ($value) {
                    return round((float) $value, 2);
                })->values(),
            ],
            'hourlyDemandProfile' => $hourlyDemandProfile,
            'customerOrderCorrelation' => $customerOrderCorrelation,
            'weekdaySeasonality' => $weekdaySeasonality,
        ];

        return view('backend.index', compact(
            'range',
            'metrics',
            'operations',
            'insights',
            'chartData',
            'forwardForecast',
            'inventoryForecast',
            'predictionActions',
            'topProducts',
            'lowStockProducts'
        ));
    }

    public function profile()
    {
        $profile = auth()->user();

        return view('backend.users.profile')->with('profile', $profile);
    }

    public function profileUpdate(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $status = $user->fill($request->all())->save();

        if ($status) {
            request()->session()->flash('success', 'Cập nhật hồ sơ thành công.');
        } else {
            request()->session()->flash('error', 'Vui lòng thử lại.');
        }

        return redirect()->back();
    }

    public function settings()
    {
        $data = Settings::first();

        return view('backend.setting')->with('data', $data);
    }

    public function settingsUpdate(Request $request)
    {
        $this->validate($request, [
            'short_des' => 'required|string',
            'description' => 'required|string',
            'photo' => 'required',
            'logo' => 'required',
            'address' => 'required|string',
            'email' => 'required|email',
            'phone' => 'required|string',
        ]);

        $settings = Settings::first();
        $status = $settings->fill($request->all())->save();

        if ($status) {
            request()->session()->flash('success', 'Cập nhật cài đặt thành công.');
        } else {
            request()->session()->flash('error', 'Vui lòng thử lại.');
        }

        return redirect()->route('admin');
    }

    public function changePassword()
    {
        return view('backend.layouts.changePassword');
    }

    public function changPasswordStore(Request $request)
    {
        $request->validate([
            'current_password' => ['required', new MatchOldPassword],
            'new_password' => ['required'],
            'new_confirm_password' => ['same:new_password'],
        ]);

        User::find(auth()->user()->id)->update(['password' => Hash::make($request->new_password)]);

        return redirect()->route('admin')->with('success', 'Đổi mật khẩu thành công.');
    }

    protected function resolveRange(Request $request)
    {
        $allowedRanges = [7, 30, 90, 365];
        $range = (int) $request->get('range', 365);

        return in_array($range, $allowedRanges, true) ? $range : 365;
    }

    protected function buildOrderTrend(Carbon $startDate, Carbon $endDate, $range)
    {
        $monthlyBuckets = $range > 90;
        $bucketExpression = $monthlyBuckets
            ? "DATE_FORMAT(created_at, '%Y-%m')"
            : "DATE_FORMAT(created_at, '%Y-%m-%d')";

        $rows = Order::query()
            ->selectRaw($bucketExpression.' as bucket')
            ->selectRaw('COUNT(*) as orders')
            ->selectRaw("SUM(CASE WHEN status IN ('delivery_success', 'completed') THEN total_amount ELSE 0 END) as revenue")
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy('bucket')
            ->orderBy('bucket')
            ->get()
            ->keyBy('bucket');

        $labels = [];
        $revenue = [];
        $orders = [];
        $cursor = $monthlyBuckets ? $startDate->copy()->startOfMonth() : $startDate->copy()->startOfDay();
        $limit = $monthlyBuckets ? $endDate->copy()->startOfMonth() : $endDate->copy()->startOfDay();

        while ($cursor <= $limit) {
            $bucket = $monthlyBuckets ? $cursor->format('Y-m') : $cursor->format('Y-m-d');
            $row = $rows->get($bucket);

            $labels[] = $monthlyBuckets ? $cursor->format('M Y') : $cursor->format('d M');
            $revenue[] = $row ? round((float) $row->revenue, 2) : 0;
            $orders[] = $row ? (int) $row->orders : 0;

            if ($monthlyBuckets) {
                $cursor->addMonth();
            } else {
                $cursor->addDay();
            }
        }

        return [
            'labels' => $labels,
            'revenue' => $revenue,
            'orders' => $orders,
        ];
    }

    protected function buildCustomerTrend(Carbon $startDate, Carbon $endDate, $range)
    {
        $monthlyBuckets = $range > 90;
        $bucketExpression = $monthlyBuckets
            ? "DATE_FORMAT(created_at, '%Y-%m')"
            : "DATE_FORMAT(created_at, '%Y-%m-%d')";

        $rows = User::query()
            ->selectRaw($bucketExpression.' as bucket')
            ->selectRaw('COUNT(*) as total')
            ->where('role', 'user')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy('bucket')
            ->orderBy('bucket')
            ->get()
            ->keyBy('bucket');

        $labels = [];
        $totals = [];
        $cursor = $monthlyBuckets ? $startDate->copy()->startOfMonth() : $startDate->copy()->startOfDay();
        $limit = $monthlyBuckets ? $endDate->copy()->startOfMonth() : $endDate->copy()->startOfDay();

        while ($cursor <= $limit) {
            $bucket = $monthlyBuckets ? $cursor->format('Y-m') : $cursor->format('Y-m-d');
            $row = $rows->get($bucket);

            $labels[] = $monthlyBuckets ? $cursor->format('M Y') : $cursor->format('d M');
            $totals[] = $row ? (int) $row->total : 0;

            if ($monthlyBuckets) {
                $cursor->addMonth();
            } else {
                $cursor->addDay();
            }
        }

        return [
            'labels' => $labels,
            'values' => $totals,
        ];
    }

    protected function buildHourlyDemandProfile(Carbon $startDate, Carbon $endDate)
    {
        $timeBuckets = [
            ['label' => '00h-05h', 'start' => 0, 'end' => 5],
            ['label' => '06h-09h', 'start' => 6, 'end' => 9],
            ['label' => '10h-13h', 'start' => 10, 'end' => 13],
            ['label' => '14h-17h', 'start' => 14, 'end' => 17],
            ['label' => '18h-23h', 'start' => 18, 'end' => 23],
        ];

        $labels = [];
        $values = array_fill(0, count($timeBuckets), 0);

        foreach ($timeBuckets as $bucket) {
            $labels[] = $bucket['label'];
        }

        $rows = Order::query()
            ->selectRaw('HOUR(created_at) as hour')
            ->selectRaw('COUNT(*) as total')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy('hour')
            ->get();

        foreach ($rows as $row) {
            $hour = (int) $row->hour;

            foreach ($timeBuckets as $index => $bucket) {
                if ($hour >= $bucket['start'] && $hour <= $bucket['end']) {
                    $values[$index] += (int) $row->total;
                    break;
                }
            }
        }

        $totalOrders = array_sum($values);
        $shares = [];
        foreach ($values as $value) {
            $shares[] = $totalOrders > 0 ? round(($value / $totalOrders) * 100, 1) : 0;
        }

        return [
            'labels' => $labels,
            'values' => $values,
            'shares' => $shares,
        ];
    }

    protected function buildCustomerOrderCorrelation(Carbon $startDate, Carbon $endDate, $range)
    {
        $monthlyBuckets = $range > 90;
        $bucketExpression = $monthlyBuckets
            ? "DATE_FORMAT(created_at, '%Y-%m')"
            : "DATE_FORMAT(created_at, '%Y-%m-%d')";

        $orderRows = Order::query()
            ->selectRaw($bucketExpression.' as bucket')
            ->selectRaw('COUNT(*) as orders')
            ->selectRaw("SUM(CASE WHEN status IN ('delivery_success', 'completed') THEN total_amount ELSE 0 END) as revenue")
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy('bucket')
            ->orderBy('bucket')
            ->get()
            ->keyBy('bucket');

        $customerRows = User::query()
            ->selectRaw($bucketExpression.' as bucket')
            ->selectRaw('COUNT(*) as customers')
            ->where('role', 'user')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy('bucket')
            ->orderBy('bucket')
            ->get()
            ->keyBy('bucket');

        $rawPoints = [];
        $cursor = $monthlyBuckets ? $startDate->copy()->startOfMonth() : $startDate->copy()->startOfDay();
        $limit = $monthlyBuckets ? $endDate->copy()->startOfMonth() : $endDate->copy()->startOfDay();

        while ($cursor <= $limit) {
            $bucket = $monthlyBuckets ? $cursor->format('Y-m') : $cursor->format('Y-m-d');
            $label = $monthlyBuckets ? $cursor->format('M Y') : $cursor->format('d M');
            $orderRow = $orderRows->get($bucket);
            $customerRow = $customerRows->get($bucket);
            $orders = $orderRow ? (int) $orderRow->orders : 0;
            $customers = $customerRow ? (int) $customerRow->customers : 0;
            $revenue = $orderRow ? round((float) $orderRow->revenue, 2) : 0;

            if ($orders > 0 || $customers > 0 || $revenue > 0) {
                $rawPoints[] = [
                    'label' => $label,
                    'x' => $customers,
                    'y' => $orders,
                    'revenue' => $revenue,
                ];
            }

            if ($monthlyBuckets) {
                $cursor->addMonth();
            } else {
                $cursor->addDay();
            }
        }

        $maxRevenue = 0;
        foreach ($rawPoints as $point) {
            if ($point['revenue'] > $maxRevenue) {
                $maxRevenue = $point['revenue'];
            }
        }

        $points = [];
        foreach ($rawPoints as $point) {
            $points[] = [
                'label' => $point['label'],
                'x' => $point['x'],
                'y' => $point['y'],
                'r' => $maxRevenue > 0
                    ? max(6, min(22, round((($point['revenue'] / $maxRevenue) * 16) + 6, 1)))
                    : 8,
                'revenue' => $point['revenue'],
            ];
        }

        return [
            'points' => $points,
            'bucketLabel' => $monthlyBuckets ? 'tháng' : 'ngày',
        ];
    }

    protected function buildWeekdaySeasonality(Carbon $startDate, Carbon $endDate)
    {
        $weekdayMap = [
            ['key' => 2, 'label' => 'Thứ 2'],
            ['key' => 3, 'label' => 'Thứ 3'],
            ['key' => 4, 'label' => 'Thứ 4'],
            ['key' => 5, 'label' => 'Thứ 5'],
            ['key' => 6, 'label' => 'Thứ 6'],
            ['key' => 7, 'label' => 'Thứ 7'],
            ['key' => 1, 'label' => 'CN'],
        ];

        $rows = Order::query()
            ->selectRaw('DAYOFWEEK(created_at) as weekday')
            ->selectRaw('COUNT(*) as orders')
            ->selectRaw("SUM(CASE WHEN status IN ('delivery_success', 'completed') THEN total_amount ELSE 0 END) as revenue")
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy('weekday')
            ->get()
            ->keyBy('weekday');

        $labels = [];
        $orders = [];
        $revenues = [];
        $totalOrders = 0;
        $totalRevenue = 0;

        foreach ($weekdayMap as $day) {
            $row = $rows->get($day['key']);
            $orderCount = $row ? (int) $row->orders : 0;
            $revenueValue = $row ? round((float) $row->revenue, 2) : 0;

            $labels[] = $day['label'];
            $orders[] = $orderCount;
            $revenues[] = $revenueValue;
            $totalOrders += $orderCount;
            $totalRevenue += $revenueValue;
        }

        $orderShares = [];
        $revenueShares = [];

        foreach ($orders as $index => $value) {
            $orderShares[] = $totalOrders > 0 ? round(($value / $totalOrders) * 100, 1) : 0;
            $revenueShares[] = $totalRevenue > 0 ? round(($revenues[$index] / $totalRevenue) * 100, 1) : 0;
        }

        return [
            'labels' => $labels,
            'orders' => $orders,
            'revenues' => $revenues,
            'orderShares' => $orderShares,
            'revenueShares' => $revenueShares,
        ];
    }

    protected function buildForwardForecast(Carbon $endDate, $range, array $trendData, array $weekdaySeasonality)
    {
        $isDailyForecast = $range <= 90;
        $horizon = $isDailyForecast ? ($range <= 30 ? 7 : 14) : 3;
        $periodLabel = $isDailyForecast ? 'ngày' : 'tháng';
        $historyRevenue = array_map(function ($value) {
            return round((float) $value, 2);
        }, $trendData['revenue']);
        $historyOrders = array_map(function ($value) {
            return (int) $value;
        }, $trendData['orders']);

        $historyLength = count($historyRevenue);
        $window = min($isDailyForecast ? 14 : 6, max(3, $historyLength));
        $recentRevenue = $historyLength > 0 ? array_slice($historyRevenue, -$window) : [0];
        $recentOrders = $historyLength > 0 ? array_slice($historyOrders, -$window) : [0];

        $revenueBase = $this->calculateWeightedAverage($recentRevenue);
        $orderBase = $this->calculateWeightedAverage($recentOrders);
        $revenueTrend = $this->calculateTrendDrift($recentRevenue);
        $orderTrend = $this->calculateTrendDrift($recentOrders);

        $revenueSeasonalityFactors = $isDailyForecast
            ? $this->buildSeasonalityFactors($weekdaySeasonality['revenueShares'])
            : [];
        $orderSeasonalityFactors = $isDailyForecast
            ? $this->buildSeasonalityFactors($weekdaySeasonality['orderShares'])
            : [];

        $forecastRevenue = [];
        $forecastOrders = [];
        $forecastLabels = [];
        $peakLabel = 'Chưa đủ dữ liệu';
        $peakRevenue = 0;
        $cursor = $isDailyForecast
            ? $endDate->copy()->addDay()->startOfDay()
            : $endDate->copy()->startOfMonth()->addMonth();

        for ($step = 1; $step <= $horizon; $step++) {
            $label = $isDailyForecast ? $cursor->format('d M') : $cursor->format('M Y');
            $revenueMultiplier = 1;
            $orderMultiplier = 1;

            if ($isDailyForecast) {
                $weekdayIndex = $cursor->dayOfWeekIso - 1;
                $revenueMultiplier = $revenueSeasonalityFactors[$weekdayIndex] ?? 1;
                $orderMultiplier = $orderSeasonalityFactors[$weekdayIndex] ?? 1;
            }

            $projectedRevenue = max(0, ($revenueBase * $revenueMultiplier) + ($revenueTrend * 0.75 * $step));
            $projectedOrders = max(0, ($orderBase * $orderMultiplier) + ($orderTrend * 0.75 * $step));

            $projectedRevenue = round($projectedRevenue, 2);
            $projectedOrders = (int) round($projectedOrders);

            $forecastLabels[] = $label;
            $forecastRevenue[] = $projectedRevenue;
            $forecastOrders[] = $projectedOrders;

            if ($projectedRevenue > $peakRevenue) {
                $peakRevenue = $projectedRevenue;
                $peakLabel = $label;
            }

            if ($isDailyForecast) {
                $cursor->addDay();
            } else {
                $cursor->addMonth();
            }
        }

        $totalForecastRevenue = array_sum($forecastRevenue);
        $totalForecastOrders = array_sum($forecastOrders);
        $averageForecastRevenue = $horizon > 0 ? $totalForecastRevenue / $horizon : 0;
        $averageForecastOrders = $horizon > 0 ? $totalForecastOrders / $horizon : 0;
        $averageHistoricalRevenue = $historyLength > 0 ? array_sum($historyRevenue) / $historyLength : 0;
        $averageHistoricalOrders = $historyLength > 0 ? array_sum($historyOrders) / $historyLength : 0;
        $forecastAov = $totalForecastOrders > 0 ? $totalForecastRevenue / $totalForecastOrders : 0;
        $historicalAov = array_sum($historyOrders) > 0 ? array_sum($historyRevenue) / array_sum($historyOrders) : 0;
        $revenueChange = $this->buildChange($averageForecastRevenue, $averageHistoricalRevenue);
        $orderChange = $this->buildChange($averageForecastOrders, $averageHistoricalOrders);
        $aovChange = $this->buildChange($forecastAov, $historicalAov);
        $confidence = $this->buildForecastConfidence($historyRevenue, $historyOrders, $window);

        return [
            'horizon' => $horizon,
            'periodLabel' => $periodLabel,
            'horizonLabel' => $horizon.' '.$periodLabel.' tới',
            'modelLabel' => $isDailyForecast
                ? 'Weighted trend + weekday seasonality'
                : 'Weighted monthly trend',
            'summary' => [
                [
                    'label' => 'Doanh thu dự báo '.$horizon.' '.$periodLabel.' tới',
                    'value' => $totalForecastRevenue,
                    'format' => 'currency',
                    'icon' => 'fa-chart-area',
                    'change' => $revenueChange,
                    'note' => 'So với doanh thu trung bình mỗi '.$periodLabel.' của kỳ đang xem.',
                ],
                [
                    'label' => 'Đơn dự báo '.$horizon.' '.$periodLabel.' tới',
                    'value' => $totalForecastOrders,
                    'format' => 'number',
                    'icon' => 'fa-shopping-basket',
                    'change' => $orderChange,
                    'note' => 'So với số đơn trung bình mỗi '.$periodLabel.' của kỳ đang xem.',
                ],
                [
                    'label' => 'AOV kỳ vọng',
                    'value' => $forecastAov,
                    'format' => 'currency',
                    'icon' => 'fa-receipt',
                    'change' => $aovChange,
                    'note' => 'Giá trị đơn hàng trung bình dự kiến nếu nhịp bán hiện tại giữ nguyên.',
                ],
                [
                    'label' => 'Mức tin cậy mô hình',
                    'value' => $confidence['label'],
                    'format' => 'text',
                    'icon' => 'fa-shield-alt',
                    'change' => null,
                    'note' => $confidence['description'],
                    'tone' => $confidence['tone'],
                ],
            ],
            'confidence' => $confidence,
            'peak' => [
                'label' => $peakLabel,
                'revenue' => $peakRevenue,
            ],
            'chart' => [
                'labels' => array_merge($trendData['labels'], $forecastLabels),
                'actualRevenue' => array_merge($historyRevenue, array_fill(0, $horizon, null)),
                'forecastRevenue' => $this->buildProjectedSeries($historyRevenue, $forecastRevenue),
                'actualOrders' => array_merge($historyOrders, array_fill(0, $horizon, null)),
                'forecastOrders' => $this->buildProjectedSeries($historyOrders, $forecastOrders),
            ],
        ];
    }

    protected function buildInventoryForecast(Carbon $endDate, $range)
    {
        $lookbackDays = min(90, max(30, $range));
        $forecastDays = $range > 90 ? 30 : ($range > 30 ? 21 : 14);
        $inventoryStartDate = $endDate->copy()->subDays($lookbackDays - 1)->startOfDay();

        $rows = Cart::query()
            ->join('orders', 'orders.id', '=', 'carts.order_id')
            ->join('products', 'products.id', '=', 'carts.product_id')
            ->select('products.id', 'products.title', 'products.stock', 'products.price')
            ->selectRaw('SUM(carts.quantity) as units_sold')
            ->whereIn('orders.status', ['delivery_success', 'completed'])
            ->whereBetween('orders.created_at', [$inventoryStartDate, $endDate])
            ->groupBy('products.id', 'products.title', 'products.stock', 'products.price')
            ->havingRaw('SUM(carts.quantity) > 0')
            ->get();

        $items = [];

        foreach ($rows as $row) {
            $stock = (int) $row->stock;
            $avgDailyUnits = round(((float) $row->units_sold) / $lookbackDays, 2);

            if ($avgDailyUnits <= 0) {
                continue;
            }

            $predictedUnits = (int) ceil($avgDailyUnits * $forecastDays);
            $safetyStock = max(2, (int) ceil($avgDailyUnits * 3));
            $recommendedRestock = max(0, ($predictedUnits + $safetyStock) - $stock);
            $daysOfCover = round($stock / $avgDailyUnits, 1);

            if ($daysOfCover <= 7) {
                $urgency = 'high';
                $urgencyLabel = 'Khẩn cấp';
                $urgencyRank = 0;
            } elseif ($daysOfCover <= $forecastDays) {
                $urgency = 'medium';
                $urgencyLabel = 'Theo dõi';
                $urgencyRank = 1;
            } else {
                $urgency = 'low';
                $urgencyLabel = 'Ổn định';
                $urgencyRank = 2;
            }

            if ($recommendedRestock <= 0 && $urgency === 'low') {
                continue;
            }

            $stockoutDate = $daysOfCover > 0
                ? $endDate->copy()->addDays((int) floor($daysOfCover))->format('d/m')
                : 'Ngay';

            $items[] = [
                'title' => $row->title,
                'stock' => $stock,
                'avg_daily_units' => $avgDailyUnits,
                'predicted_units' => $predictedUnits,
                'days_of_cover' => $daysOfCover,
                'stockout_date' => $stockoutDate,
                'recommended_restock' => $recommendedRestock,
                'urgency' => $urgency,
                'urgency_label' => $urgencyLabel,
                'urgency_rank' => $urgencyRank,
                'price' => (float) $row->price,
            ];
        }

        usort($items, function ($left, $right) {
            if ($left['urgency_rank'] === $right['urgency_rank']) {
                if ($left['days_of_cover'] === $right['days_of_cover']) {
                    return $right['recommended_restock'] <=> $left['recommended_restock'];
                }

                return $left['days_of_cover'] <=> $right['days_of_cover'];
            }

            return $left['urgency_rank'] <=> $right['urgency_rank'];
        });

        $riskCount = count(array_filter($items, function ($item) {
            return $item['urgency'] !== 'low' || $item['recommended_restock'] > 0;
        }));

        return [
            'lookbackDays' => $lookbackDays,
            'forecastDays' => $forecastDays,
            'riskCount' => $riskCount,
            'items' => array_slice($items, 0, 6),
        ];
    }

    protected function buildPredictionActions(array $forwardForecast, array $inventoryForecast, array $weekdaySeasonality)
    {
        $actions = [];
        $revenueCard = $forwardForecast['summary'][0];
        $revenueChange = $revenueCard['change'];
        $periodLabel = $forwardForecast['periodLabel'];

        if ($revenueChange['direction'] === 'up') {
            $actions[] = [
                'title' => 'Kịch bản tăng trưởng',
                'value' => '+'.number_format($revenueChange['value'], 1, '.', ',').'% / '.$periodLabel,
                'description' => 'Nếu giữ nhịp hiện tại, doanh thu trung bình mỗi '.$periodLabel.' có thể tăng. Nên chuẩn bị năng lực xử lý đơn và CSKH cho kỳ tới.',
                'icon' => 'fa-arrow-up',
            ];
        } elseif ($revenueChange['direction'] === 'down') {
            $actions[] = [
                'title' => 'Tín hiệu chậm lại',
                'value' => '-'.number_format($revenueChange['value'], 1, '.', ',').'% / '.$periodLabel,
                'description' => 'Nhu cầu dự báo đang yếu hơn nền hiện tại. Ưu tiên rà soát landing page, khuyến mãi và hiệu quả remarketing để kéo lại conversion.',
                'icon' => 'fa-chart-line',
            ];
        } else {
            $actions[] = [
                'title' => 'Nền cầu ổn định',
                'value' => 'Biến động thấp',
                'description' => 'Tốc độ bán dự báo gần như đi ngang so với kỳ hiện tại. Đây là thời điểm phù hợp để tối ưu biên lợi nhuận và cơ cấu mix sản phẩm.',
                'icon' => 'fa-balance-scale',
            ];
        }

        $bestWeekdayIndex = $this->findHighestValueIndex($weekdaySeasonality['revenueShares']);
        if ($bestWeekdayIndex !== null) {
            $weekdayLabel = $weekdaySeasonality['labels'][$bestWeekdayIndex];
            $weekdayShare = $weekdaySeasonality['revenueShares'][$bestWeekdayIndex];

            $actions[] = [
                'title' => 'Lịch đẩy chiến dịch',
                'value' => $weekdayLabel,
                'description' => 'Ngày này đang chiếm khoảng '.number_format($weekdayShare, 1, '.', ',').'% doanh thu. Nên đẩy nội dung và ngân sách trước đó 12-24 giờ để tận dụng đà mua.',
                'icon' => 'fa-bullhorn',
            ];
        }

        $topRiskItem = $inventoryForecast['items'][0] ?? null;
        if ($topRiskItem) {
            $actions[] = [
                'title' => 'Ưu tiên nhập hàng',
                'value' => $topRiskItem['title'],
                'description' => 'Tồn kho chỉ đủ khoảng '.number_format($topRiskItem['days_of_cover'], 1, '.', ',').' ngày. Nên bổ sung thêm '.number_format($topRiskItem['recommended_restock'], 0, '.', ',').' sản phẩm để tránh hụt cầu.',
                'icon' => 'fa-box-open',
            ];
        } else {
            $actions[] = [
                'title' => 'Tồn kho an toàn',
                'value' => 'Chưa có SKU rủi ro',
                'description' => 'Hiện chưa thấy sản phẩm nào cần nhập bổ sung gấp theo nhịp bán gần đây. Có thể ưu tiên ngân sách cho tăng trưởng doanh thu.',
                'icon' => 'fa-check-circle',
            ];
        }

        return array_slice($actions, 0, 3);
    }

    protected function calculateWeightedAverage(array $values)
    {
        $weightedTotal = 0;
        $weightSum = 0;

        foreach (array_values($values) as $index => $value) {
            $weight = $index + 1;
            $weightedTotal += ((float) $value) * $weight;
            $weightSum += $weight;
        }

        return $weightSum > 0 ? $weightedTotal / $weightSum : 0;
    }

    protected function calculateTrendDrift(array $values)
    {
        $count = count($values);

        if ($count < 4) {
            return 0;
        }

        $split = (int) floor($count / 2);
        $previousSlice = array_slice($values, 0, $split);
        $recentSlice = array_slice($values, -$split);
        $previousAverage = array_sum($previousSlice) / max(1, count($previousSlice));
        $recentAverage = array_sum($recentSlice) / max(1, count($recentSlice));

        return ($recentAverage - $previousAverage) / max(1, $split);
    }

    protected function buildSeasonalityFactors(array $shares)
    {
        $count = count($shares);

        if ($count === 0 || array_sum($shares) <= 0) {
            return array_fill(0, max(1, $count), 1);
        }

        $expectedShare = 100 / $count;

        return array_map(function ($share) use ($expectedShare) {
            $rawFactor = $expectedShare > 0 ? ((float) $share) / $expectedShare : 1;
            $smoothedFactor = 1 + (($rawFactor - 1) * 0.6);

            return max(0.75, min(1.25, round($smoothedFactor, 3)));
        }, $shares);
    }

    protected function buildProjectedSeries(array $history, array $forecast)
    {
        $series = array_fill(0, count($history), null);

        if (!empty($history)) {
            $series[count($history) - 1] = end($history);
        }

        return array_merge($series, $forecast);
    }

    protected function buildForecastConfidence(array $historyRevenue, array $historyOrders, $window)
    {
        $activePeriods = 0;

        foreach ($historyRevenue as $index => $revenueValue) {
            $orderValue = $historyOrders[$index] ?? 0;
            if ($revenueValue > 0 || $orderValue > 0) {
                $activePeriods++;
            }
        }

        $averageRevenue = count($historyRevenue) > 0 ? array_sum($historyRevenue) / count($historyRevenue) : 0;
        $revenueStdDev = $this->calculateStandardDeviation($historyRevenue, $averageRevenue);
        $coefficientOfVariation = $averageRevenue > 0 ? $revenueStdDev / $averageRevenue : 0;

        if ($activePeriods >= max(4, (int) ceil($window * 0.7)) && $coefficientOfVariation <= 0.6) {
            return [
                'label' => 'Cao',
                'tone' => 'success',
                'description' => 'Dữ liệu khá đều và ít nhiễu, phù hợp để ra quyết định ngắn hạn.',
            ];
        }

        if ($activePeriods >= max(2, (int) ceil($window * 0.4))) {
            return [
                'label' => 'Trung bình',
                'tone' => 'warning',
                'description' => 'Có thể dùng để tham khảo vận hành, nhưng nên kết hợp kiểm tra thủ công với chiến dịch đang chạy.',
            ];
        }

        return [
            'label' => 'Thấp',
            'tone' => 'danger',
            'description' => 'Dữ liệu còn thưa hoặc biến động mạnh, nên xem forecast như tín hiệu định hướng thay vì cam kết.',
        ];
    }

    protected function calculateStandardDeviation(array $values, $average = null)
    {
        $count = count($values);

        if ($count === 0) {
            return 0;
        }

        $average = $average === null ? (array_sum($values) / $count) : (float) $average;
        $variance = 0;

        foreach ($values as $value) {
            $variance += pow(((float) $value) - $average, 2);
        }

        return sqrt($variance / $count);
    }

    protected function findHighestValueIndex(array $values)
    {
        $bestIndex = null;
        $bestValue = null;

        foreach ($values as $index => $value) {
            if ($bestValue === null || $value > $bestValue) {
                $bestIndex = $index;
                $bestValue = $value;
            }
        }

        return $bestIndex;
    }

    protected function buildChange($current, $previous)
    {
        $current = (float) $current;
        $previous = (float) $previous;

        if ($previous == 0.0) {
            if ($current == 0.0) {
                return [
                    'direction' => 'flat',
                    'value' => 0,
                ];
            }

            return [
                'direction' => 'up',
                'value' => 100,
            ];
        }

        $change = (($current - $previous) / abs($previous)) * 100;

        if (abs($change) < 0.05) {
            return [
                'direction' => 'flat',
                'value' => 0,
            ];
        }

        return [
            'direction' => $change > 0 ? 'up' : 'down',
            'value' => round(abs($change), 1),
        ];
    }

    public function userPieChart(Request $request)
    {
        return redirect()->route('admin');
    }
}
