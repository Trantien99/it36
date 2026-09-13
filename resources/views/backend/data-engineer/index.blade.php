@extends('backend.layouts.master')
@section('title', 'Quản trị || Kiểm tra dữ liệu')

@push('styles')
<style>
  .eng-page{padding-bottom:2rem}
  .eng-hero{background:linear-gradient(135deg,#0f172a 0%,#1e293b 52%,#0f766e 100%);border-radius:1.2rem;color:#fff;padding:1.6rem;position:relative;overflow:hidden;box-shadow:0 20px 45px rgba(15,23,42,.16)}
  .eng-hero:after{content:'';position:absolute;right:-3rem;top:-3rem;width:10rem;height:10rem;border-radius:999px;background:rgba(255,255,255,.08)}
  .eng-kicker{font-size:.78rem;font-weight:800;letter-spacing:.18em;text-transform:uppercase;margin-bottom:.85rem}
  .eng-hero h1{font-size:2rem;font-weight:800;line-height:1.1;margin-bottom:.8rem}
  .eng-hero p{max-width:46rem;color:rgba(255,255,255,.84);margin-bottom:0}
  .eng-range{display:flex;flex-wrap:wrap;gap:.6rem;justify-content:flex-end}
  .eng-range a{display:inline-flex;padding:.62rem .95rem;border-radius:999px;font-weight:700;font-size:.84rem;text-decoration:none;color:#fff;background:rgba(255,255,255,.13);border:1px solid rgba(255,255,255,.16)}
  .eng-range a.active{background:#fff;color:#0f172a}
  .eng-chip-row{display:flex;flex-wrap:wrap;gap:.65rem;margin-top:1rem}
  .eng-chip{display:inline-flex;align-items:center;gap:.45rem;background:rgba(255,255,255,.14);border-radius:999px;padding:.42rem .78rem;font-size:.82rem;font-weight:700}
  .eng-stat,.eng-panel{border:0;border-radius:1rem;box-shadow:0 18px 40px rgba(15,23,42,.08)}
  .eng-stat{height:100%;overflow:hidden;position:relative;color:#fff}
  .eng-stat:after{content:'';position:absolute;right:-1.3rem;top:-1.3rem;width:5.8rem;height:5.8rem;border-radius:999px;background:rgba(255,255,255,.08)}
  .eng-stat .card-body{position:relative;z-index:1}
  .eng-stat.e1{background:linear-gradient(140deg,#0f766e 0%,#14b8a6 100%)}
  .eng-stat.e2{background:linear-gradient(140deg,#1d4ed8 0%,#2563eb 100%)}
  .eng-stat.e3{background:linear-gradient(140deg,#b45309 0%,#f59e0b 100%)}
  .eng-stat.e4{background:linear-gradient(140deg,#475569 0%,#64748b 100%)}
  .eng-stat-label{font-size:.8rem;font-weight:800;letter-spacing:.08em;text-transform:uppercase;margin-bottom:.75rem;opacity:.88}
  .eng-stat-value{font-size:1.85rem;font-weight:800;line-height:1.08;margin-bottom:.55rem}
  .eng-stat-note{font-size:.9rem;opacity:.84;margin-bottom:0}
  .eng-panel .card-header{background:#fff;border-bottom:1px solid #e2e8f0;padding:1.1rem 1.25rem}
  .eng-panel .card-body{padding:1.25rem}
  .eng-title{font-size:1.02rem;font-weight:800;color:#0f172a;margin-bottom:.2rem}
  .eng-sub{font-size:.88rem;color:#64748b;margin-bottom:0}
  .eng-grid{display:grid;gap:1rem;grid-template-columns:repeat(auto-fit,minmax(220px,1fr))}
  .eng-contract{border:1px solid #e2e8f0;border-radius:1rem;padding:1rem;background:linear-gradient(180deg,#fff 0%,#f8fbff 100%)}
  .eng-score{font-size:1.9rem;font-weight:800;color:#0f172a}
  .eng-score.good{color:#0f766e}.eng-score.warn{color:#b45309}.eng-score.bad{color:#b91c1c}
  .eng-detail{display:flex;justify-content:space-between;gap:1rem;padding:.42rem 0;border-top:1px dashed #e2e8f0;font-size:.88rem}
  .eng-canvas{height:320px;position:relative}
  .eng-table thead th{font-size:.76rem;font-weight:800;letter-spacing:.08em;text-transform:uppercase;color:#64748b;border-top:0;border-bottom:1px solid #e2e8f0}
  .eng-table tbody td{vertical-align:middle;border-color:#eef2f7}
  .eng-badge{display:inline-flex;padding:.34rem .72rem;border-radius:999px;font-size:.78rem;font-weight:700}
  .eng-badge.good{background:rgba(20,184,166,.12);color:#0f766e}.eng-badge.warn{background:rgba(245,158,11,.16);color:#b45309}.eng-badge.bad{background:rgba(239,68,68,.14);color:#b91c1c}
  .eng-list{display:grid;gap:.85rem}
  .eng-list-item{display:flex;gap:.85rem;border:1px solid #e2e8f0;border-radius:.95rem;padding:1rem;background:linear-gradient(180deg,#fff 0%,#f8fbff 100%)}
  .eng-list-icon{display:inline-flex;align-items:center;justify-content:center;width:2.7rem;height:2.7rem;border-radius:.85rem;background:rgba(15,118,110,.12);color:#0f766e;flex:0 0 2.7rem}
  .eng-export-card{border:1px solid #e2e8f0;border-radius:1rem;padding:1rem;background:linear-gradient(180deg,#fff 0%,#f8fbff 100%);height:100%}
  @media (max-width:991.98px){.eng-range{justify-content:flex-start;margin-top:1rem}.eng-hero h1{font-size:1.7rem}}
</style>
@endpush

@section('main-content')
@php
  $rangeOptions = [14 => '14D', 30 => '30D', 90 => '90D'];
  $statThemes = ['e1', 'e2', 'e3', 'e4'];
  $pickEngChart = function (array $candidates, array $exclude = []) {
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

  $engActivityCandidates = [
      [
          'key' => 'activity',
          'has' => collect($activityChart['users'])->sum() + collect($activityChart['orders'])->sum() + collect($activityChart['messages'])->sum() > 0,
          'title' => 'Tốc độ ingest 14 ngày',
          'sub' => 'Theo dõi nhịp nhận dữ liệu hằng ngày cho users, orders và messages.',
      ],
      [
          'key' => 'contractScore',
          'has' => collect($contractScoreChart['scores'])->count() > 0,
          'title' => 'Điểm data contract',
          'sub' => 'Biểu đồ dự phòng theo điểm contract để vẫn thấy trạng thái pipeline khi activity window quá mỏng.',
      ],
      [
          'key' => 'integrity',
          'has' => collect($integrityChart['counts'])->sum() > 0,
          'title' => 'Phân bố sự cố integrity',
          'sub' => 'Đọc nhanh nhóm lỗi nào đang chiếm tỷ trọng lớn trong pipeline.',
      ],
  ];

  $engFreshnessCandidates = [
      [
          'key' => 'freshness',
          'has' => collect($freshnessChart['stale_days'])->sum() > 0,
          'title' => 'Độ mới của dataset',
          'sub' => 'Số ngày kể từ lần cập nhật cuối của từng bảng dữ liệu.',
      ],
      [
          'key' => 'datasetVolume',
          'has' => collect($datasetVolumeChart['totals'])->sum() + collect($datasetVolumeChart['recent'])->sum() > 0,
          'title' => 'Quy mô dataset và nhịp nạp',
          'sub' => 'Dùng tổng bản ghi và volume 7 ngày để thay cho biểu đồ freshness khi tất cả dataset đều đang mới.',
      ],
      [
          'key' => 'contractScore',
          'has' => collect($contractScoreChart['scores'])->count() > 0,
          'title' => 'Điểm data contract',
          'sub' => 'Biểu đồ dự phòng theo chất lượng contract khi không có điểm stale đáng chú ý.',
      ],
  ];

  $engPrimaryChart = $pickEngChart($engActivityCandidates);
  $engSecondaryChart = $pickEngChart($engFreshnessCandidates, [$engPrimaryChart['key']]);
@endphp
<div class="container-fluid eng-page">
  @include('backend.layouts.notification')

  <div class="eng-hero mb-4">
    <div class="row align-items-center">
      <div class="col-xl-8">
        <div class="eng-kicker">Kiểm tra dữ liệu</div>
        <h1>Góc nhìn chất lượng dữ liệu, freshness và consistency cho pipeline</h1>
        <p>Theo dõi data contract, lệch order-cart, freshness và tốc độ ingest để kiểm soát chất lượng dữ liệu và độ ổn định của pipeline.</p>
        <div class="eng-chip-row">
          <span class="eng-chip"><i class="fas fa-calendar-alt"></i> {{ $startDate->format('d/m/Y') }} - {{ $endDate->format('d/m/Y') }}</span>
          <span class="eng-chip"><i class="fas fa-shield-alt"></i> {{ number_format($averageContractScore, 1, ',', '.') }}/100 hợp đồng dữ liệu</span>
          <span class="eng-chip"><i class="fas fa-database"></i> {{ number_format($ingestionCountLast7Days, 0, ',', '.') }} bản ghi trong 7 ngày</span>
        </div>
      </div>
      <div class="col-xl-4">
        <div class="eng-range">
          @foreach($rangeOptions as $value => $label)
            <a href="{{ route('admin.data-engineer', ['range' => $value]) }}" class="{{ $range === $value ? 'active' : '' }}">{{ $label }}</a>
          @endforeach
        </div>
      </div>
    </div>
  </div>

  <div class="row">
    @php
      $statCards = [
        ['label' => 'Điểm contract trung bình', 'value' => number_format($averageContractScore, 1, ',', '.').'/100', 'note' => 'Mức độ ổn định trung bình của 4 data contract chính.', 'icon' => 'fa-shield-alt'],
        ['label' => 'Sự cố integrity', 'value' => number_format($integrityIncidentCount, 0, ',', '.'), 'note' => 'Tổng mismatch và issue referential hiện tại.', 'icon' => 'fa-bug'],
        ['label' => 'Dataset bị stale', 'value' => number_format($staleDatasetCount, 0, ',', '.'), 'note' => 'Số bảng đã quá 7 ngày chưa có dữ liệu mới.', 'icon' => 'fa-clock'],
        ['label' => 'Bản ghi nạp trong 7 ngày', 'value' => number_format($ingestionCountLast7Days, 0, ',', '.'), 'note' => 'Tổng volume users, orders, products, messages, carts trong 7 ngày.', 'icon' => 'fa-stream'],
      ];
    @endphp
    @foreach($statCards as $index => $card)
      <div class="col-xl-3 col-md-6 mb-4">
        <div class="card eng-stat {{ $statThemes[$index] }}">
          <div class="card-body">
            <div class="d-flex justify-content-between align-items-start mb-3">
              <div class="eng-stat-label">{{ $card['label'] }}</div>
              <i class="fas {{ $card['icon'] }}"></i>
            </div>
            <div class="eng-stat-value">{{ $card['value'] }}</div>
            <p class="eng-stat-note">{{ $card['note'] }}</p>
          </div>
        </div>
      </div>
    @endforeach
  </div>

  <div class="card eng-panel mb-4">
    <div class="card-header">
      <div class="eng-title">Data contract</div>
      <p class="eng-sub">Mỗi contract là một lớp kiểm tra riêng, không trộn với metric kinh doanh.</p>
    </div>
    <div class="card-body">
      <div class="eng-grid">
        @foreach($contracts as $contract)
          @php
            $scoreClass = $contract['score'] >= 90 ? 'good' : ($contract['score'] >= 75 ? 'warn' : 'bad');
          @endphp
          <div class="eng-contract">
            <div class="d-flex justify-content-between align-items-start mb-3">
              <div>
                <div class="font-weight-bold text-dark">{{ $contract['label'] }}</div>
                <div class="small text-muted">Tách riêng theo domain dữ liệu</div>
              </div>
              <div class="eng-score {{ $scoreClass }}">{{ number_format($contract['score'], 1, ',', '.') }}</div>
            </div>
            @foreach($contract['details'] as $label => $count)
              <div class="eng-detail">
                <span class="text-muted">{{ $label }}</span>
                <strong>{{ number_format($count, 0, ',', '.') }}</strong>
              </div>
            @endforeach
          </div>
        @endforeach
      </div>
    </div>
  </div>

  <div class="row">
    <div class="col-xl-7 mb-4">
      <div class="card eng-panel h-100">
        <div class="card-header">
          <div class="eng-title">Tốc độ ingest 14 ngày</div>
          <p class="eng-sub">Theo dõi nhịp nhận dữ liệu hằng ngày cho users, orders và messages.</p>
        </div>
        <div class="card-body">
          <div class="eng-canvas">
            <canvas id="engPrimaryChart" data-chart-mode="{{ $engPrimaryChart['key'] }}" data-chart-title="{{ $engPrimaryChart['title'] }}" data-chart-sub="{{ $engPrimaryChart['sub'] }}"></canvas>
          </div>
        </div>
      </div>
    </div>
    <div class="col-xl-5 mb-4">
      <div class="card eng-panel h-100">
        <div class="card-header">
          <div class="eng-title">Độ mới của dataset</div>
          <p class="eng-sub">Số ngày kể từ lần cập nhật cuối của từng bảng dữ liệu.</p>
        </div>
        <div class="card-body">
          <div class="eng-canvas">
            <canvas id="engSecondaryChart" data-chart-mode="{{ $engSecondaryChart['key'] }}" data-chart-title="{{ $engSecondaryChart['title'] }}" data-chart-sub="{{ $engSecondaryChart['sub'] }}"></canvas>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="row">
    <div class="col-xl-7 mb-4">
      <div class="card eng-panel h-100">
        <div class="card-header">
          <div class="eng-title">Chi tiết freshness</div>
          <p class="eng-sub">Bảng cho team data engineer khi cần kiểm tra nhanh điểm stale.</p>
        </div>
        <div class="card-body">
          <div class="table-responsive">
            <table class="table eng-table mb-0">
              <thead>
                <tr>
                  <th>Dataset</th>
                  <th>Tổng</th>
                  <th>7D</th>
                  <th>Cập nhật cuối</th>
                  <th>Độ trễ</th>
                </tr>
              </thead>
              <tbody>
                @foreach($freshnessCards as $dataset)
                  @php
                    $staleClass = ($dataset['stale_days'] ?? 0) >= 7 ? 'bad' : (($dataset['stale_days'] ?? 0) >= 3 ? 'warn' : 'good');
                  @endphp
                  <tr>
                    <td><i class="fas {{ $dataset['icon'] }} mr-2 text-muted"></i>{{ $dataset['label'] }}</td>
                    <td>{{ number_format($dataset['total'], 0, ',', '.') }}</td>
                    <td>{{ number_format($dataset['recent_count'], 0, ',', '.') }}</td>
                    <td>{{ $dataset['latest_at'] ? $dataset['latest_at']->format('d/m/Y H:i') : 'Chưa có dữ liệu' }}</td>
                    <td><span class="eng-badge {{ $staleClass }}">{{ $dataset['stale_days'] ?? 0 }} ngày</span></td>
                  </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
    <div class="col-xl-5 mb-4">
      <div class="card eng-panel h-100">
        <div class="card-header">
          <div class="eng-title">Cảnh báo pipeline</div>
          <p class="eng-sub">Danh sách cảnh báo ưu tiên cho vận hành dữ liệu.</p>
        </div>
        <div class="card-body">
          <div class="eng-list">
            @foreach($pipelineAlerts as $alert)
              <div class="eng-list-item">
                <div class="eng-list-icon"><i class="fas fa-exclamation-triangle"></i></div>
                <div>
                  <div class="font-weight-bold text-dark mb-1">{{ $alert['title'] }}</div>
                  <div class="text-muted small">{{ $alert['description'] }}</div>
                </div>
              </div>
            @endforeach
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="card eng-panel">
    <div class="card-header">
      <div class="eng-title">Sự cố integrity</div>
      <p class="eng-sub">Một bảng riêng cho mismatch và referential issue cần được xử lý trong pipeline.</p>
    </div>
    <div class="card-body">
      <div class="table-responsive">
        <table class="table eng-table mb-0">
          <thead>
            <tr>
              <th>Vấn đề</th>
              <th>Số lượng</th>
              <th>Ảnh hưởng</th>
            </tr>
          </thead>
          <tbody>
            @foreach($integrityRows as $row)
              <tr>
                <td>{{ $row['label'] }}</td>
                <td>{{ number_format($row['count'], 0, ',', '.') }}</td>
                <td class="text-muted">{{ $row['description'] }}</td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
  </div>
  <div class="card eng-panel mb-4">
    <div class="card-header">
      <div class="eng-title">Xuất danh sách lỗi</div>
      <p class="eng-sub">Xuất CSV để đội data làm việc theo batch thay vì chỉ xem cảnh báo tổng quan.</p>
    </div>
    <div class="card-body">
      <div class="row">
        @foreach($exportTargets as $target)
          <div class="col-xl-4 col-md-6 mb-3">
            <div class="eng-export-card">
              <div class="font-weight-bold text-dark mb-2">{{ $target['label'] }}</div>
              <div class="small text-muted mb-3">{{ $target['note'] }}</div>
              <a href="{{ route('admin.data-engineer.export', ['dataset' => $target['key']]) }}" class="btn btn-outline-primary btn-sm">Tải CSV</a>
            </div>
          </div>
        @endforeach
      </div>
    </div>
  </div>

  <div class="card eng-panel">
    <div class="card-header">
      <div class="eng-title">Hàng đợi lỗi cần xử lý</div>
      <p class="eng-sub">Liệt kê cụ thể order, sản phẩm, user hoặc lead đang có lỗi để mở trực tiếp và xử lý.</p>
    </div>
    <div class="card-body">
      <div class="table-responsive">
        <table class="table eng-table mb-0">
          <thead>
            <tr>
              <th>Thực thể</th>
              <th>Tham chiếu</th>
              <th>Vấn đề</th>
              <th>Ảnh hưởng</th>
              <th>Mức ưu tiên</th>
              <th>Xử lý</th>
            </tr>
          </thead>
          <tbody>
            @forelse($errorQueue as $item)
              @php
                $priorityClass = $item['priority_label'] === 'Cao' ? 'bad' : 'warn';
              @endphp
              <tr>
                <td>
                  <div class="font-weight-bold text-dark">{{ $item['entity_type'] }}</div>
                  <div class="small text-muted">{{ $item['created_at'] }}</div>
                </td>
                <td>
                  <div class="font-weight-bold text-dark">{{ $item['reference'] }}</div>
                  @if(!empty($item['owner']))
                    <div class="small text-muted">{{ $item['owner'] }}</div>
                  @endif
                  @if(!empty($item['email']))
                    <div class="small text-muted">{{ $item['email'] }}</div>
                  @endif
                </td>
                <td>{{ $item['issue'] }}</td>
                <td class="text-muted">{{ $item['impact'] }}</td>
                <td><span class="eng-badge {{ $priorityClass }}">{{ $item['priority_label'] }}</span></td>
                <td>
                  @if(!empty($item['action_url']))
                    <a href="{{ $item['action_url'] }}" class="btn btn-outline-secondary btn-sm">{{ $item['action_label'] }}</a>
                  @else
                    <span class="text-muted small">Chưa có link</span>
                  @endif
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="6" class="text-center text-muted py-4">Chưa có lỗi nào đủ điều kiện đưa vào hàng đợi xử lý.</td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script>
  const engChartPayload = {
    activity: @json($activityChart),
    freshness: @json($freshnessChart)
  };

  function buildEngActivityChart() {
    const ctx = document.getElementById('engActivityChart');
    if (!ctx) {
      return;
    }

    new Chart(ctx, {
      type: 'line',
      data: {
        labels: engChartPayload.activity.labels,
        datasets: [{
          label: 'Người dùng',
          data: engChartPayload.activity.users,
          fill: false,
          borderColor: '#2563eb',
          borderWidth: 3
        }, {
          label: 'Đơn hàng',
          data: engChartPayload.activity.orders,
          fill: false,
          borderColor: '#0f766e',
          borderWidth: 3
        }, {
          label: 'Tin nhắn',
          data: engChartPayload.activity.messages,
          fill: false,
          borderColor: '#d97706',
          borderWidth: 3
        }]
      },
      options: {
        maintainAspectRatio: false,
        legend: { position: 'bottom' },
        scales: {
          xAxes: [{ gridLines: { display: false, drawBorder: false } }],
          yAxes: [{
            ticks: { beginAtZero: true, precision: 0 },
            gridLines: { color: 'rgba(226, 232, 240, 0.8)', drawBorder: false }
          }]
        }
      }
    });
  }

  function buildEngFreshnessChart() {
    const ctx = document.getElementById('engFreshnessChart');
    if (!ctx) {
      return;
    }

    new Chart(ctx, {
      type: 'horizontalBar',
      data: {
        labels: engChartPayload.freshness.labels,
        datasets: [{
          label: 'Số ngày bị trễ',
          data: engChartPayload.freshness.stale_days,
          backgroundColor: ['#0f766e', '#2563eb', '#b45309', '#dc2626', '#64748b']
        }]
      },
      options: {
        maintainAspectRatio: false,
        legend: { display: false },
        scales: {
          xAxes: [{
            ticks: { beginAtZero: true, precision: 0 },
            gridLines: { color: 'rgba(226, 232, 240, 0.8)', drawBorder: false }
          }],
          yAxes: [{ gridLines: { display: false, drawBorder: false } }]
        }
      }
    });
  }

  buildEngActivityChart();
  buildEngFreshnessChart();
</script>
<script>
  const engFallbackPayload = {
    activity: @json($activityChart),
    freshness: @json($freshnessChart),
    contractScore: @json($contractScoreChart),
    datasetVolume: @json($datasetVolumeChart),
    integrity: @json($integrityChart)
  };

  function syncEngChartHeader(ctx) {
    const card = ctx.closest('.card');
    if (!card) {
      return;
    }

    const title = card.querySelector('.eng-title');
    const sub = card.querySelector('.eng-sub');

    if (title && ctx.dataset.chartTitle) {
      title.textContent = ctx.dataset.chartTitle;
    }

    if (sub && ctx.dataset.chartSub) {
      sub.textContent = ctx.dataset.chartSub;
    }
  }

  function createEngActivityChartFallback(ctx) {
    new Chart(ctx, {
      type: 'line',
      data: {
        labels: engFallbackPayload.activity.labels,
        datasets: [{
          label: 'Người dùng',
          data: engFallbackPayload.activity.users,
          fill: false,
          borderColor: '#2563eb',
          borderWidth: 3
        }, {
          label: 'Đơn hàng',
          data: engFallbackPayload.activity.orders,
          fill: false,
          borderColor: '#0f766e',
          borderWidth: 3
        }, {
          label: 'Tin nhắn',
          data: engFallbackPayload.activity.messages,
          fill: false,
          borderColor: '#d97706',
          borderWidth: 3
        }]
      },
      options: {
        maintainAspectRatio: false,
        legend: { position: 'bottom' },
        scales: {
          xAxes: [{ gridLines: { display: false, drawBorder: false } }],
          yAxes: [{
            ticks: { beginAtZero: true, precision: 0 },
            gridLines: { color: 'rgba(226, 232, 240, 0.8)', drawBorder: false }
          }]
        }
      }
    });
  }

  function createEngFreshnessChartFallback(ctx) {
    new Chart(ctx, {
      type: 'horizontalBar',
      data: {
        labels: engFallbackPayload.freshness.labels,
        datasets: [{
          label: 'Số ngày bị trễ',
          data: engFallbackPayload.freshness.stale_days,
          backgroundColor: ['#0f766e', '#2563eb', '#b45309', '#dc2626', '#64748b']
        }]
      },
      options: {
        maintainAspectRatio: false,
        legend: { display: false },
        scales: {
          xAxes: [{
            ticks: { beginAtZero: true, precision: 0 },
            gridLines: { color: 'rgba(226, 232, 240, 0.8)', drawBorder: false }
          }],
          yAxes: [{ gridLines: { display: false, drawBorder: false } }]
        }
      }
    });
  }

  function createEngContractScoreChartFallback(ctx) {
    new Chart(ctx, {
      type: 'bar',
      data: {
        labels: engFallbackPayload.contractScore.labels,
        datasets: [{
          label: 'Điểm contract',
          data: engFallbackPayload.contractScore.scores,
          backgroundColor: ['#0f766e', '#2563eb', '#f59e0b', '#64748b'],
          borderRadius: 10
        }]
      },
      options: {
        maintainAspectRatio: false,
        legend: { display: false },
        scales: {
          xAxes: [{ gridLines: { display: false, drawBorder: false } }],
          yAxes: [{
            ticks: { beginAtZero: true, max: 100 },
            gridLines: { color: 'rgba(226, 232, 240, 0.8)', drawBorder: false }
          }]
        }
      }
    });
  }

  function createEngDatasetVolumeChartFallback(ctx) {
    new Chart(ctx, {
      type: 'bar',
      data: {
        labels: engFallbackPayload.datasetVolume.labels,
        datasets: [{
          label: 'Tổng bản ghi',
          data: engFallbackPayload.datasetVolume.totals,
          backgroundColor: '#2563eb',
          borderRadius: 10,
          yAxisID: 'total-axis'
        }, {
          label: '7 ngày gần đây',
          data: engFallbackPayload.datasetVolume.recent,
          type: 'line',
          fill: false,
          borderColor: '#0f766e',
          backgroundColor: '#0f766e',
          pointBackgroundColor: '#fff',
          pointBorderColor: '#0f766e',
          pointBorderWidth: 2,
          borderWidth: 3,
          yAxisID: 'recent-axis'
        }]
      },
      options: {
        maintainAspectRatio: false,
        legend: { position: 'bottom' },
        scales: {
          xAxes: [{ gridLines: { display: false, drawBorder: false } }],
          yAxes: [{
            id: 'total-axis',
            position: 'left',
            ticks: { beginAtZero: true, precision: 0 },
            gridLines: { color: 'rgba(226, 232, 240, 0.8)', drawBorder: false }
          }, {
            id: 'recent-axis',
            position: 'right',
            ticks: { beginAtZero: true, precision: 0 },
            gridLines: { display: false, drawBorder: false }
          }]
        }
      }
    });
  }

  function createEngIntegrityChartFallback(ctx) {
    new Chart(ctx, {
      type: 'horizontalBar',
      data: {
        labels: engFallbackPayload.integrity.labels,
        datasets: [{
          label: 'Số lượng sự cố',
          data: engFallbackPayload.integrity.counts,
          backgroundColor: ['#dc2626', '#f97316', '#f59e0b', '#2563eb', '#64748b']
        }]
      },
      options: {
        maintainAspectRatio: false,
        legend: { display: false },
        scales: {
          xAxes: [{
            ticks: { beginAtZero: true, precision: 0 },
            gridLines: { color: 'rgba(226, 232, 240, 0.8)', drawBorder: false }
          }],
          yAxes: [{ gridLines: { display: false, drawBorder: false } }]
        }
      }
    });
  }

  function buildEngFallbackChart(canvasId) {
    const ctx = document.getElementById(canvasId);
    if (!ctx) {
      return;
    }

    syncEngChartHeader(ctx);

    if (ctx.dataset.chartMode === 'contractScore') {
      createEngContractScoreChartFallback(ctx);
      return;
    }

    if (ctx.dataset.chartMode === 'datasetVolume') {
      createEngDatasetVolumeChartFallback(ctx);
      return;
    }

    if (ctx.dataset.chartMode === 'integrity') {
      createEngIntegrityChartFallback(ctx);
      return;
    }

    if (ctx.dataset.chartMode === 'freshness') {
      createEngFreshnessChartFallback(ctx);
      return;
    }

    createEngActivityChartFallback(ctx);
  }

  buildEngFallbackChart('engPrimaryChart');
  buildEngFallbackChart('engSecondaryChart');
</script>
@endpush
