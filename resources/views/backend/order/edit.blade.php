@extends('backend.layouts.master')

@section('title', 'Web bán tai nghe || Cập nhật đơn hàng')

@section('main-content')
@php
    $statusMeta = [
        'pending_confirmation' => ['label' => 'Chờ xác nhận', 'icon' => 'fas fa-bell', 'tone' => 'tone-new', 'hint' => 'Đơn vừa phát sinh và chờ shop kiểm tra.'],
        'preparing' => ['label' => 'Đang chuẩn bị hàng', 'icon' => 'fas fa-box-open', 'tone' => 'tone-process', 'hint' => 'Shop đã duyệt và kho đang đóng gói.'],
        'ready' => ['label' => 'Đơn hàng đã sẵn sàng', 'icon' => 'fas fa-box', 'tone' => 'tone-process', 'hint' => 'Đơn đã đóng gói và chờ bàn giao vận chuyển.'],
        'shipping' => ['label' => 'Đang giao hàng', 'icon' => 'fas fa-truck', 'tone' => 'tone-process', 'hint' => 'Đơn đang được đơn vị vận chuyển phát đến khách.'],
        'delivery_failed' => ['label' => 'Giao hàng thất bại', 'icon' => 'fas fa-exclamation-triangle', 'tone' => 'tone-cancel', 'hint' => 'Đơn giao chưa thành công và cần giao lại hoặc hoàn hàng.'],
        'returning' => ['label' => 'Đang hoàn hàng', 'icon' => 'fas fa-undo', 'tone' => 'tone-process', 'hint' => 'Đơn đang được chuyển ngược về kho shop.'],
        'returned' => ['label' => 'Hoàn hàng thành công', 'icon' => 'fas fa-archive', 'tone' => 'tone-process', 'hint' => 'Shop đã nhận lại và kiểm tra hàng hoàn.'],
        'delivery_success' => ['label' => 'Giao hàng thành công', 'icon' => 'fas fa-check-circle', 'tone' => 'tone-delivered', 'hint' => 'Khách đã nhận hàng; COD vẫn chờ đối soát nếu chưa thu tiền.'],
        'completed' => ['label' => 'Hoàn thành', 'icon' => 'fas fa-check-double', 'tone' => 'tone-delivered', 'hint' => 'Đơn đã giao và thanh toán/đối soát hoàn tất.'],
        'cancelled' => ['label' => 'Đã hủy', 'icon' => 'fas fa-times-circle', 'tone' => 'tone-cancel', 'hint' => 'Đơn dừng xử lý trước khi hoàn tất giao hàng.'],
        'ended' => ['label' => 'Kết thúc', 'icon' => 'fas fa-flag-checkered', 'tone' => 'tone-muted', 'hint' => 'Đơn đã kết thúc và không còn thao tác.'],
    ];

    $paymentMethodMap = [
        'cod' => ['label' => 'COD', 'class' => 'payment-method-cod', 'icon' => 'fas fa-money-bill-wave'],
        'paypal' => ['label' => 'PayPal', 'class' => 'payment-method-paypal', 'icon' => 'fab fa-paypal'],
        'momo' => ['label' => 'MoMo', 'class' => 'payment-method-momo', 'icon' => 'fas fa-mobile-alt'],
    ];

    $paymentStatusMap = [
        'pending' => ['label' => 'Chờ thanh toán', 'class' => 'payment-state-review'],
        'paid' => ['label' => 'Đã thanh toán', 'class' => 'payment-state-paid'],
        'unpaid' => ['label' => 'Chưa thanh toán', 'class' => 'payment-state-unpaid'],
        'refunded' => ['label' => 'Đã hoàn tiền', 'class' => 'payment-state-review'],
    ];

    $allowedStatuses = \App\Models\Order::ORDER_STATUS_TRANSITIONS;

    $currentStatusKey = trim((string) $order->status);
    $selectedStatus = old('status', $currentStatusKey);
    $currentStatus = $statusMeta[$currentStatusKey] ?? ['label' => ucfirst($currentStatusKey), 'icon' => 'fas fa-question-circle', 'tone' => 'tone-muted', 'hint' => ''];
    $availableStatuses = $allowedStatuses[$currentStatusKey] ?? [$currentStatusKey];
    $reachedStatuses = $order->statusHistory->pluck('status')->unique()->values()->all();
    if (!in_array($currentStatusKey, $reachedStatuses, true)) {
        $reachedStatuses[] = $currentStatusKey;
    }
    $isLockedFlow = count($availableStatuses) === 1;

    $paymentMethodKey = strtolower(trim((string) $order->payment_method));
    $paymentStatusKey = strtolower(trim((string) $order->payment_status));
    $paymentMethod = $paymentMethodMap[$paymentMethodKey] ?? ['label' => strtoupper($order->payment_method), 'class' => 'payment-method-other', 'icon' => 'fas fa-wallet'];
    $paymentState = $paymentStatusMap[$paymentStatusKey] ?? ['label' => ucfirst($order->payment_status), 'class' => 'payment-state-review'];

    $customerName = trim($order->first_name . ' ' . $order->last_name) ?: 'Khách hàng chưa cập nhật';
    $customerAddress = collect([$order->address1, $order->address2])->filter()->implode(', ');
    $shippingLabel = optional($order->shipping)->type ?: 'Chưa chọn hình thức giao hàng';
    $shippingFee = optional($order->shipping)->price ?: 0;
    $money = function ($value) {
        return number_format((float) $value, 0, ',', '.') . 'đ';
    };
