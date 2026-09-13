<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Message;
use App\Models\Order;
use App\Models\Product;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class DataEngineerController extends Controller
{
    public function index(Request $request)
    {
        $range = $this->resolveRange($request);
        $endDate = Carbon::now()->endOfDay();
        $startDate = Carbon::now()->subDays($range - 1)->startOfDay();
        $recentThreshold = Carbon::now()->subDays(7)->startOfDay();
        $activityStartDate = Carbon::now()->subDays(13)->startOfDay();

        $ordersWithoutLines = DB::query()
            ->fromSub($this->buildOrderAuditBase(), 'order_audit')
            ->where('line_count', 0)
            ->count();
        $quantityMismatch = DB::query()
            ->fromSub($this->buildOrderAuditBase(), 'order_audit')
            ->where('line_count', '>', 0)
            ->whereColumn('cart_quantity', '<>', 'quantity')
            ->count();
        $amountMismatch = DB::query()
            ->fromSub($this->buildOrderAuditBase(), 'order_audit')
            ->where('line_count', '>', 0)
            ->whereRaw('ABS(cart_amount - sub_total) > 1')
            ->count();

        $activeProducts = Product::query()->where('status', 'active');
        $activeProductCount = (int) (clone $activeProducts)->count();
        $missingProductBrand = (int) (clone $activeProducts)->whereNull('brand_id')->count();
        $missingProductCategory = (int) (clone $activeProducts)->whereNull('cat_id')->count();
        $missingProductPhoto = (int) (clone $activeProducts)
            ->where(function ($query) {
                $query->whereNull('photo')->orWhereRaw("TRIM(photo) = ''");
            })
            ->count();
        $missingProductDescription = (int) (clone $activeProducts)
            ->where(function ($query) {
                $query->whereNull('description')->orWhereRaw("TRIM(description) = ''");
            })
            ->count();
        $catalogScore = $this->calculateContractScore($activeProductCount, [
            $missingProductBrand,
            $missingProductCategory,
            $missingProductPhoto,
            $missingProductDescription,
        ]);

        $totalOrders = (int) Order::count();
        $missingShipping = (int) Order::whereNull('shipping_id')->count();
        $salesFactScore = $this->calculateContractScore($totalOrders, [
            $ordersWithoutLines,
            $quantityMismatch,
            $amountMismatch,
            $missingShipping,
        ]);

        $totalUsers = (int) User::count();
        $missingCustomerEmail = (int) User::query()
            ->where(function ($query) {
                $query->whereNull('email')->orWhereRaw("TRIM(email) = ''");
            })
            ->count();
        $blankCustomerName = (int) User::query()
            ->whereRaw("TRIM(COALESCE(name, '')) = ''")
            ->count();
        $socialLinkMismatch = (int) User::query()
            ->where(function ($query) {
                $query->whereNull('provider')->whereNotNull('provider_id');
            })
            ->orWhere(function ($query) {
                $query->whereNotNull('provider')->whereNull('provider_id');
            })
            ->count();
        $duplicateCustomerEmails = (int) DB::table('users')
            ->select('email', DB::raw('COUNT(*) as duplicate_count'))
            ->whereNotNull('email')
            ->whereRaw("TRIM(email) <> ''")
            ->groupBy('email')
            ->havingRaw('COUNT(*) > 1')
            ->get()
            ->sum('duplicate_count');
        $customerScore = $this->calculateContractScore($totalUsers, [
            $missingCustomerEmail,
            $blankCustomerName,
            $socialLinkMismatch,
            $duplicateCustomerEmails,
        ]);

        $totalMessages = (int) Message::count();
        $missingLeadSubject = (int) Message::query()
            ->whereRaw("TRIM(COALESCE(subject, '')) = ''")
            ->count();
        $missingLeadEmail = (int) Message::query()
            ->whereRaw("TRIM(COALESCE(email, '')) = ''")
            ->count();
        $missingLeadPhone = (int) Message::query()
            ->whereRaw("TRIM(COALESCE(phone, '')) = ''")
            ->count();
        $staleUnreadMessages = (int) Message::query()
            ->whereNull('read_at')
            ->where('created_at', '<', Carbon::now()->subHours(48))
            ->count();
        $leadScore = $this->calculateContractScore($totalMessages, [
            $missingLeadSubject,
            $missingLeadEmail,
            $missingLeadPhone,
            $staleUnreadMessages,
        ]);

        $contracts = collect([
            [
                'label' => 'Data contract danh mục',
                'score' => $catalogScore,
                'details' => [
                    'Brand thiếu trong sản phẩm active' => $missingProductBrand,
                    'Danh mục thiếu trong sản phẩm active' => $missingProductCategory,
                    'Ảnh sản phẩm bị thiếu' => $missingProductPhoto,
                    'Mô tả sản phẩm bị thiếu' => $missingProductDescription,
                ],
            ],
            [
                'label' => 'Data contract sales fact',
                'score' => $salesFactScore,
                'details' => [
                    'Đơn hàng không có dòng cart' => $ordersWithoutLines,
                    'Lệch tổng số lượng' => $quantityMismatch,
                    'Lệch sub_total và cart' => $amountMismatch,
                    'Đơn hàng thiếu shipping_id' => $missingShipping,
                ],
            ],
            [
                'label' => 'Data contract khách hàng',
                'score' => $customerScore,
                'details' => [
                    'Tài khoản thiếu email' => $missingCustomerEmail,
                    'Tài khoản tên rỗng' => $blankCustomerName,
                    'Liên kết social bị lệch' => $socialLinkMismatch,
                    'Email bị trùng' => $duplicateCustomerEmails,
                ],
            ],
            [
                'label' => 'Data contract lead inbox',
                'score' => $leadScore,
                'details' => [
                    'Lead thiếu tiêu đề' => $missingLeadSubject,
                    'Lead thiếu email' => $missingLeadEmail,
                    'Lead thiếu số điện thoại' => $missingLeadPhone,
                    'Chưa đọc quá 48 giờ' => $staleUnreadMessages,
                ],
            ],
        ]);

        $freshnessCards = collect([
            ['label' => 'Users', 'model' => User::class, 'icon' => 'fa-users'],
            ['label' => 'Orders', 'model' => Order::class, 'icon' => 'fa-shopping-cart'],
            ['label' => 'Products', 'model' => Product::class, 'icon' => 'fa-boxes'],
            ['label' => 'Messages', 'model' => Message::class, 'icon' => 'fa-envelope'],
            ['label' => 'Carts', 'model' => Cart::class, 'icon' => 'fa-stream'],
        ])->map(function (array $dataset) use ($recentThreshold) {
            $model = $dataset['model'];
            $latestAt = $model::query()->max('created_at');
            $latest = $latestAt ? Carbon::parse($latestAt) : null;

            return [
                'label' => $dataset['label'],
                'icon' => $dataset['icon'],
                'total' => (int) $model::count(),
                'recent_count' => (int) $model::query()->where('created_at', '>=', $recentThreshold)->count(),
                'latest_at' => $latest,
                'stale_days' => $latest ? Carbon::now()->diffInDays($latest) : null,
            ];
        })->values();

        $averageContractScore = round($contracts->avg('score'), 1);
        $staleDatasetCount = $freshnessCards->filter(function (array $dataset) {
            return $dataset['stale_days'] !== null && $dataset['stale_days'] >= 7;
        })->count();
        $integrityIncidentCount = $ordersWithoutLines + $quantityMismatch + $amountMismatch + $duplicateCustomerEmails + $socialLinkMismatch;
        $ingestionCountLast7Days = $freshnessCards->sum('recent_count');

        $activityChart = [
            'labels' => [],
            'users' => [],
            'orders' => [],
            'messages' => [],
        ];
        $userActivity = $this->buildDailyCounts(User::query(), $activityStartDate, $endDate);
        $orderActivity = $this->buildDailyCounts(Order::query(), $activityStartDate, $endDate);
        $messageActivity = $this->buildDailyCounts(Message::query(), $activityStartDate, $endDate);

        $cursor = $activityStartDate->copy();
        while ($cursor <= $endDate) {
            $bucket = $cursor->format('Y-m-d');
            $activityChart['labels'][] = $cursor->format('d/m');
            $activityChart['users'][] = $userActivity[$bucket] ?? 0;
            $activityChart['orders'][] = $orderActivity[$bucket] ?? 0;
            $activityChart['messages'][] = $messageActivity[$bucket] ?? 0;
            $cursor->addDay();
        }

        $freshnessChart = [
            'labels' => $freshnessCards->pluck('label')->values(),
            'stale_days' => $freshnessCards->map(function (array $dataset) {
                return $dataset['stale_days'] ?? 0;
            })->values(),
        ];

        $contractScoreChart = [
            'labels' => $contracts->pluck('label')->values(),
            'scores' => $contracts->pluck('score')->values(),
        ];

        $datasetVolumeChart = [
            'labels' => $freshnessCards->pluck('label')->values(),
            'totals' => $freshnessCards->pluck('total')->values(),
            'recent' => $freshnessCards->pluck('recent_count')->values(),
        ];

        $integrityRows = [
            [
                'label' => 'Đơn hàng không có dòng cart',
                'count' => $ordersWithoutLines,
                'description' => 'Rủi ro mất dữ liệu line-item cho dashboard và báo cáo chi tiết.',
            ],
            [
                'label' => 'Lệch tổng số lượng',
                'count' => $quantityMismatch,
                'description' => 'Tổng số lượng trên orders khác tổng số lượng lấy từ carts.',
            ],
            [
                'label' => 'Lệch sub_total',
                'count' => $amountMismatch,
                'description' => 'Tổng amount từ cart khác sub_total trên orders.',
            ],
            [
                'label' => 'Email khách hàng bị trùng',
                'count' => $duplicateCustomerEmails,
                'description' => 'Cảnh báo duplicate logic trên định danh khách hàng.',
            ],
            [
                'label' => 'Liên kết social bị lệch',
                'count' => $socialLinkMismatch,
                'description' => 'provider và provider_id hiện không đi cùng nhau.',
            ],
        ];

        $integrityChart = [
            'labels' => collect($integrityRows)->pluck('label')->values(),
            'counts' => collect($integrityRows)->pluck('count')->values(),
        ];

        $pipelineAlerts = [];
        if ($amountMismatch > 0 || $quantityMismatch > 0) {
            $pipelineAlerts[] = [
                'title' => 'Cần đối soát fact order-cart',
                'description' => 'Phát hiện '.$quantityMismatch.' lệch quantity và '.$amountMismatch.' lệch amount, nên ưu tiên đối chiếu luồng nạp fact.',
            ];
        }

        if ($catalogScore < 85) {
            $pipelineAlerts[] = [
                'title' => 'Danh mục chưa đạt contract',
                'description' => 'Điểm contract danh mục đang ở mức '.$catalogScore.'/100, nên bổ sung brand/category/ảnh/mô tả trước khi mở rộng dashboard sản phẩm.',
            ];
        }

        if ($staleDatasetCount > 0) {
            $pipelineAlerts[] = [
                'title' => 'Có dataset đang stale',
                'description' => $staleDatasetCount.' bảng dữ liệu chưa có cập nhật trong hơn 7 ngày, nên kiểm tra scheduler và ingest job.',
            ];
        }

        if ($staleUnreadMessages > 0) {
            $pipelineAlerts[] = [
                'title' => 'Lead queue chưa được xử lý',
                'description' => $staleUnreadMessages.' lead chưa đọc quá 48 giờ, nên thêm SLA tracking để không mất cơ hội bán hàng.',
            ];
        }

        if (empty($pipelineAlerts)) {
            $pipelineAlerts[] = [
                'title' => 'Pipeline đang ổn định',
                'description' => 'Chưa có cảnh báo lớn về freshness hay referential consistency trong chu kỳ hiện tại.',
            ];
        }

        $errorQueue = $this->buildErrorQueue(18);
        $exportTargets = [
            [
                'key' => 'order-cart-mismatch',
                'label' => 'Xuất lỗi order-cart',
                'note' => 'Xuất các đơn thiếu dòng cart, lệch số lượng và lệch amount.',
            ],
            [
                'key' => 'product-contract-gap',
                'label' => 'Xuất lỗi danh mục',
                'note' => 'Xuất sản phẩm active đang thiếu brand, category, ảnh hoặc mô tả.',
            ],
            [
                'key' => 'duplicate-user-email',
                'label' => 'Xuất user trùng email',
                'note' => 'Xuất các tài khoản dùng chung email để đội data xử lý hợp nhất.',
            ],
        ];

        return view('backend.data-engineer.index', compact(
            'range',
            'startDate',
            'endDate',
            'averageContractScore',
            'staleDatasetCount',
            'integrityIncidentCount',
            'ingestionCountLast7Days',
            'contracts',
            'freshnessCards',
            'integrityRows',
            'pipelineAlerts',
            'activityChart',
            'freshnessChart',
            'contractScoreChart',
            'datasetVolumeChart',
            'integrityChart',
            'errorQueue',
            'exportTargets'
        ));
    }

    public function export($dataset)
    {
        if ($dataset === 'order-cart-mismatch') {
            $rows = $this->buildOrderMismatchRows();
            $headers = ['Loai loi', 'Order ID', 'Order number', 'Khach hang', 'Email', 'So luong order', 'So luong cart', 'Sub total', 'Cart amount', 'Muc uu tien', 'Ngay tao'];

            return $this->streamCsvDownload('data-engineer-order-cart-mismatch', $headers, $rows, function (array $row) {
                return [
                    $row['issue'],
                    $row['entity_id'],
                    $row['reference'],
                    $row['owner'],
                    $row['email'],
                    $row['order_quantity'],
                    $row['cart_quantity'],
                    $row['sub_total'],
                    $row['cart_amount'],
                    $row['priority_label'],
                    $row['created_at'],
                ];
            });
        }

        if ($dataset === 'product-contract-gap') {
            $rows = $this->buildCatalogGapRows();
            $headers = ['Product ID', 'San pham', 'Gia', 'Ton kho', 'Van de', 'Muc uu tien', 'Ngay cap nhat'];

            return $this->streamCsvDownload('data-engineer-product-contract-gap', $headers, $rows, function (array $row) {
                return [
                    $row['entity_id'],
                    $row['reference'],
                    $row['price'],
                    $row['stock'],
                    $row['issue'],
                    $row['priority_label'],
                    $row['created_at'],
                ];
            });
        }

        if ($dataset === 'duplicate-user-email') {
            $rows = $this->buildDuplicateUserRows();
            $headers = ['User ID', 'Ten', 'Email', 'So ban ghi trung', 'Trang thai', 'Van de', 'Muc uu tien', 'Ngay cap nhat'];

            return $this->streamCsvDownload('data-engineer-duplicate-user-email', $headers, $rows, function (array $row) {
                return [
                    $row['entity_id'],
                    $row['reference'],
                    $row['email'],
                    $row['duplicate_count'],
                    $row['status'],
                    $row['issue'],
                    $row['priority_label'],
                    $row['created_at'],
                ];
            });
        }

        abort(404);
    }

    protected function resolveRange(Request $request)
    {
        $allowedRanges = [14, 30, 90];
        $range = (int) $request->query('range', 30);

        return in_array($range, $allowedRanges, true) ? $range : 30;
    }

    protected function calculateContractScore($total, array $issues)
    {
        $total = (int) $total;
        if ($total <= 0) {
            return 100.0;
        }

        $issueRatio = 0;
        foreach ($issues as $issueCount) {
            $issueRatio += ((int) $issueCount) / $total;
        }

        return round(max(0, 100 - (($issueRatio / max(1, count($issues))) * 100)), 1);
    }

    protected function buildOrderAuditBase()
    {
        return DB::table('orders')
            ->leftJoin('carts', 'orders.id', '=', 'carts.order_id')
            ->select('orders.id', 'orders.quantity', 'orders.sub_total')
            ->selectRaw('COUNT(carts.id) as line_count')
            ->selectRaw('COALESCE(SUM(carts.quantity), 0) as cart_quantity')
            ->selectRaw('COALESCE(SUM(carts.amount), 0) as cart_amount')
            ->groupBy('orders.id', 'orders.quantity', 'orders.sub_total');
    }

    protected function buildDailyCounts($query, Carbon $startDate, Carbon $endDate)
    {
        return $query
            ->whereBetween('created_at', [$startDate, $endDate])
            ->selectRaw("DATE(created_at) as bucket")
            ->selectRaw('COUNT(*) as total')
            ->groupBy('bucket')
            ->orderBy('bucket')
            ->get()
            ->pluck('total', 'bucket')
            ->toArray();
    }

    protected function buildErrorQueue($limit = 15)
    {
        return $this->buildOrderMismatchRows()
            ->concat($this->buildCatalogGapRows())
            ->concat($this->buildDuplicateUserRows())
            ->concat($this->buildLeadGapRows())
            ->sort(function (array $left, array $right) {
                if ($left['priority_score'] === $right['priority_score']) {
                    return $right['created_at_sort'] <=> $left['created_at_sort'];
                }

                return $right['priority_score'] <=> $left['priority_score'];
            })
            ->take($limit)
            ->values();
    }

    protected function buildOrderMismatchRows($limit = null)
    {
        $rows = collect();
        $baseQuery = function () {
            return DB::query()
                ->fromSub($this->buildOrderAuditBase(), 'order_audit')
                ->join('orders', 'orders.id', '=', 'order_audit.id')
                ->leftJoin('users', 'users.id', '=', 'orders.user_id')
                ->select(
                    'orders.id',
                    'orders.order_number',
                    'orders.email',
                    'orders.created_at',
                    'users.name as user_name'
                )
                ->selectRaw('order_audit.line_count, order_audit.quantity, order_audit.cart_quantity, order_audit.sub_total, order_audit.cart_amount');
        };

        $noLineQuery = $baseQuery()
            ->where('order_audit.line_count', 0)
            ->orderByDesc('orders.created_at');
        $quantityQuery = $baseQuery()
            ->where('order_audit.line_count', '>', 0)
            ->whereColumn('order_audit.cart_quantity', '<>', 'order_audit.quantity')
            ->orderByDesc('orders.created_at');
        $amountQuery = $baseQuery()
            ->where('order_audit.line_count', '>', 0)
            ->whereRaw('ABS(order_audit.cart_amount - order_audit.sub_total) > 1')
            ->orderByDesc('orders.created_at');

        if ($limit !== null) {
            $perTypeLimit = max(3, (int) ceil($limit / 3));
            $noLineQuery->limit($perTypeLimit);
            $quantityQuery->limit($perTypeLimit);
            $amountQuery->limit($perTypeLimit);
        }

        $rows = $rows
            ->concat($noLineQuery->get()->map(function ($row) {
                return [
                    'entity_type' => 'Đơn hàng',
                    'entity_id' => $row->id,
                    'reference' => $row->order_number,
                    'owner' => $row->user_name ?: 'Khách lẻ',
                    'email' => $row->email,
                    'issue' => 'Thiếu dòng cart',
                    'impact' => 'Mất line-item nên báo cáo đơn hàng sẽ sai chi tiết.',
                    'priority_label' => 'Cao',
                    'priority_score' => 90,
                    'created_at' => Carbon::parse($row->created_at)->format('d/m/Y H:i'),
                    'created_at_sort' => Carbon::parse($row->created_at)->timestamp,
                    'order_quantity' => (int) $row->quantity,
                    'cart_quantity' => (int) $row->cart_quantity,
                    'sub_total' => round((float) $row->sub_total, 2),
                    'cart_amount' => round((float) $row->cart_amount, 2),
                    'action_label' => 'Mở đơn',
                    'action_url' => url('/admin/order/'.$row->id.'/edit'),
                ];
            }))
            ->concat($quantityQuery->get()->map(function ($row) {
                return [
                    'entity_type' => 'Đơn hàng',
                    'entity_id' => $row->id,
                    'reference' => $row->order_number,
                    'owner' => $row->user_name ?: 'Khách lẻ',
                    'email' => $row->email,
                    'issue' => 'Lệch tổng số lượng',
                    'impact' => 'Số lượng trên order khác cart, có thể làm sai AOV và bundle analysis.',
                    'priority_label' => 'Cao',
                    'priority_score' => 85,
                    'created_at' => Carbon::parse($row->created_at)->format('d/m/Y H:i'),
                    'created_at_sort' => Carbon::parse($row->created_at)->timestamp,
                    'order_quantity' => (int) $row->quantity,
                    'cart_quantity' => (int) $row->cart_quantity,
                    'sub_total' => round((float) $row->sub_total, 2),
                    'cart_amount' => round((float) $row->cart_amount, 2),
                    'action_label' => 'Mở đơn',
                    'action_url' => url('/admin/order/'.$row->id.'/edit'),
                ];
            }))
            ->concat($amountQuery->get()->map(function ($row) {
                return [
                    'entity_type' => 'Đơn hàng',
                    'entity_id' => $row->id,
                    'reference' => $row->order_number,
                    'owner' => $row->user_name ?: 'Khách lẻ',
                    'email' => $row->email,
                    'issue' => 'Lệch sub_total và cart',
                    'impact' => 'Amount trên cart khác sub_total của order, dễ làm sai revenue fact.',
                    'priority_label' => 'Cao',
                    'priority_score' => 88,
                    'created_at' => Carbon::parse($row->created_at)->format('d/m/Y H:i'),
                    'created_at_sort' => Carbon::parse($row->created_at)->timestamp,
                    'order_quantity' => (int) $row->quantity,
                    'cart_quantity' => (int) $row->cart_quantity,
                    'sub_total' => round((float) $row->sub_total, 2),
                    'cart_amount' => round((float) $row->cart_amount, 2),
                    'action_label' => 'Mở đơn',
                    'action_url' => url('/admin/order/'.$row->id.'/edit'),
                ];
            }));

        return $limit !== null ? $rows->take($limit)->values() : $rows->values();
    }

    protected function buildCatalogGapRows($limit = null)
    {
        $query = Product::query()
            ->where('status', 'active')
            ->where(function ($query) {
                $query->whereNull('brand_id')
                    ->orWhereNull('cat_id')
                    ->orWhereNull('photo')
                    ->orWhereRaw("TRIM(COALESCE(photo, '')) = ''")
                    ->orWhereNull('description')
                    ->orWhereRaw("TRIM(COALESCE(description, '')) = ''");
            })
            ->orderByDesc('updated_at');

        if ($limit !== null) {
            $query->limit($limit);
        }

        return $query->get()->map(function (Product $product) {
            $issues = [];
            if ($product->brand_id === null) {
                $issues[] = 'thiếu brand';
            }
            if ($product->cat_id === null) {
                $issues[] = 'thiếu danh mục';
            }
            if (trim((string) $product->photo) === '') {
                $issues[] = 'thiếu ảnh';
            }
            if (trim((string) $product->description) === '') {
                $issues[] = 'thiếu mô tả';
            }

            $priorityScore = count($issues) >= 3 ? 80 : 65;

            return [
                'entity_type' => 'Sản phẩm',
                'entity_id' => $product->id,
                'reference' => $product->title,
                'issue' => ucfirst(implode(', ', $issues)),
                'impact' => 'Metadata thiếu làm dashboard danh mục, phân loại và recommendation kém tin cậy.',
                'priority_label' => $priorityScore >= 80 ? 'Cao' : 'Vừa',
                'priority_score' => $priorityScore,
                'created_at' => optional($product->updated_at)->format('d/m/Y H:i'),
                'created_at_sort' => optional($product->updated_at)->timestamp ?: 0,
                'price' => round((float) $product->price, 2),
                'stock' => (int) $product->stock,
                'action_label' => 'Mở sản phẩm',
                'action_url' => url('/admin/product/'.$product->id.'/edit'),
            ];
        })->values();
    }

    protected function buildDuplicateUserRows($limit = null)
    {
        $duplicateEmailBase = DB::table('users')
            ->select('email')
            ->selectRaw('COUNT(*) as duplicate_count')
            ->whereNotNull('email')
            ->whereRaw("TRIM(email) <> ''")
            ->groupBy('email')
            ->havingRaw('COUNT(*) > 1');

        $query = User::query()
            ->joinSub($duplicateEmailBase, 'duplicate_emails', function ($join) {
                $join->on('users.email', '=', 'duplicate_emails.email');
            })
            ->select('users.id', 'users.name', 'users.email', 'users.status', 'users.updated_at', 'duplicate_emails.duplicate_count')
            ->orderByDesc('duplicate_emails.duplicate_count')
            ->orderBy('users.email');

        if ($limit !== null) {
            $query->limit($limit);
        }

        return $query->get()->map(function ($user) {
            return [
                'entity_type' => 'Khách hàng',
                'entity_id' => $user->id,
                'reference' => $user->name ?: 'Tài khoản #'.$user->id,
                'email' => $user->email,
                'status' => $user->status,
                'duplicate_count' => (int) $user->duplicate_count,
                'issue' => 'Email bị trùng trên nhiều tài khoản',
                'impact' => 'Sai định danh khách hàng, dễ làm lệch segment và retention report.',
                'priority_label' => (int) $user->duplicate_count >= 3 ? 'Cao' : 'Vừa',
                'priority_score' => (int) $user->duplicate_count >= 3 ? 82 : 68,
                'created_at' => Carbon::parse($user->updated_at)->format('d/m/Y H:i'),
                'created_at_sort' => Carbon::parse($user->updated_at)->timestamp,
                'action_label' => 'Mở user',
                'action_url' => url('/admin/users/'.$user->id.'/edit'),
            ];
        })->values();
    }

    protected function buildLeadGapRows($limit = null)
    {
        $query = Message::query()
            ->where(function ($query) {
                $query->whereRaw("TRIM(COALESCE(subject, '')) = ''")
                    ->orWhereRaw("TRIM(COALESCE(email, '')) = ''")
                    ->orWhereRaw("TRIM(COALESCE(phone, '')) = ''")
                    ->orWhere(function ($inner) {
                        $inner->whereNull('read_at')
                            ->where('created_at', '<', Carbon::now()->subHours(48));
                    });
            })
            ->orderByDesc('created_at');

        if ($limit !== null) {
            $query->limit($limit);
        }

        return $query->get()->map(function (Message $message) {
            $issues = [];
            if (trim((string) $message->subject) === '') {
                $issues[] = 'thiếu tiêu đề';
            }
            if (trim((string) $message->email) === '') {
                $issues[] = 'thiếu email';
            }
            if (trim((string) $message->phone) === '') {
                $issues[] = 'thiếu số điện thoại';
            }
            if ($message->read_at === null && $message->created_at < Carbon::now()->subHours(48)) {
                $issues[] = 'quá SLA đọc';
            }

            return [
                'entity_type' => 'Lead inbox',
                'entity_id' => $message->id,
                'reference' => $message->name,
                'issue' => ucfirst(implode(', ', $issues)),
                'impact' => 'Lead thiếu trường bắt buộc sẽ làm scoring và SLA không chính xác.',
                'priority_label' => in_array('quá SLA đọc', $issues, true) ? 'Cao' : 'Vừa',
                'priority_score' => in_array('quá SLA đọc', $issues, true) ? 78 : 58,
                'created_at' => Carbon::parse($message->created_at)->format('d/m/Y H:i'),
                'created_at_sort' => Carbon::parse($message->created_at)->timestamp,
                'action_label' => 'Mở lead',
                'action_url' => url('/admin/message/'.$message->id),
            ];
        })->values();
    }

    protected function streamCsvDownload($filename, array $headers, Collection $rows, callable $resolver)
    {
        return response()->streamDownload(function () use ($headers, $rows, $resolver) {
            $handle = fopen('php://output', 'w');
            fwrite($handle, chr(239).chr(187).chr(191));
            fputcsv($handle, $headers);

            foreach ($rows as $row) {
                fputcsv($handle, $resolver($row));
            }

            fclose($handle);
        }, $filename.'-'.Carbon::now()->format('Ymd-His').'.csv', [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }
}
