@extends('user.layouts.master')

@section('title', 'Chi tiết đơn hàng')

@section('main-content')
@php
    $statusMeta = collect(\App\Models\Order::ORDER_STATUS_LABELS)->except(['returning', 'returned'])->mapWithKeys(function ($label, $key) {
        return [$key => ['label' => $label, 'icon' => 'fas fa-circle', 'tone' => 'status-process', 'summary' => 'Trạng thái đơn hàng đang được cập nhật.']];
    })->all();

    $paymentMethodMap = [
        'cod' => ['label' => 'Thanh toán khi nhận hàng', 'short' => 'COD', 'icon' => 'fas fa-money-bill-wave', 'class' => 'payment-method-cod'],
        'paypal' => ['label' => 'Thanh toán qua PayPal', 'short' => 'PayPal', 'icon' => 'fab fa-paypal', 'class' => 'payment-method-paypal'],
        'momo' => ['label' => 'Thanh toán qua MoMo', 'short' => 'MoMo', 'icon' => 'fas fa-mobile-alt', 'class' => 'payment-method-momo'],
    ];

    $paymentStatusMap = collect(\App\Models\Order::PAYMENT_STATUS_LABELS)->mapWithKeys(function ($label, $key) {
        return [$key => ['label' => $label, 'class' => 'payment-state-review']];
    })->all();

    $currentStatusKey = trim((string) $order->status);
    $flowStatusKey = in_array($currentStatusKey, ['returning', 'returned'], true) ? 'delivery_failed' : $currentStatusKey;
    $currentStatus = $statusMeta[$flowStatusKey] ?? ['label' => ucfirst($flowStatusKey), 'icon' => 'fas fa-question-circle', 'tone' => 'status-muted', 'summary' => 'Đơn hàng đang được cập nhật trạng thái.'];

    $paymentMethodKey = strtolower(trim((string) $order->payment_method));
    $paymentStatusKey = strtolower(trim((string) $order->payment_status));
    $paymentMethod = $paymentMethodMap[$paymentMethodKey] ?? ['label' => strtoupper($order->payment_method), 'short' => strtoupper($order->payment_method), 'icon' => 'fas fa-wallet', 'class' => 'payment-method-other'];
    $paymentState = $paymentStatusMap[$paymentStatusKey] ?? ['label' => ucfirst($order->payment_status), 'class' => 'payment-state-review'];

    $customerName = trim($order->first_name . ' ' . $order->last_name) ?: 'Khách hàng chưa cập nhật';
    $shippingFee = optional($order->shipping)->price ?: 0;
    $shippingType = optional($order->shipping)->type ?: 'Chưa chọn hình thức giao hàng';
    $customerAddress = collect([$order->address1, $order->address2])->filter()->implode(', ');
    $locationText = collect([$order->country, $order->post_code])->filter()->implode(' • ');
    $couponValue = (float) ($order->coupon ?: 0);
    $money = function ($value) {
        return number_format((float) $value, 0, ',', '.') . 'đ';
    };

    $progressRank = [
        'pending_confirmation' => 1,
        'preparing' => 2,
        'ready' => 3,
        'shipping' => 4,
        'delivery_failed' => 5,
        'returning' => 6,
        'returned' => 7,
        'delivery_success' => 8,
        'completed' => 9,
        'ended' => 10,
        'cancelled' => 0,
    ];

    $trackingStages = [
        'pending_confirmation' => ['label' => 'Chờ xác nhận', 'icon' => 'fas fa-receipt'],
        'preparing' => ['label' => 'Đang chuẩn bị hàng', 'icon' => 'fas fa-cogs'],
        'ready' => ['label' => 'Đơn hàng đã sẵn sàng', 'icon' => 'fas fa-box'],
        'shipping' => ['label' => 'Đang giao hàng', 'icon' => 'fas fa-truck'],
        'delivery_failed' => ['label' => 'Giao hàng thất bại', 'icon' => 'fas fa-exclamation-triangle'],
        'delivery_success' => ['label' => 'Giao hàng thành công', 'icon' => 'fas fa-check-circle'],
        'completed' => ['label' => 'Hoàn thành', 'icon' => 'fas fa-check-double'],
    ];

    $currentRank = $progressRank[$flowStatusKey] ?? 0;
@endphp

