@extends('backend.layouts.master')
@section('title', 'Quản trị || Phân tích kinh doanh')

@push('styles')
<style>
  .role-page{padding-bottom:2rem}
  .role-hero{border-radius:1.2rem;color:#fff;overflow:hidden;padding:1.6rem;position:relative;box-shadow:0 20px 45px rgba(15,23,42,.12)}
  .role-hero:after{content:'';position:absolute;right:-3rem;top:-3rem;width:10rem;height:10rem;border-radius:999px;background:rgba(255,255,255,.08)}
  .role-hero.bi{background:linear-gradient(135deg,#123c69 0%,#1d5fa8 55%,#0ea5a4 100%)}
  .role-kicker{font-size:.78rem;font-weight:800;letter-spacing:.18em;text-transform:uppercase;margin-bottom:.85rem}
  .role-hero h1{font-size:2rem;font-weight:800;line-height:1.1;margin-bottom:.8rem}
  .role-hero p{max-width:44rem;color:rgba(255,255,255,.84);margin-bottom:0}
  .role-range{display:flex;flex-wrap:wrap;gap:.6rem;justify-content:flex-end}
  .role-range a{display:inline-flex;padding:.62rem .95rem;border-radius:999px;font-weight:700;font-size:.84rem;text-decoration:none;color:#fff;background:rgba(255,255,255,.13);border:1px solid rgba(255,255,255,.16)}
  .role-range a.active{background:#fff;color:#123c69}
  .role-chip-row{display:flex;flex-wrap:wrap;gap:.65rem;margin-top:1rem}
  .role-chip{display:inline-flex;align-items:center;gap:.45rem;background:rgba(255,255,255,.14);border-radius:999px;padding:.42rem .78rem;font-size:.82rem;font-weight:700}
  .role-stat,.role-panel{border:0;border-radius:1rem;box-shadow:0 18px 40px rgba(15,23,42,.08)}
  .role-stat{height:100%;overflow:hidden;position:relative;color:#fff}
  .role-stat:after{content:'';position:absolute;right:-1.4rem;top:-1.4rem;width:5.8rem;height:5.8rem;border-radius:999px;background:rgba(255,255,255,.08)}
  .role-stat .card-body{position:relative;z-index:1}
  .role-stat.bi-a{background:linear-gradient(140deg,#1d4ed8 0%,#2563eb 100%)}
  .role-stat.bi-b{background:linear-gradient(140deg,#0f766e 0%,#0ea5a4 100%)}
  .role-stat.bi-c{background:linear-gradient(140deg,#b45309 0%,#d97706 100%)}
  .role-stat.bi-d{background:linear-gradient(140deg,#475569 0%,#64748b 100%)}
  .role-stat-label{font-size:.8rem;font-weight:800;letter-spacing:.08em;text-transform:uppercase;margin-bottom:.75rem;opacity:.88}
  .role-stat-value{font-size:1.85rem;font-weight:800;line-height:1.08;margin-bottom:.55rem}
  .role-stat-note{font-size:.9rem;opacity:.84;margin-bottom:0}
  .role-panel .card-header{background:#fff;border-bottom:1px solid #e2e8f0;padding:1.1rem 1.25rem}
  .role-panel .card-body{padding:1.25rem}
  .role-title{font-size:1.02rem;font-weight:800;color:#0f172a;margin-bottom:.2rem}
  .role-sub{font-size:.88rem;color:#64748b;margin-bottom:0}
  .role-canvas{height:320px;position:relative}
  .role-list{display:grid;gap:.85rem}
  .role-list-item{display:flex;gap:.85rem;border:1px solid #e2e8f0;border-radius:.95rem;padding:1rem;background:linear-gradient(180deg,#fff 0%,#f8fbff 100%)}
  .role-list-icon{display:inline-flex;align-items:center;justify-content:center;width:2.7rem;height:2.7rem;border-radius:.85rem;background:rgba(37,99,235,.12);color:#2563eb;flex:0 0 2.7rem}
  .role-table thead th{font-size:.76rem;font-weight:800;letter-spacing:.08em;text-transform:uppercase;color:#64748b;border-top:0;border-bottom:1px solid #e2e8f0}
  .role-table tbody td{vertical-align:middle;border-color:#eef2f7}
  .role-pill{display:inline-flex;padding:.34rem .72rem;border-radius:999px;font-size:.78rem;font-weight:700}
  .role-pill.good{background:rgba(14,165,164,.12);color:#0f766e}
  .role-pill.warn{background:rgba(245,158,11,.16);color:#b45309}
  .role-pill.muted{background:rgba(100,116,139,.14);color:#475569}
  .role-mini-grid{display:grid;gap:.9rem;grid-template-columns:repeat(auto-fit,minmax(180px,1fr))}
  .role-mini-card{border:1px solid #e2e8f0;border-radius:1rem;padding:1rem;background:linear-gradient(180deg,#fff 0%,#f8fbff 100%)}
  .role-mini-label{font-size:.78rem;font-weight:800;letter-spacing:.08em;text-transform:uppercase;color:#64748b;margin-bottom:.45rem}
  .role-mini-value{font-size:1.35rem;font-weight:800;color:#0f172a}
  .role-note{font-size:.84rem;color:#64748b}
  @media (max-width:991.98px){.role-range{justify-content:flex-start;margin-top:1rem}.role-hero h1{font-size:1.7rem}}
</style>
@endpush

@section('main-content')
@php
  $rangeOptions = [30 => '30D', 90 => '90D', 180 => '180D'];
  $cardThemes = ['bi-a', 'bi-b', 'bi-c', 'bi-d'];
  $pickRoleChart = function (array $candidates, array $exclude = []) {
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

  $rolePaymentCandidates = [
      [
          'key' => 'paymentMix',
          'has' => collect($chartData['paymentMix']['revenue'])->sum() + collect($chartData['paymentMix']['orders'])->sum() > 0,
          'title' => 'Cơ cấu thanh toán và doanh thu',
          'sub' => 'So sánh đóng góp doanh thu và số đơn theo từng phương thức thanh toán.',
      ],
      [
          'key' => 'couponMix',
          'has' => collect($chartData['couponMix']['revenue'])->sum() + collect($chartData['couponMix']['orders'])->sum() > 0,
          'title' => 'Hiệu quả coupon theo doanh thu',
          'sub' => 'Đọc nhanh tỷ trọng doanh thu và số đơn của nhóm dùng coupon so với nhóm mua đủ giá.',
      ],
      [
          'key' => 'basketMix',
          'has' => collect($chartData['basketMix']['revenue'])->sum() + collect($chartData['basketMix']['orders'])->sum() > 0,
          'title' => 'Cơ cấu cỡ giỏ hàng',
          'sub' => 'Nhìn nhanh tỷ trọng đơn và doanh thu giữa giỏ đơn chiếc, combo và giỏ lớn.',
      ],
  ];

  $roleCustomerCandidates = [
      [
          'key' => 'customerSegments',
          'has' => collect($chartData['customerSegments']['revenue'])->sum() + collect($chartData['customerSegments']['orders'])->sum() > 0,
          'title' => 'Khách mới và khách quay lại',
          'sub' => 'Tỷ trọng đơn hàng và doanh thu theo vòng đời mua hàng.',
      ],
      [
          'key' => 'basketMix',
          'has' => collect($chartData['basketMix']['revenue'])->sum() + collect($chartData['basketMix']['orders'])->sum() > 0,
          'title' => 'Cơ cấu cỡ giỏ hàng',
          'sub' => 'Biểu đồ dự phòng để vẫn giữ được tín hiệu bundle và upsell khi chưa đủ dữ liệu khách hàng.',
      ],
      [
          'key' => 'couponMix',
          'has' => collect($chartData['couponMix']['revenue'])->sum() + collect($chartData['couponMix']['orders'])->sum() > 0,
          'title' => 'Hiệu quả coupon',
          'sub' => 'Dùng khi phân khúc khách hàng chưa đủ dữ liệu để vẫn đọc được chuyển động giá trị đơn.',
      ],
  ];

  $rolePrimaryChart = $pickRoleChart($rolePaymentCandidates);
  $roleSecondaryChart = $pickRoleChart($roleCustomerCandidates, [$rolePrimaryChart['key']]);
@endphp
<div class="container-fluid role-page">
  @include('backend.layouts.notification')

  <div class="role-hero bi mb-4">
    <div class="row align-items-center">
      <div class="col-xl-8">
        <div class="role-kicker">Phân tích kinh doanh</div>
        <h1>Góc nhìn chẩn đoán cho hành vi mua hàng và mix doanh thu</h1>
        <p>Theo dõi khách mới, khách quay lại, coupon, phương thức thanh toán và cỡ giỏ hàng để đọc rõ cấu trúc doanh thu và hành vi mua hàng.</p>
        <div class="role-chip-row">
          <span class="role-chip"><i class="fas fa-calendar-alt"></i> {{ $startDate->format('d/m/Y') }} - {{ $endDate->format('d/m/Y') }}</span>
          <span class="role-chip"><i class="fas fa-shopping-bag"></i> {{ number_format($deliveredOrderCount, 0, ',', '.') }} đơn đã giao</span>
          <span class="role-chip"><i class="fas fa-coins"></i> {{ number_format($deliveredRevenue, 0, ',', '.') }}đ doanh thu</span>
        </div>
      </div>
      <div class="col-xl-4">
        <div class="role-range">
          @foreach($rangeOptions as $value => $label)
            <a href="{{ route('admin.bi-analyst', ['range' => $value]) }}" class="{{ $range === $value ? 'active' : '' }}">{{ $label }}</a>
          @endforeach
        </div>
      </div>
    </div>
  </div>

  <div class="row">
    @foreach($summaryCards as $index => $card)
      @php
        $value = $card['value'];
        if ($card['format'] === 'currency') {
          $value = number_format($card['value'], 0, ',', '.').'d';
        } elseif ($card['format'] === 'percent') {
          $value = number_format($card['value'], 1, ',', '.').'%';
        } else {
          $value = number_format($card['value'], 1, ',', '.');
        }
      @endphp
      <div class="col-xl-3 col-md-6 mb-4">
        <div class="card role-stat {{ $cardThemes[$index] }}">
          <div class="card-body">
            <div class="d-flex justify-content-between align-items-start mb-3">
              <div class="role-stat-label">{{ $card['label'] }}</div>
              <i class="fas {{ $card['icon'] }}"></i>
            </div>
            <div class="role-stat-value">{{ $value }}</div>
            <p class="role-stat-note">{{ $card['note'] }}</p>
          </div>
        </div>
      </div>
    @endforeach
  </div>

  <div class="row">
    <div class="col-xl-7 mb-4">
      <div class="card role-panel h-100">
        <div class="card-header">
          <div class="role-title">Cơ cấu thanh toán và doanh thu</div>
          <p class="role-sub">So sánh đóng góp doanh thu và số đơn theo từng phương thức thanh toán.</p>
        </div>
        <div class="card-body">
          <div class="role-canvas">
            <canvas id="biPrimaryChart" data-chart-mode="{{ $rolePrimaryChart['key'] }}" data-chart-title="{{ $rolePrimaryChart['title'] }}" data-chart-sub="{{ $rolePrimaryChart['sub'] }}"></canvas>
          </div>
        </div>
      </div>
    </div>
    <div class="col-xl-5 mb-4">
      <div class="card role-panel h-100">
        <div class="card-header">
          <div class="role-title">Khách mới và khách quay lại</div>
          <p class="role-sub">Tỷ trọng đơn hàng và doanh thu theo vòng đời mua hàng.</p>
        </div>
        <div class="card-body">
          <div class="role-canvas">
            <canvas id="biSecondaryChart" data-chart-mode="{{ $roleSecondaryChart['key'] }}" data-chart-title="{{ $roleSecondaryChart['title'] }}" data-chart-sub="{{ $roleSecondaryChart['sub'] }}"></canvas>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="row">
    <div class="col-xl-6 mb-4">
      <div class="card role-panel h-100">
        <div class="card-header">
          <div class="role-title">Hiệu quả coupon</div>
          <p class="role-sub">Dùng để xem coupon đang mở rộng đơn hay chỉ ăn vào biên giá trị.</p>
        </div>
        <div class="card-body">
          <div class="table-responsive">
            <table class="table role-table mb-0">
              <thead>
                <tr>
                  <th>Nhóm</th>
                  <th>Đơn hàng</th>
                  <th>AOV</th>
                  <th>SL TB</th>
                  <th>Tỷ trọng</th>
                </tr>
              </thead>
              <tbody>
                @foreach($couponMix as $coupon)
                  <tr>
                    <td>{{ $coupon['label'] }}</td>
                    <td>{{ number_format($coupon['order_count'], 0, ',', '.') }}</td>
                    <td>{{ number_format($coupon['average_order_value'], 0, ',', '.') }}đ</td>
                    <td>{{ number_format($coupon['average_units'], 1, ',', '.') }}</td>
                    <td><span class="role-pill {{ $coupon['key'] === 'coupon' ? 'warn' : 'good' }}">{{ number_format($coupon['order_share'], 1, ',', '.') }}%</span></td>
                  </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
    <div class="col-xl-6 mb-4">
      <div class="card role-panel h-100">
        <div class="card-header">
          <div class="role-title">Cơ cấu cỡ giỏ hàng</div>
          <p class="role-sub">Nhận nhanh cơ hội bundle, upsell và cross-sell.</p>
        </div>
        <div class="card-body">
          <div class="table-responsive">
            <table class="table role-table mb-0">
              <thead>
                <tr>
                  <th>Nhóm giỏ hàng</th>
                  <th>Đơn hàng</th>
                  <th>Doanh thu</th>
                  <th>Tỷ trọng</th>
                </tr>
              </thead>
              <tbody>
                @foreach($basketMix as $basket)
                  <tr>
                    <td>{{ $basket['label'] }}</td>
                    <td>{{ number_format($basket['order_count'], 0, ',', '.') }}</td>
                    <td>{{ number_format($basket['revenue'], 0, ',', '.') }}đ</td>
                    <td><span class="role-pill {{ $basket['key'] === 'single' ? 'muted' : 'good' }}">{{ number_format($basket['order_share'], 1, ',', '.') }}%</span></td>
                  </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="row">
    <div class="col-xl-7 mb-4">
      <div class="card role-panel h-100">
        <div class="card-header">
          <div class="role-title">Bảng chi tiết thanh toán</div>
          <p class="role-sub">Bảng phụ để BI Analyst so sánh tỷ trọng doanh thu và AOV giữa các hình thức thanh toán.</p>
        </div>
        <div class="card-body">
          <div class="table-responsive">
            <table class="table role-table mb-0">
              <thead>
                <tr>
                  <th>Phương thức</th>
                  <th>Đơn hàng</th>
                  <th>Doanh thu</th>
                  <th>AOV</th>
                  <th>Tỷ trọng doanh thu</th>
                </tr>
              </thead>
              <tbody>
                @foreach($paymentMix as $payment)
                  <tr>
                    <td>{{ $payment['label'] }}</td>
                    <td>{{ number_format($payment['order_count'], 0, ',', '.') }}</td>
                    <td>{{ number_format($payment['revenue'], 0, ',', '.') }}đ</td>
                    <td>{{ number_format($payment['average_order_value'], 0, ',', '.') }}đ</td>
                    <td><span class="role-pill {{ in_array($payment['key'], ['paypal', 'momo'], true) ? 'good' : 'muted' }}">{{ number_format($payment['revenue_share'], 1, ',', '.') }}%</span></td>
                  </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
    <div class="col-xl-5 mb-4">
      <div class="card role-panel h-100">
        <div class="card-header">
          <div class="role-title">Tín hiệu kinh doanh</div>
          <p class="role-sub">Danh sách hành động ưu tiên rút ra từ mix mua hàng.</p>
        </div>
        <div class="card-body">
          <div class="role-list">
            @foreach($commercialSignals as $signal)
              <div class="role-list-item">
                <div class="role-list-icon"><i class="fas fa-lightbulb"></i></div>
                <div>
                  <div class="font-weight-bold text-dark mb-1">{{ $signal['title'] }}</div>
                  <div class="text-muted small">{{ $signal['description'] }}</div>
                </div>
              </div>
            @endforeach
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="row">
    <div class="col-xl-7 mb-4">
      <div class="card role-panel h-100">
        <div class="card-header">
          <div class="role-title">Mô phỏng kịch bản kinh doanh</div>
          <p class="role-sub">Đổi coupon, ngưỡng freeship và tỷ lệ dịch chuyển COD để ước tính tác động lên tập đơn hiện tại.</p>
        </div>
        <div class="card-body">
          <form method="GET" action="{{ route('admin.bi-analyst') }}" class="mb-4">
            <input type="hidden" name="range" value="{{ $range }}">
            <div class="form-row">
              <div class="form-group col-md-4">
                <label class="small font-weight-bold text-uppercase text-muted">Coupon giả định</label>
                <select name="scenario_coupon_code" class="form-control">
                  <option value="">Không áp coupon</option>
                  @foreach($activeCoupons as $coupon)
                    <option value="{{ $coupon->code }}" {{ $simulatorInput['coupon_code'] === $coupon->code ? 'selected' : '' }}>
                      {{ $coupon->code }} - {{ $coupon->type === 'percent' ? number_format($coupon->value, 0, ',', '.').'%' : number_format($coupon->value, 0, ',', '.').'đ' }}
                    </option>
                  @endforeach
                </select>
              </div>
              <div class="form-group col-md-3">
                <label class="small font-weight-bold text-uppercase text-muted">Tỷ lệ đơn áp coupon</label>
                <input type="number" min="0" max="100" name="scenario_coupon_reach" class="form-control" value="{{ $simulatorInput['coupon_reach'] }}">
              </div>
              <div class="form-group col-md-3">
                <label class="small font-weight-bold text-uppercase text-muted">Ngưỡng freeship</label>
                <input type="number" min="0" step="100000" name="scenario_free_ship_threshold" class="form-control" value="{{ (int) $simulatorInput['free_ship_threshold'] }}">
              </div>
              <div class="form-group col-md-2">
                <label class="small font-weight-bold text-uppercase text-muted">COD sang trả trước</label>
                <input type="number" min="0" max="100" name="scenario_cod_shift" class="form-control" value="{{ $simulatorInput['cod_shift'] }}">
              </div>
            </div>
            <button type="submit" class="btn btn-primary">Chạy mô phỏng</button>
          </form>

          <div class="role-mini-grid mb-4">
            @foreach($scenarioSummary as $item)
              @php
                if ($item['format'] === 'currency') {
                  $scenarioValue = number_format($item['value'], 0, ',', '.').'đ';
                } elseif ($item['format'] === 'percent') {
                  $scenarioValue = number_format($item['value'], 1, ',', '.').'%';
                } else {
                  $scenarioValue = number_format($item['value'], 0, ',', '.');
                }
              @endphp
              <div class="role-mini-card">
                <div class="role-mini-label">{{ $item['label'] }}</div>
                <div class="role-mini-value">{{ $scenarioValue }}</div>
                <div class="role-note mt-2">{{ $item['note'] }}</div>
              </div>
            @endforeach
          </div>

          <div class="row">
            <div class="col-md-6 mb-3 mb-md-0">
              <div class="small font-weight-bold text-uppercase text-muted mb-2">Giả định đang dùng</div>
              <div class="role-note mb-2">Coupon: <strong class="text-dark">{{ $scenarioAssumptions['selected_coupon'] }}</strong></div>
              <div class="role-note mb-2">Ngân sách coupon: <strong class="text-dark">{{ number_format($scenarioAssumptions['coupon_budget'], 0, ',', '.') }}đ</strong></div>
              <div class="role-note">Ngân sách freeship: <strong class="text-dark">{{ number_format($scenarioAssumptions['free_ship_budget'], 0, ',', '.') }}đ</strong></div>
            </div>
            <div class="col-md-6">
              <div class="small font-weight-bold text-uppercase text-muted mb-2">Điểm cần đọc</div>
              <div class="role-note mb-2">{{ number_format($scenarioAssumptions['free_ship_eligible_count'], 0, ',', '.') }} đơn chạm ngưỡng freeship dưới kịch bản mới.</div>
              <div class="role-note">{{ number_format($scenarioAssumptions['shifted_cod_orders'], 0, ',', '.') }} đơn COD được giả định chuyển sang trả trước, tương ứng {{ number_format($scenarioAssumptions['shifted_revenue'], 0, ',', '.') }}đ doanh thu.</div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-xl-5 mb-4">
      <div class="card role-panel h-100">
        <div class="card-header">
          <div class="role-title">Danh sách cơ hội</div>
          <p class="role-sub">Không chỉ đọc số, màn này gom sẵn các cơ hội có thể ưu tiên để kéo doanh thu.</p>
        </div>
        <div class="card-body">
          <div class="role-list">
            <div class="role-list-item">
              <div class="role-list-icon"><i class="fas fa-user-rotate"></i></div>
              <div>
                <div class="font-weight-bold text-dark mb-1">Kéo khách quay lại</div>
                <div class="text-muted small">{{ number_format($winBackCustomers->count(), 0, ',', '.') }} khách đang đủ điều kiện win-back theo recency và lịch sử mua.</div>
              </div>
            </div>
            <div class="role-list-item">
              <div class="role-list-icon"><i class="fas fa-arrow-up-right-dots"></i></div>
              <div>
                <div class="font-weight-bold text-dark mb-1">Upsell từ đơn 1 sản phẩm</div>
                <div class="text-muted small">{{ number_format($singleItemUpsellRows->sum('single_order_count'), 0, ',', '.') }} đơn 1 sản phẩm đang là nguồn dễ đẩy phụ kiện hoặc phiên bản cao hơn.</div>
              </div>
            </div>
            <div class="role-list-item">
              <div class="role-list-icon"><i class="fas fa-object-group"></i></div>
              <div>
                <div class="font-weight-bold text-dark mb-1">Ghép combo có xác suất cao</div>
                <div class="text-muted small">{{ number_format(collect($bundleOpportunities)->count(), 0, ',', '.') }} cặp sản phẩm đồng mua đang đủ mạnh để biến thành gợi ý combo.</div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="row">
    <div class="col-xl-6 mb-4">
      <div class="card role-panel h-100">
        <div class="card-header">
          <div class="role-title">Khách nên kéo quay lại</div>
          <p class="role-sub">Ưu tiên những khách có doanh thu lịch sử tốt nhưng đã lâu chưa mua lại.</p>
        </div>
        <div class="card-body">
          <div class="table-responsive">
            <table class="table role-table mb-0">
              <thead>
                <tr>
                  <th>Khách hàng</th>
                  <th>Đơn lịch sử</th>
                  <th>Doanh thu</th>
                  <th>Lần mua gần nhất</th>
                  <th>Playbook</th>
                </tr>
              </thead>
              <tbody>
                @forelse($winBackCustomers as $customer)
                  <tr>
                    <td>
                      <div class="font-weight-bold text-dark">{{ $customer['name'] }}</div>
                      <div class="small text-muted">{{ $customer['email'] }}</div>
                    </td>
                    <td>{{ number_format($customer['order_count'], 0, ',', '.') }}</td>
                    <td>{{ number_format($customer['revenue'], 0, ',', '.') }}đ</td>
                    <td>{{ number_format($customer['days_since_last_order'], 0, ',', '.') }} ngày</td>
                    <td class="text-muted">{{ $customer['playbook'] }}</td>
                  </tr>
                @empty
                  <tr>
                    <td colspan="5" class="text-center text-muted py-4">Chưa có nhóm win-back nổi bật trong dữ liệu hiện tại.</td>
                  </tr>
                @endforelse
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
    <div class="col-xl-6 mb-4">
      <div class="card role-panel h-100">
        <div class="card-header">
          <div class="role-title">Đơn 1 sản phẩm có thể upsell</div>
          <p class="role-sub">Các SKU này thường xuất hiện trong đơn đơn chiếc, phù hợp để đẩy phụ kiện hoặc phiên bản cao hơn.</p>
        </div>
        <div class="card-body">
          <div class="table-responsive">
            <table class="table role-table mb-0">
              <thead>
                <tr>
                  <th>Sản phẩm</th>
                  <th>Đơn đơn chiếc</th>
                  <th>Doanh thu</th>
                  <th>Gợi ý upsell</th>
                </tr>
              </thead>
              <tbody>
                @forelse($singleItemUpsellRows as $row)
                  <tr>
                    <td>
                      <div class="font-weight-bold text-dark">{{ $row['title'] }}</div>
                      <div class="small text-muted">Giá {{ number_format($row['price'], 0, ',', '.') }}đ | Tồn {{ number_format($row['stock'], 0, ',', '.') }}</div>
                    </td>
                    <td>{{ number_format($row['single_order_count'], 0, ',', '.') }}</td>
                    <td>{{ number_format($row['revenue'], 0, ',', '.') }}đ</td>
                    <td class="text-muted">{{ $row['upsell_note'] }}</td>
                  </tr>
                @empty
                  <tr>
                    <td colspan="4" class="text-center text-muted py-4">Chưa có đủ đơn 1 sản phẩm để tạo gợi ý upsell.</td>
                  </tr>
                @endforelse
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="card role-panel mb-4">
    <div class="card-header">
      <div class="role-title">Sản phẩm nên ghép combo</div>
      <p class="role-sub">Danh sách cơ hội ghép combo lấy từ lịch sử đồng mua, không phải gợi ý cảm tính.</p>
    </div>
    <div class="card-body">
      <div class="table-responsive">
        <table class="table role-table mb-0">
          <thead>
            <tr>
              <th>Combo</th>
              <th>Số lần đồng mua</th>
              <th>Doanh thu combo</th>
              <th>Gợi ý dùng</th>
            </tr>
          </thead>
          <tbody>
            @forelse($bundleOpportunities as $bundle)
              <tr>
                <td>{{ $bundle['label'] }}</td>
                <td>{{ number_format($bundle['bundle_count'], 0, ',', '.') }}</td>
                <td>{{ number_format($bundle['bundle_revenue'], 0, ',', '.') }}đ</td>
                <td class="text-muted">Đưa vào widget mua kèm, combo landing hoặc ưu đãi theo ngưỡng.</td>
              </tr>
            @empty
              <tr>
                <td colspan="4" class="text-center text-muted py-4">Chưa có đủ lịch sử đồng mua để tạo combo đáng tin cậy.</td>
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
  const biChartPayload = @json($chartData);

  function biFormatVnd(value) {
    return new Intl.NumberFormat('vi-VN').format(Math.round(value)) + 'đ';
  }

  function buildBiPaymentMixChart() {
    const ctx = document.getElementById('biPaymentMixChart');
    if (!ctx) {
      return;
    }

    new Chart(ctx, {
      type: 'bar',
      data: {
        labels: biChartPayload.paymentMix.labels,
        datasets: [{
          label: 'Doanh thu',
          data: biChartPayload.paymentMix.revenue,
          backgroundColor: 'rgba(37, 99, 235, 0.82)',
          borderRadius: 10,
          yAxisID: 'revenue-axis'
        }, {
          label: 'Đơn hàng',
          data: biChartPayload.paymentMix.orders,
          type: 'line',
          fill: false,
          borderColor: 'rgba(14, 165, 164, 1)',
          backgroundColor: 'rgba(14, 165, 164, 1)',
          pointBackgroundColor: '#fff',
          pointBorderColor: 'rgba(14, 165, 164, 1)',
          pointBorderWidth: 2,
          borderWidth: 3,
          yAxisID: 'order-axis'
        }]
      },
      options: {
        maintainAspectRatio: false,
        legend: { position: 'bottom' },
        scales: {
          xAxes: [{ gridLines: { display: false, drawBorder: false } }],
          yAxes: [{
            id: 'revenue-axis',
            position: 'left',
            ticks: {
              beginAtZero: true,
              callback: function(value) { return biFormatVnd(value); }
            },
            gridLines: { color: 'rgba(226, 232, 240, 0.8)', drawBorder: false }
          }, {
            id: 'order-axis',
            position: 'right',
            ticks: { beginAtZero: true, precision: 0 },
            gridLines: { display: false, drawBorder: false }
          }]
        },
        tooltips: {
          callbacks: {
            label: function(tooltipItem, data) {
              const dataset = data.datasets[tooltipItem.datasetIndex];
              if (dataset.yAxisID === 'revenue-axis') {
                return dataset.label + ': ' + biFormatVnd(tooltipItem.yLabel);
              }

              return dataset.label + ': ' + new Intl.NumberFormat('vi-VN').format(tooltipItem.yLabel);
            }
          }
        }
      }
    });
  }

  function buildBiCustomerSegmentChart() {
    const ctx = document.getElementById('biCustomerSegmentChart');
    if (!ctx) {
      return;
    }

    new Chart(ctx, {
      type: 'doughnut',
      data: {
        labels: biChartPayload.customerSegments.labels,
        datasets: [{
          data: biChartPayload.customerSegments.revenue,
          backgroundColor: ['#0ea5a4', '#1d4ed8'],
          borderColor: '#fff',
          borderWidth: 4
        }]
      },
      options: {
        maintainAspectRatio: false,
        legend: { position: 'bottom' },
        cutoutPercentage: 68,
        tooltips: {
          callbacks: {
            label: function(tooltipItem, data) {
              const value = data.datasets[tooltipItem.datasetIndex].data[tooltipItem.index] || 0;
              const orders = biChartPayload.customerSegments.orders[tooltipItem.index] || 0;
              return data.labels[tooltipItem.index] + ': ' + biFormatVnd(value) + ' / ' + orders + ' đơn';
            }
          }
        }
      }
    });
  }

  buildBiPaymentMixChart();
  buildBiCustomerSegmentChart();
</script>
<script>
  function biFormatVndFallback(value) {
    return new Intl.NumberFormat('vi-VN').format(Math.round(value)) + 'đ';
  }

  function syncBiChartHeader(ctx) {
    const card = ctx.closest('.card');
    if (!card) {
      return;
    }

    const title = card.querySelector('.role-title');
    const sub = card.querySelector('.role-sub');

    if (title && ctx.dataset.chartTitle) {
      title.textContent = ctx.dataset.chartTitle;
    }

    if (sub && ctx.dataset.chartSub) {
      sub.textContent = ctx.dataset.chartSub;
    }
  }

  function createBiPaymentMixChartFallback(ctx) {
    new Chart(ctx, {
      type: 'bar',
      data: {
        labels: biChartPayload.paymentMix.labels,
        datasets: [{
          label: 'Doanh thu',
          data: biChartPayload.paymentMix.revenue,
          backgroundColor: 'rgba(37, 99, 235, 0.82)',
          borderRadius: 10,
          yAxisID: 'revenue-axis'
        }, {
          label: 'Đơn hàng',
          data: biChartPayload.paymentMix.orders,
          type: 'line',
          fill: false,
          borderColor: 'rgba(14, 165, 164, 1)',
          backgroundColor: 'rgba(14, 165, 164, 1)',
          pointBackgroundColor: '#fff',
          pointBorderColor: 'rgba(14, 165, 164, 1)',
          pointBorderWidth: 2,
          borderWidth: 3,
          yAxisID: 'order-axis'
        }]
      },
      options: {
        maintainAspectRatio: false,
        legend: { position: 'bottom' },
        scales: {
          xAxes: [{ gridLines: { display: false, drawBorder: false } }],
          yAxes: [{
            id: 'revenue-axis',
            position: 'left',
            ticks: {
              beginAtZero: true,
              callback: function(value) { return biFormatVndFallback(value); }
            },
            gridLines: { color: 'rgba(226, 232, 240, 0.8)', drawBorder: false }
          }, {
            id: 'order-axis',
            position: 'right',
            ticks: { beginAtZero: true, precision: 0 },
            gridLines: { display: false, drawBorder: false }
          }]
        },
        tooltips: {
          callbacks: {
            label: function(tooltipItem, data) {
              const dataset = data.datasets[tooltipItem.datasetIndex];
              if (dataset.yAxisID === 'revenue-axis') {
                return dataset.label + ': ' + biFormatVndFallback(tooltipItem.yLabel);
              }

              return dataset.label + ': ' + new Intl.NumberFormat('vi-VN').format(tooltipItem.yLabel);
            }
          }
        }
      }
    });
  }

  function createBiCustomerSegmentChartFallback(ctx) {
    new Chart(ctx, {
      type: 'doughnut',
      data: {
        labels: biChartPayload.customerSegments.labels,
        datasets: [{
          data: biChartPayload.customerSegments.revenue,
          backgroundColor: ['#0ea5a4', '#1d4ed8'],
          borderColor: '#fff',
          borderWidth: 4
        }]
      },
      options: {
        maintainAspectRatio: false,
        legend: { position: 'bottom' },
        cutoutPercentage: 68,
        tooltips: {
          callbacks: {
            label: function(tooltipItem, data) {
              const value = data.datasets[tooltipItem.datasetIndex].data[tooltipItem.index] || 0;
              const orders = biChartPayload.customerSegments.orders[tooltipItem.index] || 0;
              return data.labels[tooltipItem.index] + ': ' + biFormatVndFallback(value) + ' / ' + orders + ' đơn';
            }
          }
        }
      }
    });
  }

  function createBiCouponMixChartFallback(ctx) {
    new Chart(ctx, {
      type: 'doughnut',
      data: {
        labels: biChartPayload.couponMix.labels,
        datasets: [{
          data: biChartPayload.couponMix.revenue,
          backgroundColor: ['#f59e0b', '#0ea5a4'],
          borderColor: '#fff',
          borderWidth: 4
        }]
      },
      options: {
        maintainAspectRatio: false,
        legend: { position: 'bottom' },
        cutoutPercentage: 68,
        tooltips: {
          callbacks: {
            label: function(tooltipItem, data) {
              const value = data.datasets[tooltipItem.datasetIndex].data[tooltipItem.index] || 0;
              const orders = biChartPayload.couponMix.orders[tooltipItem.index] || 0;
              return data.labels[tooltipItem.index] + ': ' + biFormatVndFallback(value) + ' / ' + orders + ' đơn';
            }
          }
        }
      }
    });
  }

  function createBiBasketMixChartFallback(ctx) {
    new Chart(ctx, {
      type: 'bar',
      data: {
        labels: biChartPayload.basketMix.labels,
        datasets: [{
          label: 'Doanh thu',
          data: biChartPayload.basketMix.revenue,
          backgroundColor: ['#bfdbfe', '#93c5fd', '#60a5fa', '#2563eb'],
          borderRadius: 10,
          yAxisID: 'revenue-axis'
        }, {
          label: 'Đơn hàng',
          data: biChartPayload.basketMix.orders,
          type: 'line',
          fill: false,
          borderColor: '#0f766e',
          backgroundColor: '#0f766e',
          pointBackgroundColor: '#fff',
          pointBorderColor: '#0f766e',
          pointBorderWidth: 2,
          borderWidth: 3,
          yAxisID: 'order-axis'
        }]
      },
      options: {
        maintainAspectRatio: false,
        legend: { position: 'bottom' },
        scales: {
          xAxes: [{ gridLines: { display: false, drawBorder: false } }],
          yAxes: [{
            id: 'revenue-axis',
            position: 'left',
            ticks: {
              beginAtZero: true,
              callback: function(value) { return biFormatVndFallback(value); }
            },
            gridLines: { color: 'rgba(226, 232, 240, 0.8)', drawBorder: false }
          }, {
            id: 'order-axis',
            position: 'right',
            ticks: { beginAtZero: true, precision: 0 },
            gridLines: { display: false, drawBorder: false }
          }]
        },
        tooltips: {
          callbacks: {
            label: function(tooltipItem, data) {
              const dataset = data.datasets[tooltipItem.datasetIndex];
              if (dataset.yAxisID === 'revenue-axis') {
                return dataset.label + ': ' + biFormatVndFallback(tooltipItem.yLabel);
              }

              return dataset.label + ': ' + new Intl.NumberFormat('vi-VN').format(tooltipItem.yLabel);
            }
          }
        }
      }
    });
  }

  function buildBiFallbackChart(canvasId) {
    const ctx = document.getElementById(canvasId);
    if (!ctx) {
      return;
    }

    syncBiChartHeader(ctx);

    if (ctx.dataset.chartMode === 'customerSegments') {
      createBiCustomerSegmentChartFallback(ctx);
      return;
    }

    if (ctx.dataset.chartMode === 'couponMix') {
      createBiCouponMixChartFallback(ctx);
      return;
    }

    if (ctx.dataset.chartMode === 'basketMix') {
      createBiBasketMixChartFallback(ctx);
      return;
    }

    createBiPaymentMixChartFallback(ctx);
  }

  buildBiFallbackChart('biPrimaryChart');
  buildBiFallbackChart('biSecondaryChart');
</script>
@endpush
