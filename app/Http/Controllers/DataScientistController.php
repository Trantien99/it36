<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DataScientistController extends Controller
{
    public function index(Request $request)
    {
        $range = $this->resolveRange($request);
        $endDate = Carbon::now()->endOfDay();
        $startDate = Carbon::now()->subDays($range - 1)->startOfDay();

        $dailyRows = Order::query()
            ->whereIn('status', ['delivery_success', 'completed'])
            ->whereBetween('created_at', [$startDate, $endDate])
            ->selectRaw("DATE(created_at) as bucket")
            ->selectRaw('COUNT(*) as order_count')
            ->selectRaw('SUM(total_amount) as revenue')
            ->groupBy('bucket')
            ->orderBy('bucket')
            ->get()
            ->keyBy('bucket');

        $dailySeries = [
            'labels' => [],
            'revenue' => [],
            'orders' => [],
        ];

        $cursor = $startDate->copy();
        while ($cursor <= $endDate) {
            $bucket = $cursor->format('Y-m-d');
            $row = $dailyRows->get($bucket);
            $dailySeries['labels'][] = $cursor->format('d/m');
            $dailySeries['revenue'][] = $row ? round((float) $row->revenue, 2) : 0;
            $dailySeries['orders'][] = $row ? (int) $row->order_count : 0;
            $cursor->addDay();
        }

        $averageRevenue = count($dailySeries['revenue']) > 0
            ? array_sum($dailySeries['revenue']) / count($dailySeries['revenue'])
            : 0;
        $stdRevenue = $this->calculateStandardDeviation($dailySeries['revenue'], $averageRevenue);
        $upperBound = $averageRevenue + ($stdRevenue * 1.5);
        $lowerBound = max(0, $averageRevenue - ($stdRevenue * 1.5));

        $anomalyMarkers = [];
        $anomalyRows = [];
        $anomalyMix = [
            'positive' => 0,
            'negative' => 0,
        ];
        foreach ($dailySeries['revenue'] as $index => $revenueValue) {
            $isAnomaly = $stdRevenue > 0 && ($revenueValue > $upperBound || $revenueValue < $lowerBound);
            $anomalyMarkers[] = $isAnomaly ? $revenueValue : null;

            if ($isAnomaly) {
                $tone = $revenueValue > $upperBound ? 'positive' : 'negative';
                $baseline = max(1, $averageRevenue);
                $changePercent = round((($revenueValue - $averageRevenue) / $baseline) * 100, 1);
                $anomalyMix[$tone]++;
                $anomalyRows[] = [
                    'label' => $dailySeries['labels'][$index],
                    'revenue' => $revenueValue,
                    'orders' => $dailySeries['orders'][$index],
                    'change_percent' => $changePercent,
                    'tone' => $tone,
                    'explanation' => $tone === 'positive'
                        ? 'Vượt baseline 1.5 độ lệch chuẩn, cần đối chiếu với campaign hoặc flash-sale.'
                        : 'Thấp hơn baseline 1.5 độ lệch chuẩn, cần kiểm tra supply, UX hoặc traffic.',
                ];
            }
        }

        usort($anomalyRows, function ($left, $right) {
            return abs($right['change_percent']) <=> abs($left['change_percent']);
        });
        $anomalyRows = array_slice($anomalyRows, 0, 8);

        $customerRows = Order::query()
            ->whereIn('status', ['delivery_success', 'completed'])
            ->whereNotNull('user_id')
            ->select('user_id')
            ->selectRaw('COUNT(*) as order_count')
            ->selectRaw('SUM(total_amount) as revenue')
            ->selectRaw('MIN(created_at) as first_order_at')
            ->selectRaw('MAX(created_at) as last_order_at')
            ->groupBy('user_id')
            ->get();

        $medianRevenue = $this->calculateMedian($customerRows->pluck('revenue')->all());
        $customerSegmentsMap = [
            'champion' => ['label' => 'Khách trung thành', 'customer_count' => 0, 'revenue' => 0],
            'growth' => ['label' => 'Nhóm tăng trưởng', 'customer_count' => 0, 'revenue' => 0],
            'new' => ['label' => 'Khách mới', 'customer_count' => 0, 'revenue' => 0],
            'at_risk' => ['label' => 'Nguy cơ rời bỏ', 'customer_count' => 0, 'revenue' => 0],
            'low_touch' => ['label' => 'Tương tác thấp', 'customer_count' => 0, 'revenue' => 0],
        ];

        foreach ($customerRows as $row) {
            $segmentKey = $this->resolveCustomerSegment(
                Carbon::parse($row->last_order_at)->diffInDays(Carbon::now()),
                (int) $row->order_count,
                (float) $row->revenue,
                Carbon::parse($row->first_order_at)->diffInDays(Carbon::now()),
                $medianRevenue
            );

            $customerSegmentsMap[$segmentKey]['customer_count']++;
            $customerSegmentsMap[$segmentKey]['revenue'] += (float) $row->revenue;
        }

        $customerSegments = collect($customerSegmentsMap)
            ->map(function (array $segment, $key) use ($customerRows) {
                $totalCustomers = max(1, $customerRows->count());

                return [
                    'key' => $key,
                    'label' => $segment['label'],
                    'customer_count' => $segment['customer_count'],
                    'revenue' => round($segment['revenue'], 2),
                    'share' => round(($segment['customer_count'] / $totalCustomers) * 100, 1),
                ];
            })
            ->values();

        $recentStartDate = Carbon::now()->subDays(13)->startOfDay();
        $previousEndDate = $recentStartDate->copy()->subSecond();
        $previousStartDate = $recentStartDate->copy()->subDays(14)->startOfDay();

        $momentumRows = Cart::query()
            ->join('orders', 'orders.id', '=', 'carts.order_id')
            ->join('products', 'products.id', '=', 'carts.product_id')
            ->whereIn('orders.status', ['delivery_success', 'completed'])
            ->whereBetween('orders.created_at', [$previousStartDate, $endDate])
            ->select('products.id', 'products.slug', 'products.title', 'products.stock')
            ->selectRaw("
                SUM(CASE
                    WHEN orders.created_at >= '".$recentStartDate->format('Y-m-d H:i:s')."'
                    THEN carts.quantity ELSE 0 END
                ) as recent_units
            ")
            ->selectRaw("
                SUM(CASE
                    WHEN orders.created_at >= '".$previousStartDate->format('Y-m-d H:i:s')."' AND orders.created_at <= '".$previousEndDate->format('Y-m-d H:i:s')."'
                    THEN carts.quantity ELSE 0 END
                ) as previous_units
            ")
            ->selectRaw("
                SUM(CASE
                    WHEN orders.created_at >= '".$recentStartDate->format('Y-m-d H:i:s')."'
                    THEN carts.amount ELSE 0 END
                ) as recent_revenue
            ")
            ->groupBy('products.id', 'products.slug', 'products.title', 'products.stock')
            ->get();

        $momentumProducts = [];
        foreach ($momentumRows as $row) {
            $recentUnits = (int) $row->recent_units;
            $previousUnits = (int) $row->previous_units;
            if ($recentUnits === 0 && $previousUnits === 0) {
                continue;
            }

            $growthRate = $previousUnits > 0
                ? round((($recentUnits - $previousUnits) / $previousUnits) * 100, 1)
                : ($recentUnits > 0 ? 100.0 : 0.0);

            if ($recentUnits >= 4 && ($previousUnits === 0 || $recentUnits >= ($previousUnits * 1.5))) {
                $tone = 'breakout';
                $toneLabel = 'Bứt phá';
                $toneWeight = 3;
            } elseif ($previousUnits > 0 && $recentUnits <= ($previousUnits * 0.7)) {
                $tone = 'cooling';
                $toneLabel = 'Giảm nhiệt';
                $toneWeight = 1;
            } else {
                $tone = 'steady';
                $toneLabel = 'Ổn định';
                $toneWeight = 2;
            }

            $momentumScore = ($recentUnits * 4) + max(-30, min(120, $growthRate)) - ((int) $row->stock <= $recentUnits ? 8 : 0);

            $momentumProducts[] = [
                'id' => (int) $row->id,
                'slug' => $row->slug,
                'title' => $row->title,
                'stock' => (int) $row->stock,
                'recent_units' => $recentUnits,
                'previous_units' => $previousUnits,
                'recent_revenue' => round((float) $row->recent_revenue, 2),
                'growth_rate' => $growthRate,
                'tone' => $tone,
                'tone_label' => $toneLabel,
                'tone_weight' => $toneWeight,
                'momentum_score' => round($momentumScore, 1),
                'signal_explanation' => $this->buildMomentumExplanation($row->title, $recentUnits, $previousUnits, (int) $row->stock, $growthRate, $toneLabel),
            ];
        }

        usort($momentumProducts, function ($left, $right) {
            if ($left['tone_weight'] === $right['tone_weight']) {
                return $right['momentum_score'] <=> $left['momentum_score'];
            }

            return $right['tone_weight'] <=> $left['tone_weight'];
        });

        $momentumProducts = array_slice($momentumProducts, 0, 8);
        $breakoutCount = count(array_filter($momentumProducts, function (array $product) {
            return $product['tone'] === 'breakout';
        }));
        $atRiskSegment = $customerSegments->firstWhere('key', 'at_risk');
        $championSegment = $customerSegments->firstWhere('key', 'champion');

        $recommendations = [];
        if ($atRiskSegment && $atRiskSegment['revenue'] > 0) {
            $recommendations[] = [
                'title' => 'Ưu tiên win-back nhóm có nguy cơ rời bỏ',
                'description' => 'Nhóm có nguy cơ rời bỏ đang giữ '.number_format($atRiskSegment['revenue'], 0, '.', ',').' doanh thu lịch sử, phù hợp để chạy email hoặc remarketing kéo quay lại.',
            ];
        }

        $lowStockMomentum = array_values(array_filter($momentumProducts, function (array $product) {
            return $product['tone'] === 'breakout' && $product['stock'] <= max(3, $product['recent_units']);
        }));
        if (!empty($lowStockMomentum)) {
            $recommendations[] = [
                'title' => 'Sản phẩm bứt phá cần theo dõi tồn',
                'description' => $lowStockMomentum[0]['title'].' đang tăng tốc nhanh nhưng tồn kho không cao, nên cân bằng giữa đẩy marketing và kế hoạch nhập thêm.',
            ];
        }

        if (!empty($anomalyRows)) {
            $recommendations[] = [
                'title' => 'Đọc anomaly theo sự kiện',
                'description' => 'Có '.count($anomalyRows).' ngày vượt khỏi baseline, nên đối chiếu với campaign, traffic source và thay đổi vận hành trong cùng ngày.',
            ];
        }

        if (empty($recommendations)) {
            $recommendations[] = [
                'title' => 'Tín hiệu mô hình đang ổn định',
                'description' => 'Chưa có xu hướng bất thường rõ rệt về demand, segment hay momentum trong chu kỳ hiện tại.',
            ];
        }

        $chartData = [
            'revenueSignal' => [
                'labels' => $dailySeries['labels'],
                'revenue' => $dailySeries['revenue'],
                'baseline' => array_fill(0, count($dailySeries['labels']), round($averageRevenue, 2)),
                'anomalies' => $anomalyMarkers,
            ],
            'customerSegments' => [
                'labels' => $customerSegments->pluck('label')->values(),
                'counts' => $customerSegments->pluck('customer_count')->values(),
            ],
            'anomalyMix' => [
                'labels' => collect([
                    'Tăng đột biến',
                    'Giảm mạnh',
                ]),
                'counts' => collect([
                    $anomalyMix['positive'],
                    $anomalyMix['negative'],
                ]),
            ],
            'momentum' => [
                'labels' => collect($momentumProducts)->take(6)->pluck('title')->values(),
                'recent_units' => collect($momentumProducts)->take(6)->pluck('recent_units')->values(),
                'growth_rates' => collect($momentumProducts)->take(6)->pluck('growth_rate')->values(),
            ],
        ];

        $summaryCards = [
            [
                'label' => 'Khách trung thành',
                'value' => $championSegment ? $championSegment['customer_count'] : 0,
                'format' => 'number',
                'icon' => 'fa-crown',
                'note' => 'Khách có recency tốt, tần suất cao và doanh thu vượt median.',
            ],
            [
                'label' => 'Doanh thu nhóm rời bỏ',
                'value' => $atRiskSegment ? $atRiskSegment['revenue'] : 0,
                'format' => 'currency',
                'icon' => 'fa-life-ring',
                'note' => 'Doanh thu lịch sử đang nằm ở nhóm có nguy cơ rời bỏ.',
            ],
            [
                'label' => 'Ngày bất thường',
                'value' => count($anomalyRows),
                'format' => 'number',
                'icon' => 'fa-wave-square',
                'note' => 'Số ngày có doanh thu lệch khỏi baseline 1.5 độ lệch chuẩn.',
            ],
            [
                'label' => 'Sản phẩm bứt phá',
                'value' => $breakoutCount,
                'format' => 'number',
                'icon' => 'fa-rocket',
                'note' => 'SKU có tốc độ tăng nhu cầu rõ hơn giai đoạn trước.',
            ],
        ];

        $signalExplanations = collect([]);
        foreach (collect($momentumProducts)->take(4) as $product) {
            $signalExplanations->push([
                'type' => 'sku',
                'title' => $product['title'],
                'description' => $product['signal_explanation'],
            ]);
        }

        foreach (collect($anomalyRows)->take(3) as $row) {
            $signalExplanations->push([
                'type' => 'anomaly',
                'title' => 'Ngày '.$row['label'],
                'description' => 'Doanh thu '.number_format($row['revenue'], 0, ',', '.').'đ với '.number_format($row['orders'], 0, ',', '.').' đơn, lệch '.number_format($row['change_percent'], 1, ',', '.').'% so với baseline. '.$row['explanation'],
            ]);
        }

        foreach ($customerSegments->filter(function (array $segment) {
            return $segment['customer_count'] > 0;
        })->sortByDesc('revenue')->take(3) as $segment) {
            $signalExplanations->push([
                'type' => 'segment',
                'title' => $segment['label'],
                'description' => 'Nhóm này đang có '.number_format($segment['customer_count'], 0, ',', '.').' khách, chiếm '.number_format($segment['share'], 1, ',', '.').'% tệp có giao dịch và tạo '.number_format($segment['revenue'], 0, ',', '.').'đ doanh thu lịch sử.',
            ]);
        }

        $watchlistCandidates = [
            'products' => collect($momentumProducts)->take(6)->map(function (array $product) {
                return [
                    'key' => 'product:'.$product['id'],
                    'type' => 'SKU',
                    'label' => $product['title'],
                    'meta' => '14 ngày: '.$product['recent_units'].' | trước đó: '.$product['previous_units'].' | tồn: '.$product['stock'],
                    'description' => $product['signal_explanation'],
                ];
            })->values(),
            'segments' => $customerSegments->filter(function (array $segment) {
                return $segment['customer_count'] > 0;
            })->take(5)->map(function (array $segment) {
                return [
                    'key' => 'segment:'.$segment['key'],
                    'type' => 'Nhóm khách',
                    'label' => $segment['label'],
                    'meta' => number_format($segment['customer_count'], 0, ',', '.').' khách | '.number_format($segment['share'], 1, ',', '.').'%',
                    'description' => 'Theo dõi nhóm này để không bỏ lỡ biến động về recency, doanh thu hoặc tỷ trọng khách.',
                ];
            })->values(),
            'anomalies' => collect($anomalyRows)->take(6)->map(function (array $row) {
                return [
                    'key' => 'anomaly:'.$row['label'],
                    'type' => 'Ngày bất thường',
                    'label' => 'Ngày '.$row['label'],
                    'meta' => number_format($row['change_percent'], 1, ',', '.').'% | '.number_format($row['orders'], 0, ',', '.').' đơn',
                    'description' => $row['explanation'],
                ];
            })->values(),
        ];

        return view('backend.data-scientist.index', compact(
            'range',
            'startDate',
            'endDate',
            'summaryCards',
            'customerSegments',
            'momentumProducts',
            'anomalyRows',
            'recommendations',
            'chartData',
            'signalExplanations',
            'watchlistCandidates'
        ));
    }

    protected function resolveRange(Request $request)
    {
        $allowedRanges = [60, 90, 180];
        $range = (int) $request->query('range', 90);

        return in_array($range, $allowedRanges, true) ? $range : 90;
    }

    protected function calculateStandardDeviation(array $values, $mean)
    {
        $count = count($values);
        if ($count === 0) {
            return 0;
        }

        $variance = 0;
        foreach ($values as $value) {
            $variance += pow(((float) $value) - (float) $mean, 2);
        }

        return sqrt($variance / $count);
    }

    protected function calculateMedian(array $values)
    {
        $values = array_values(array_filter($values, function ($value) {
            return $value !== null;
        }));

        if (empty($values)) {
            return 0;
        }

        sort($values);
        $count = count($values);
        $middle = (int) floor($count / 2);

        if ($count % 2 === 0) {
            return ((float) $values[$middle - 1] + (float) $values[$middle]) / 2;
        }

        return (float) $values[$middle];
    }

    protected function resolveCustomerSegment($recencyDays, $orderCount, $revenue, $daysSinceFirstOrder, $medianRevenue)
    {
        if ($recencyDays <= 30 && $orderCount >= 3 && $revenue >= $medianRevenue) {
            return 'champion';
        }

        if ($daysSinceFirstOrder <= 30 && $orderCount === 1) {
            return 'new';
        }

        if ($recencyDays <= 45 && $orderCount >= 2) {
            return 'growth';
        }

        if ($recencyDays >= 90 && ($orderCount >= 2 || $revenue >= $medianRevenue)) {
            return 'at_risk';
        }

        return 'low_touch';
    }

    protected function buildMomentumExplanation($title, $recentUnits, $previousUnits, $stock, $growthRate, $toneLabel)
    {
        return $title.' đang ở trạng thái '.$toneLabel.' vì 14 ngày gần đây bán '.$recentUnits.' sản phẩm, giai đoạn trước bán '.$previousUnits.' sản phẩm, tồn còn '.$stock.' và tăng trưởng '.number_format($growthRate, 1, ',', '.').'%';
    }
}
