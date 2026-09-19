@extends('backend.layouts.master')

@section('title', 'Web bán tai nghe || Hóa đơn đơn hàng')

@section('main-content')
@php
    $storeName = ucwords(str_replace(['-', '_'], ' ', config('app.name')));
    $storeTagline = optional($settings)->short_des ?: optional($settings)->description;
    $storeAddress = optional($settings)->address ?: env('APP_ADDRESS');
    $storePhone = optional($settings)->phone ?: env('APP_PHONE');
    $storeEmail = optional($settings)->email ?: env('APP_EMAIL');
    $storeLogo = optional($settings)->logo;

    if ($storeLogo) {
        $storeLogo = preg_match('/^https?:\/\//i', $storeLogo) ? $storeLogo : asset($storeLogo);
    } else {
        $storeLogo = asset('backend/img/logo.png');
    }

    $shippingFee = optional($order->shipping)->price ?: 0;
    $couponValue = $order->coupon ?: 0;
    $customerName = trim($order->first_name . ' ' . $order->last_name);
    $customerAddress = collect([$order->address1, $order->address2])->filter()->implode(', ');

    $statusMap = collect(\App\Models\Order::ORDER_STATUS_LABELS)->mapWithKeys(function ($label, $key) {
        return [$key => ['label' => $label, 'class' => in_array($key, ['cancelled', 'delivery_failed'], true) ? 'status-cancel' : (in_array($key, ['delivery_success', 'completed'], true) ? 'status-delivered' : 'status-process')]];
    })->all();

    $paymentMethodMap = [
        'cod' => 'Thanh toán khi nhận hàng',
        'paypal' => 'PayPal',
        'momo' => 'MoMo',
    ];

    $paymentStatusMap = collect(\App\Models\Order::PAYMENT_STATUS_LABELS)->mapWithKeys(function ($label, $key) {
        return [$key => ['label' => $label, 'class' => $key === 'paid' ? 'status-delivered' : 'status-cancel']];
    })->all();

    $currentStatus = $statusMap[$order->status] ?? ['label' => ucfirst($order->status), 'class' => 'status-process'];
    $currentPaymentStatus = $paymentStatusMap[strtolower($order->payment_status)] ?? ['label' => ucfirst($order->payment_status), 'class' => 'status-process'];
    $paymentMethodLabel = $paymentMethodMap[$order->payment_method] ?? strtoupper($order->payment_method);
    $money = function ($value) {
        return number_format((float) $value, 0, ',', '.') . 'đ';
    };
@endphp

