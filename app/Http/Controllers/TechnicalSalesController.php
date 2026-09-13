<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Coupon;
use App\Models\Message;
use App\Models\Order;
use App\Models\Product;
use App\Models\Shipping;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class TechnicalSalesController extends Controller
{
    public function index(Request $request)
    {
        $range = $this->resolveRange($request);
        $endDate = Carbon::now()->endOfDay();
        $startDate = Carbon::now()->subDays($range - 1)->startOfDay();

        $leadRows = Message::query()
            ->whereBetween('created_at', [$startDate, $endDate])
            ->orderByDesc('created_at')
            ->get()
            ->map(function (Message $message) {
                $analysis = $this->analyzeLead($message);

                return [
                    'id' => $message->id,
                    'name' => $message->name,
                    'email' => $message->email,
                    'phone' => $message->phone,
                    'subject' => $message->subject,
                    'message' => trim((string) $message->message),
                    'created_at' => $message->created_at,
                    'age_hours' => $analysis['age_hours'],
                    'is_unread' => $analysis['is_unread'],
                    'intent_key' => $analysis['intent_key'],
                    'intent_label' => $analysis['intent_label'],
                    'priority_score' => $analysis['priority_score'],
                    'priority_key' => $analysis['priority_key'],
                    'priority_label' => $analysis['priority_label'],
                    'summary' => Str::limit(trim((string) $message->message), 95),
                ];
            })
            ->sortByDesc('priority_score')
            ->values();

        $intentMap = collect([
            ['key' => 'pricing', 'label' => 'Báo giá'],
            ['key' => 'technical', 'label' => 'Tư vấn kỹ thuật'],
            ['key' => 'enterprise', 'label' => 'Doanh nghiệp'],
            ['key' => 'support', 'label' => 'Hậu mãi'],
            ['key' => 'general', 'label' => 'Tổng quát'],
        ]);

        $intentDistribution = $intentMap->map(function (array $intent) use ($leadRows) {
            return [
                'key' => $intent['key'],
                'label' => $intent['label'],
                'count' => $leadRows->where('intent_key', $intent['key'])->count(),
            ];
        })->values();

        $leadPriorityDistribution = collect([
            ['key' => 'hot', 'label' => 'Ưu tiên cao'],
            ['key' => 'warm', 'label' => 'Ưu tiên vừa'],
            ['key' => 'followup', 'label' => 'Theo dõi'],
        ])->map(function (array $priority) use ($leadRows) {
            return [
                'key' => $priority['key'],
                'label' => $priority['label'],
                'count' => $leadRows->where('priority_key', $priority['key'])->count(),
            ];
        })->values();

        $leadQueue = $leadRows->take(8)->values();
        $unreadLeadCount = $leadRows->where('is_unread', true)->count();
        $hotLeadCount = $leadRows->where('priority_key', 'hot')->count();

        $bundlePairs = $this->buildBundlePairs($startDate, $endDate);
        $bundleChart = collect($bundlePairs)
            ->take(5)
            ->map(function (array $bundle) {
                return [
                    'label' => $bundle['label'],
                    'count' => $bundle['bundle_count'],
                    'revenue' => round((float) $bundle['bundle_revenue'], 2),
                ];
            })
            ->values();

        $deliveredOrders = Order::query()
            ->where('status', 'delivered')
            ->whereBetween('created_at', [$startDate, $endDate]);
        $deliveredRevenue = (float) (clone $deliveredOrders)->sum('total_amount');

        $priceBandsRaw = (clone $deliveredOrders)
            ->selectRaw("
                CASE
                    WHEN total_amount < 1000000 THEN 'entry'
                    WHEN total_amount < 3000000 THEN 'core'
                    ELSE 'premium'
                END as band_key
            ")
            ->selectRaw('COUNT(*) as order_count')
            ->selectRaw('SUM(total_amount) as revenue')
            ->selectRaw('AVG(total_amount) as average_order_value')
            ->groupBy('band_key')
            ->get()
            ->keyBy('band_key');

        $priceBands = collect([
            ['key' => 'entry', 'label' => 'Entry'],
            ['key' => 'core', 'label' => 'Core'],
            ['key' => 'premium', 'label' => 'Premium'],
        ])->map(function (array $band) use ($priceBandsRaw, $deliveredRevenue) {
            $row = $priceBandsRaw->get($band['key']);
            $revenue = $row ? round((float) $row->revenue, 2) : 0;
            $orderCount = $row ? (int) $row->order_count : 0;

            return [
                'key' => $band['key'],
                'label' => $band['label'],
                'order_count' => $orderCount,
                'revenue' => $revenue,
                'average_order_value' => $row ? round((float) $row->average_order_value, 2) : 0,
                'revenue_share' => $deliveredRevenue > 0 ? round(($revenue / $deliveredRevenue) * 100, 1) : 0,
            ];
        })->values();

        $productTalkTracks = Cart::query()
            ->join('orders', 'orders.id', '=', 'carts.order_id')
            ->join('products', 'products.id', '=', 'carts.product_id')
            ->where('orders.status', 'delivered')
            ->whereBetween('orders.created_at', [$startDate, $endDate])
            ->select('products.id', 'products.title', 'products.condition', 'products.price', 'products.stock')
            ->selectRaw('SUM(carts.quantity) as units_sold')
            ->selectRaw('SUM(carts.amount) as revenue')
            ->groupBy('products.id', 'products.title', 'products.condition', 'products.price', 'products.stock')
            ->orderByDesc('revenue')
            ->limit(5)
            ->get();

        $talkTrackChart = $productTalkTracks
            ->map(function ($product) {
                return [
                    'label' => $product->title,
                    'revenue' => round((float) $product->revenue, 2),
                    'units' => (int) $product->units_sold,
                ];
            })
            ->values();

        $premiumBand = $priceBands->firstWhere('key', 'premium');
        $actions = [];
        if ($hotLeadCount > 0) {
            $actions[] = [
                'title' => 'Ưu tiên hot lead',
                'description' => 'Đang có '.$hotLeadCount.' lead điểm cao, phù hợp để gọi lại sớm thay vì chỉ trả lời email.',
            ];
        }

        if (!empty($bundlePairs)) {
            $actions[] = [
                'title' => 'Chốt combo thay vì sản phẩm lẻ',
                'description' => 'Cặp '.$bundlePairs[0]['label'].' đang xuất hiện '.$bundlePairs[0]['bundle_count'].' lần trong đơn thành công, có thể đưa vào script technical sales.',
            ];
        }

        if ($premiumBand && $premiumBand['revenue_share'] >= 30) {
            $actions[] = [
                'title' => 'Đẩy playbook premium',
                'description' => 'Nhóm premium đang đóng góp '.$premiumBand['revenue_share'].'% doanh thu, nên ưu tiên thông điệp hiệu năng, bảo hành và upsell phụ kiện.',
            ];
        }

        if (empty($actions)) {
            $actions[] = [
                'title' => 'Lead queue đang cân bằng',
                'description' => 'Chưa có dấu hiệu bất thường về intent hoặc cơ hội combo trong chu kỳ hiện tại.',
            ];
        }

        $chartData = [
            'intentDistribution' => [
                'labels' => $intentDistribution->pluck('label')->values(),
                'counts' => $intentDistribution->pluck('count')->values(),
            ],
            'leadPriority' => [
                'labels' => $leadPriorityDistribution->pluck('label')->values(),
                'counts' => $leadPriorityDistribution->pluck('count')->values(),
            ],
            'priceBands' => [
                'labels' => $priceBands->pluck('label')->values(),
                'revenue' => $priceBands->pluck('revenue')->values(),
                'orders' => $priceBands->pluck('order_count')->values(),
            ],
            'bundlePairs' => [
                'labels' => $bundleChart->pluck('label')->values(),
                'counts' => $bundleChart->pluck('count')->values(),
                'revenue' => $bundleChart->pluck('revenue')->values(),
            ],
            'talkTracks' => [
                'labels' => $talkTrackChart->pluck('label')->values(),
                'revenue' => $talkTrackChart->pluck('revenue')->values(),
                'units' => $talkTrackChart->pluck('units')->values(),
            ],
        ];

        $summaryCards = [
            [
                'label' => 'Lead chưa đọc',
                'value' => $unreadLeadCount,
                'format' => 'number',
                'icon' => 'fa-envelope-open-text',
                'note' => 'Lead trong '.$range.' ngày chưa được đọc.',
            ],
            [
                'label' => 'Lead ưu tiên cao',
                'value' => $hotLeadCount,
                'format' => 'number',
                'icon' => 'fa-bolt',
                'note' => 'Lead được ưu tiên cao theo độ mới, số điện thoại và intent.',
            ],
            [
                'label' => 'Cơ hội combo',
                'value' => count($bundlePairs),
                'format' => 'number',
                'icon' => 'fa-layer-group',
                'note' => 'Cặp sản phẩm có tần suất đồng mua để đưa vào script technical sales.',
            ],
            [
                'label' => 'Tỷ trọng doanh thu premium',
                'value' => $premiumBand ? $premiumBand['revenue_share'] : 0,
                'format' => 'percent',
                'icon' => 'fa-gem',
                'note' => 'Tỷ trọng doanh thu đến từ đơn giá trị cao.',
            ],
        ];

        $replySuggestions = $leadQueue->map(function (array $lead) use ($productTalkTracks, $bundlePairs) {
            return $this->buildReplySuggestion($lead, $productTalkTracks, $bundlePairs);
        })->values();

        $quoteProducts = Product::query()
            ->where('status', 'active')
            ->orderBy('title')
            ->limit(40)
            ->get(['id', 'title', 'price', 'stock', 'condition']);

        $quoteShippings = Shipping::query()
            ->where('status', 'active')
            ->orderBy('price')
            ->get(['id', 'type', 'price']);

        $quoteCoupons = Coupon::query()
            ->where('status', 'active')
            ->orderBy('code')
            ->get(['id', 'code', 'type', 'value']);

        $quoteCombos = collect($bundlePairs)->map(function (array $bundle) {
            return [
                'label' => $bundle['label'],
                'products' => $bundle['product_titles'],
                'bundle_count' => $bundle['bundle_count'],
                'bundle_revenue' => round((float) $bundle['bundle_revenue'], 2),
            ];
        })->values();

        return view('backend.technical-sales.index', compact(
            'range',
            'startDate',
            'endDate',
            'summaryCards',
            'leadQueue',
            'intentDistribution',
            'bundlePairs',
            'priceBands',
            'productTalkTracks',
            'actions',
            'chartData',
            'replySuggestions',
            'quoteProducts',
            'quoteShippings',
            'quoteCoupons',
            'quoteCombos'
        ));
    }

    protected function resolveRange(Request $request)
    {
        $allowedRanges = [30, 90, 180];
        $range = (int) $request->query('range', 90);

        return in_array($range, $allowedRanges, true) ? $range : 90;
    }

    protected function analyzeLead(Message $message)
    {
        $normalized = Str::lower(Str::ascii(trim($message->subject.' '.$message->message)));
        $intentKey = 'general';

        $intentKeywords = [
            'enterprise' => ['doanh nghiep', 'company', 'mua si', 'wholesale', 'du an', 'partner', 'reseller'],
            'technical' => ['tu van', 'technical', 'thong so', 'so sanh', 'compare', 'bluetooth', 'codec', 'driver', 'micro'],
            'pricing' => ['bao gia', 'gia', 'price', 'quote', 'discount', 'cost'],
            'support' => ['bao hanh', 'support', 'repair', 'doi tra', 'loi', 'hong'],
        ];

        foreach ($intentKeywords as $candidateKey => $keywords) {
            foreach ($keywords as $keyword) {
                if (Str::contains($normalized, $keyword)) {
                    $intentKey = $candidateKey;
                    break 2;
                }
            }
        }

        $intentLabels = [
            'enterprise' => 'Doanh nghiệp',
            'technical' => 'Tư vấn kỹ thuật',
            'pricing' => 'Báo giá',
            'support' => 'Hậu mãi',
            'general' => 'Tổng quát',
        ];

        $ageHours = Carbon::parse($message->created_at)->diffInHours(Carbon::now());
        $priorityScore = $message->read_at ? 10 : 40;

        if ($ageHours <= 12) {
            $priorityScore += 20;
        } elseif ($ageHours <= 48) {
            $priorityScore += 12;
        } else {
            $priorityScore += 6;
        }

        if (!empty(trim((string) $message->phone))) {
            $priorityScore += 8;
        }

        if (in_array($intentKey, ['enterprise', 'technical', 'pricing'], true)) {
            $priorityScore += 12;
        }

        if (Str::contains($normalized, ['gap', 'urgent', 'ngay', 'som'])) {
            $priorityScore += 8;
        }

        if ($priorityScore >= 70) {
            $priorityKey = 'hot';
            $priorityLabel = 'Hot';
        } elseif ($priorityScore >= 50) {
            $priorityKey = 'warm';
            $priorityLabel = 'Warm';
        } else {
            $priorityKey = 'followup';
            $priorityLabel = 'Theo dõi';
        }

        return [
            'intent_key' => $intentKey,
            'intent_label' => $intentLabels[$intentKey] ?? 'Tổng quát',
            'age_hours' => $ageHours,
            'is_unread' => $message->read_at === null,
            'priority_score' => $priorityScore,
            'priority_key' => $priorityKey,
            'priority_label' => $priorityLabel,
        ];
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

    protected function buildReplySuggestion(array $lead, $productTalkTracks, array $bundlePairs)
    {
        $topProduct = $productTalkTracks->first();
        $topBundle = $bundlePairs[0]['label'] ?? null;
        $name = trim((string) $lead['name']) !== '' ? $lead['name'] : 'anh/chị';

        $templates = [
            'pricing' => [
                'subject' => 'Phản hồi yêu cầu báo giá từ shop',
                'body' => 'Chào '.$name.', bên em đã nhận nhu cầu báo giá. Để gửi đúng cấu hình và mức giá phù hợp, anh/chị cho em xin thêm mức ngân sách dự kiến, thiết bị đang dùng và thời điểm cần nhận hàng. '.($topBundle ? 'Nếu cần chốt nhanh, em có thể gửi luôn phương án combo '.$topBundle.'.' : ''),
                'next_step' => 'Gửi bảng giá + gọi lại trong 2 giờ',
            ],
            'technical' => [
                'subject' => 'Phản hồi nhu cầu tư vấn kỹ thuật',
                'body' => 'Chào '.$name.', em đã đọc yêu cầu tư vấn kỹ thuật. Anh/chị cho em xin thêm thiết bị nguồn phát, môi trường sử dụng và ưu tiên âm thanh để em chốt model phù hợp hơn. '.($topProduct ? 'Hiện bên em đang có '.$topProduct->title.' được khách hỏi nhiều ở nhóm nhu cầu tương tự.' : ''),
                'next_step' => 'Xác nhận use-case + chốt 2 lựa chọn kỹ thuật',
            ],
            'enterprise' => [
                'subject' => 'Phản hồi nhu cầu mua số lượng / doanh nghiệp',
                'body' => 'Chào '.$name.', em đã nhận yêu cầu mua theo dự án/doanh nghiệp. Anh/chị giúp em thêm số lượng dự kiến, thời gian cần hàng và yêu cầu xuất hóa đơn để em chuẩn bị báo giá và chính sách phù hợp.',
                'next_step' => 'Xin số lượng + timeline + hóa đơn',
            ],
            'support' => [
                'subject' => 'Phản hồi yêu cầu hỗ trợ sau bán',
                'body' => 'Chào '.$name.', bên em đã nhận yêu cầu hỗ trợ. Anh/chị giúp em gửi thêm mã đơn hàng, hình ảnh/video lỗi và thời điểm phát sinh để em kiểm tra bảo hành hoặc hướng xử lý nhanh nhất.',
                'next_step' => 'Xin mã đơn + bằng chứng lỗi',
            ],
            'general' => [
                'subject' => 'Phản hồi thông tin từ shop',
                'body' => 'Chào '.$name.', bên em đã nhận tin nhắn của anh/chị. Anh/chị cho em xin thêm nhu cầu sử dụng, mức ngân sách và sản phẩm đang quan tâm để em tư vấn ngắn gọn và đúng trọng tâm hơn.',
                'next_step' => 'Làm rõ nhu cầu + ngân sách',
            ],
        ];

        $template = $templates[$lead['intent_key']] ?? $templates['general'];

        return [
            'lead_id' => $lead['id'],
            'name' => $lead['name'],
            'intent_label' => $lead['intent_label'],
            'priority_label' => $lead['priority_label'],
            'subject' => $template['subject'],
            'body' => $template['body'],
            'next_step' => $template['next_step'],
            'copy_text' => "Chủ đề: ".$template['subject']."\n\n".$template['body'],
        ];
    }
}
