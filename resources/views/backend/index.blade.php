@extends('backend.layouts.master')
@section('title', 'Web bán tai nghe || Phân tích kinh doanh')

@push('styles')
<style>
  :root {
    --analytics-ink: #153243;
    --analytics-sky: #2b6cb0;
    --analytics-teal: #0f9d94;
    --analytics-gold: #d97706;
    --analytics-slate: #4a5568;
    --analytics-danger: #c53030;
    --analytics-surface: #f7fafc;
    --analytics-panel-shadow: 0 20px 45px rgba(21, 50, 67, 0.08);
  }

  .analytics-page {
    padding-bottom: 2rem;
  }

  .analytics-hero {
    background:
      radial-gradient(circle at top right, rgba(43, 108, 176, 0.32), transparent 32%),
      linear-gradient(135deg, #16384c 0%, #245f73 48%, #0f9d94 100%);
    border-radius: 1.25rem;
    box-shadow: var(--analytics-panel-shadow);
    color: #fff;
    overflow: hidden;
    padding: 1.5rem;
    position: relative;
  }

  .analytics-hero::after {
    background: rgba(255, 255, 255, 0.08);
    border-radius: 999px;
    content: '';
    height: 14rem;
    position: absolute;
    right: -4rem;
    top: -6rem;
    width: 14rem;
  }

  .analytics-kicker {
    font-size: 0.8rem;
    font-weight: 700;
    letter-spacing: 0.22em;
    margin-bottom: 0.75rem;
    text-transform: uppercase;
  }

  .analytics-hero h1 {
    font-size: 2rem;
    font-weight: 800;
    line-height: 1.15;
    margin-bottom: 0.75rem;
  }

  .analytics-hero p {
    color: rgba(255, 255, 255, 0.84);
    margin-bottom: 0;
    max-width: 40rem;
  }

  .analytics-range-group {
    display: flex;
    flex-wrap: wrap;
    gap: 0.5rem;
    justify-content: flex-end;
    position: relative;
    z-index: 1;
  }

  .analytics-range {
    background: rgba(255, 255, 255, 0.12);
    border: 1px solid rgba(255, 255, 255, 0.18);
    border-radius: 999px;
    color: #fff;
    display: inline-flex;
    font-size: 0.85rem;
    font-weight: 700;
    padding: 0.6rem 1rem;
    text-decoration: none;
    transition: all 0.2s ease;
  }

  .analytics-range:hover,
  .analytics-range:focus {
    color: #fff;
    text-decoration: none;
    transform: translateY(-1px);
  }

  .analytics-range.active {
    background: #fff;
    color: var(--analytics-ink);
  }

  .analytics-role-grid {
    display: grid;
    gap: 1rem;
    grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
  }

  .analytics-role-card {
    background: linear-gradient(180deg, #ffffff 0%, #f8fbff 100%);
    border: 1px solid #e2e8f0;
    border-radius: 1rem;
    color: inherit;
    display: block;
    min-height: 100%;
    padding: 1rem;
    position: relative;
    text-decoration: none;
    transition: transform 0.18s ease, box-shadow 0.18s ease, border-color 0.18s ease;
  }

  .analytics-role-card:hover,
  .analytics-role-card:focus {
    border-color: rgba(43, 108, 176, 0.28);
    box-shadow: 0 16px 30px rgba(21, 50, 67, 0.08);
    text-decoration: none;
    transform: translateY(-2px);
  }

  .analytics-role-top {
    align-items: center;
    display: flex;
    justify-content: space-between;
    margin-bottom: 1rem;
  }

  .analytics-role-icon {
    align-items: center;
    background: rgba(43, 108, 176, 0.1);
    border-radius: 0.9rem;
    color: var(--analytics-sky);
    display: inline-flex;
    font-size: 1.1rem;
    height: 2.8rem;
    justify-content: center;
    width: 2.8rem;
  }

  .analytics-role-title {
    color: var(--analytics-ink);
    font-size: 1.08rem;
    font-weight: 800;
    margin-bottom: 0.5rem;
  }

  .analytics-role-copy {
    color: #4a5568;
    font-size: 0.88rem;
    line-height: 1.6;
    margin-bottom: 1rem;
  }

  .analytics-role-link {
    color: var(--analytics-sky);
    display: inline-flex;
    font-size: 0.83rem;
    font-weight: 800;
    gap: 0.35rem;
    text-transform: uppercase;
  }

  .analytics-kpi {
    border: 0;
    border-radius: 1rem;
    box-shadow: var(--analytics-panel-shadow);
    color: #fff;
    min-height: 100%;
    overflow: hidden;
    position: relative;
  }

  .analytics-kpi::after {
    background: rgba(255, 255, 255, 0.08);
    border-radius: 999px;
    content: '';
    height: 7rem;
    position: absolute;
    right: -1.5rem;
    top: -1.5rem;
    width: 7rem;
  }

  .analytics-kpi .card-body {
    position: relative;
    z-index: 1;
  }

  .analytics-kpi-primary {
    background: linear-gradient(140deg, #235789 0%, #2b6cb0 100%);
  }

  .analytics-kpi-success {
    background: linear-gradient(140deg, #0f766e 0%, #0f9d94 100%);
  }

  .analytics-kpi-info {
    background: linear-gradient(140deg, #176087 0%, #1d8db5 100%);
  }

  .analytics-kpi-warning {
    background: linear-gradient(140deg, #b45309 0%, #d97706 100%);
  }

  .analytics-kpi-secondary {
    background: linear-gradient(140deg, #4c566a 0%, #718096 100%);
  }

  .analytics-kpi-danger {
    background: linear-gradient(140deg, #9b2c2c 0%, #c53030 100%);
  }

  .analytics-kpi-label {
    font-size: 0.82rem;
    font-weight: 700;
    letter-spacing: 0.08em;
    margin-bottom: 0.75rem;
    opacity: 0.9;
    text-transform: uppercase;
  }

  .analytics-kpi-value {
    font-size: 1.8rem;
    font-weight: 800;
    line-height: 1.1;
    margin-bottom: 0.65rem;
  }

  .analytics-kpi-desc {
    font-size: 0.9rem;
    margin-bottom: 0.85rem;
    opacity: 0.82;
  }

  .analytics-kpi-icon {
    font-size: 2rem;
    opacity: 0.85;
  }

  .analytics-chip {
    align-items: center;
    background: rgba(255, 255, 255, 0.16);
    border-radius: 999px;
    display: inline-flex;
    font-size: 0.78rem;
    font-weight: 700;
    padding: 0.35rem 0.7rem;
  }

  .analytics-chip.change-flat {
    background: rgba(255, 255, 255, 0.12);
  }

  .analytics-chip.change-muted {
    background: rgba(255, 255, 255, 0.12);
  }

  .analytics-panel {
    background: #fff;
    border: 0;
    border-radius: 1rem;
    box-shadow: var(--analytics-panel-shadow);
    overflow: hidden;
  }

  .analytics-panel .card-header {
    align-items: flex-start;
    background: #fff;
    border-bottom: 1px solid rgba(226, 232, 240, 0.9);
    display: flex;
    justify-content: space-between;
    padding: 1.15rem 1.25rem;
  }

  .analytics-panel-title {
    color: var(--analytics-ink);
    font-size: 1rem;
    font-weight: 800;
    margin-bottom: 0.15rem;
  }

  .analytics-panel-subtitle {
    color: #718096;
    font-size: 0.85rem;
    margin-bottom: 0;
  }

  .analytics-panel .card-body {
    padding: 1.25rem;
  }

  .analytics-panel-badge {
    background: rgba(43, 108, 176, 0.1);
    border-radius: 999px;
    color: var(--analytics-sky);
    font-size: 0.75rem;
    font-weight: 700;
    padding: 0.35rem 0.7rem;
  }

  .analytics-ops-grid {
    display: grid;
    gap: 1rem;
    grid-template-columns: repeat(auto-fit, minmax(160px, 1fr));
  }

  .analytics-ops-item {
    background: linear-gradient(180deg, #ffffff 0%, #f8fbff 100%);
    border: 1px solid #e2e8f0;
    border-radius: 0.85rem;
    padding: 1rem;
  }

  .analytics-ops-item i {
    color: var(--analytics-sky);
    margin-bottom: 0.8rem;
  }

  .analytics-ops-label {
    color: #718096;
    font-size: 0.82rem;
    margin-bottom: 0.25rem;
  }

  .analytics-ops-value {
    color: var(--analytics-ink);
    font-size: 1.35rem;
    font-weight: 800;
  }

  .analytics-chart-shell {
    height: 320px;
    position: relative;
  }

  .analytics-chart-shell.tall {
    height: 360px;
  }

  .analytics-insight-list {
    display: grid;
    gap: 0.9rem;
  }

  .analytics-insight-item {
    background: linear-gradient(180deg, #ffffff 0%, #f8fbff 100%);
    border: 1px solid #e2e8f0;
    border-radius: 0.9rem;
    display: flex;
    gap: 0.9rem;
    padding: 1rem;
  }

  .analytics-insight-icon {
    align-items: center;
    background: rgba(15, 157, 148, 0.12);
    border-radius: 0.85rem;
    color: var(--analytics-teal);
    display: inline-flex;
    height: 2.6rem;
    justify-content: center;
    width: 2.6rem;
  }

  .analytics-insight-title {
    color: #718096;
    font-size: 0.78rem;
    font-weight: 700;
    letter-spacing: 0.08em;
    margin-bottom: 0.3rem;
    text-transform: uppercase;
  }

  .analytics-insight-value {
    color: var(--analytics-ink);
    font-size: 1.05rem;
    font-weight: 800;
    margin-bottom: 0.3rem;
  }

  .analytics-insight-text {
    color: #4a5568;
    font-size: 0.88rem;
    margin-bottom: 0;
  }

  .analytics-table {
    margin-bottom: 0;
  }

  .analytics-table thead th {
    border-bottom: 1px solid #e2e8f0;
    border-top: 0;
    color: #718096;
    font-size: 0.78rem;
    font-weight: 700;
    letter-spacing: 0.08em;
    text-transform: uppercase;
  }

  .analytics-table tbody td {
    color: #2d3748;
    font-size: 0.9rem;
    vertical-align: middle;
  }

  .analytics-empty {
    color: #718096;
    font-size: 0.95rem;
    margin: 0;
  }

  .prediction-note {
    align-items: flex-start;
    background: linear-gradient(135deg, rgba(21, 50, 67, 0.05) 0%, rgba(15, 157, 148, 0.08) 100%);
    border: 1px solid rgba(43, 108, 176, 0.12);
    border-radius: 0.95rem;
    color: #2d3748;
    display: flex;
    gap: 0.9rem;
    padding: 1rem 1.1rem;
  }

  .prediction-note i {
    color: var(--analytics-sky);
    font-size: 1rem;
    margin-top: 0.15rem;
  }

  .prediction-grid {
    display: grid;
    gap: 1rem;
    grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
  }

  .prediction-card {
    background: linear-gradient(180deg, #ffffff 0%, #f7fbff 100%);
    border: 1px solid #e2e8f0;
    border-radius: 1rem;
    min-height: 100%;
    overflow: hidden;
    padding: 1rem;
    position: relative;
  }

  .prediction-card::after {
    background: rgba(43, 108, 176, 0.06);
    border-radius: 999px;
    content: '';
    height: 5rem;
    position: absolute;
    right: -1.4rem;
    top: -1.4rem;
    width: 5rem;
  }

  .prediction-card > * {
    position: relative;
    z-index: 1;
  }

  .prediction-card-label {
    color: #718096;
    font-size: 0.78rem;
    font-weight: 700;
    letter-spacing: 0.08em;
    margin-bottom: 0.45rem;
    text-transform: uppercase;
  }

  .prediction-card-value {
    color: var(--analytics-ink);
    font-size: 1.5rem;
    font-weight: 800;
    line-height: 1.15;
    margin-bottom: 0.75rem;
  }

  .prediction-card-note {
    color: #4a5568;
    font-size: 0.88rem;
    margin-bottom: 0.85rem;
  }

  .prediction-card-icon {
    color: var(--analytics-sky);
    font-size: 1.4rem;
  }

  .prediction-chip {
    border-radius: 999px;
    display: inline-flex;
    font-size: 0.78rem;
    font-weight: 700;
    padding: 0.35rem 0.72rem;
  }

  .prediction-chip.up,
  .prediction-chip.success {
    background: rgba(56, 161, 105, 0.14);
    color: #2f855a;
  }

  .prediction-chip.down,
  .prediction-chip.danger {
    background: rgba(229, 62, 62, 0.14);
    color: #c53030;
  }

  .prediction-chip.flat,
  .prediction-chip.muted {
    background: rgba(113, 128, 150, 0.12);
    color: #4a5568;
  }

  .prediction-chip.warning {
    background: rgba(217, 119, 6, 0.16);
    color: #b45309;
  }

  .prediction-method {
    border-top: 1px dashed rgba(203, 213, 224, 0.9);
    color: #4a5568;
    font-size: 0.88rem;
    margin-top: 1rem;
    padding-top: 1rem;
  }

  .prediction-risk-badge {
    border-radius: 999px;
    display: inline-flex;
    font-size: 0.75rem;
    font-weight: 700;
    padding: 0.3rem 0.68rem;
  }

  .prediction-risk-high {
    background: rgba(229, 62, 62, 0.12);
    color: #c53030;
  }

  .prediction-risk-medium {
    background: rgba(217, 119, 6, 0.16);
    color: #b45309;
  }

  .prediction-risk-low {
    background: rgba(56, 161, 105, 0.14);
    color: #2f855a;
  }

  .prediction-table td:last-child {
    font-weight: 700;
  }

  @media (max-width: 991.98px) {
    .analytics-hero h1 {
      font-size: 1.7rem;
    }

    .analytics-range-group {
      justify-content: flex-start;
      margin-top: 1rem;
    }
  }
</style>
@endpush

@section('main-content')
@php
  $rangeLabels = [
    7 => '7 ngày gần đây',
    30 => '30 ngày gần đây',
    90 => '90 ngày gần đây',
    365 => '12 tháng gần đây',
  ];
@endphp
@php
  $roleTracks = [
    [
      'title' => 'Phân tích kinh doanh',
      'label' => 'Chẩn đoán kinh doanh',
      'copy' => 'Đọc mix khách mới/khách quay lại, coupon, phương thức thanh toán và cỡ giỏ hàng mà không lặp lại dashboard tổng hợp.',
      'icon' => 'fa-search-dollar',
      'route' => route('admin.bi-analyst'),
    ],
    [
      'title' => 'Kiểm tra dữ liệu',
      'label' => 'Chất lượng pipeline',
      'copy' => 'Theo dõi data contract, freshness và mismatch order-cart để kiểm soát data reliability.',
      'icon' => 'fa-database',
      'route' => route('admin.data-engineer'),
    ],
    [
      'title' => 'Mô hình hóa tín hiệu',
      'label' => 'Phòng tín hiệu',
      'copy' => 'Xem segment, anomaly và momentum sản phẩm để ưu tiên experiment và khai thác mô hình.',
      'icon' => 'fa-brain',
      'route' => route('admin.data-scientist'),
    ],
    [
      'title' => 'Hỗ trợ bán hàng',
      'label' => 'Kích hoạt lead',
      'copy' => 'Ưu tiên lead, đọc intent liên hệ và combo bán hàng mà không trùng với inbox hay product list.',
      'icon' => 'fa-bullseye',
      'route' => route('admin.technical-sales'),
    ],
  ];
@endphp
<div class="container-fluid analytics-page">
  @include('backend.layouts.notification')

  <div class="analytics-hero mb-4">
    <div class="row align-items-center">
      <div class="col-xl-8">
        <div class="analytics-kicker">Phân tích kinh doanh</div>
        <h1>Bảng điều khiển </h1>
        <p>Tổng hợp {{ mb_strtolower($rangeLabels[$range], 'UTF-8') }} về doanh thu, tăng trưởng khách hàng, hiệu suất đơn hàng và rủi ro tồn kho để hỗ trợ góc nhìn điều hành.</p>
      </div>
      <div class="col-xl-4">
        <div class="analytics-range-group">
          <a class="analytics-range {{ $range === 7 ? 'active' : '' }}" href="{{ route('admin', ['range' => 7]) }}">7D</a>
          <a class="analytics-range {{ $range === 30 ? 'active' : '' }}" href="{{ route('admin', ['range' => 30]) }}">30D</a>
          <a class="analytics-range {{ $range === 90 ? 'active' : '' }}" href="{{ route('admin', ['range' => 90]) }}">90D</a>
          <a class="analytics-range {{ $range === 365 ? 'active' : '' }}" href="{{ route('admin', ['range' => 365]) }}">12M</a>
        </div>
      </div>
    </div>
  </div>

  <div class="card analytics-panel mb-4">
    <div class="card-header">
      <div>
        <div class="analytics-panel-title">Không gian làm việc theo vai trò</div>
        <p class="analytics-panel-subtitle">4 luồng làm việc theo từng vai trò để đi sâu vào phân tích, dữ liệu, mô hình và bán hàng.</p>
      </div>
      <span class="analytics-panel-badge">Module riêng</span>
    </div>
    <div class="card-body">
      <div class="analytics-role-grid">
        @foreach($roleTracks as $track)
          <a class="analytics-role-card" href="{{ $track['route'] }}">
            <div class="analytics-role-top">
              <div class="analytics-role-icon">
                <i class="fas {{ $track['icon'] }}"></i>
              </div>
              <span class="analytics-panel-badge">{{ $track['label'] }}</span>
            </div>
            <div class="analytics-role-title">{{ $track['title'] }}</div>
            <p class="analytics-role-copy">{{ $track['copy'] }}</p>
            <span class="analytics-role-link">Mở module <i class="fas fa-arrow-right"></i></span>
          </a>
        @endforeach
      </div>
    </div>
  </div>

  <div class="card analytics-panel mb-4">
    <div class="card-header">
      <div>
        <div class="analytics-panel-title">Trung tâm dự báo điều hành</div>
        <p class="analytics-panel-subtitle">Lớp insight hướng tới tương lai dành cho business analyst: nhìn trước nhu cầu, doanh thu và điểm nghẽn tồn kho thay vì chỉ đọc dữ liệu đã xảy ra.</p>
      </div>
      <span class="analytics-panel-badge">{{ $forwardForecast['horizonLabel'] }}</span>
    </div>
    <div class="card-body">
      <div class="prediction-note mb-4">
        <i class="fas fa-lightbulb"></i>
        <div>
          <strong>Mô hình cơ sở:</strong> {{ $forwardForecast['modelLabel'] }}.
          Forecast ưu tiên dữ liệu gần hiện tại hơn, sau đó điều chỉnh theo mùa vụ theo thứ để hỗ trợ quyết định ngắn hạn.
        </div>
      </div>

      <div class="prediction-grid">
        @foreach($forwardForecast['summary'] as $prediction)
          @php
            $predictionValue = $prediction['value'];
            if ($prediction['format'] === 'currency') {
              $predictionValue = number_format($prediction['value'], 0, '.', ',').'đ';
            } elseif ($prediction['format'] === 'number') {
              $predictionValue = number_format($prediction['value'], 0, '.', ',');
            }

            $predictionChipClass = 'muted';
            $predictionChipText = 'Mô hình cơ sở';
            if (!empty($prediction['change'])) {
              if ($prediction['change']['direction'] === 'up') {
                $predictionChipClass = 'up';
                $predictionChipText = 'Tăng '.number_format($prediction['change']['value'], 1, '.', ',').'%';
              } elseif ($prediction['change']['direction'] === 'down') {
                $predictionChipClass = 'down';
                $predictionChipText = 'Giảm '.number_format($prediction['change']['value'], 1, '.', ',').'%';
              } else {
                $predictionChipClass = 'flat';
                $predictionChipText = 'Ổn định';
              }
            } elseif (!empty($prediction['tone'])) {
              $predictionChipClass = $prediction['tone'];
              if ($prediction['tone'] === 'success') {
                $predictionChipText = 'Dữ liệu tốt';
              } elseif ($prediction['tone'] === 'warning') {
                $predictionChipText = 'Cần theo dõi';
              } elseif ($prediction['tone'] === 'danger') {
                $predictionChipText = 'Nên kiểm tra tay';
              }
            }
          @endphp
          <div class="prediction-card">
            <div class="d-flex justify-content-between align-items-start mb-3">
              <div>
                <div class="prediction-card-label">{{ $prediction['label'] }}</div>
                <div class="prediction-card-value">{{ $predictionValue }}</div>
              </div>
              <div class="prediction-card-icon">
                <i class="fas {{ $prediction['icon'] }}"></i>
              </div>
            </div>
            <p class="prediction-card-note">{{ $prediction['note'] }}</p>
            <span class="prediction-chip {{ $predictionChipClass }}">{{ $predictionChipText }}</span>
          </div>
        @endforeach
      </div>
    </div>
  </div>

  <div class="row">
    <div class="col-xl-8 mb-4">
      <div class="card analytics-panel h-100">
        <div class="card-header">
          <div>
            <div class="analytics-panel-title">Doanh thu thực tế so với dự báo</div>
            <p class="analytics-panel-subtitle">Đường liền là dữ liệu đã phát sinh, đường nét đứt là kịch bản cơ sở cho {{ $forwardForecast['horizonLabel'] }}.</p>
          </div>
          <span class="analytics-panel-badge">{{ $forwardForecast['confidence']['label'] }}</span>
        </div>
        <div class="card-body">
          <div class="analytics-chart-shell tall">
            <canvas id="forwardForecastChart"></canvas>
          </div>
          <div class="prediction-method">
            @if($forwardForecast['peak']['revenue'] > 0)
              Điểm cao nhất dự kiến rơi vào <strong>{{ $forwardForecast['peak']['label'] }}</strong> với khoảng <strong>{{ number_format($forwardForecast['peak']['revenue'], 0, '.', ',') }}đ</strong> doanh thu.
            @else
              Forecast chưa xác định được điểm cao nhất vì dữ liệu giao hàng trong kỳ còn mỏng.
            @endif
          </div>
        </div>
      </div>
    </div>

    <div class="col-xl-4 mb-4">
      <div class="card analytics-panel h-100">
        <div class="card-header">
          <div>
            <div class="analytics-panel-title">Khuyến nghị business analyst</div>
            <p class="analytics-panel-subtitle">Nhóm hành động ngắn hạn rút ra từ forecast, nhịp bán và rủi ro thiếu hàng.</p>
          </div>
          <span class="analytics-panel-badge">Action</span>
        </div>
        <div class="card-body">
          <div class="analytics-insight-list">
            @foreach($predictionActions as $action)
              <div class="analytics-insight-item">
                <div class="analytics-insight-icon">
                  <i class="fas {{ $action['icon'] }}"></i>
                </div>
                <div>
                  <div class="analytics-insight-title">{{ $action['title'] }}</div>
                  <div class="analytics-insight-value">{{ $action['value'] }}</div>
                  <p class="analytics-insight-text">{{ $action['description'] }}</p>
                </div>
              </div>
            @endforeach
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="card analytics-panel mb-4">
    <div class="card-header">
      <div>
        <div class="analytics-panel-title">Dự báo nhu cầu và đề xuất nhập hàng</div>
        <p class="analytics-panel-subtitle">Khác với bảng tồn kho thấp hiện có, bảng này dự đoán SKU nào sẽ thiếu hàng theo tốc độ bán thực tế.</p>
      </div>
      <span class="analytics-panel-badge">{{ $inventoryForecast['forecastDays'] }} ngày</span>
    </div>
    <div class="card-body">
      @if(empty($inventoryForecast['items']))
        <p class="analytics-empty">Chưa có đủ dữ liệu đơn giao thành công để đưa ra đề xuất nhập hàng.</p>
      @else
        <div class="table-responsive">
          <table class="table analytics-table prediction-table">
            <thead>
              <tr>
                <th>Sản phẩm</th>
                <th>Bán TB/ngày</th>
                <th>Nhu cầu {{ $inventoryForecast['forecastDays'] }} ngày</th>
                <th>Tồn kho</th>
                <th>Days cover</th>
                <th>Nguy cơ</th>
                <th>Đề xuất nhập</th>
              </tr>
            </thead>
            <tbody>
              @foreach($inventoryForecast['items'] as $productForecast)
                <tr>
                  <td>{{ $productForecast['title'] }}</td>
                  <td>{{ number_format($productForecast['avg_daily_units'], 2, '.', ',') }}</td>
                  <td>{{ number_format($productForecast['predicted_units'], 0, '.', ',') }}</td>
                  <td>{{ number_format($productForecast['stock'], 0, '.', ',') }}</td>
                  <td>{{ number_format($productForecast['days_of_cover'], 1, '.', ',') }} ngày</td>
                  <td>
                    <span class="prediction-risk-badge prediction-risk-{{ $productForecast['urgency'] }}">{{ $productForecast['urgency_label'] }}</span>
                    <div class="text-muted small mt-1">Có thể chạm ngưỡng vào {{ $productForecast['stockout_date'] }}</div>
                  </td>
                  <td>{{ number_format($productForecast['recommended_restock'], 0, '.', ',') }}</td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      @endif
    </div>
  </div>

  <div class="row">
    @foreach($metrics as $metric)
      @php
        $formattedValue = number_format($metric['value'], 0, '.', ',');
        if ($metric['format'] === 'currency') {
          $formattedValue .= 'đ';
        }
        if ($metric['format'] === 'percent') {
          $formattedValue = number_format($metric['value'], 1, '.', ',').'%';
        }

        $changeText = 'Ngưỡng cảnh báo <= 5';
        $changeClass = 'change-muted';
        if ($metric['change']) {
          if ($metric['change']['direction'] === 'up') {
            $changeText = '+'.number_format($metric['change']['value'], 1, '.', ',').'% so với kỳ trước';
            $changeClass = 'change-up';
          } elseif ($metric['change']['direction'] === 'down') {
            $changeText = '-'.number_format($metric['change']['value'], 1, '.', ',').'% so với kỳ trước';
            $changeClass = 'change-down';
          } else {
            $changeText = 'Không biến động đáng kể so với kỳ trước';
            $changeClass = 'change-flat';
          }
        }
      @endphp
      <div class="col-xl-4 col-md-6 mb-4">
        <div class="card analytics-kpi analytics-kpi-{{ $metric['tone'] }}">
          <div class="card-body">
            <div class="d-flex justify-content-between align-items-start mb-3">
              <div>
                <div class="analytics-kpi-label">{{ $metric['label'] }}</div>
                <div class="analytics-kpi-value">{{ $formattedValue }}</div>
              </div>
              <div class="analytics-kpi-icon">
                <i class="fas {{ $metric['icon'] }}"></i>
              </div>
            </div>
            <div class="analytics-kpi-desc">{{ $metric['description'] }}</div>
            <span class="analytics-chip {{ $changeClass }}">{{ $changeText }}</span>
          </div>
        </div>
      </div>
    @endforeach
  </div>

  <div class="card analytics-panel mb-4">
    <div class="card-header">
      <div>
        <div class="analytics-panel-title">Tổng quan vận hành</div>
        <p class="analytics-panel-subtitle">Tóm tắt nhanh từ các phân hệ hiện có mà không lặp lại các màn hình quản lý chi tiết.</p>
      </div>
      <span class="analytics-panel-badge">{{ $rangeLabels[$range] }}</span>
    </div>
    <div class="card-body">
      <div class="analytics-ops-grid">
        @foreach($operations as $operation)
          <div class="analytics-ops-item">
            <i class="fas {{ $operation['icon'] }}"></i>
            <div class="analytics-ops-label">{{ $operation['label'] }}</div>
            <div class="analytics-ops-value">{{ number_format($operation['value'], 0, '.', ',') }}</div>
          </div>
        @endforeach
      </div>
    </div>
  </div>

  <div class="row">
    <div class="col-xl-8 mb-4">
      <div class="card analytics-panel h-100">
        <div class="card-header">
          <div>
            <div class="analytics-panel-title">Xu hướng doanh thu và đơn hàng</div>
            <p class="analytics-panel-subtitle">Đường thể hiện doanh thu đơn đã giao, cột thể hiện số lượng đơn theo thời gian.</p>
          </div>
          <span class="analytics-panel-badge">Xu hướng</span>
        </div>
        <div class="card-body">
          <div class="analytics-chart-shell tall">
            <canvas id="trendChart"></canvas>
          </div>
        </div>
      </div>
    </div>

    <div class="col-xl-4 mb-4">
      <div class="card analytics-panel h-100">
        <div class="card-header">
          <div>
            <div class="analytics-panel-title">Cơ cấu trạng thái đơn hàng</div>
            <p class="analytics-panel-subtitle">Phân bổ các đơn mới, đang xử lý, đã giao và đã hủy.</p>
          </div>
          <span class="analytics-panel-badge">Cơ cấu</span>
        </div>
        <div class="card-body">
          <div class="analytics-chart-shell">
            <canvas id="statusChart"></canvas>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="row">
    <div class="col-xl-6 mb-4">
      <div class="card analytics-panel h-100">
        <div class="card-header">
          <div>
            <div class="analytics-panel-title">Sản phẩm bán chạy</div>
            <p class="analytics-panel-subtitle">Xếp hạng sản phẩm theo số lượng bán ra từ các đơn đã giao.</p>
          </div>
          <span class="analytics-panel-badge">Sản phẩm</span>
        </div>
        <div class="card-body">
          <div class="analytics-chart-shell">
            <canvas id="topProductsChart"></canvas>
          </div>
        </div>
      </div>
    </div>

    <div class="col-xl-6 mb-4">
      <div class="card analytics-panel h-100">
        <div class="card-header">
          <div>
            <div class="analytics-panel-title">Doanh thu theo danh mục</div>
            <p class="analytics-panel-subtitle">Mức đóng góp doanh thu của từng danh mục từ các đơn đã giao.</p>
          </div>
          <span class="analytics-panel-badge">Danh mục</span>
        </div>
        <div class="card-body">
          <div class="analytics-chart-shell">
            <canvas id="categoryRevenueChart"></canvas>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="row">
    <div class="col-xl-7 mb-4">
      <div class="card analytics-panel h-100">
        <div class="card-header">
          <div>
            <div class="analytics-panel-title">Xu hướng khách hàng mới</div>
            <p class="analytics-panel-subtitle">Số lượng khách hàng đăng ký mới trong khoảng thời gian đã chọn.</p>
          </div>
          <span class="analytics-panel-badge">Khách hàng</span>
        </div>
        <div class="card-body">
          <div class="analytics-chart-shell">
            <canvas id="customerTrendChart"></canvas>
          </div>
        </div>
      </div>
    </div>

    <div class="col-xl-5 mb-4">
      <div class="card analytics-panel h-100">
        <div class="card-header">
          <div>
            <div class="analytics-panel-title">Gợi ý phân tích</div>
            <p class="analytics-panel-subtitle">Một số nhận định kinh doanh rút ra từ dữ liệu trong kỳ.</p>
          </div>
          <span class="analytics-panel-badge">Nhận định</span>
        </div>
        <div class="card-body">
          <div class="analytics-insight-list">
            @foreach($insights as $insight)
              <div class="analytics-insight-item">
                <div class="analytics-insight-icon">
                  <i class="fas {{ $insight['icon'] }}"></i>
                </div>
                <div>
                  <div class="analytics-insight-title">{{ $insight['title'] }}</div>
                  <div class="analytics-insight-value">{{ $insight['value'] }}</div>
                  <p class="analytics-insight-text">{{ $insight['description'] }}</p>
                </div>
              </div>
            @endforeach
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="row">
    <div class="col-xl-4 mb-4">
      <div class="card analytics-panel h-100">
        <div class="card-header">
          <div>
            <div class="analytics-panel-title">Khung giờ phát sinh đơn</div>
            <p class="analytics-panel-subtitle">Polar area giúp nhìn nhanh thời điểm khách có xu hướng tạo đơn để tối ưu ca trực, push khuyến mãi và remarketing.</p>
          </div>
          <span class="analytics-panel-badge">Polar</span>
        </div>
        <div class="card-body">
          <div class="analytics-chart-shell">
            <canvas id="hourlyDemandChart"></canvas>
          </div>
        </div>
      </div>
    </div>

    <div class="col-xl-8 mb-4">
      <div class="card analytics-panel h-100">
        <div class="card-header">
          <div>
            <div class="analytics-panel-title">Tương quan khách mới, số đơn và doanh thu</div>
            <p class="analytics-panel-subtitle">Mỗi bong bóng là một ngày hoặc tháng. Trục X là khách mới, trục Y là số đơn, kích thước bong bóng biểu diễn doanh thu đã giao.</p>
          </div>
          <span class="analytics-panel-badge">Bubble</span>
        </div>
        <div class="card-body">
          <div class="analytics-chart-shell tall">
            <canvas id="customerOrderCorrelationChart"></canvas>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="row">
    <div class="col-12 mb-4">
      <div class="card analytics-panel h-100">
        <div class="card-header">
          <div>
            <div class="analytics-panel-title">Dấu vân tay mùa vụ theo thứ trong tuần</div>
            <p class="analytics-panel-subtitle">Radar so sánh tỷ trọng đơn hàng và doanh thu theo từng ngày trong tuần để nhận ra nhịp mua sắm nổi bật.</p>
          </div>
          <span class="analytics-panel-badge">Radar</span>
        </div>
        <div class="card-body">
          <div class="analytics-chart-shell tall">
            <canvas id="weekdaySeasonalityChart"></canvas>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="row">
    <div class="col-xl-6 mb-4">
      <div class="card analytics-panel h-100">
        <div class="card-header">
          <div>
            <div class="analytics-panel-title">Bảng hiệu suất sản phẩm</div>
            <p class="analytics-panel-subtitle">Các sản phẩm dẫn đầu về số lượng bán và doanh thu giao thành công.</p>
          </div>
          <span class="analytics-panel-badge">Bảng</span>
        </div>
        <div class="card-body">
          @if($topProducts->isEmpty())
            <p class="analytics-empty">Chưa có dữ liệu bán hàng giao thành công trong khoảng thời gian này.</p>
          @else
            <div class="table-responsive">
              <table class="table analytics-table">
                <thead>
                  <tr>
                    <th>Sản phẩm</th>
                    <th>Đã bán</th>
                    <th>Doanh thu</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach($topProducts as $product)
                    <tr>
                      <td>{{ $product->title }}</td>
                      <td>{{ number_format($product->units_sold, 0, '.', ',') }}</td>
                      <td>{{ number_format($product->revenue, 0, '.', ',') }}đ</td>
                    </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
          @endif
        </div>
      </div>
    </div>

    <div class="col-xl-6 mb-4">
      <div class="card analytics-panel h-100">
        <div class="card-header">
          <div>
            <div class="analytics-panel-title">Bảng cảnh báo tồn kho thấp</div>
            <p class="analytics-panel-subtitle">Các sản phẩm có nguy cơ hết hàng cao nhất.</p>
          </div>
          <span class="analytics-panel-badge">Rủi ro</span>
        </div>
        <div class="card-body">
          @if($lowStockProducts->isEmpty())
            <p class="analytics-empty">Hiện chưa có cảnh báo tồn kho thấp.</p>
          @else
            <div class="table-responsive">
              <table class="table analytics-table">
                <thead>
                  <tr>
                    <th>Sản phẩm</th>
                    <th>Tồn kho</th>
                    <th>Giá bán</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach($lowStockProducts as $product)
                    <tr>
                      <td>{{ $product->title }}</td>
                      <td>{{ number_format($product->stock, 0, '.', ',') }}</td>
                      <td>{{ number_format($product->price, 0, '.', ',') }}đ</td>
                    </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
          @endif
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script>
  const chartPayload = @json($chartData);

  Chart.defaults.global.defaultFontFamily = 'Nunito', '-apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif';
  Chart.defaults.global.defaultFontColor = '#4a5568';

  function formatWholeNumber(value) {
    return new Intl.NumberFormat('vi-VN').format(Math.round(value));
  }

  function resolvePercentAxisMax(values) {
    const maxValue = values.reduce(function(currentMax, value) {
      return Math.max(currentMax, value);
    }, 0);

    if (maxValue <= 10) {
      return 10;
    }

    return Math.min(100, Math.ceil(maxValue / 5) * 5 + 5);
  }

  function formatVnd(value) {
    return new Intl.NumberFormat('vi-VN').format(Math.round(value)) + 'đ';
  }

  function buildForwardForecastChart() {
    const ctx = document.getElementById('forwardForecastChart');
    if (!ctx) {
      return;
    }

    const forecast = chartPayload.forwardForecast;

    new Chart(ctx, {
      type: 'bar',
      data: {
        labels: forecast.labels,
        datasets: [
          {
            type: 'bar',
            label: 'Đơn thực tế',
            data: forecast.actualOrders,
            backgroundColor: 'rgba(43, 108, 176, 0.14)',
            borderColor: 'rgba(43, 108, 176, 0.38)',
            borderWidth: 1,
            yAxisID: 'orders-axis',
          },
          {
            type: 'bar',
            label: 'Đơn dự báo',
            data: forecast.forecastOrders,
            backgroundColor: 'rgba(15, 157, 148, 0.22)',
            borderColor: 'rgba(15, 157, 148, 0.9)',
            borderWidth: 1,
            yAxisID: 'orders-axis',
          },
          {
            type: 'line',
            label: 'Doanh thu thực tế',
            data: forecast.actualRevenue,
            fill: false,
            lineTension: 0.24,
            backgroundColor: 'rgba(21, 50, 67, 1)',
            borderColor: 'rgba(21, 50, 67, 1)',
            borderWidth: 3,
            pointBackgroundColor: '#ffffff',
            pointBorderColor: 'rgba(21, 50, 67, 1)',
            pointBorderWidth: 2,
            pointRadius: 3,
            yAxisID: 'revenue-axis',
          },
          {
            type: 'line',
            label: 'Doanh thu dự báo',
            data: forecast.forecastRevenue,
            fill: false,
            lineTension: 0.24,
            backgroundColor: 'rgba(217, 119, 6, 1)',
            borderColor: 'rgba(217, 119, 6, 1)',
            borderDash: [8, 6],
            borderWidth: 3,
            pointBackgroundColor: '#ffffff',
            pointBorderColor: 'rgba(217, 119, 6, 1)',
            pointBorderWidth: 2,
            pointRadius: 4,
            yAxisID: 'revenue-axis',
          }
        ]
      },
      options: {
        maintainAspectRatio: false,
        legend: {
          position: 'bottom'
        },
        scales: {
          xAxes: [{
            gridLines: {
              display: false,
              drawBorder: false
            }
          }],
          yAxes: [{
            id: 'revenue-axis',
            position: 'left',
            ticks: {
              beginAtZero: true,
              callback: function(value) {
                return formatVnd(value);
              }
            },
            gridLines: {
              color: 'rgba(226, 232, 240, 0.75)',
              drawBorder: false
            }
          }, {
            id: 'orders-axis',
            position: 'right',
            ticks: {
              beginAtZero: true,
              precision: 0
            },
            gridLines: {
              display: false,
              drawBorder: false
            }
          }]
        },
        tooltips: {
          callbacks: {
            label: function(tooltipItem, data) {
              const dataset = data.datasets[tooltipItem.datasetIndex];
              const isRevenueSeries = dataset.yAxisID === 'revenue-axis';

              if (isRevenueSeries) {
                return dataset.label + ': ' + formatVnd(tooltipItem.yLabel);
              }

              return dataset.label + ': ' + formatWholeNumber(tooltipItem.yLabel);
            }
          }
        }
      }
    });
  }

  function buildTrendChart() {
    const ctx = document.getElementById('trendChart');
    if (!ctx) {
      return;
    }

    new Chart(ctx, {
      type: 'bar',
      data: {
        labels: chartPayload.trend.labels,
        datasets: [
          {
            type: 'bar',
            label: 'Số đơn',
            data: chartPayload.trend.orders,
            backgroundColor: 'rgba(15, 157, 148, 0.22)',
            borderColor: 'rgba(15, 157, 148, 0.9)',
            borderWidth: 1,
            yAxisID: 'orders-axis',
          },
          {
            type: 'line',
            label: 'Doanh thu đã giao',
            data: chartPayload.trend.revenue,
            fill: false,
            lineTension: 0.25,
            backgroundColor: 'rgba(43, 108, 176, 1)',
            borderColor: 'rgba(43, 108, 176, 1)',
            pointBackgroundColor: '#ffffff',
            pointBorderColor: 'rgba(43, 108, 176, 1)',
            pointBorderWidth: 2,
            pointRadius: 4,
            yAxisID: 'revenue-axis',
          }
        ]
      },
      options: {
        maintainAspectRatio: false,
        legend: {
          position: 'bottom'
        },
        scales: {
          xAxes: [{
            gridLines: {
              display: false,
              drawBorder: false
            }
          }],
          yAxes: [{
            id: 'revenue-axis',
            position: 'left',
            ticks: {
              beginAtZero: true,
              callback: function(value) {
                return formatVnd(value);
              }
            },
            gridLines: {
              color: 'rgba(226, 232, 240, 0.75)',
              drawBorder: false
            }
          }, {
            id: 'orders-axis',
            position: 'right',
            ticks: {
              beginAtZero: true,
              precision: 0
            },
            gridLines: {
              display: false,
              drawBorder: false
            }
          }]
        },
        tooltips: {
          callbacks: {
            label: function(tooltipItem, data) {
              const dataset = data.datasets[tooltipItem.datasetIndex];
              if (dataset.label === 'Doanh thu đã giao') {
                return dataset.label + ': ' + formatVnd(tooltipItem.yLabel);
              }

              return dataset.label + ': ' + new Intl.NumberFormat('en-US').format(tooltipItem.yLabel);
            }
          }
        }
      }
    });
  }

  function buildStatusChart() {
    const ctx = document.getElementById('statusChart');
    if (!ctx) {
      return;
    }

    new Chart(ctx, {
      type: 'doughnut',
      data: {
        labels: chartPayload.statusBreakdown.labels,
        datasets: [{
          data: chartPayload.statusBreakdown.values,
          backgroundColor: ['#3182ce', '#f6ad55', '#38a169', '#e53e3e'],
          borderColor: '#ffffff',
          borderWidth: 4
        }]
      },
      options: {
        maintainAspectRatio: false,
        legend: {
          position: 'bottom'
        },
        cutoutPercentage: 68
      }
    });
  }

  function buildTopProductsChart() {
    const ctx = document.getElementById('topProductsChart');
    if (!ctx) {
      return;
    }

    new Chart(ctx, {
      type: 'horizontalBar',
      data: {
        labels: chartPayload.topProducts.labels,
        datasets: [{
          label: 'Số lượng bán',
          data: chartPayload.topProducts.values,
          backgroundColor: ['#245f73', '#2b6cb0', '#0f9d94', '#d97706', '#4c566a'],
          borderRadius: 8
        }]
      },
      options: {
        maintainAspectRatio: false,
        legend: {
          display: false
        },
        scales: {
          xAxes: [{
            ticks: {
              beginAtZero: true,
              precision: 0
            },
            gridLines: {
              color: 'rgba(226, 232, 240, 0.75)',
              drawBorder: false
            }
          }],
          yAxes: [{
            gridLines: {
              display: false,
              drawBorder: false
            }
          }]
        }
      }
    });
  }

  function buildCategoryRevenueChart() {
    const ctx = document.getElementById('categoryRevenueChart');
    if (!ctx) {
      return;
    }

    new Chart(ctx, {
      type: 'bar',
      data: {
        labels: chartPayload.categoryRevenue.labels,
        datasets: [{
          label: 'Doanh thu',
          data: chartPayload.categoryRevenue.values,
          backgroundColor: 'rgba(217, 119, 6, 0.78)',
          borderColor: 'rgba(180, 83, 9, 1)',
          borderWidth: 1
        }]
      },
      options: {
        maintainAspectRatio: false,
        legend: {
          display: false
        },
        scales: {
          xAxes: [{
            gridLines: {
              display: false,
              drawBorder: false
            }
          }],
          yAxes: [{
            ticks: {
              beginAtZero: true,
              callback: function(value) {
                return formatVnd(value);
              }
            },
            gridLines: {
              color: 'rgba(226, 232, 240, 0.75)',
              drawBorder: false
            }
          }]
        },
        tooltips: {
          callbacks: {
            label: function(tooltipItem) {
              return 'Doanh thu: ' + formatVnd(tooltipItem.yLabel);
            }
          }
        }
      }
    });
  }

  function buildCustomerTrendChart() {
    const ctx = document.getElementById('customerTrendChart');
    if (!ctx) {
      return;
    }

    new Chart(ctx, {
      type: 'line',
      data: {
        labels: chartPayload.customerTrend.labels,
        datasets: [{
          label: 'Khách hàng mới',
          data: chartPayload.customerTrend.values,
          lineTension: 0.25,
          backgroundColor: 'rgba(56, 161, 105, 0.16)',
          borderColor: 'rgba(56, 161, 105, 1)',
          borderWidth: 3,
          pointBackgroundColor: '#ffffff',
          pointBorderColor: 'rgba(56, 161, 105, 1)',
          pointBorderWidth: 2,
          pointRadius: 4
        }]
      },
      options: {
        maintainAspectRatio: false,
        legend: {
          display: false
        },
        scales: {
          xAxes: [{
            gridLines: {
              display: false,
              drawBorder: false
            }
          }],
          yAxes: [{
            ticks: {
              beginAtZero: true,
              precision: 0
            },
            gridLines: {
              color: 'rgba(226, 232, 240, 0.75)',
              drawBorder: false
            }
          }]
        }
      }
    });
  }

  function buildHourlyDemandChart() {
    const ctx = document.getElementById('hourlyDemandChart');
    if (!ctx) {
      return;
    }

    const profile = chartPayload.hourlyDemandProfile;

    new Chart(ctx, {
      type: 'polarArea',
      data: {
        labels: profile.labels,
        datasets: [{
          data: profile.values,
          backgroundColor: [
            'rgba(21, 50, 67, 0.78)',
            'rgba(43, 108, 176, 0.76)',
            'rgba(15, 157, 148, 0.76)',
            'rgba(217, 119, 6, 0.76)',
            'rgba(197, 48, 48, 0.74)'
          ],
          borderColor: '#ffffff',
          borderWidth: 3
        }]
      },
      options: {
        maintainAspectRatio: false,
        legend: {
          position: 'bottom'
        },
        scale: {
          ticks: {
            beginAtZero: true,
            precision: 0
          },
          gridLines: {
            color: 'rgba(226, 232, 240, 0.8)'
          }
        },
        tooltips: {
          callbacks: {
            label: function(tooltipItem, data) {
              const value = data.datasets[tooltipItem.datasetIndex].data[tooltipItem.index] || 0;
              const share = profile.shares[tooltipItem.index] || 0;

              return data.labels[tooltipItem.index] + ': ' + formatWholeNumber(value) + ' đơn (' + share + '%)';
            }
          }
        }
      }
    });
  }

  function buildCustomerOrderCorrelationChart() {
    const ctx = document.getElementById('customerOrderCorrelationChart');
    if (!ctx) {
      return;
    }

    const correlation = chartPayload.customerOrderCorrelation;

    new Chart(ctx, {
      type: 'bubble',
      data: {
        datasets: [{
          label: 'Tương quan theo kỳ',
          data: correlation.points,
          backgroundColor: 'rgba(43, 108, 176, 0.32)',
          borderColor: 'rgba(21, 50, 67, 0.9)',
          borderWidth: 1.5,
          hoverBackgroundColor: 'rgba(15, 157, 148, 0.45)'
        }]
      },
      options: {
        maintainAspectRatio: false,
        legend: {
          display: false
        },
        scales: {
          xAxes: [{
            ticks: {
              beginAtZero: true,
              precision: 0
            },
            scaleLabel: {
              display: true,
              labelString: 'Khách hàng mới'
            },
            gridLines: {
              color: 'rgba(226, 232, 240, 0.75)',
              drawBorder: false
            }
          }],
          yAxes: [{
            ticks: {
              beginAtZero: true,
              precision: 0
            },
            scaleLabel: {
              display: true,
              labelString: 'Đơn hàng phát sinh'
            },
            gridLines: {
              color: 'rgba(226, 232, 240, 0.75)',
              drawBorder: false
            }
          }]
        },
        tooltips: {
          callbacks: {
            title: function(tooltipItems, data) {
              if (!tooltipItems.length) {
                return '';
              }

              const point = data.datasets[tooltipItems[0].datasetIndex].data[tooltipItems[0].index];
              return point.label;
            },
            label: function(tooltipItem, data) {
              const point = data.datasets[tooltipItem.datasetIndex].data[tooltipItem.index];

              return [
                'Khách mới: ' + formatWholeNumber(point.x),
                'Đơn phát sinh: ' + formatWholeNumber(point.y),
                'Doanh thu đã giao: ' + formatVnd(point.revenue)
              ];
            },
            afterTitle: function() {
              return 'Đơn vị thời gian: ' + correlation.bucketLabel;
            }
          }
        }
      }
    });
  }

  function buildWeekdaySeasonalityChart() {
    const ctx = document.getElementById('weekdaySeasonalityChart');
    if (!ctx) {
      return;
    }

    const seasonality = chartPayload.weekdaySeasonality;
    const suggestedMax = resolvePercentAxisMax(
      seasonality.orderShares.concat(seasonality.revenueShares)
    );

    new Chart(ctx, {
      type: 'radar',
      data: {
        labels: seasonality.labels,
        datasets: [{
          label: 'Tỷ trọng đơn hàng',
          data: seasonality.orderShares,
          backgroundColor: 'rgba(43, 108, 176, 0.14)',
          borderColor: 'rgba(43, 108, 176, 1)',
          borderWidth: 3,
          pointBackgroundColor: '#ffffff',
          pointBorderColor: 'rgba(43, 108, 176, 1)',
          pointRadius: 4
        }, {
          label: 'Tỷ trọng doanh thu',
          data: seasonality.revenueShares,
          backgroundColor: 'rgba(15, 157, 148, 0.16)',
          borderColor: 'rgba(15, 157, 148, 1)',
          borderWidth: 3,
          pointBackgroundColor: '#ffffff',
          pointBorderColor: 'rgba(15, 157, 148, 1)',
          pointRadius: 4
        }]
      },
      options: {
        maintainAspectRatio: false,
        legend: {
          position: 'bottom'
        },
        scale: {
          ticks: {
            beginAtZero: true,
            suggestedMax: suggestedMax,
            callback: function(value) {
              return value + '%';
            }
          },
          pointLabels: {
            fontSize: 12,
            fontColor: '#2d3748'
          },
          angleLines: {
            color: 'rgba(203, 213, 224, 0.7)'
          },
          gridLines: {
            color: 'rgba(226, 232, 240, 0.8)'
          }
        },
        tooltips: {
          callbacks: {
            label: function(tooltipItem, data) {
              const dataset = data.datasets[tooltipItem.datasetIndex];
              const index = tooltipItem.index;

              if (tooltipItem.datasetIndex === 0) {
                return dataset.label + ': ' + seasonality.orderShares[index] + '% (' + formatWholeNumber(seasonality.orders[index]) + ' đơn)';
              }

              return dataset.label + ': ' + seasonality.revenueShares[index] + '% (' + formatVnd(seasonality.revenues[index]) + ')';
            }
          }
        }
      }
    });
  }

  buildForwardForecastChart();
  buildTrendChart();
  buildStatusChart();
  buildTopProductsChart();
  buildCategoryRevenueChart();
  buildCustomerTrendChart();
  buildHourlyDemandChart();
  buildCustomerOrderCorrelationChart();
  buildWeekdaySeasonalityChart();
</script>
@endpush