<div class="container-fluid invoice-page">
    <div class="row no-print">
        <div class="col-12">
            @include('backend.layouts.notification')
        </div>
    </div>

    <div class="card shadow invoice-card">
        <div class="card-body p-4 p-lg-5">
            <div class="invoice-toolbar no-print">
                <div>
                    <p class="invoice-overline mb-2">Quản trị đơn hàng</p>
                    <h1 class="invoice-page-title mb-1">Hóa đơn #{{ $order->order_number }}</h1>
                    <p class="invoice-page-subtitle mb-0">Trang này đã được tối ưu để in trực tiếp trên khổ A4.</p>
                </div>

                <div class="invoice-actions">
                    <a href="{{ route('order.index') }}" class="btn btn-outline-secondary btn-sm">
                        <i class="fas fa-arrow-left mr-1"></i> Danh sách đơn
                    </a>
                    <a href="{{ route('order.edit', $order->id) }}" class="btn btn-outline-primary btn-sm">
                        <i class="fas fa-edit mr-1"></i> Cập nhật
                    </a>
                    <a href="{{ route('order.pdf', $order->id) }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-file-pdf mr-1"></i> Xuất PDF
                    </a>
                    <button type="button" class="btn btn-dark btn-sm" onclick="window.print()">
                        <i class="fas fa-print mr-1"></i> In hóa đơn
                    </button>
                </div>
            </div>

            <div class="invoice-shell">
                <div class="invoice-header">
                    <div class="invoice-brand">
                        <img src="{{ $storeLogo }}" alt="{{ $storeName }}" class="invoice-logo">

                        <div>
                            <p class="invoice-overline mb-2">Hóa đơn bán hàng</p>
                            <h2 class="invoice-store-name">{{ $storeName }}</h2>

                            @if($storeTagline)
                                <p class="invoice-store-description mb-0">{{ $storeTagline }}</p>
                            @endif
                        </div>
                    </div>

                    <div class="invoice-meta">
                        <span class="invoice-badge {{ $currentStatus['class'] }}">{{ $currentStatus['label'] }}</span>
                        <h3 class="invoice-code">Mã đơn: {{ $order->order_number }}</h3>
                        <p class="mb-1"><strong>Ngày tạo:</strong> {{ optional($order->created_at)->format('d/m/Y H:i') }}</p>
                        <p class="mb-1"><strong>Khách hàng:</strong> {{ $customerName }}</p>
                        <p class="mb-0"><strong>Số lượng:</strong> {{ $order->quantity }} sản phẩm</p>
                    </div>
                </div>

                <div class="invoice-panels">
                    <div class="invoice-panel">
                        <h4>Thông tin cửa hàng</h4>
                        <p><strong>Tên:</strong> {{ $storeName }}</p>
                        <p><strong>Địa chỉ:</strong> {{ $storeAddress ?: 'Đang cập nhật' }}</p>
                        <p><strong>Điện thoại:</strong> {{ $storePhone ?: 'Đang cập nhật' }}</p>
                        <p><strong>Email:</strong> {{ $storeEmail ?: 'Đang cập nhật' }}</p>
                    </div>

                    <div class="invoice-panel">
                        <h4>Thông tin người nhận</h4>
                        <p><strong>Họ tên:</strong> {{ $customerName }}</p>
                        <p><strong>Email:</strong> {{ $order->email }}</p>
                        <p><strong>Điện thoại:</strong> {{ $order->phone }}</p>
                        <p><strong>Địa chỉ:</strong> {{ $customerAddress ?: 'Đang cập nhật' }}</p>
                        <p><strong>Khu vực:</strong> {{ optional($order->shipping)->type ?: $order->country }}{{ $order->post_code ? ' - ' . $order->post_code : '' }}</p>
                    </div>

                    <div class="invoice-panel">
                        <h4>Thanh toán và giao hàng</h4>
                        <p><strong>Phương thức:</strong> {{ $paymentMethodLabel }}</p>
                        <p>
                            <strong>Thanh toán:</strong>
                            <span class="invoice-inline-badge {{ $currentPaymentStatus['class'] }}">{{ $currentPaymentStatus['label'] }}</span>
                        </p>
                        <p><strong>Trạng thái đơn:</strong> {{ $currentStatus['label'] }}</p>
                        <p><strong>Phí vận chuyển:</strong> {{ $money($shippingFee) }}</p>
                        <p><strong>Mã đơn nội bộ:</strong> #{{ $order->id }}</p>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table invoice-table mb-0">
                        <thead>
                            <tr>
                                <th class="text-center" style="width: 72px;">STT</th>
                                <th>Sản phẩm</th>
                                <th class="text-center" style="width: 120px;">SL</th>
                                <th class="text-right" style="width: 160px;">Đơn giá</th>
                                <th class="text-right" style="width: 180px;">Thành tiền</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($order->cart_info as $item)
                                @php
                                    $lineTotal = $item->amount ?: ($item->price * $item->quantity);
                                @endphp
                                <tr>
                                    <td class="text-center">{{ $loop->iteration }}</td>
                                    <td>
                                        <strong>{{ optional($item->product)->title ?: 'Sản phẩm đã xóa' }}</strong>
                                        @if($item->color_code)
                                            <div class="invoice-product-note">Mã màu: {{ $item->color_code }}</div>
                                        @endif
                                        @if(optional($item->product)->summary)
                                            <div class="invoice-product-note">{{ strip_tags(optional($item->product)->summary) }}</div>
                                        @endif
                                    </td>
                                    <td class="text-center">{{ $item->quantity }}</td>
                                    <td class="text-right">{{ $money($item->price) }}</td>
                                    <td class="text-right">{{ $money($lineTotal) }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted py-4">Đơn hàng chưa có sản phẩm.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="invoice-summary">
                    <div class="invoice-summary-note">
                        <h4>Ghi chú hóa đơn</h4>
                        <p class="mb-2">Vui lòng kiểm tra đầy đủ sản phẩm và thông tin người nhận trước khi bàn giao.</p>
                        <p class="mb-2">Trang này có thể in trực tiếp từ trình duyệt bằng nút <strong>In hóa đơn</strong>.</p>
                        <p class="mb-0">Khách hàng: {{ $customerName }} | Điện thoại: {{ $order->phone }}</p>
                    </div>

                    <div class="invoice-totals">
                        <div class="invoice-total-row">
                            <span>Tạm tính</span>
                            <strong>{{ $money($order->sub_total) }}</strong>
                        </div>

                        @if($couponValue > 0)
                            <div class="invoice-total-row">
                                <span>Giảm giá</span>
                                <strong>-{{ $money($couponValue) }}</strong>
                            </div>
                        @endif

                        <div class="invoice-total-row">
                            <span>Phí vận chuyển</span>
                            <strong>{{ $money($shippingFee) }}</strong>
                        </div>

                        <div class="invoice-total-row invoice-total-row-grand">
                            <span>Tổng thanh toán</span>
                            <strong>{{ $money($order->total_amount) }}</strong>
                        </div>
                    </div>
                </div>

                <div class="invoice-footer">
                    <div>
                        <p class="mb-1"><strong>Cảm ơn quý khách đã mua hàng.</strong></p>
                        <p class="mb-0 text-muted">Hóa đơn được tạo từ hệ thống quản trị và có giá trị lưu kho/in bàn giao.</p>
                    </div>

                    <div class="invoice-signature">
                        <span>Người xác nhận</span>
                        <strong>{{ optional(auth()->user())->name ?: 'Admin' }}</strong>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .invoice-page {
        padding-bottom: 2rem;
    }

    .invoice-card {
        border: 0;
        border-radius: 1.25rem;
    }

    .invoice-toolbar {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    .invoice-actions {
        display: flex;
        flex-wrap: wrap;
        justify-content: flex-end;
        gap: .75rem;
    }

    .invoice-overline {
        color: #2563eb;
        font-size: .75rem;
        font-weight: 700;
        letter-spacing: .16em;
        text-transform: uppercase;
    }

    .invoice-page-title {
        color: #0f172a;
        font-family: Georgia, "Times New Roman", serif;
        font-size: 2rem;
        font-weight: 700;
    }

    .invoice-page-subtitle {
        color: #64748b;
        font-size: .95rem;
    }

    .invoice-shell {
        background: linear-gradient(180deg, #fff 0%, #f8fafc 100%);
        border: 1px solid #e2e8f0;
        border-radius: 1.5rem;
        box-shadow: 0 24px 60px rgba(15, 23, 42, .08);
        overflow: hidden;
    }

    .invoice-header {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 2rem;
        padding: 2rem;
        border-bottom: 1px solid #e2e8f0;
        background:
            radial-gradient(circle at top left, rgba(37, 99, 235, .09), transparent 35%),
            radial-gradient(circle at right center, rgba(15, 23, 42, .08), transparent 32%);
    }

    .invoice-brand {
        display: flex;
        align-items: flex-start;
        gap: 1rem;
        max-width: 62%;
    }

    .invoice-logo {
        width: 88px;
        height: 88px;
        object-fit: contain;
        background: #fff;
        border: 1px solid #dbeafe;
        border-radius: 1rem;
        padding: .75rem;
    }

    .invoice-store-name,
    .invoice-code {
        color: #0f172a;
        font-family: Georgia, "Times New Roman", serif;
    }

    .invoice-store-name {
        margin-bottom: .5rem;
        font-size: 1.65rem;
        font-weight: 700;
    }

    .invoice-store-description {
        color: #475569;
        line-height: 1.65;
    }

    .invoice-meta {
        min-width: 260px;
        text-align: right;
    }

    .invoice-code {
        margin: 1rem 0 .75rem;
        font-size: 1.1rem;
        font-weight: 700;
    }

    .invoice-badge,
    .invoice-inline-badge {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: .4rem .85rem;
        border-radius: 999px;
        font-size: .78rem;
        font-weight: 700;
    }

    .status-new {
        background: #dbeafe;
        color: #1d4ed8;
    }

    .status-process {
        background: #fef3c7;
        color: #b45309;
    }

    .status-delivered {
        background: #dcfce7;
        color: #15803d;
    }

    .status-cancel {
        background: #fee2e2;
        color: #b91c1c;
    }

    .invoice-panels {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 1rem;
        padding: 1.5rem 2rem 0;
    }

    .invoice-panel {
        background: #fff;
        border: 1px solid #e2e8f0;
        border-radius: 1rem;
        padding: 1.25rem;
    }

    .invoice-panel h4,
    .invoice-summary-note h4 {
        margin-bottom: .85rem;
        color: #0f172a;
        font-size: 1rem;
        font-weight: 700;
    }

    .invoice-panel p {
        margin-bottom: .55rem;
        color: #475569;
        line-height: 1.55;
    }

    .invoice-inline-badge {
        margin-left: .35rem;
        vertical-align: middle;
    }

    .invoice-table {
        margin-top: 1.5rem;
    }

    .invoice-table thead th {
        border-top: 0;
        border-bottom: 1px solid #cbd5e1;
        background: #eff6ff;
        color: #1e3a8a;
        font-size: .82rem;
        font-weight: 700;
        letter-spacing: .03em;
        text-transform: uppercase;
    }

    .invoice-table td {
        border-color: #e2e8f0;
        color: #334155;
        padding: 1rem .85rem;
        vertical-align: top;
    }

    .invoice-product-note {
        margin-top: .35rem;
        color: #64748b;
        font-size: .88rem;
        line-height: 1.55;
    }

    .invoice-summary {
        display: grid;
        grid-template-columns: minmax(0, 1.2fr) minmax(320px, .8fr);
        gap: 1.5rem;
        padding: 1.5rem 2rem 0;
    }

    .invoice-summary-note,
    .invoice-totals {
        background: #fff;
        border: 1px solid #e2e8f0;
        border-radius: 1rem;
        padding: 1.25rem;
    }

    .invoice-summary-note p {
        color: #475569;
        line-height: 1.65;
    }

    .invoice-total-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 1rem;
        padding: .85rem 0;
        border-bottom: 1px dashed #cbd5e1;
        color: #334155;
    }

    .invoice-total-row:last-child {
        border-bottom: 0;
        padding-bottom: 0;
    }

    .invoice-total-row-grand {
        padding-top: 1.1rem;
        color: #0f172a;
        font-size: 1.05rem;
        font-weight: 700;
    }

    .invoice-footer {
        display: flex;
        align-items: flex-end;
        justify-content: space-between;
        gap: 1.5rem;
        padding: 1.5rem 2rem 2rem;
    }

    .invoice-signature {
        min-width: 220px;
        text-align: center;
    }

    .invoice-signature span {
        display: block;
        margin-bottom: 3rem;
        color: #64748b;
    }

    .invoice-signature strong {
        display: inline-block;
        min-width: 160px;
        border-top: 1px solid #94a3b8;
        color: #0f172a;
        padding-top: .65rem;
    }

    @media (max-width: 991.98px) {
        .invoice-toolbar,
        .invoice-header,
        .invoice-footer {
            flex-direction: column;
        }

        .invoice-brand,
        .invoice-meta {
            max-width: 100%;
            min-width: 100%;
            text-align: left;
        }

        .invoice-panels,
        .invoice-summary {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 575.98px) {
        .invoice-header,
        .invoice-panels,
        .invoice-summary,
        .invoice-footer {
            padding-left: 1rem;
            padding-right: 1rem;
        }

        .invoice-page-title {
            font-size: 1.65rem;
        }

        .invoice-logo {
            width: 72px;
            height: 72px;
        }
    }

    @media print {
        @page {
            size: A4;
            margin: 10mm;
        }

        body {
            background: #fff !important;
        }

        #accordionSidebar,
        .topbar,
        footer.sticky-footer,
        .scroll-to-top,
        .no-print {
            display: none !important;
        }

        #wrapper,
        #content-wrapper,
        #content,
        .invoice-page,
        .invoice-card,
        .card-body {
            margin: 0 !important;
            padding: 0 !important;
            background: #fff !important;
        }

        .invoice-card,
        .invoice-shell {
            border: 0 !important;
            box-shadow: none !important;
        }

        .invoice-shell {
            border-radius: 0 !important;
        }
    }
</style>
@endpush