<div class="container-fluid user-order-page">
    @include('user.layouts.notification')

    <div class="user-order-hero mb-4">
        <div class="row align-items-center">
            <div class="col-xl-8">
                <div class="user-order-kicker">Order Detail</div>
                <h1>Đơn hàng {{ $order->order_number }}</h1>
                <p>{{ $currentStatus['summary'] }}</p>
                <div class="user-order-meta">
                    <span><i class="far fa-calendar-alt mr-2"></i>{{ optional($order->created_at)->format('d/m/Y H:i') }}</span>
                    <span><i class="fas fa-user mr-2"></i>{{ $customerName }}</span>
                    <span><i class="fas fa-truck mr-2"></i>{{ $shippingType }}</span>
                </div>
            </div>

            <div class="col-xl-4">
                <div class="user-order-hero-panel">
                    <span class="order-pill {{ $currentStatus['tone'] }}">
                        <i class="{{ $currentStatus['icon'] }} mr-2"></i>{{ $currentStatus['label'] }}
                    </span>
                    <div class="user-order-hero-actions">
                        <a href="{{ route('user.order.index') }}" class="btn btn-light btn-sm">
                            <i class="fas fa-arrow-left mr-1"></i> Đơn hàng của tôi
                        </a>
                        <a href="{{ route('order.pdf', $order->id) }}" class="btn btn-outline-light btn-sm">
                            <i class="fas fa-download mr-1"></i> Xuất PDF
                        </a>
                        @if ($paymentMethodKey === 'momo' && $paymentStatusKey !== 'paid')
                            <a href="{{ route('momo.payment', $order->id) }}" class="btn btn-outline-light btn-sm">
                                <i class="fas fa-mobile-alt mr-1"></i> Thanh toán lại với MoMo
                            </a>
                        @endif
                    </div>
                    <div class="user-order-hero-total">
                        <span>Tổng thanh toán</span>
                        <strong>{{ $money($order->total_amount) }}</strong>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card order-metric-card">
                <div class="card-body">
                    <div class="metric-label">Số lượng sản phẩm</div>
                    <div class="metric-value">{{ number_format($order->quantity, 0, ',', '.') }}</div>
                    <div class="metric-note">Tổng số sản phẩm trong đơn hàng này.</div>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card order-metric-card">
                <div class="card-body">
                    <div class="metric-label">Phí vận chuyển</div>
                    <div class="metric-value">{{ $money($shippingFee) }}</div>
                    <div class="metric-note">{{ $shippingType }}</div>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-md-12 mb-4">
            <div class="card order-metric-card">
                <div class="card-body">
                    <div class="metric-label">Thanh toán</div>
                    <div class="badge-row mb-2">
                        <span class="order-pill {{ $paymentMethod['class'] }}">
                            <i class="{{ $paymentMethod['icon'] }} mr-2"></i>{{ $paymentMethod['short'] }}
                        </span>
                        <span class="order-pill {{ $paymentState['class'] }}">{{ $paymentState['label'] }}</span>
                    </div>
                    <div class="metric-note">{{ $paymentMethod['label'] }}</div>
                </div>
            </div>
        </div>
    </div>

    <div class="card order-panel mb-4">
        <div class="card-header">
            <h2>Hành trình đơn hàng</h2>
            <p>Trạng thái hiện tại của đơn được cập nhật theo tiến trình xử lý từ shop.</p>
        </div>
        <div class="card-body">
            <div class="tracking-grid">
                @foreach ($trackingStages as $key => $stage)
                    @php
                        if ($flowStatusKey === 'cancelled') {
                            $stageState = 'is-muted';
                        } else {
                            $stageRank = $progressRank[$key] ?? 0;
                            $stageState = $stageRank < $currentRank ? 'is-done' : ($stageRank === $currentRank ? 'is-current' : 'is-muted');
                        }
                    @endphp
                    <div class="tracking-step {{ $stageState }}">
                        <div class="tracking-step-icon">
                            <i class="{{ $stage['icon'] }}"></i>
                        </div>
                        <div class="tracking-step-title">{{ $stage['label'] }}</div>
                    </div>
                @endforeach
            </div>

            @if ($flowStatusKey === 'cancelled')
                <div class="tracking-alert tracking-alert-danger">
                    <i class="fas fa-info-circle mr-2"></i>
                    Đơn hàng này đã bị hủy. Nếu bạn cần hỗ trợ thêm, vui lòng liên hệ shop để được kiểm tra lại thông tin đơn.
                </div>
            @elseif (in_array($flowStatusKey, ['delivery_success', 'completed'], true))
                <div class="tracking-alert tracking-alert-success">
                    <i class="fas fa-heart mr-2"></i>
                    Đơn hàng đã giao thành công. Bạn có thể lưu PDF hóa đơn hoặc tiếp tục mua sắm thêm.
                </div>
            @elseif ($flowStatusKey === 'delivery_failed')
                <div class="tracking-alert tracking-alert-danger">
                    <i class="fas fa-exclamation-triangle mr-2"></i>
                    Đơn hàng đang ở luồng giao không thành công hoặc hoàn hàng. Shop sẽ tiếp tục cập nhật trạng thái xử lý cho bạn.
                </div>
            @elseif (in_array($flowStatusKey, ['pending_confirmation', 'preparing', 'ready', 'shipping'], true))
                <div class="tracking-alert tracking-alert-info">
                    <i class="fas fa-clock mr-2"></i>
                    Đơn hàng vẫn đang được xử lý. Shop sẽ cập nhật tiếp khi trạng thái thay đổi.
                </div>
            @endif
        </div>
    </div>

    <div class="row">
        <div class="col-xl-8 mb-4">
            <div class="card order-panel h-100">
                <div class="card-header">
                    <h2>Sản phẩm trong đơn</h2>
                    <p>Danh sách các sản phẩm đã đặt cùng số lượng và thành tiền tương ứng.</p>
                </div>
                <div class="card-body">
                    <div class="product-stack">
                        @forelse ($order->cart_info as $item)
                            @php
                                $lineTotal = $item->amount ?: ($item->price * $item->quantity);
                            @endphp
                            <div class="product-row">
                                <div class="product-row-main">
                                    <div class="product-title">{{ optional($item->product)->title ?: 'Sản phẩm đã xóa' }}</div>
                                    <div class="product-subtitle">
                                        {{ number_format($item->quantity, 0, ',', '.') }} x {{ $money($item->price) }}
                                    </div>
                                    @if(optional($item->product)->summary)
                                        <div class="product-note">{{ \Illuminate\Support\Str::limit(strip_tags(optional($item->product)->summary), 120) }}</div>
                                    @endif
                                </div>
                                <div class="product-row-total">{{ $money($lineTotal) }}</div>
                            </div>
                        @empty
                            <div class="empty-panel">Đơn hàng này hiện chưa có sản phẩm hiển thị.</div>
                        @endforelse
                    </div>

                    <div class="totals-box">
                        <div class="totals-row">
                            <span>Tạm tính</span>
                            <strong>{{ $money($order->sub_total) }}</strong>
                        </div>
                        @if ($couponValue > 0)
                            <div class="totals-row">
                                <span>Giảm giá</span>
                                <strong>-{{ $money($couponValue) }}</strong>
                            </div>
                        @endif
                        <div class="totals-row">
                            <span>Phí vận chuyển</span>
                            <strong>{{ $money($shippingFee) }}</strong>
                        </div>
                        <div class="totals-row totals-row-grand">
                            <span>Tổng thanh toán</span>
                            <strong>{{ $money($order->total_amount) }}</strong>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4 mb-4">
            <div class="card order-panel mb-4">
                <div class="card-header">
                    <h2>Thông tin nhận hàng</h2>
                    <p>Kiểm tra lại thông tin giao hàng đã dùng cho đơn này.</p>
                </div>
                <div class="card-body">
                    <ul class="info-list">
                        <li><strong>Người nhận:</strong> {{ $customerName }}</li>
                        <li><strong>Email:</strong> {{ $order->email }}</li>
                        <li><strong>Điện thoại:</strong> {{ $order->phone ?: 'Chưa cập nhật' }}</li>
                        <li><strong>Địa chỉ:</strong> {{ $customerAddress ?: 'Chưa cập nhật địa chỉ' }}</li>
                        <li><strong>Khu vực:</strong> {{ $locationText ?: 'Chưa cập nhật khu vực' }}</li>
                    </ul>
                </div>
            </div>

            <div class="card order-panel">
                <div class="card-header">
                    <h2>Thông tin đơn hàng</h2>
                    <p>Một số thông tin quan trọng để bạn dễ tra cứu lại khi cần.</p>
                </div>
                <div class="card-body">
                    <ul class="info-list">
                        <li><strong>Mã đơn:</strong> {{ $order->order_number }}</li>
                        <li><strong>Mã nội bộ:</strong> #{{ $order->id }}</li>
                        <li><strong>Ngày đặt:</strong> {{ optional($order->created_at)->format('d/m/Y') }}</li>
                        <li><strong>Giờ đặt:</strong> {{ optional($order->created_at)->format('H:i') }}</li>
                        <li><strong>Phương thức thanh toán:</strong> {{ $paymentMethod['label'] }}</li>
                        <li><strong>Trạng thái thanh toán:</strong> {{ $paymentState['label'] }}</li>
                    </ul>

                    <div class="support-box">
                        <i class="fas fa-headset"></i>
                        <div>
                            <strong>Cần hỗ trợ đơn hàng?</strong>
                            <p class="mb-0">Nếu có thay đổi hoặc cần đối soát thông tin, bạn có thể liên hệ shop và cung cấp mã đơn <strong>{{ $order->order_number }}</strong>.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .user-order-page { padding-bottom: 2rem; }

    .user-order-hero {
        background:
            radial-gradient(circle at top right, rgba(96, 165, 250, .28), transparent 35%),
            linear-gradient(135deg, #17324d 0%, #255e73 52%, #0f9d94 100%);
        border-radius: 1.4rem;
        box-shadow: 0 24px 55px rgba(15, 23, 42, .12);
        color: #fff;
        padding: 1.75rem;
    }

    .user-order-kicker {
        font-size: .8rem;
        font-weight: 800;
        letter-spacing: .2em;
        margin-bottom: .85rem;
        text-transform: uppercase;
    }

    .user-order-hero h1 {
        font-size: 2rem;
        font-weight: 800;
        line-height: 1.15;
        margin-bottom: .85rem;
    }

    .user-order-hero p {
        color: rgba(255,255,255,.84);
        margin-bottom: 0;
        max-width: 40rem;
    }

    .user-order-meta {
        display: flex;
        flex-wrap: wrap;
        gap: .75rem 1.1rem;
        margin-top: 1.1rem;
    }

    .user-order-meta span {
        color: rgba(255,255,255,.88);
        font-size: .9rem;
    }

    .user-order-hero-panel {
        background: rgba(255,255,255,.12);
        border: 1px solid rgba(255,255,255,.16);
        border-radius: 1rem;
        padding: 1rem;
    }

    .user-order-hero-actions {
        display: flex;
        flex-wrap: wrap;
        gap: .75rem;
        margin: .95rem 0 1rem;
    }

    .user-order-hero-total span {
        color: rgba(255,255,255,.72);
        display: block;
        font-size: .8rem;
        margin-bottom: .3rem;
        text-transform: uppercase;
    }

    .user-order-hero-total strong {
        color: #fff;
        font-size: 1.45rem;
        font-weight: 800;
    }

    .order-metric-card,
    .order-panel {
        border: 0;
        border-radius: 1.15rem;
        box-shadow: 0 18px 40px rgba(15, 23, 42, .08);
        overflow: hidden;
    }

    .order-metric-card .card-body {
        padding: 1.25rem;
    }

    .metric-label {
        color: #64748b;
        font-size: .8rem;
        font-weight: 700;
        letter-spacing: .08em;
        margin-bottom: .6rem;
        text-transform: uppercase;
    }

    .metric-value {
        color: #0f172a;
        font-size: 1.65rem;
        font-weight: 800;
        line-height: 1.1;
        margin-bottom: .45rem;
    }

    .metric-note {
        color: #64748b;
        font-size: .9rem;
        line-height: 1.55;
    }

    .order-panel .card-header {
        background: #fff;
        border-bottom: 1px solid #e2e8f0;
        padding: 1.2rem 1.35rem .95rem;
    }

    .order-panel .card-header h2 {
        color: #0f172a;
        font-size: 1.02rem;
        font-weight: 800;
        margin-bottom: .25rem;
    }

    .order-panel .card-header p {
        color: #64748b;
        font-size: .9rem;
        margin-bottom: 0;
    }

    .order-panel .card-body {
        padding: 1.3rem 1.35rem 1.35rem;
    }

    .tracking-grid {
        display: grid;
        gap: .85rem;
        grid-template-columns: repeat(4, minmax(0, 1fr));
    }

    .tracking-step {
        align-items: center;
        background: linear-gradient(180deg, #fff 0%, #f8fafc 100%);
        border: 1px solid #e2e8f0;
        border-radius: 1rem;
        display: flex;
        flex-direction: column;
        gap: .65rem;
        min-height: 8.5rem;
        justify-content: center;
        padding: 1rem;
        text-align: center;
    }

    .tracking-step-icon {
        align-items: center;
        border-radius: 999px;
        display: inline-flex;
        height: 3.2rem;
        justify-content: center;
        width: 3.2rem;
    }

    .tracking-step-title {
        color: #0f172a;
        font-size: .9rem;
        font-weight: 700;
        line-height: 1.4;
    }

    .tracking-step.is-done .tracking-step-icon,
    .tracking-step.is-current .tracking-step-icon { background: rgba(37, 99, 235, .12); color: #2563eb; }
    .tracking-step.is-done { border-color: rgba(37, 99, 235, .28); }
    .tracking-step.is-current { border-color: #2563eb; box-shadow: 0 12px 24px rgba(37, 99, 235, .12); }
    .tracking-step.is-muted { opacity: .58; }

    .tracking-alert {
        border-radius: 1rem;
        font-size: .92rem;
        line-height: 1.6;
        margin-top: 1rem;
        padding: .95rem 1rem;
    }

    .tracking-alert-info { background: #eff6ff; border: 1px solid #bfdbfe; color: #1d4ed8; }
    .tracking-alert-success { background: #ecfdf5; border: 1px solid #a7f3d0; color: #047857; }
    .tracking-alert-danger { background: #fef2f2; border: 1px solid #fecaca; color: #b91c1c; }

    .product-stack {
        display: grid;
        gap: .85rem;
    }

    .product-row {
        align-items: flex-start;
        background: linear-gradient(180deg, #fff 0%, #f8fafc 100%);
        border: 1px solid #e2e8f0;
        border-radius: 1rem;
        display: flex;
        gap: 1rem;
        justify-content: space-between;
        padding: 1rem;
    }

    .product-title {
        color: #0f172a;
        font-size: .96rem;
        font-weight: 800;
        margin-bottom: .3rem;
    }

    .product-subtitle,
    .product-note {
        color: #64748b;
        font-size: .88rem;
        line-height: 1.55;
    }

    .product-note { margin-top: .35rem; }

    .product-row-total {
        color: #0f172a;
        font-size: .95rem;
        font-weight: 800;
        white-space: nowrap;
    }

    .totals-box {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 1rem;
        margin-top: 1rem;
        padding: 1rem 1.05rem;
    }

    .totals-row {
        align-items: center;
        color: #334155;
        display: flex;
        font-size: .92rem;
        justify-content: space-between;
        padding: .55rem 0;
    }

    .totals-row + .totals-row {
        border-top: 1px dashed #cbd5e1;
    }

    .totals-row-grand {
        color: #0f172a;
        font-size: 1rem;
        font-weight: 800;
    }

    .info-list {
        list-style: none;
        margin: 0;
        padding: 0;
    }

    .info-list li {
        color: #475569;
        line-height: 1.65;
        padding: .38rem 0;
    }

    .info-list strong {
        color: #0f172a;
    }

    .support-box {
        align-items: flex-start;
        background: #f8fbff;
        border: 1px solid #dbeafe;
        border-radius: 1rem;
        color: #1e3a8a;
        display: flex;
        gap: .85rem;
        margin-top: 1rem;
        padding: 1rem;
    }

    .support-box i {
        font-size: 1.15rem;
        margin-top: .1rem;
    }

    .badge-row {
        display: flex;
        flex-wrap: wrap;
        gap: .55rem;
    }

    .order-pill {
        align-items: center;
        border-radius: 999px;
        display: inline-flex;
        font-size: .78rem;
        font-weight: 700;
        line-height: 1;
        padding: .5rem .85rem;
    }

    .status-new { background: rgba(59, 130, 246, .14); color: #1d4ed8; }
    .status-process { background: rgba(249, 115, 22, .14); color: #c2410c; }
    .status-delivered { background: rgba(16, 185, 129, .14); color: #047857; }
    .status-cancel { background: rgba(239, 68, 68, .14); color: #b91c1c; }
    .status-muted { background: rgba(148, 163, 184, .18); color: #475569; }
    .payment-method-cod { background: rgba(14, 165, 233, .16); color: #0369a1; }
    .payment-method-paypal { background: rgba(37, 99, 235, .16); color: #1d4ed8; }
    .payment-method-momo { background: rgba(236, 72, 153, .16); color: #be185d; }
    .payment-method-other { background: rgba(148, 163, 184, .18); color: #475569; }
    .payment-state-paid { background: rgba(16, 185, 129, .14); color: #047857; }
    .payment-state-unpaid { background: rgba(100, 116, 139, .18); color: #334155; }
    .payment-state-review { background: rgba(217, 119, 6, .16); color: #b45309; }

    .empty-panel {
        background: #f8fafc;
        border: 1px dashed #cbd5e1;
        border-radius: 1rem;
        color: #64748b;
        padding: 1rem;
        text-align: center;
    }

    @media (max-width: 1199.98px) {
        .tracking-grid { grid-template-columns: repeat(2, minmax(0, 1fr)); }
    }

    @media (max-width: 991.98px) {
        .user-order-hero h1 { font-size: 1.65rem; }
    }

    @media (max-width: 575.98px) {
        .tracking-grid { grid-template-columns: 1fr; }
        .product-row { flex-direction: column; }
        .user-order-hero-actions .btn { width: 100%; }
    }
</style>
@endpush
