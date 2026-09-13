@extends('backend.layouts.master')
@section('title', 'Quản trị || Mô hình hóa tín hiệu')

@push('styles')
<style>
  .ds-page{padding-bottom:2rem}
  .ds-hero{background:linear-gradient(135deg,#3b0764 0%,#6d28d9 50%,#0284c7 100%);border-radius:1.2rem;color:#fff;padding:1.6rem;position:relative;overflow:hidden;box-shadow:0 20px 45px rgba(59,7,100,.18)}
  .ds-hero:after{content:'';position:absolute;right:-3rem;top:-3rem;width:10rem;height:10rem;border-radius:999px;background:rgba(255,255,255,.08)}
  .ds-kicker{font-size:.78rem;font-weight:800;letter-spacing:.18em;text-transform:uppercase;margin-bottom:.85rem}
  .ds-hero h1{font-size:2rem;font-weight:800;line-height:1.1;margin-bottom:.8rem}
  .ds-hero p{max-width:46rem;color:rgba(255,255,255,.84);margin-bottom:0}
  .ds-range{display:flex;flex-wrap:wrap;gap:.6rem;justify-content:flex-end}
  .ds-range a{display:inline-flex;padding:.62rem .95rem;border-radius:999px;font-weight:700;font-size:.84rem;text-decoration:none;color:#fff;background:rgba(255,255,255,.13);border:1px solid rgba(255,255,255,.16)}
  .ds-range a.active{background:#fff;color:#4c1d95}
  .ds-chip-row{display:flex;flex-wrap:wrap;gap:.65rem;margin-top:1rem}
  .ds-chip{display:inline-flex;align-items:center;gap:.45rem;background:rgba(255,255,255,.14);border-radius:999px;padding:.42rem .78rem;font-size:.82rem;font-weight:700}
  .ds-stat,.ds-panel{border:0;border-radius:1rem;box-shadow:0 18px 40px rgba(15,23,42,.08)}
  .ds-stat{height:100%;overflow:hidden;position:relative;color:#fff}
  .ds-stat:after{content:'';position:absolute;right:-1.3rem;top:-1.3rem;width:5.8rem;height:5.8rem;border-radius:999px;background:rgba(255,255,255,.08)}
  .ds-stat .card-body{position:relative;z-index:1}
  .ds-stat.s1{background:linear-gradient(140deg,#6d28d9 0%,#8b5cf6 100%)}
  .ds-stat.s2{background:linear-gradient(140deg,#0284c7 0%,#0ea5e9 100%)}
  .ds-stat.s3{background:linear-gradient(140deg,#b45309 0%,#f59e0b 100%)}
  .ds-stat.s4{background:linear-gradient(140deg,#0f766e 0%,#14b8a6 100%)}
  .ds-stat-label{font-size:.8rem;font-weight:800;letter-spacing:.08em;text-transform:uppercase;margin-bottom:.75rem;opacity:.88}
  .ds-stat-value{font-size:1.85rem;font-weight:800;line-height:1.08;margin-bottom:.55rem}
  .ds-stat-note{font-size:.9rem;opacity:.84;margin-bottom:0}
  .ds-panel .card-header{background:#fff;border-bottom:1px solid #e2e8f0;padding:1.1rem 1.25rem}
  .ds-panel .card-body{padding:1.25rem}
  .ds-title{font-size:1.02rem;font-weight:800;color:#0f172a;margin-bottom:.2rem}
  .ds-sub{font-size:.88rem;color:#64748b;margin-bottom:0}
  .ds-canvas{height:320px;position:relative}
  .ds-table thead th{font-size:.76rem;font-weight:800;letter-spacing:.08em;text-transform:uppercase;color:#64748b;border-top:0;border-bottom:1px solid #e2e8f0}
  .ds-table tbody td{vertical-align:middle;border-color:#eef2f7}
  .ds-badge{display:inline-flex;padding:.34rem .72rem;border-radius:999px;font-size:.78rem;font-weight:700}
  .ds-badge.good{background:rgba(20,184,166,.12);color:#0f766e}.ds-badge.warn{background:rgba(245,158,11,.16);color:#b45309}.ds-badge.bad{background:rgba(239,68,68,.14);color:#b91c1c}.ds-badge.info{background:rgba(99,102,241,.14);color:#4338ca}
  .ds-list{display:grid;gap:.85rem}
  .ds-list-item{display:flex;gap:.85rem;border:1px solid #e2e8f0;border-radius:.95rem;padding:1rem;background:linear-gradient(180deg,#fff 0%,#f8fbff 100%)}
  .ds-list-icon{display:inline-flex;align-items:center;justify-content:center;width:2.7rem;height:2.7rem;border-radius:.85rem;background:rgba(109,40,217,.12);color:#6d28d9;flex:0 0 2.7rem}
  .ds-watch-grid{display:grid;gap:.85rem;grid-template-columns:repeat(auto-fit,minmax(220px,1fr))}
  .ds-watch-card{border:1px solid #e2e8f0;border-radius:1rem;padding:1rem;background:linear-gradient(180deg,#fff 0%,#f8fbff 100%)}
  @media (max-width:991.98px){.ds-range{justify-content:flex-start;margin-top:1rem}.ds-hero h1{font-size:1.7rem}}
</style>
@endpush

@section('main-content')
@php
  $rangeOptions = [60 => '60D', 90 => '90D', 180 => '180D'];
  $themes = ['s1', 's2', 's3', 's4'];
  $pickDsChart = function (array $candidates, array $exclude = []) {
      foreach ($candidates as $candidate) {
          if ($candidate['has'] && !in_array($candidate['key'], $exclude, true)) {
              return $candidate;
          }
      }

      foreach ($candidates as $candidate) {
          if ($candidate['has']) {
              return $candidate;
          }
      }

      return $candidates[0];
  };

  $dsSignalCandidates = [
      [
          'key' => 'revenueSignal',
          'has' => collect($chartData['revenueSignal']['revenue'])->sum() > 0,
          'title' => 'Tín hiệu doanh thu và điểm bất thường',
          'sub' => 'Chuỗi doanh thu theo ngày với baseline và điểm anomaly để đọc tín hiệu rõ hơn.',
      ],
      [
          'key' => 'momentum',
          'has' => collect($chartData['momentum']['recent_units'])->sum() > 0,
          'title' => 'Đà tăng của sản phẩm',
          'sub' => 'Theo dõi sản phẩm đang tăng tốc để thay cho chuỗi doanh thu khi range hiện tại quá mỏng.',
      ],
      [
          'key' => 'customerSegments',
          'has' => collect($chartData['customerSegments']['counts'])->sum() > 0,
          'title' => 'Phân khúc khách hàng',
          'sub' => 'Chuyển sang góc nhìn phân khúc khi chưa đủ doanh thu để đọc anomaly.',
      ],
      [
          'key' => 'anomalyMix',
          'has' => collect($chartData['anomalyMix']['counts'])->sum() > 0,
          'title' => 'Cơ cấu ngày bất thường',
          'sub' => 'Phân tách số ngày tăng đột biến và số ngày giảm mạnh trong cửa sổ hiện tại.',
      ],
  ];

  $dsSegmentCandidates = [
      [
          'key' => 'customerSegments',
          'has' => collect($chartData['customerSegments']['counts'])->sum() > 0,
          'title' => 'Phân khúc khách hàng',
          'sub' => 'Nhóm hóa khách hàng theo recency, frequency và doanh thu.',
      ],
      [
          'key' => 'anomalyMix',
          'has' => collect($chartData['anomalyMix']['counts'])->sum() > 0,
          'title' => 'Cơ cấu ngày bất thường',
          'sub' => 'Dùng dữ liệu anomaly để vẫn giữ được biểu đồ có tín hiệu khi phân khúc khách hàng chưa đủ dữ liệu.',
      ],
      [
          'key' => 'momentum',
          'has' => collect($chartData['momentum']['recent_units'])->sum() > 0,
          'title' => 'Đà tăng của sản phẩm',
          'sub' => 'Biểu đồ dự phòng từ SKU tăng tốc để không bỏ trống khung phân khúc.',
      ],
  ];

  $dsPrimaryChart = $pickDsChart($dsSignalCandidates);
  $dsSecondaryChart = $pickDsChart($dsSegmentCandidates, [$dsPrimaryChart['key']]);
@endphp
<div class="container-fluid ds-page">
  @include('backend.layouts.notification')

  <div class="ds-hero mb-4">
    <div class="row align-items-center">
      <div class="col-xl-8">
        <div class="ds-kicker">Mô hình hóa tín hiệu</div>
        <h1>Phòng thí nghiệm segment, anomaly và momentum cho khai thác tín hiệu</h1>
        <p>Quan sát pattern khách hàng, ngày bất thường và SKU đang tăng tốc để nhận diện tín hiệu tăng trưởng, rủi ro và cơ hội tối ưu.</p>
        <div class="ds-chip-row">
          <span class="ds-chip"><i class="fas fa-calendar-alt"></i> {{ $startDate->format('d/m/Y') }} - {{ $endDate->format('d/m/Y') }}</span>
          <span class="ds-chip"><i class="fas fa-brain"></i> {{ count($anomalyRows) }} điểm bất thường</span>
          <span class="ds-chip"><i class="fas fa-chart-line"></i> {{ count($momentumProducts) }} ứng viên momentum</span>
        </div>
      </div>
      <div class="col-xl-4">
        <div class="ds-range">
          @foreach($rangeOptions as $value => $label)
            <a href="{{ route('admin.data-scientist', ['range' => $value]) }}" class="{{ $range === $value ? 'active' : '' }}">{{ $label }}</a>
          @endforeach
        </div>
      </div>
    </div>
  </div>

  <div class="row">
    @foreach($summaryCards as $index => $card)
      @php
        if ($card['format'] === 'currency') {
          $value = number_format($card['value'], 0, ',', '.').'đ';
        } else {
          $value = number_format($card['value'], 0, ',', '.');
        }
      @endphp
      <div class="col-xl-3 col-md-6 mb-4">
        <div class="card ds-stat {{ $themes[$index] }}">
          <div class="card-body">
            <div class="d-flex justify-content-between align-items-start mb-3">
              <div class="ds-stat-label">{{ $card['label'] }}</div>
              <i class="fas {{ $card['icon'] }}"></i>
            </div>
            <div class="ds-stat-value">{{ $value }}</div>
            <p class="ds-stat-note">{{ $card['note'] }}</p>
          </div>
        </div>
      </div>
    @endforeach
  </div>

  <div class="row">
    <div class="col-xl-7 mb-4">
      <div class="card ds-panel h-100">
        <div class="card-header">
          <div class="ds-title">Tín hiệu doanh thu và điểm bất thường</div>
          <p class="ds-sub">Chuỗi doanh thu theo ngày với baseline và điểm anomaly để đọc tín hiệu rõ hơn.</p>
        </div>
        <div class="card-body">
          <div class="ds-canvas">
            <canvas id="dsPrimaryChart" data-chart-mode="{{ $dsPrimaryChart['key'] }}" data-chart-title="{{ $dsPrimaryChart['title'] }}" data-chart-sub="{{ $dsPrimaryChart['sub'] }}"></canvas>
          </div>
        </div>
      </div>
    </div>
    <div class="col-xl-5 mb-4">
      <div class="card ds-panel h-100">
        <div class="card-header">
          <div class="ds-title">Phân khúc khách hàng</div>
          <p class="ds-sub">Nhóm hóa khách hàng theo recency, frequency và doanh thu.</p>
        </div>
        <div class="card-body">
          <div class="ds-canvas">
            <canvas id="dsSecondaryChart" data-chart-mode="{{ $dsSecondaryChart['key'] }}" data-chart-title="{{ $dsSecondaryChart['title'] }}" data-chart-sub="{{ $dsSecondaryChart['sub'] }}"></canvas>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="row">
    <div class="col-xl-7 mb-4">
      <div class="card ds-panel h-100">
        <div class="card-header">
          <div class="ds-title">Sản phẩm có momentum</div>
          <p class="ds-sub">Không lặp lại top products: bảng này đo tăng trưởng gần đây so với giai đoạn trước.</p>
        </div>
        <div class="card-body">
          <div class="table-responsive">
            <table class="table ds-table mb-0">
              <thead>
                <tr>
                  <th>Sản phẩm</th>
                  <th>SL gần đây</th>
                  <th>SL trước đó</th>
                  <th>Tăng trưởng</th>
                  <th>Trạng thái</th>
                </tr>
              </thead>
              <tbody>
                @foreach($momentumProducts as $product)
                  @php
                    $toneClass = $product['tone'] === 'breakout' ? 'good' : ($product['tone'] === 'cooling' ? 'bad' : 'info');
                  @endphp
                  <tr>
                    <td>
                      <div class="font-weight-bold text-dark">{{ $product['title'] }}</div>
                      <div class="small text-muted">Tồn kho {{ number_format($product['stock'], 0, ',', '.') }} | Điểm {{ number_format($product['momentum_score'], 1, ',', '.') }}</div>
                    </td>
                    <td>{{ number_format($product['recent_units'], 0, ',', '.') }}</td>
                    <td>{{ number_format($product['previous_units'], 0, ',', '.') }}</td>
                    <td>{{ number_format($product['growth_rate'], 1, ',', '.') }}%</td>
                    <td><span class="ds-badge {{ $toneClass }}">{{ $product['tone_label'] }}</span></td>
                  </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
    <div class="col-xl-5 mb-4">
      <div class="card ds-panel h-100">
        <div class="card-header">
          <div class="ds-title">Khuyến nghị mô hình</div>
          <p class="ds-sub">Danh sách hành động rút ra từ anomaly, segment và momentum.</p>
        </div>
        <div class="card-body">
          <div class="ds-list">
            @foreach($recommendations as $recommendation)
              <div class="ds-list-item">
                <div class="ds-list-icon"><i class="fas fa-flask"></i></div>
                <div>
                  <div class="font-weight-bold text-dark mb-1">{{ $recommendation['title'] }}</div>
                  <div class="text-muted small">{{ $recommendation['description'] }}</div>
                </div>
              </div>
            @endforeach
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="card ds-panel">
    <div class="card-header">
      <div class="ds-title">Nhật ký bất thường</div>
      <p class="ds-sub">Bảng này phục vụ đọc mô hình, không phải bảng vận hành đơn hàng.</p>
    </div>
    <div class="card-body">
      <div class="table-responsive">
        <table class="table ds-table mb-0">
          <thead>
            <tr>
              <th>Ngày</th>
              <th>Doanh thu</th>
              <th>Đơn hàng</th>
              <th>Chênh lệch so với baseline</th>
              <th>Tín hiệu</th>
              <th>Giải thích</th>
            </tr>
          </thead>
          <tbody>
            @forelse($anomalyRows as $row)
              <tr>
                <td>{{ $row['label'] }}</td>
                <td>{{ number_format($row['revenue'], 0, ',', '.') }}đ</td>
                <td>{{ number_format($row['orders'], 0, ',', '.') }}</td>
                <td>{{ number_format($row['change_percent'], 1, ',', '.') }}%</td>
                <td><span class="ds-badge {{ $row['tone'] === 'positive' ? 'good' : 'bad' }}">{{ $row['tone'] === 'positive' ? 'Tăng đột biến' : 'Giảm mạnh' }}</span></td>
                <td class="text-muted">{{ $row['explanation'] }}</td>
              </tr>
            @empty
              <tr>
                <td colspan="6" class="text-center text-muted py-4">Chưa có anomaly vượt baseline trong cửa sổ hiện tại.</td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </div>
  <div class="row mt-4">
    <div class="col-xl-7 mb-4">
      <div class="card ds-panel h-100">
        <div class="card-header d-flex justify-content-between align-items-center">
          <div>
            <div class="ds-title">Watchlist tín hiệu</div>
            <p class="ds-sub">Ghim SKU, nhóm khách hoặc ngày bất thường để theo dõi liên tục trong trình duyệt.</p>
          </div>
          <button type="button" class="btn btn-outline-secondary btn-sm" id="dsWatchlistClear">Xóa watchlist</button>
        </div>
        <div class="card-body">
          <div id="dsWatchlistPinned" class="ds-watch-grid mb-4"></div>

          <div class="small font-weight-bold text-uppercase text-muted mb-2">Nguồn để ghim</div>
          <div class="mb-3">
            <div class="small font-weight-bold text-muted mb-2">SKU đang có momentum</div>
            <div class="ds-watch-grid">
              @foreach($watchlistCandidates['products'] as $item)
                @php $encodedWatchItem = base64_encode(json_encode($item, JSON_UNESCAPED_UNICODE)); @endphp
                <div class="ds-watch-card">
                  <div class="small text-muted text-uppercase mb-1">{{ $item['type'] }}</div>
                  <div class="font-weight-bold text-dark mb-1">{{ $item['label'] }}</div>
                  <div class="small text-muted mb-3">{{ $item['meta'] }}</div>
                  <button type="button" class="btn btn-outline-primary btn-sm ds-watch-add" data-watch-item="{{ $encodedWatchItem }}">Ghim vào watchlist</button>
                </div>
              @endforeach
            </div>
          </div>

          <div class="mb-3">
            <div class="small font-weight-bold text-muted mb-2">Nhóm khách cần theo dõi</div>
            <div class="ds-watch-grid">
              @foreach($watchlistCandidates['segments'] as $item)
                @php $encodedWatchItem = base64_encode(json_encode($item, JSON_UNESCAPED_UNICODE)); @endphp
                <div class="ds-watch-card">
                  <div class="small text-muted text-uppercase mb-1">{{ $item['type'] }}</div>
                  <div class="font-weight-bold text-dark mb-1">{{ $item['label'] }}</div>
                  <div class="small text-muted mb-3">{{ $item['meta'] }}</div>
                  <button type="button" class="btn btn-outline-primary btn-sm ds-watch-add" data-watch-item="{{ $encodedWatchItem }}">Ghim vào watchlist</button>
                </div>
              @endforeach
            </div>
          </div>

          <div>
            <div class="small font-weight-bold text-muted mb-2">Ngày bất thường</div>
            <div class="ds-watch-grid">
              @foreach($watchlistCandidates['anomalies'] as $item)
                @php $encodedWatchItem = base64_encode(json_encode($item, JSON_UNESCAPED_UNICODE)); @endphp
                <div class="ds-watch-card">
                  <div class="small text-muted text-uppercase mb-1">{{ $item['type'] }}</div>
                  <div class="font-weight-bold text-dark mb-1">{{ $item['label'] }}</div>
                  <div class="small text-muted mb-3">{{ $item['meta'] }}</div>
                  <button type="button" class="btn btn-outline-primary btn-sm ds-watch-add" data-watch-item="{{ $encodedWatchItem }}">Ghim vào watchlist</button>
                </div>
              @endforeach
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-xl-5 mb-4">
      <div class="card ds-panel h-100">
        <div class="card-header">
          <div class="ds-title">Giải thích tín hiệu</div>
          <p class="ds-sub">Các câu giải thích ngắn để biết vì sao hệ thống đang gắn cờ một SKU, nhóm khách hoặc ngày cụ thể.</p>
        </div>
        <div class="card-body">
          <div class="ds-list">
            @foreach($signalExplanations as $explanation)
              <div class="ds-list-item">
                <div class="ds-list-icon"><i class="fas fa-microscope"></i></div>
                <div>
                  <div class="font-weight-bold text-dark mb-1">{{ $explanation['title'] }}</div>
                  <div class="text-muted small">{{ $explanation['description'] }}</div>
                </div>
              </div>
            @endforeach
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script>
  const dsChartPayload = @json($chartData);

  function dsFormatVnd(value) {
    return new Intl.NumberFormat('vi-VN').format(Math.round(value)) + 'đ';
  }

  function buildDsRevenueSignalChart() {
    const ctx = document.getElementById('dsRevenueSignalChart');
    if (!ctx) {
      return;
    }

    new Chart(ctx, {
      type: 'line',
      data: {
        labels: dsChartPayload.revenueSignal.labels,
        datasets: [{
          label: 'Doanh thu',
          data: dsChartPayload.revenueSignal.revenue,
          fill: false,
          borderColor: '#6d28d9',
          borderWidth: 3,
          pointRadius: 2
        }, {
          label: 'Đường cơ sở',
          data: dsChartPayload.revenueSignal.baseline,
          fill: false,
          borderColor: '#94a3b8',
          borderWidth: 2,
          borderDash: [8, 6],
          pointRadius: 0
        }, {
          label: 'Điểm bất thường',
          data: dsChartPayload.revenueSignal.anomalies,
          fill: false,
          showLine: false,
          borderColor: '#ef4444',
          backgroundColor: '#ef4444',
          pointRadius: 5
        }]
      },
      options: {
        maintainAspectRatio: false,
        legend: { position: 'bottom' },
        scales: {
          xAxes: [{ gridLines: { display: false, drawBorder: false } }],
          yAxes: [{
            ticks: {
              beginAtZero: true,
              callback: function(value) { return dsFormatVnd(value); }
            },
            gridLines: { color: 'rgba(226, 232, 240, 0.8)', drawBorder: false }
          }]
        },
        tooltips: {
          callbacks: {
            label: function(tooltipItem, data) {
              return data.datasets[tooltipItem.datasetIndex].label + ': ' + dsFormatVnd(tooltipItem.yLabel);
            }
          }
        }
      }
    });
  }

  function buildDsSegmentChart() {
    const ctx = document.getElementById('dsSegmentChart');
    if (!ctx) {
      return;
    }

    new Chart(ctx, {
      type: 'doughnut',
      data: {
        labels: dsChartPayload.customerSegments.labels,
        datasets: [{
          data: dsChartPayload.customerSegments.counts,
          backgroundColor: ['#8b5cf6', '#0ea5e9', '#22c55e', '#f59e0b', '#94a3b8'],
          borderColor: '#fff',
          borderWidth: 4
        }]
      },
      options: {
        maintainAspectRatio: false,
        legend: { position: 'bottom' },
        cutoutPercentage: 65
      }
    });
  }

  buildDsRevenueSignalChart();
  buildDsSegmentChart();
</script>
<script>
  function dsFormatVndFallback(value) {
    return new Intl.NumberFormat('vi-VN').format(Math.round(value)) + 'đ';
  }

  function syncDsChartHeader(ctx) {
    const card = ctx.closest('.card');
    if (!card) {
      return;
    }

    const title = card.querySelector('.ds-title');
    const sub = card.querySelector('.ds-sub');

    if (title && ctx.dataset.chartTitle) {
      title.textContent = ctx.dataset.chartTitle;
    }

    if (sub && ctx.dataset.chartSub) {
      sub.textContent = ctx.dataset.chartSub;
    }
  }

  function createDsRevenueSignalChartFallback(ctx) {
    new Chart(ctx, {
      type: 'line',
      data: {
        labels: dsChartPayload.revenueSignal.labels,
        datasets: [{
          label: 'Doanh thu',
          data: dsChartPayload.revenueSignal.revenue,
          fill: false,
          borderColor: '#6d28d9',
          borderWidth: 3,
          pointRadius: 2
        }, {
          label: 'Đường cơ sở',
          data: dsChartPayload.revenueSignal.baseline,
          fill: false,
          borderColor: '#94a3b8',
          borderWidth: 2,
          borderDash: [8, 6],
          pointRadius: 0
        }, {
          label: 'Điểm bất thường',
          data: dsChartPayload.revenueSignal.anomalies,
          fill: false,
          showLine: false,
          borderColor: '#ef4444',
          backgroundColor: '#ef4444',
          pointRadius: 5
        }]
      },
      options: {
        maintainAspectRatio: false,
        legend: { position: 'bottom' },
        scales: {
          xAxes: [{ gridLines: { display: false, drawBorder: false } }],
          yAxes: [{
            ticks: {
              beginAtZero: true,
              callback: function(value) { return dsFormatVndFallback(value); }
            },
            gridLines: { color: 'rgba(226, 232, 240, 0.8)', drawBorder: false }
          }]
        },
        tooltips: {
          callbacks: {
            label: function(tooltipItem, data) {
              return data.datasets[tooltipItem.datasetIndex].label + ': ' + dsFormatVndFallback(tooltipItem.yLabel);
            }
          }
        }
      }
    });
  }

  function createDsSegmentChartFallback(ctx) {
    new Chart(ctx, {
      type: 'doughnut',
      data: {
        labels: dsChartPayload.customerSegments.labels,
        datasets: [{
          data: dsChartPayload.customerSegments.counts,
          backgroundColor: ['#8b5cf6', '#0ea5e9', '#22c55e', '#f59e0b', '#94a3b8'],
          borderColor: '#fff',
          borderWidth: 4
        }]
      },
      options: {
        maintainAspectRatio: false,
        legend: { position: 'bottom' },
        cutoutPercentage: 65
      }
    });
  }

  function createDsMomentumChartFallback(ctx) {
    new Chart(ctx, {
      type: 'bar',
      data: {
        labels: dsChartPayload.momentum.labels,
        datasets: [{
          label: 'SL gần đây',
          data: dsChartPayload.momentum.recent_units,
          backgroundColor: '#8b5cf6',
          borderRadius: 10,
          yAxisID: 'unit-axis'
        }, {
          label: 'Tăng trưởng',
          data: dsChartPayload.momentum.growth_rates,
          type: 'line',
          fill: false,
          borderColor: '#0ea5e9',
          backgroundColor: '#0ea5e9',
          pointBackgroundColor: '#fff',
          pointBorderColor: '#0ea5e9',
          pointBorderWidth: 2,
          borderWidth: 3,
          yAxisID: 'growth-axis'
        }]
      },
      options: {
        maintainAspectRatio: false,
        legend: { position: 'bottom' },
        scales: {
          xAxes: [{ gridLines: { display: false, drawBorder: false } }],
          yAxes: [{
            id: 'unit-axis',
            position: 'left',
            ticks: { beginAtZero: true, precision: 0 },
            gridLines: { color: 'rgba(226, 232, 240, 0.8)', drawBorder: false }
          }, {
            id: 'growth-axis',
            position: 'right',
            ticks: {
              callback: function(value) { return new Intl.NumberFormat('vi-VN').format(value) + '%'; }
            },
            gridLines: { display: false, drawBorder: false }
          }]
        },
        tooltips: {
          callbacks: {
            label: function(tooltipItem, data) {
              const dataset = data.datasets[tooltipItem.datasetIndex];
              if (dataset.yAxisID === 'growth-axis') {
                return dataset.label + ': ' + new Intl.NumberFormat('vi-VN').format(tooltipItem.yLabel) + '%';
              }

              return dataset.label + ': ' + new Intl.NumberFormat('vi-VN').format(tooltipItem.yLabel);
            }
          }
        }
      }
    });
  }

  function createDsAnomalyMixChartFallback(ctx) {
    new Chart(ctx, {
      type: 'doughnut',
      data: {
        labels: dsChartPayload.anomalyMix.labels,
        datasets: [{
          data: dsChartPayload.anomalyMix.counts,
          backgroundColor: ['#ef4444', '#f59e0b'],
          borderColor: '#fff',
          borderWidth: 4
        }]
      },
      options: {
        maintainAspectRatio: false,
        legend: { position: 'bottom' },
        cutoutPercentage: 65
      }
    });
  }

  function buildDsFallbackChart(canvasId) {
    const ctx = document.getElementById(canvasId);
    if (!ctx) {
      return;
    }

    syncDsChartHeader(ctx);

    if (ctx.dataset.chartMode === 'momentum') {
      createDsMomentumChartFallback(ctx);
      return;
    }

    if (ctx.dataset.chartMode === 'customerSegments') {
      createDsSegmentChartFallback(ctx);
      return;
    }

    if (ctx.dataset.chartMode === 'anomalyMix') {
      createDsAnomalyMixChartFallback(ctx);
      return;
    }

    createDsRevenueSignalChartFallback(ctx);
  }

  buildDsFallbackChart('dsPrimaryChart');
  buildDsFallbackChart('dsSecondaryChart');
</script>
<script>
  const dsWatchlistStorageKey = 'shopbanhang.data-scientist.watchlist.v1';

  function dsEscapeHtml(value) {
    return String(value || '')
      .replace(/&/g, '&amp;')
      .replace(/</g, '&lt;')
      .replace(/>/g, '&gt;')
      .replace(/"/g, '&quot;')
      .replace(/'/g, '&#39;');
  }

  function dsReadWatchlist() {
    try {
      const raw = window.localStorage.getItem(dsWatchlistStorageKey);
      return raw ? JSON.parse(raw) : [];
    } catch (error) {
      return [];
    }
  }

  function dsWriteWatchlist(items) {
    try {
      window.localStorage.setItem(dsWatchlistStorageKey, JSON.stringify(items));
    } catch (error) {
      return;
    }
  }

  function dsDecodeBase64Utf8(value) {
    try {
      return decodeURIComponent(Array.prototype.map.call(atob(value), function(char) {
        return '%' + ('00' + char.charCodeAt(0).toString(16)).slice(-2);
      }).join(''));
    } catch (error) {
      return atob(value);
    }
  }

  function dsRenderWatchlist() {
    const container = document.getElementById('dsWatchlistPinned');
    if (!container) {
      return;
    }

    const items = dsReadWatchlist();
    if (!items.length) {
      container.innerHTML = '<div class="ds-watch-card text-muted small">Chưa có mục nào được ghim. Hãy ghim một SKU, nhóm khách hoặc ngày bất thường để theo dõi liên tục.</div>';
      return;
    }

    container.innerHTML = items.map(function(item) {
      return '<div class="ds-watch-card">'
        + '<div class="small text-muted text-uppercase mb-1">' + dsEscapeHtml(item.type) + '</div>'
        + '<div class="font-weight-bold text-dark mb-1">' + dsEscapeHtml(item.label) + '</div>'
        + '<div class="small text-muted mb-2">' + dsEscapeHtml(item.meta || '') + '</div>'
        + '<div class="small text-muted mb-3">' + dsEscapeHtml(item.description || '') + '</div>'
        + '<button type="button" class="btn btn-outline-danger btn-sm ds-watch-remove" data-key="' + dsEscapeHtml(item.key) + '">Bỏ ghim</button>'
        + '</div>';
    }).join('');

    container.querySelectorAll('.ds-watch-remove').forEach(function(button) {
      button.addEventListener('click', function() {
        const nextItems = dsReadWatchlist().filter(function(item) {
          return item.key !== button.dataset.key;
        });
        dsWriteWatchlist(nextItems);
        dsRenderWatchlist();
      });
    });
  }

  document.querySelectorAll('.ds-watch-add').forEach(function(button) {
    button.addEventListener('click', function() {
      try {
        const item = JSON.parse(dsDecodeBase64Utf8(button.dataset.watchItem));
        const items = dsReadWatchlist();
        if (!items.some(function(current) { return current.key === item.key; })) {
          items.unshift(item);
          dsWriteWatchlist(items.slice(0, 18));
          dsRenderWatchlist();
        }
      } catch (error) {
        return;
      }
    });
  });

  const dsWatchlistClear = document.getElementById('dsWatchlistClear');
  if (dsWatchlistClear) {
    dsWatchlistClear.addEventListener('click', function() {
      dsWriteWatchlist([]);
      dsRenderWatchlist();
    });
  }

  dsRenderWatchlist();
</script>
@endpush