@endphp

<div class="container-fluid edit-order-page">
    @include('backend.layouts.notification')

    <div class="edit-order-hero mb-4">
        <div class="row align-items-center">
            <div class="col-xl-8">
                <div class="edit-order-kicker">Order Update Studio</div>
                <h1>Cập nhật đơn hàng {{ $order->order_number }} nhanh hơn và rõ trạng thái hơn</h1>
                <p>Theo dõi tình trạng vận hành, thông tin nhận hàng và giá trị đơn ngay trên một màn hình để giảm thao tác chuyển trang khi admin xử lý đơn.</p>
            </div>
            <div class="col-xl-4">
                <div class="edit-order-hero-actions">
                    <a href="{{ route('order.index') }}" class="btn btn-light btn-sm">
                        <i class="fas fa-arrow-left mr-1"></i> Danh sách đơn
                    </a>
                    <a href="{{ route('order.show', $order->id) }}" class="btn btn-outline-light btn-sm">
                        <i class="fas fa-eye mr-1"></i> Xem hóa đơn
                    </a>
                </div>
                <div class="edit-order-current">
                    <span class="edit-order-pill {{ $currentStatus['tone'] }}">
                        <i class="{{ $currentStatus['icon'] }} mr-2"></i>{{ $currentStatus['label'] }}
                    </span>
                    <div class="edit-order-current-meta">
                        <div><strong>Tạo lúc:</strong> {{ optional($order->created_at)->format('d/m/Y H:i') }}</div>
                        <div><strong>Cập nhật:</strong> {{ optional($order->updated_at)->format('d/m/Y H:i') }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-7 mb-4">
            <div class="card edit-order-panel h-100">
                <div class="card-header">
                    <h2>Cập nhật trạng thái đơn hàng</h2>
                    <p>Chọn trạng thái phù hợp với tiến trình xử lý thực tế. Một số trạng thái sẽ bị khóa để tránh thay đổi gây lệch dữ liệu tồn kho hoặc lịch sử đơn.</p>
                </div>
                <div class="card-body">
                    <div class="edit-order-status-overview">
                        @foreach ($statusMeta as $key => $meta)
                            @php
                                $overviewClass = $key === $currentStatusKey ? 'is-current' : (in_array($key, $reachedStatuses, true) ? 'is-reached' : 'is-locked');
                            @endphp
                            <div class="status-overview-item {{ $overviewClass }}" aria-disabled="{{ $overviewClass === 'is-locked' ? 'true' : 'false' }}">
                                <div class="status-overview-icon {{ $meta['tone'] }}">
                                    <i class="{{ $meta['icon'] }}"></i>
                                </div>
                                <div>
                                    <div class="status-overview-title">{{ $meta['label'] }}</div>
                                    <div class="status-overview-text">{{ $meta['hint'] }}</div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <form action="{{ route('order.update', $order->id) }}" method="POST">
                        @csrf
                        @method('PATCH')

                        <div class="form-group mb-4">
                            <label class="edit-order-label">Chọn trạng thái mới</label>
                            <div class="status-choice-grid">
                                <input type="hidden" name="status" value="{{ old('status', $currentStatusKey) }}">
                                @foreach ($statusMeta as $key => $meta)
                                    @php
                                        $isReached = in_array($key, $reachedStatuses, true);
                                        $isCurrent = $key === $currentStatusKey;
                                        $isDisabled = $isReached || !in_array($key, $availableStatuses, true);
                                    @endphp
                                    <label class="status-choice">
                                        <input
                                            type="radio"
                                            name="status_choice"
                                            value="{{ $key }}"
                                            {{ $selectedStatus === $key && !$isDisabled ? 'checked' : '' }}
                                            {{ $isDisabled ? 'disabled' : '' }}
                                            onchange="this.form.querySelector('input[name=status]').value=this.value"
                                        >
                                        <span class="status-choice-card {{ $isReached ? 'is-reached' : '' }} {{ $isCurrent ? 'is-current' : '' }}">
                                            <span class="status-choice-icon {{ $meta['tone'] }}">
                                                <i class="{{ $meta['icon'] }}"></i>
                                            </span>
                                            <span class="status-choice-body">
                                                <strong>{{ $meta['label'] }}</strong>
                                                <small>{{ $meta['hint'] }}</small>
                                            </span>
                                        </span>
                                    </label>
                                @endforeach
                            </div>
                            @error('status')
                                <div class="text-danger small mt-2">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group mb-4">
                            <label class="edit-order-label" for="payment_status">Trạng thái thanh toán</label>
                            <select class="form-control" id="payment_status" name="payment_status">
                                @foreach ($paymentStatusMap as $key => $meta)
                                    <option value="{{ $key }}" {{ $paymentStatusKey === $key ? 'selected' : '' }}>{{ $meta['label'] }}</option>
                                @endforeach
                            </select>
                            @error('payment_status')
                                <div class="text-danger small mt-2">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="edit-order-note">
                            <i class="fas fa-info-circle mr-2"></i>
                            @if ($isLockedFlow)
                                Đơn này đang ở trạng thái cố định. Bạn chỉ có thể lưu lại trạng thái hiện tại để đảm bảo lịch sử xử lý không bị sai.
                            @else
                                Khi đơn chuyển sang <strong>Đã giao</strong>, hệ thống sẽ trừ tồn kho một lần duy nhất cho các sản phẩm trong đơn.
                            @endif
                        </div>

                        <div class="edit-order-actions">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save mr-1"></i> Cập nhật đơn hàng
                            </button>
                            <a href="{{ route('order.show', $order->id) }}" class="btn btn-outline-secondary">
                                <i class="fas fa-file-invoice mr-1"></i> Xem chi tiết
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-xl-5 mb-4">
            <div class="card edit-order-panel mb-4">
                <div class="card-header">
                    <h2>Tóm tắt đơn hàng</h2>
                    <p>Nhìn nhanh giá trị, vận chuyển và thanh toán trước khi quyết định đổi trạng thái.</p>
                </div>
                <div class="card-body">
                    <div class="edit-order-metrics">
                        <div class="metric-box">
                            <span>Tổng thanh toán</span>
                            <strong>{{ $money($order->total_amount) }}</strong>
                        </div>
                        <div class="metric-box">
                            <span>Số lượng</span>
                            <strong>{{ number_format($order->quantity, 0, ',', '.') }}</strong>
                        </div>
                        <div class="metric-box">
                            <span>Phí vận chuyển</span>
                            <strong>{{ $money($shippingFee) }}</strong>
                        </div>
                        <div class="metric-box">
                            <span>Tạm tính</span>
                            <strong>{{ $money($order->sub_total) }}</strong>
                        </div>
                    </div>

                    <div class="summary-section">
                        <div class="summary-title">Thanh toán</div>
                        <div class="badge-row">
                            <span class="edit-order-pill {{ $paymentMethod['class'] }}">
                                <i class="{{ $paymentMethod['icon'] }} mr-2"></i>{{ $paymentMethod['label'] }}
                            </span>
                            <span class="edit-order-pill {{ $paymentState['class'] }}">{{ $paymentState['label'] }}</span>
                        </div>
                    </div>

                    <div class="summary-section">
                        <div class="summary-title">Khách hàng và giao hàng</div>
                        <ul class="summary-list">
                            <li><strong>Khách hàng:</strong> {{ $customerName }}</li>
                            <li><strong>Email:</strong> {{ $order->email }}</li>
                            <li><strong>Điện thoại:</strong> {{ $order->phone ?: 'Chưa cập nhật' }}</li>
                            <li><strong>Địa chỉ:</strong> {{ $customerAddress ?: 'Chưa cập nhật địa chỉ giao hàng' }}</li>
                            <li><strong>Vận chuyển:</strong> {{ $shippingLabel }}</li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="card edit-order-panel">
                <div class="card-header">
                    <h2>Sản phẩm trong đơn</h2>
                    <p>Kiểm tra nhanh các mặt hàng liên quan trước khi đánh dấu giao thành công hoặc hủy đơn.</p>
                </div>
                <div class="card-body">
                    <div class="product-list">
                        @forelse ($order->cart as $item)
                            <div class="product-item">
                                <div class="product-item-main">
                                    <strong>{{ optional($item->product)->title ?: 'Sản phẩm đã xóa' }}</strong>
                                    <span>{{ number_format($item->quantity, 0, ',', '.') }} x {{ $money($item->price) }}</span>
                                </div>
                                <div class="product-item-total">{{ $money($item->amount ?: ($item->quantity * $item->price)) }}</div>
                            </div>
                        @empty
                            <div class="empty-state">Đơn hàng này hiện chưa có sản phẩm hiển thị.</div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .edit-order-page { padding-bottom: 2rem; }
    .edit-order-hero {
        background:
            radial-gradient(circle at top right, rgba(96, 165, 250, .28), transparent 34%),
            linear-gradient(135deg, #16384c 0%, #1d4f6a 48%, #0f766e 100%);
        border-radius: 1.4rem;
        color: #fff;
        box-shadow: 0 22px 50px rgba(15, 23, 42, .12);
        padding: 1.75rem;
    }
    .edit-order-kicker {
        font-size: .78rem;
        font-weight: 800;
        letter-spacing: .22em;
        margin-bottom: .85rem;
        text-transform: uppercase;
    }
    .edit-order-hero h1 { font-size: 2rem; font-weight: 800; line-height: 1.15; margin-bottom: .85rem; }
    .edit-order-hero p { color: rgba(255,255,255,.82); margin-bottom: 0; max-width: 40rem; }
    .edit-order-hero-actions { display: flex; flex-wrap: wrap; gap: .75rem; justify-content: flex-end; margin-bottom: 1rem; }
    .edit-order-current { background: rgba(255,255,255,.12); border: 1px solid rgba(255,255,255,.16); border-radius: 1rem; padding: 1rem; }
    .edit-order-current-meta { color: rgba(255,255,255,.82); display: grid; gap: .35rem; font-size: .88rem; margin-top: .85rem; }

    .edit-order-panel { border: 0; border-radius: 1.2rem; box-shadow: 0 18px 40px rgba(15, 23, 42, .08); overflow: hidden; }
    .edit-order-panel .card-header { background: #fff; border-bottom: 1px solid #e2e8f0; padding: 1.25rem 1.4rem 1rem; }
    .edit-order-panel .card-header h2 { color: #0f172a; font-size: 1.05rem; font-weight: 800; margin-bottom: .25rem; }
    .edit-order-panel .card-header p { color: #64748b; font-size: .9rem; margin-bottom: 0; }
    .edit-order-panel .card-body { padding: 1.35rem 1.4rem 1.4rem; }

    .edit-order-status-overview { display: grid; gap: .85rem; margin-bottom: 1.25rem; }
    .status-overview-item { align-items: flex-start; background: linear-gradient(180deg, #fff 0%, #f8fafc 100%); border: 1px solid #e2e8f0; border-radius: 1rem; display: flex; gap: .9rem; padding: .95rem 1rem; }
    .status-overview-item.is-current { border-color: #2563eb; box-shadow: 0 10px 24px rgba(37, 99, 235, .12); }
    .status-overview-item.is-reached { border-color: #16a34a; background: #f0fdf4; }
    .status-overview-item.is-locked { opacity: .56; }
    .status-overview-icon { align-items: center; border-radius: .9rem; display: inline-flex; height: 2.8rem; justify-content: center; width: 2.8rem; }
    .status-overview-title { color: #0f172a; font-size: .95rem; font-weight: 800; margin-bottom: .2rem; }
    .status-overview-text { color: #64748b; font-size: .84rem; line-height: 1.5; }

    .edit-order-label { color: #0f172a; display: block; font-size: .92rem; font-weight: 700; margin-bottom: .85rem; }
    .status-choice-grid { display: grid; gap: .85rem; }
    .status-choice { cursor: pointer; display: block; margin-bottom: 0; }
    .status-choice input { display: none; }
    .status-choice-card {
        align-items: flex-start;
        background: #fff;
        border: 1px solid #dbe3ee;
        border-radius: 1rem;
        display: flex;
        gap: .9rem;
        padding: 1rem;
        transition: all .2s ease;
    }
    .status-choice input:checked + .status-choice-card {
        border-color: #2563eb;
        box-shadow: 0 12px 28px rgba(37, 99, 235, .14);
        transform: translateY(-1px);
    }
    .status-choice input:disabled + .status-choice-card { background: #f8fafc; cursor: not-allowed; opacity: .5; }
    .status-choice input:disabled + .status-choice-card.is-reached { background: #f0fdf4; border-color: #16a34a; opacity: 1; }
    .status-choice input:disabled + .status-choice-card.is-current { background: #eff6ff; border-color: #2563eb; opacity: 1; }
    .status-choice-icon { align-items: center; border-radius: .9rem; display: inline-flex; height: 3rem; justify-content: center; width: 3rem; }
    .status-choice-body strong { color: #0f172a; display: block; font-size: .95rem; margin-bottom: .3rem; }
    .status-choice-body small { color: #64748b; display: block; font-size: .84rem; line-height: 1.55; }

    .edit-order-note { background: #eff6ff; border: 1px solid #bfdbfe; border-radius: 1rem; color: #1e3a8a; font-size: .9rem; line-height: 1.6; padding: .95rem 1rem; }
    .edit-order-actions { display: flex; flex-wrap: wrap; gap: .75rem; margin-top: 1.25rem; }

    .edit-order-metrics { display: grid; gap: .8rem; grid-template-columns: repeat(2, minmax(0, 1fr)); margin-bottom: 1.15rem; }
    .metric-box { background: linear-gradient(180deg, #fff 0%, #f8fbff 100%); border: 1px solid #e2e8f0; border-radius: 1rem; padding: .95rem 1rem; }
    .metric-box span { color: #64748b; display: block; font-size: .8rem; margin-bottom: .35rem; text-transform: uppercase; }
    .metric-box strong { color: #0f172a; display: block; font-size: 1.12rem; font-weight: 800; }

    .summary-section + .summary-section { margin-top: 1rem; }
    .summary-title { color: #0f172a; font-size: .92rem; font-weight: 800; margin-bottom: .7rem; }
    .badge-row { display: flex; flex-wrap: wrap; gap: .55rem; }
    .summary-list { list-style: none; margin: 0; padding: 0; }
    .summary-list li { color: #475569; line-height: 1.6; padding: .3rem 0; }
    .summary-list strong { color: #0f172a; }

    .product-list { display: grid; gap: .8rem; }
    .product-item { align-items: center; background: linear-gradient(180deg, #fff 0%, #f8fafc 100%); border: 1px solid #e2e8f0; border-radius: 1rem; display: flex; gap: 1rem; justify-content: space-between; padding: .95rem 1rem; }
    .product-item-main strong { color: #0f172a; display: block; margin-bottom: .25rem; }
    .product-item-main span { color: #64748b; font-size: .85rem; }
    .product-item-total { color: #0f172a; font-size: .95rem; font-weight: 800; white-space: nowrap; }
    .empty-state { background: #f8fafc; border: 1px dashed #cbd5e1; border-radius: 1rem; color: #64748b; padding: 1rem; text-align: center; }

    .edit-order-pill { align-items: center; border-radius: 999px; display: inline-flex; font-size: .78rem; font-weight: 700; line-height: 1; padding: .5rem .85rem; }
    .tone-new { background: rgba(59, 130, 246, .14); color: #1d4ed8; }
    .tone-process { background: rgba(249, 115, 22, .14); color: #c2410c; }
    .tone-delivered { background: rgba(16, 185, 129, .14); color: #047857; }
    .tone-cancel { background: rgba(239, 68, 68, .14); color: #b91c1c; }
    .tone-muted { background: rgba(148, 163, 184, .18); color: #475569; }
    .payment-method-cod { background: rgba(14, 165, 233, .16); color: #0369a1; }
    .payment-method-paypal { background: rgba(37, 99, 235, .16); color: #1d4ed8; }
    .payment-method-momo { background: rgba(236, 72, 153, .16); color: #be185d; }
    .payment-method-other { background: rgba(148, 163, 184, .18); color: #475569; }
    .payment-state-paid { background: rgba(16, 185, 129, .14); color: #047857; }
    .payment-state-unpaid { background: rgba(100, 116, 139, .18); color: #334155; }
    .payment-state-review { background: rgba(217, 119, 6, .16); color: #b45309; }

    @media (max-width: 991.98px) {
        .edit-order-hero h1 { font-size: 1.65rem; }
        .edit-order-hero-actions { justify-content: flex-start; margin-top: 1rem; }
    }

    @media (max-width: 575.98px) {
        .edit-order-metrics { grid-template-columns: 1fr; }
        .product-item { align-items: flex-start; flex-direction: column; }
        .edit-order-actions { flex-direction: column; }
        .edit-order-actions .btn { width: 100%; }
    }
</style>
@endpush
