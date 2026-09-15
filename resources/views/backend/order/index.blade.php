@extends('backend.layouts.master')

@section('title', 'Web bán tai nghe || Quản lý đơn hàng')

@push('styles')
  <link href="{{ asset('backend/vendor/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
  <style>
    :root {
      --order-ink: #153243;
      --order-sky: #2b6cb0;
      --order-teal: #0f9d94;
      --order-amber: #d97706;
      --order-danger: #c53030;
      --order-slate: #5b677a;
      --order-surface: #f7fafc;
      --order-border: #e2e8f0;
      --order-panel-shadow: 0 20px 45px rgba(21, 50, 67, 0.08);
      --order-soft-shadow: 0 14px 30px rgba(21, 50, 67, 0.06);
    }

    .order-admin-page {
      padding-bottom: 2rem;
    }

    .order-hero {
      background:
        radial-gradient(circle at top right, rgba(43, 108, 176, 0.35), transparent 32%),
        linear-gradient(135deg, #16384c 0%, #1f5972 48%, #0f9d94 100%);
      border-radius: 1.4rem;
      box-shadow: var(--order-panel-shadow);
      color: #fff;
      overflow: hidden;
      padding: 1.75rem;
      position: relative;
    }

    .order-hero::after {
      background: rgba(255, 255, 255, 0.08);
      border-radius: 999px;
      content: '';
      height: 16rem;
      position: absolute;
      right: -5rem;
      top: -6rem;
      width: 16rem;
    }

    .order-hero-copy,
    .order-hero-focus {
      position: relative;
      z-index: 1;
    }

    .order-hero-kicker {
      font-size: 0.8rem;
      font-weight: 800;
      letter-spacing: 0.22em;
      margin-bottom: 0.85rem;
      text-transform: uppercase;
    }

    .order-hero h1 {
      font-size: 2rem;
      font-weight: 800;
      line-height: 1.15;
      margin-bottom: 0.9rem;
      max-width: 38rem;
    }

    .order-hero p {
      color: rgba(255, 255, 255, 0.84);
      margin-bottom: 0;
      max-width: 42rem;
    }

    .order-hero-actions {
      display: flex;
      flex-wrap: wrap;
      gap: 0.75rem;
      margin-top: 1.5rem;
    }

    .order-hero-btn {
      align-items: center;
      border: 0;
      border-radius: 999px;
      display: inline-flex;
      font-size: 0.9rem;
      font-weight: 700;
      gap: 0.4rem;
      padding: 0.8rem 1.1rem;
      text-decoration: none;
      transition: all 0.2s ease;
    }

    .order-hero-btn:hover,
    .order-hero-btn:focus {
      text-decoration: none;
      transform: translateY(-1px);
    }

    .order-hero-btn-light {
      background: #fff;
      color: var(--order-ink);
    }

    .order-hero-btn-light:hover,
    .order-hero-btn-light:focus {
      color: var(--order-ink);
    }

    .order-hero-btn-dark {
      background: rgba(255, 255, 255, 0.12);
      color: #fff;
    }

    .order-hero-btn-dark:hover,
    .order-hero-btn-dark:focus {
      color: #fff;
    }

    .order-hero-focus {
      background: rgba(255, 255, 255, 0.12);
      border: 1px solid rgba(255, 255, 255, 0.16);
      border-radius: 1.15rem;
      box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.08);
      height: 100%;
      padding: 1.25rem;
    }

    .order-focus-top {
      display: flex;
      flex-wrap: wrap;
      gap: 1rem;
      justify-content: space-between;
    }

    .order-focus-label {
      color: rgba(255, 255, 255, 0.72);
      font-size: 0.78rem;
      font-weight: 700;
      letter-spacing: 0.08em;
      margin-bottom: 0.35rem;
      text-transform: uppercase;
    }

    .order-focus-code {
      font-size: 1.35rem;
      font-weight: 800;
      line-height: 1.2;
      margin-bottom: 0.3rem;
    }

    .order-focus-subtitle {
      color: rgba(255, 255, 255, 0.82);
      font-size: 0.9rem;
      line-height: 1.5;
    }

    .order-focus-grid {
      display: grid;
      gap: 0.8rem;
      grid-template-columns: repeat(2, minmax(0, 1fr));
      margin-top: 1.2rem;
    }

    .order-focus-item {
      background: rgba(255, 255, 255, 0.08);
      border-radius: 0.95rem;
      padding: 0.9rem 1rem;
    }

    .order-focus-item span {
      color: rgba(255, 255, 255, 0.74);
      display: block;
      font-size: 0.78rem;
      margin-bottom: 0.35rem;
      text-transform: uppercase;
    }

    .order-focus-item strong {
      color: #fff;
      display: block;
      font-size: 1.2rem;
      font-weight: 800;
      line-height: 1.2;
    }

    .order-focus-meta {
      color: rgba(255, 255, 255, 0.82);
      display: grid;
      gap: 0.55rem;
      font-size: 0.88rem;
      margin-top: 1rem;
    }

    .order-summary-card {
      border: 0;
      border-radius: 1rem;
      box-shadow: var(--order-soft-shadow);
      color: #fff;
      height: 100%;
      min-height: 100%;
      overflow: hidden;
      position: relative;
    }

    .order-summary-card::after {
      background: rgba(255, 255, 255, 0.08);
      border-radius: 999px;
      content: '';
      height: 7rem;
      position: absolute;
      right: -1.6rem;
      top: -1.6rem;
      width: 7rem;
    }

    .order-summary-card .card-body {
      position: relative;
      z-index: 1;
    }

    .order-summary-primary {
      background: linear-gradient(140deg, #235789 0%, #2b6cb0 100%);
    }

    .order-summary-warning {
      background: linear-gradient(140deg, #b45309 0%, #d97706 100%);
    }

    .order-summary-success {
      background: linear-gradient(140deg, #0f766e 0%, #0f9d94 100%);
    }

    .order-summary-dark {
      background: linear-gradient(140deg, #334155 0%, #475569 100%);
    }

    .order-summary-label {
      font-size: 0.78rem;
      font-weight: 700;
      letter-spacing: 0.08em;
      margin-bottom: 0.7rem;
      opacity: 0.9;
      text-transform: uppercase;
    }

    .order-summary-value {
      font-size: 1.8rem;
      font-weight: 800;
      line-height: 1.1;
      margin-bottom: 0.65rem;
    }

    .order-summary-text {
      font-size: 0.88rem;
      margin-bottom: 0.85rem;
      opacity: 0.84;
    }

    .order-summary-icon {
      font-size: 1.9rem;
      opacity: 0.88;
    }

    .order-chip {
      align-items: center;
      background: rgba(255, 255, 255, 0.16);
      border-radius: 999px;
      display: inline-flex;
      font-size: 0.78rem;
      font-weight: 700;
      padding: 0.35rem 0.7rem;
    }

    .order-panel {
      background: #fff;
      border: 0;
      border-radius: 1.25rem;
      box-shadow: var(--order-panel-shadow);
      overflow: hidden;
    }

    .order-panel .card-header {
      background: #fff;
      border-bottom: 1px solid rgba(226, 232, 240, 0.95);
      padding: 1.4rem 1.5rem 1rem;
    }

    .order-panel .card-body {
      padding: 1.4rem 1.5rem 1.5rem;
    }

    .order-panel-title {
      color: var(--order-ink);
      font-size: 1.08rem;
      font-weight: 800;
      margin-bottom: 0.2rem;
    }

    .order-panel-subtitle {
      color: #718096;
      font-size: 0.9rem;
      margin-bottom: 0;
      max-width: 42rem;
    }

    .order-panel-meta {
      display: flex;
      flex-wrap: wrap;
      gap: 0.6rem;
      justify-content: flex-end;
      margin-top: 1rem;
    }

    .order-panel-badge {
      align-items: center;
      background: #f8fbff;
      border: 1px solid var(--order-border);
      border-radius: 999px;
      color: var(--order-ink);
      display: inline-flex;
      font-size: 0.8rem;
      font-weight: 700;
      gap: 0.35rem;
      padding: 0.45rem 0.8rem;
    }

    .order-toolbar {
      align-items: center;
      display: flex;
      flex-wrap: wrap;
      gap: 1rem;
      justify-content: space-between;
      margin-top: 1.2rem;
    }

    .order-filter-group {
      display: flex;
      flex-wrap: wrap;
      gap: 0.65rem;
    }

    .order-filter-chip {
      align-items: center;
      background: #fff;
      border: 1px solid var(--order-border);
      border-radius: 999px;
      color: #475569;
      display: inline-flex;
      font-size: 0.85rem;
      font-weight: 700;
      gap: 0.45rem;
      padding: 0.7rem 1rem;
      transition: all 0.2s ease;
    }

    .order-filter-chip span {
      background: #f1f5f9;
      border-radius: 999px;
      color: #334155;
      display: inline-flex;
      justify-content: center;
      min-width: 1.75rem;
      padding: 0.15rem 0.45rem;
    }

    .order-filter-chip:hover,
    .order-filter-chip:focus {
      box-shadow: 0 10px 20px rgba(21, 50, 67, 0.08);
      color: #1e293b;
      outline: none;
      text-decoration: none;
      transform: translateY(-1px);
    }

    .order-filter-chip.active {
      background: linear-gradient(135deg, #153243 0%, #2b6cb0 100%);
      border-color: transparent;
      color: #fff;
    }

    .order-filter-chip.active span {
      background: rgba(255, 255, 255, 0.15);
      color: #fff;
    }

    .order-toolbar-search {
      position: relative;
      width: min(100%, 24rem);
    }

    .order-toolbar-search i {
      color: #64748b;
      left: 1rem;
      position: absolute;
      top: 50%;
      transform: translateY(-50%);
    }

    .order-toolbar-search .form-control {
      border: 1px solid var(--order-border);
      border-radius: 999px;
      box-shadow: none;
      font-size: 0.92rem;
      height: 3rem;
      padding-left: 2.7rem;
    }

    .order-toolbar-search .form-control:focus {
      border-color: rgba(43, 108, 176, 0.5);
      box-shadow: 0 0 0 0.2rem rgba(43, 108, 176, 0.12);
    }

    .order-table {
      margin-bottom: 0;
    }

    .order-table thead th {
      border-bottom: 1px solid var(--order-border);
      border-top: 0;
      color: #718096;
      font-size: 0.76rem;
      font-weight: 800;
      letter-spacing: 0.08em;
      padding: 1rem;
      text-transform: uppercase;
      white-space: nowrap;
    }

    .order-table tbody tr {
      transition: background-color 0.18s ease;
    }

    .order-table tbody tr:hover {
      background: #fbfdff;
    }

    .order-table tbody td {
      border-color: var(--order-border);
      padding: 1rem;
      vertical-align: top;
    }

    .order-table-title {
      color: var(--order-ink);
      font-size: 0.95rem;
      font-weight: 800;
      line-height: 1.35;
    }

    .order-table-subtext {
      color: #64748b;
      font-size: 0.83rem;
      line-height: 1.5;
      margin-top: 0.3rem;
    }

    .order-table-link {
      color: var(--order-sky);
      font-weight: 600;
      text-decoration: none;
    }

    .order-table-link:hover,
    .order-table-link:focus {
      color: #1d4ed8;
      text-decoration: none;
    }

    .order-table-price {
      color: var(--order-ink);
      font-size: 1.05rem;
      font-weight: 800;
      line-height: 1.25;
    }

    .order-badge-row {
      display: flex;
      flex-wrap: wrap;
      gap: 0.5rem;
    }

    .order-pill {
      align-items: center;
      border-radius: 999px;
      display: inline-flex;
      font-size: 0.78rem;
      font-weight: 700;
      line-height: 1;
      padding: 0.48rem 0.8rem;
      white-space: nowrap;
    }

    .status-new {
      background: rgba(59, 130, 246, 0.14);
      color: #1d4ed8;
    }

    .status-process {
      background: rgba(249, 115, 22, 0.14);
      color: #c2410c;
    }

    .status-delivered {
      background: rgba(16, 185, 129, 0.14);
      color: #047857;
    }

    .status-cancel {
      background: rgba(239, 68, 68, 0.14);
      color: #b91c1c;
    }

    .status-muted {
      background: rgba(148, 163, 184, 0.18);
      color: #475569;
    }

    .payment-method-cod {
      background: rgba(14, 165, 233, 0.16);
      color: #0369a1;
    }

    .payment-method-paypal {
      background: rgba(37, 99, 235, 0.16);
      color: #1d4ed8;
    }

    .payment-method-momo {
      background: rgba(236, 72, 153, 0.16);
      color: #be185d;
    }

    .payment-method-muted {
      background: rgba(148, 163, 184, 0.18);
      color: #475569;
    }

    .payment-state-paid {
      background: rgba(16, 185, 129, 0.14);
      color: #047857;
    }

    .payment-state-unpaid {
      background: rgba(100, 116, 139, 0.18);
      color: #334155;
    }

    .payment-state-review {
      background: rgba(217, 119, 6, 0.16);
      color: #b45309;
    }

    .order-action-group {
      display: flex;
      flex-wrap: wrap;
      gap: 0.5rem;
    }

    .order-action-btn {
      align-items: center;
      border-radius: 0.85rem;
      display: inline-flex;
      font-size: 0.82rem;
      font-weight: 700;
      gap: 0.45rem;
      justify-content: center;
      min-width: 5rem;
      padding: 0.68rem 0.85rem;
      text-decoration: none;
      transition: all 0.2s ease;
    }

    .order-action-btn:hover,
    .order-action-btn:focus {
      text-decoration: none;
      transform: translateY(-1px);
    }

    .order-action-view {
      background: rgba(15, 157, 148, 0.14);
      color: #0f766e;
    }

    .order-action-edit {
      background: rgba(43, 108, 176, 0.14);
      color: #1d4ed8;
    }

    .order-empty {
      background: linear-gradient(180deg, #ffffff 0%, #f8fbff 100%);
      border: 1px dashed var(--order-border);
      border-radius: 1rem;
      padding: 2rem 1.5rem;
      text-align: center;
    }

    .order-empty-icon {
      align-items: center;
      background: rgba(43, 108, 176, 0.1);
      border-radius: 999px;
      color: var(--order-sky);
      display: inline-flex;
      font-size: 1.35rem;
      height: 4rem;
      justify-content: center;
      margin-bottom: 1rem;
      width: 4rem;
    }

    .order-empty h3 {
      color: var(--order-ink);
      font-size: 1.2rem;
      font-weight: 800;
      margin-bottom: 0.55rem;
    }

    .order-empty p {
      color: #718096;
      margin-bottom: 1.2rem;
    }

    .order-pagination {
      margin-top: 1.25rem;
    }

    .order-pagination .pagination {
      justify-content: flex-end;
      margin-bottom: 0;
    }

    div.dataTables_wrapper div.dataTables_filter,
    div.dataTables_wrapper div.dataTables_length,
    div.dataTables_wrapper div.dataTables_info,
    div.dataTables_wrapper div.dataTables_paginate {
      display: none;
    }

    table.dataTable {
      border-collapse: collapse !important;
      margin-top: 0 !important;
      width: 100% !important;
    }

    @media (max-width: 991.98px) {
      .order-hero h1 {
        font-size: 1.65rem;
      }

      .order-panel-meta {
        justify-content: flex-start;
      }
    }

    @media (max-width: 767.98px) {
      .order-hero,
      .order-panel .card-header,
      .order-panel .card-body {
        padding-left: 1rem;
        padding-right: 1rem;
      }

      .order-focus-grid {
        grid-template-columns: 1fr;
      }

      .order-toolbar-search {
        width: 100%;
      }

      .order-pagination .pagination {
        justify-content: center;
      }
    }
  </style>
@endpush

@section('main-content')
  @php
    $statusMeta = collect(\App\Models\Order::ORDER_STATUS_LABELS)->mapWithKeys(function ($label, $key) {
      return [$key => ['label' => $label, 'class' => in_array($key, ['cancelled', 'delivery_failed'], true) ? 'status-cancel' : (in_array($key, ['delivery_success', 'completed'], true) ? 'status-delivered' : 'status-process'), 'icon' => 'fas fa-circle']];
    })->all();

    $paymentMethodMeta = [
      'cod' => ['label' => 'COD', 'class' => 'payment-method-cod', 'icon' => 'fas fa-money-bill-wave'],
      'paypal' => ['label' => 'PayPal', 'class' => 'payment-method-paypal', 'icon' => 'fab fa-paypal'],
      'momo' => ['label' => 'MoMo', 'class' => 'payment-method-momo', 'icon' => 'fas fa-mobile-alt'],
    ];

    $paymentStateMeta = collect(\App\Models\Order::PAYMENT_STATUS_LABELS)->mapWithKeys(function ($label, $key) {
      return [$key => ['label' => $label, 'class' => $key === 'paid' ? 'payment-state-paid' : ($key === 'unpaid' ? 'payment-state-unpaid' : 'payment-state-review')]];
    })->all();

    $fallbackStatus = ['label' => 'Chưa xác định', 'class' => 'status-muted', 'icon' => 'fas fa-question-circle'];
    $fallbackMethod = ['label' => 'Khác', 'class' => 'payment-method-muted', 'icon' => 'fas fa-wallet'];
    $fallbackPaymentState = ['label' => 'Đang đối soát', 'class' => 'payment-state-review'];

    $latestStatusKey = $latestOrder ? trim((string) $latestOrder->status) : null;
    $latestStatus = $latestStatusKey && isset($statusMeta[$latestStatusKey]) ? $statusMeta[$latestStatusKey] : $fallbackStatus;
  @endphp

  <div class="container-fluid order-admin-page">
    @include('backend.layouts.notification')

    <div class="order-hero mb-4">
      <div class="row align-items-stretch">
        <div class="col-xl-7 mb-4 mb-xl-0">
          <div class="order-hero-copy">
            <div class="order-hero-kicker">Order Control Center</div>
            <h1>Quản lý đơn hàng gọn mắt, có nhịp ưu tiên và dễ thao tác hơn</h1>
            <p>Trang quản trị này gom các tín hiệu quan trọng như đơn mới, đơn cần xử lý, doanh thu đã giao và trạng thái thanh toán để đội admin nhìn vào là biết việc gì cần làm trước.</p>
            <div class="order-hero-actions">
              <a href="{{ route('admin') }}" class="order-hero-btn order-hero-btn-light">
                <i class="fas fa-chart-line"></i>
                <span>Xem dashboard</span>
              </a>
              <button type="button" id="orderQuickRefresh" class="order-hero-btn order-hero-btn-dark">
                <i class="fas fa-sync-alt"></i>
                <span>Làm mới danh sách</span>
              </button>
            </div>
          </div>
        </div>

        <div class="col-xl-5">
          <div class="order-hero-focus">
            <div class="order-focus-top">
              <div>
                <div class="order-focus-label">Đơn mới nhất</div>
                @if ($latestOrder)
                  <div class="order-focus-code">{{ $latestOrder->order_number }}</div>
                  <div class="order-focus-subtitle">
                    {{ trim($latestOrder->first_name . ' ' . $latestOrder->last_name) ?: 'Khách hàng chưa cập nhật' }}
                    <span class="mx-2">•</span>
                    {{ optional($latestOrder->created_at)->format('d/m/Y H:i') }}
                  </div>
                @else
                  <div class="order-focus-code">Chưa có đơn hàng</div>
                  <div class="order-focus-subtitle">Dữ liệu đơn mới nhất sẽ xuất hiện khi hệ thống có đơn đầu tiên.</div>
                @endif
              </div>

              @if ($latestOrder)
                <span class="order-pill {{ $latestStatus['class'] }}">
                  <i class="{{ $latestStatus['icon'] }} mr-2"></i>
                  {{ $latestStatus['label'] }}
                </span>
              @endif
            </div>

            <div class="order-focus-grid">
              <div class="order-focus-item">
                <span>Đơn hôm nay</span>
                <strong>{{ number_format($todayOrders, 0, ',', '.') }}</strong>
              </div>
              <div class="order-focus-item">
                <span>Tỉ lệ hoàn tất</span>
                <strong>{{ number_format($fulfillmentRate, 1, ',', '.') }}%</strong>
              </div>
              <div class="order-focus-item">
                <span>COD</span>
                <strong>{{ number_format((int) $paymentSummary->get('cod', 0), 0, ',', '.') }}</strong>
              </div>
              <div class="order-focus-item">
                <span>MoMo</span>
                <strong>{{ number_format((int) $paymentSummary->get('momo', 0), 0, ',', '.') }}</strong>
              </div>
            </div>

            @if ($latestOrder)
              <div class="order-focus-meta">
                <div>
                  <i class="fas fa-map-marker-alt mr-2"></i>
                  {{ $latestOrder->address1 }}{{ $latestOrder->address2 ? ', ' . $latestOrder->address2 : '' }}
                </div>
                <div>
                  <i class="fas fa-truck mr-2"></i>
                  {{ optional($latestOrder->shipping)->type ?: 'Chưa chọn hình thức giao hàng' }}
                </div>
              </div>
            @endif
          </div>
        </div>
      </div>
    </div>

    <div class="row">
      <div class="col-xl-3 col-md-6 mb-4">
        <div class="card order-summary-card order-summary-primary">
          <div class="card-body">
            <div class="d-flex justify-content-between align-items-start mb-3">
              <div>
                <div class="order-summary-label">Tổng đơn hàng</div>
                <div class="order-summary-value">{{ number_format($totalOrders, 0, ',', '.') }}</div>
              </div>
              <div class="order-summary-icon">
                <i class="fas fa-shopping-bag"></i>
              </div>
            </div>
            <div class="order-summary-text">Tổng tất cả đơn đã phát sinh trong hệ thống, bao gồm cả đơn hoàn tất và đơn hủy.</div>
            <span class="order-chip">{{ number_format($cancelledOrders, 0, ',', '.') }} đơn đã hủy</span>
          </div>
        </div>
      </div>

      <div class="col-xl-3 col-md-6 mb-4">
        <div class="card order-summary-card order-summary-warning">
          <div class="card-body">
            <div class="d-flex justify-content-between align-items-start mb-3">
              <div>
                <div class="order-summary-label">Cần xử lý</div>
                <div class="order-summary-value">{{ number_format($pendingOrders, 0, ',', '.') }}</div>
              </div>
              <div class="order-summary-icon">
                <i class="fas fa-shipping-fast"></i>
              </div>
            </div>
            <div class="order-summary-text">Gồm các đơn mới tạo và đơn đang xử lý, phù hợp để đội admin ưu tiên xử lý ngay.</div>
            <span class="order-chip">{{ number_format((int) $statusSummary->get('pending_confirmation', 0), 0, ',', '.') }} chờ xác nhận • {{ number_format((int) $statusSummary->get('preparing', 0), 0, ',', '.') }} đang chuẩn bị</span>
          </div>
        </div>
      </div>

      <div class="col-xl-3 col-md-6 mb-4">
        <div class="card order-summary-card order-summary-success">
          <div class="card-body">
            <div class="d-flex justify-content-between align-items-start mb-3">
              <div>
                <div class="order-summary-label">Đã giao thành công</div>
                <div class="order-summary-value">{{ number_format($deliveredOrders, 0, ',', '.') }}</div>
              </div>
              <div class="order-summary-icon">
                <i class="fas fa-check-double"></i>
              </div>
            </div>
            <div class="order-summary-text">Phần đơn đã hoàn tất giao hàng, phản ánh năng lực vận hành thực tế của shop.</div>
            <span class="order-chip">Tỉ lệ hoàn tất {{ number_format($fulfillmentRate, 1, ',', '.') }}%</span>
          </div>
        </div>
      </div>

      <div class="col-xl-3 col-md-6 mb-4">
        <div class="card order-summary-card order-summary-dark">
          <div class="card-body">
            <div class="d-flex justify-content-between align-items-start mb-3">
              <div>
                <div class="order-summary-label">Doanh thu đã giao</div>
                <div class="order-summary-value">{{ number_format($deliveredRevenue, 0, ',', '.') }}đ</div>
              </div>
              <div class="order-summary-icon">
                <i class="fas fa-wallet"></i>
              </div>
            </div>
            <div class="order-summary-text">Chỉ tính các đơn đã giao để tránh nhiễu từ đơn mới, đơn xử lý hoặc đơn bị hủy.</div>
            <span class="order-chip">Giá trị TB {{ number_format($averageOrderValue, 0, ',', '.') }}đ</span>
          </div>
        </div>
      </div>
    </div>

    <div class="card order-panel">
      <div class="card-header">
        <div class="row align-items-lg-center">
          <div class="col-lg-8">
            <div class="order-panel-title">Danh sách đơn hàng</div>
            <p class="order-panel-subtitle">Lọc nhanh theo trạng thái, tìm theo mã đơn hoặc khách hàng, rồi mở chi tiết hay cập nhật trạng thái chỉ với vài thao tác ngắn.</p>
          </div>
          <div class="col-lg-4">
            <div class="order-panel-meta">
              <span class="order-panel-badge">
                <i class="fas fa-layer-group"></i>
                Hiển thị {{ number_format($orders->count(), 0, ',', '.') }} / {{ number_format($totalOrders, 0, ',', '.') }} đơn
              </span>
              <span class="order-panel-badge">
                <i class="fas fa-clock"></i>
                {{ number_format($pendingOrders, 0, ',', '.') }} đơn chờ xử lý
              </span>
            </div>
          </div>
        </div>

        <div class="order-toolbar">
          <div class="order-filter-group" role="group" aria-label="Lọc trạng thái đơn hàng">
            <button type="button" class="order-filter-chip active" data-status="all">
              Tất cả
              <span>{{ number_format($totalOrders, 0, ',', '.') }}</span>
            </button>
            <button type="button" class="order-filter-chip" data-status="pending_confirmation">
              Chờ xác nhận
              <span>{{ number_format((int) $statusSummary->get('pending_confirmation', 0), 0, ',', '.') }}</span>
            </button>
            <button type="button" class="order-filter-chip" data-status="preparing">
              Đang chuẩn bị
              <span>{{ number_format((int) $statusSummary->get('preparing', 0), 0, ',', '.') }}</span>
            </button>
            <button type="button" class="order-filter-chip" data-status="ready">
              Đã sẵn sàng
              <span>{{ number_format((int) $statusSummary->get('ready', 0), 0, ',', '.') }}</span>
            </button>
            <button type="button" class="order-filter-chip" data-status="shipping">
              Đang giao hàng
              <span>{{ number_format((int) $statusSummary->get('shipping', 0), 0, ',', '.') }}</span>
            </button>
            <button type="button" class="order-filter-chip" data-status="delivery_failed">
              Giao hàng thất bại
              <span>{{ number_format((int) $statusSummary->get('delivery_failed', 0), 0, ',', '.') }}</span>
            </button>
            <button type="button" class="order-filter-chip" data-status="returning">
              Đang hoàn hàng
              <span>{{ number_format((int) $statusSummary->get('returning', 0), 0, ',', '.') }}</span>
            </button>
            <button type="button" class="order-filter-chip" data-status="returned">
              Hoàn hàng thành công
              <span>{{ number_format((int) $statusSummary->get('returned', 0), 0, ',', '.') }}</span>
            </button>
            <button type="button" class="order-filter-chip" data-status="delivery_success">
              Giao hàng thành công
              <span>{{ number_format((int) $statusSummary->get('delivery_success', 0), 0, ',', '.') }}</span>
            </button>
            <button type="button" class="order-filter-chip" data-status="completed">
              Hoàn thành
              <span>{{ number_format((int) $statusSummary->get('completed', 0), 0, ',', '.') }}</span>
            </button>
            <button type="button" class="order-filter-chip" data-status="cancelled">
              Đã hủy
              <span>{{ number_format((int) $statusSummary->get('cancelled', 0), 0, ',', '.') }}</span>
            </button>
            <button type="button" class="order-filter-chip" data-status="ended">
              Kết thúc
              <span>{{ number_format((int) $statusSummary->get('ended', 0), 0, ',', '.') }}</span>
            </button>
          </div>

          <div class="order-toolbar-search">
            <i class="fas fa-search"></i>
            <input
              type="text"
              id="orderSearchInput"
              class="form-control"
              placeholder="Tìm theo mã đơn, khách hàng, email, số điện thoại..."
            >
          </div>
        </div>
      </div>

      <div class="card-body">
        @if ($orders->count())
          <div class="table-responsive">
            <table class="table order-table" id="order-dataTable" width="100%" cellspacing="0">
              <thead>
                <tr>
                  <th>Đơn hàng</th>
                  <th>Khách hàng</th>
                  <th>Giao hàng</th>
                  <th>Giá trị</th>
                  <th>Thanh toán</th>
                  <th>Trạng thái</th>
                  <th>Thao tác</th>
                </tr>
              </thead>
              <tbody>
                @foreach ($orders as $order)
                  @php
                    $statusKey = trim((string) $order->status);
                    $status = $statusMeta[$statusKey] ?? $fallbackStatus;

                    $paymentMethodKey = strtolower(trim((string) $order->payment_method));
                    $paymentMethod = $paymentMethodMeta[$paymentMethodKey] ?? $fallbackMethod;

                    $paymentStateKey = strtolower(trim((string) $order->payment_status));
                    $paymentState = $paymentStateMeta[$paymentStateKey] ?? $fallbackPaymentState;

                    $shippingPrice = optional($order->shipping)->price ?? 0;
                    $shippingType = optional($order->shipping)->type ?: 'Chưa chọn';
                    $customerName = trim($order->first_name . ' ' . $order->last_name) ?: 'Khách hàng chưa cập nhật';
                    $addressLine = collect([$order->address1, $order->address2])->filter()->implode(', ');
                    $locationLine = collect([$order->country, $order->post_code])->filter()->implode(' • ');
                    $avgPerItem = $order->quantity > 0 ? ($order->total_amount / $order->quantity) : $order->total_amount;
                  @endphp

                  <tr data-order-status="{{ $statusKey }}">
                    <td>
                      <div class="order-table-title">{{ $order->order_number }}</div>
                      <div class="order-table-subtext">Mã nội bộ #{{ $order->id }}</div>
                      <div class="order-table-subtext">{{ optional($order->created_at)->format('d/m/Y H:i') }}</div>
                    </td>

                    <td>
                      <div class="order-table-title">{{ $customerName }}</div>
                      <div class="order-table-subtext">
                        <a href="mailto:{{ $order->email }}" class="order-table-link">{{ $order->email }}</a>
                      </div>
                      <div class="order-table-subtext">{{ $order->phone ?: 'Chưa có số điện thoại' }}</div>
                    </td>

                    <td>
                      <div class="order-table-title">{{ $shippingType }}</div>
                      <div class="order-table-subtext">{{ $addressLine ?: 'Chưa cập nhật địa chỉ giao hàng' }}</div>
                      <div class="order-table-subtext">{{ $locationLine ?: 'Chưa cập nhật khu vực giao hàng' }}</div>
                    </td>

                    <td>
                      <div class="order-table-price">{{ number_format($order->total_amount, 0, ',', '.') }}đ</div>
                      <div class="order-table-subtext">Số lượng {{ number_format($order->quantity, 0, ',', '.') }} • Ship {{ number_format($shippingPrice, 0, ',', '.') }}đ</div>
                      <div class="order-table-subtext">Trung bình {{ number_format($avgPerItem, 0, ',', '.') }}đ / sản phẩm</div>
                    </td>

                    <td>
                      <div class="order-badge-row">
                        <span class="order-pill {{ $paymentMethod['class'] }}">
                          <i class="{{ $paymentMethod['icon'] }} mr-2"></i>
                          {{ $paymentMethod['label'] }}
                        </span>
                        <span class="order-pill {{ $paymentState['class'] }}">
                          {{ $paymentState['label'] }}
                        </span>
                      </div>
                    </td>

                    <td>
                      <span class="order-pill {{ $status['class'] }}">
                        <i class="{{ $status['icon'] }} mr-2"></i>
                        {{ $status['label'] }}
                      </span>
                    </td>

                    <td>
                      <div class="order-action-group">
                        <a
                          href="{{ route('order.show', $order->id) }}"
                          class="order-action-btn order-action-view"
                          data-toggle="tooltip"
                          title="Xem chi tiết đơn hàng"
                        >
                          <i class="fas fa-eye"></i>
                          <span>Xem</span>
                        </a>
                        <a
                          href="{{ route('order.edit', $order->id) }}"
                          class="order-action-btn order-action-edit"
                          data-toggle="tooltip"
                          title="Cập nhật trạng thái đơn hàng"
                        >
                          <i class="fas fa-pen"></i>
                          <span>Sửa</span>
                        </a>
                      </div>
                    </td>
                  </tr>
                @endforeach
              </tbody>
            </table>
          </div>

          <div class="order-pagination">
            {{ $orders->links() }}
          </div>
        @else
          <div class="order-empty">
            <div class="order-empty-icon">
              <i class="fas fa-receipt"></i>
            </div>
            <h3>Chưa có đơn hàng nào</h3>
            <p>Ngay khi khách đặt mua, đơn hàng sẽ xuất hiện tại đây để bạn theo dõi và xử lý.</p>
            <a href="{{ route('admin') }}" class="order-hero-btn order-hero-btn-light">
              <i class="fas fa-chart-line"></i>
              <span>Về dashboard</span>
            </a>
          </div>
        @endif
      </div>
    </div>
  </div>
@endsection

@push('scripts')
  <script src="{{ asset('backend/vendor/datatables/jquery.dataTables.min.js') }}"></script>
  <script src="{{ asset('backend/vendor/datatables/dataTables.bootstrap4.min.js') }}"></script>
  <script>
    $(function () {
      const $table = $('#order-dataTable');
      const statusFilter = { value: 'all' };

      $.fn.dataTable.ext.search.push(function (settings, data, dataIndex) {
        if (settings.nTable.id !== 'order-dataTable') {
          return true;
        }

        if (statusFilter.value === 'all') {
          return true;
        }

        const rowNode = settings.aoData[dataIndex] && settings.aoData[dataIndex].nTr;
        const rowStatus = rowNode ? (rowNode.getAttribute('data-order-status') || '').trim() : '';

        return rowStatus === statusFilter.value;
      });

      if ($table.length) {
        const orderTable = $table.DataTable({
          autoWidth: false,
          info: false,
          language: {
            emptyTable: 'Chưa có đơn hàng nào.',
            zeroRecords: 'Không tìm thấy đơn hàng phù hợp trên trang này.'
          },
          lengthChange: false,
          order: [],
          paging: false,
          searching: true,
          columnDefs: [
            {
              orderable: false,
              targets: [6]
            }
          ]
        });

        $('#orderSearchInput').on('keyup', function () {
          orderTable.search(this.value).draw();
        });

        $('.order-filter-chip').on('click', function () {
          const $chip = $(this);

          statusFilter.value = $chip.data('status');
          $('.order-filter-chip').removeClass('active');
          $chip.addClass('active');
          orderTable.draw();
        });
      }

      $('#orderQuickRefresh').on('click', function () {
        window.location.reload();
      });

      $('[data-toggle="tooltip"]').tooltip();
    });
  </script>
@endpush
