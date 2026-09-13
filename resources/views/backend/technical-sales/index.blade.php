@extends('backend.layouts.master')
@section('title', 'Quản trị || Hỗ trợ bán hàng')

@push('styles')
<style>
  .ts-page{padding-bottom:2rem}
  .ts-hero{background:linear-gradient(135deg,#7c2d12 0%,#c2410c 50%,#0f766e 100%);border-radius:1.2rem;color:#fff;padding:1.6rem;position:relative;overflow:hidden;box-shadow:0 20px 45px rgba(124,45,18,.16)}
  .ts-hero:after{content:'';position:absolute;right:-3rem;top:-3rem;width:10rem;height:10rem;border-radius:999px;background:rgba(255,255,255,.08)}
  .ts-kicker{font-size:.78rem;font-weight:800;letter-spacing:.18em;text-transform:uppercase;margin-bottom:.85rem}
  .ts-hero h1{font-size:2rem;font-weight:800;line-height:1.1;margin-bottom:.8rem}
  .ts-hero p{max-width:46rem;color:rgba(255,255,255,.84);margin-bottom:0}
  .ts-range{display:flex;flex-wrap:wrap;gap:.6rem;justify-content:flex-end}
  .ts-range a{display:inline-flex;padding:.62rem .95rem;border-radius:999px;font-weight:700;font-size:.84rem;text-decoration:none;color:#fff;background:rgba(255,255,255,.13);border:1px solid rgba(255,255,255,.16)}
  .ts-range a.active{background:#fff;color:#9a3412}
  .ts-chip-row{display:flex;flex-wrap:wrap;gap:.65rem;margin-top:1rem}
  .ts-chip{display:inline-flex;align-items:center;gap:.45rem;background:rgba(255,255,255,.14);border-radius:999px;padding:.42rem .78rem;font-size:.82rem;font-weight:700}
  .ts-stat,.ts-panel{border:0;border-radius:1rem;box-shadow:0 18px 40px rgba(15,23,42,.08)}
  .ts-stat{height:100%;overflow:hidden;position:relative;color:#fff}
  .ts-stat:after{content:'';position:absolute;right:-1.3rem;top:-1.3rem;width:5.8rem;height:5.8rem;border-radius:999px;background:rgba(255,255,255,.08)}
  .ts-stat .card-body{position:relative;z-index:1}
  .ts-stat.t1{background:linear-gradient(140deg,#c2410c 0%,#ea580c 100%)}
  .ts-stat.t2{background:linear-gradient(140deg,#0f766e 0%,#14b8a6 100%)}
  .ts-stat.t3{background:linear-gradient(140deg,#1d4ed8 0%,#2563eb 100%)}
  .ts-stat.t4{background:linear-gradient(140deg,#7c3aed 0%,#8b5cf6 100%)}
  .ts-stat-label{font-size:.8rem;font-weight:800;letter-spacing:.08em;text-transform:uppercase;margin-bottom:.75rem;opacity:.88}
  .ts-stat-value{font-size:1.85rem;font-weight:800;line-height:1.08;margin-bottom:.55rem}
  .ts-stat-note{font-size:.9rem;opacity:.84;margin-bottom:0}
  .ts-panel .card-header{background:#fff;border-bottom:1px solid #e2e8f0;padding:1.1rem 1.25rem}
  .ts-panel .card-body{padding:1.25rem}
  .ts-title{font-size:1.02rem;font-weight:800;color:#0f172a;margin-bottom:.2rem}
  .ts-sub{font-size:.88rem;color:#64748b;margin-bottom:0}
  .ts-canvas{height:320px;position:relative}
  .ts-table thead th{font-size:.76rem;font-weight:800;letter-spacing:.08em;text-transform:uppercase;color:#64748b;border-top:0;border-bottom:1px solid #e2e8f0}
  .ts-table tbody td{vertical-align:middle;border-color:#eef2f7}
  .ts-badge{display:inline-flex;padding:.34rem .72rem;border-radius:999px;font-size:.78rem;font-weight:700}
  .ts-badge.hot{background:rgba(239,68,68,.14);color:#b91c1c}.ts-badge.warm{background:rgba(245,158,11,.16);color:#b45309}.ts-badge.followup{background:rgba(148,163,184,.16);color:#475569}.ts-badge.good{background:rgba(20,184,166,.12);color:#0f766e}.ts-badge.info{background:rgba(37,99,235,.14);color:#1d4ed8}
  .ts-list{display:grid;gap:.85rem}
  .ts-list-item{display:flex;gap:.85rem;border:1px solid #e2e8f0;border-radius:.95rem;padding:1rem;background:linear-gradient(180deg,#fff 0%,#f8fbff 100%)}
  .ts-list-icon{display:inline-flex;align-items:center;justify-content:center;width:2.7rem;height:2.7rem;border-radius:.85rem;background:rgba(234,88,12,.12);color:#c2410c;flex:0 0 2.7rem}
  .ts-quote-summary{border:1px solid #e2e8f0;border-radius:1rem;padding:1rem;background:linear-gradient(180deg,#fff 0%,#f8fbff 100%)}
  @media (max-width:991.98px){.ts-range{justify-content:flex-start;margin-top:1rem}.ts-hero h1{font-size:1.7rem}}
</style>
@endpush

@section('main-content')
@php
  $rangeOptions = [30 => '30D', 90 => '90D', 180 => '180D'];
  $themes = ['t1', 't2', 't3', 't4'];
  $pickTsChart = function (array $candidates, array $exclude = []) {
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

  $tsIntentCandidates = [
      [
          'key' => 'intentDistribution',
          'has' => collect($chartData['intentDistribution']['counts'])->sum() > 0,
          'title' => 'Phân bố intent',
          'sub' => 'Phân loại nhu cầu liên hệ để điều phối sales script phù hợp.',
      ],
      [
          'key' => 'leadPriority',
          'has' => collect($chartData['leadPriority']['counts'])->sum() > 0,
          'title' => 'Mức độ ưu tiên lead',
          'sub' => 'Theo dõi tỷ trọng lead nóng, lead ấm và nhóm cần theo dõi thêm.',
      ],
      [
          'key' => 'talkTracks',
          'has' => collect($chartData['talkTracks']['revenue'])->sum() + collect($chartData['talkTracks']['units'])->sum() > 0,
          'title' => 'Sản phẩm cần ưu tiên tư vấn',
          'sub' => 'Biểu đồ dự phòng từ talk track có doanh thu thật để tránh khung chart trống.',
      ],
      [
          'key' => 'bundlePairs',
          'has' => collect($chartData['bundlePairs']['counts'])->sum() + collect($chartData['bundlePairs']['revenue'])->sum() > 0,
          'title' => 'Cơ hội combo',
          'sub' => 'Nhóm sản phẩm thường đi cùng nhau để đẩy combo và cross-sell.',
      ],
  ];

  $tsValueCandidates = [
      [
          'key' => 'priceBands',
          'has' => collect($chartData['priceBands']['revenue'])->sum() + collect($chartData['priceBands']['orders'])->sum() > 0,
          'title' => 'Phân khúc giá trị đơn',
          'sub' => 'Điểm tựa tư vấn cho phân khúc entry, core và premium.',
      ],
      [
          'key' => 'bundlePairs',
          'has' => collect($chartData['bundlePairs']['counts'])->sum() + collect($chartData['bundlePairs']['revenue'])->sum() > 0,
          'title' => 'Hiệu quả combo theo doanh thu',
          'sub' => 'Dùng dữ liệu combo để đọc nhanh hướng chốt đơn khi phân khúc giá trị chưa có số liệu.',
      ],
      [
          'key' => 'talkTracks',
          'has' => collect($chartData['talkTracks']['revenue'])->sum() + collect($chartData['talkTracks']['units'])->sum() > 0,
          'title' => 'Talk track theo doanh thu',
          'sub' => 'Top sản phẩm có thể kéo doanh thu khi đội sales cần một góc nhìn thay thế.',
      ],
      [
          'key' => 'leadPriority',
          'has' => collect($chartData['leadPriority']['counts'])->sum() > 0,
          'title' => 'Mức độ ưu tiên lead',
          'sub' => 'Biểu đồ dự phòng theo lead nóng để vẫn còn nhịp hành động khi chưa có dữ liệu đơn hàng.',
      ],
  ];

  $tsPrimaryChart = $pickTsChart($tsIntentCandidates);
  $tsSecondaryChart = $pickTsChart($tsValueCandidates, [$tsPrimaryChart['key']]);
@endphp
<div class="container-fluid ts-page">
  @include('backend.layouts.notification')

  <div class="ts-hero mb-4">
    <div class="row align-items-center">
      <div class="col-xl-8">
        <div class="ts-kicker">Hỗ trợ bán hàng</div>
        <h1>Điều phối lead, đọc intent và hỗ trợ combo cho đội sales</h1>
        <p>Ưu tiên lead cần xử lý sớm, đọc intent liên hệ, tìm combo hay được mua cùng nhau và nhìn lại phân khúc đơn hàng giá trị.</p>
        <div class="ts-chip-row">
          <span class="ts-chip"><i class="fas fa-calendar-alt"></i> {{ $startDate->format('d/m/Y') }} - {{ $endDate->format('d/m/Y') }}</span>
          <span class="ts-chip"><i class="fas fa-user-clock"></i> {{ $leadQueue->count() }} lead trong hàng đợi</span>
          <span class="ts-chip"><i class="fas fa-layer-group"></i> {{ count($bundlePairs) }} ý tưởng combo đang hoạt động</span>
        </div>
      </div>
      <div class="col-xl-4">
        <div class="ts-range">
          @foreach($rangeOptions as $value => $label)
            <a href="{{ route('admin.technical-sales', ['range' => $value]) }}" class="{{ $range === $value ? 'active' : '' }}">{{ $label }}</a>
          @endforeach
        </div>
      </div>
    </div>
  </div>

  <div class="row">
    @foreach($summaryCards as $index => $card)
      @php
        if ($card['format'] === 'percent') {
          $value = number_format($card['value'], 1, ',', '.').'%';
        } else {
          $value = number_format($card['value'], 0, ',', '.');
        }
      @endphp
      <div class="col-xl-3 col-md-6 mb-4">
        <div class="card ts-stat {{ $themes[$index] }}">
          <div class="card-body">
            <div class="d-flex justify-content-between align-items-start mb-3">
              <div class="ts-stat-label">{{ $card['label'] }}</div>
              <i class="fas {{ $card['icon'] }}"></i>
            </div>
            <div class="ts-stat-value">{{ $value }}</div>
            <p class="ts-stat-note">{{ $card['note'] }}</p>
          </div>
        </div>
      </div>
    @endforeach
  </div>

  <div class="row">
    <div class="col-xl-5 mb-4">
      <div class="card ts-panel h-100">
        <div class="card-header">
          <div class="ts-title">Phân bổ intent</div>
          <p class="ts-sub">Phân loại nhu cầu liên hệ để điều phối sales script phù hợp.</p>
        </div>
        <div class="card-body">
          <div class="ts-canvas">
            <canvas id="tsPrimaryChart" data-chart-mode="{{ $tsPrimaryChart['key'] }}" data-chart-title="{{ $tsPrimaryChart['title'] }}" data-chart-sub="{{ $tsPrimaryChart['sub'] }}"></canvas>
          </div>
        </div>
      </div>
    </div>
    <div class="col-xl-7 mb-4">
      <div class="card ts-panel h-100">
        <div class="card-header">
          <div class="ts-title">Phân khúc giá trị đơn</div>
          <p class="ts-sub">Điểm tựa tư vấn cho phân khúc entry, core và premium.</p>
        </div>
        <div class="card-body">
          <div class="ts-canvas">
            <canvas id="tsSecondaryChart" data-chart-mode="{{ $tsSecondaryChart['key'] }}" data-chart-title="{{ $tsSecondaryChart['title'] }}" data-chart-sub="{{ $tsSecondaryChart['sub'] }}"></canvas>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="row">
    <div class="col-xl-7 mb-4">
      <div class="card ts-panel h-100">
        <div class="card-header">
          <div class="ts-title">Hàng đợi lead ưu tiên</div>
          <p class="ts-sub">Khác với inbox message thường: queue này đã có scoring và intent.</p>
        </div>
        <div class="card-body">
          <div class="table-responsive">
            <table class="table ts-table mb-0">
              <thead>
                <tr>
                  <th>Lead</th>
                  <th>Intent</th>
                  <th>Độ mới</th>
                  <th>Ưu tiên</th>
                </tr>
              </thead>
              <tbody>
                @forelse($leadQueue as $lead)
                  <tr>
                    <td>
                      <div class="font-weight-bold text-dark">{{ $lead['name'] }}</div>
                      <div class="small text-muted">{{ $lead['email'] }}{{ !empty($lead['phone']) ? ' | '.$lead['phone'] : '' }}</div>
                      <div class="small text-muted">{{ $lead['summary'] }}</div>
                    </td>
                    <td><span class="ts-badge info">{{ $lead['intent_label'] }}</span></td>
                    <td>{{ number_format($lead['age_hours'], 0, ',', '.') }} giờ</td>
                    <td><span class="ts-badge {{ $lead['priority_key'] }}">{{ $lead['priority_label'] }} ({{ number_format($lead['priority_score'], 0, ',', '.') }})</span></td>
                  </tr>
                @empty
                  <tr>
                    <td colspan="4" class="text-center text-muted py-4">Không có lead nào trong cửa sổ hiện tại.</td>
                  </tr>
                @endforelse
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
    <div class="col-xl-5 mb-4">
      <div class="card ts-panel h-100">
        <div class="card-header">
          <div class="ts-title">Hành động bán hàng</div>
          <p class="ts-sub">Danh sách hành động để technical sales có thể chốt nhanh hơn.</p>
        </div>
        <div class="card-body">
          <div class="ts-list">
            @foreach($actions as $action)
              <div class="ts-list-item">
                <div class="ts-list-icon"><i class="fas fa-bullseye"></i></div>
                <div>
                  <div class="font-weight-bold text-dark mb-1">{{ $action['title'] }}</div>
                  <div class="text-muted small">{{ $action['description'] }}</div>
                </div>
              </div>
            @endforeach
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="row">
    <div class="col-xl-6 mb-4">
      <div class="card ts-panel h-100">
        <div class="card-header">
          <div class="ts-title">Cơ hội combo</div>
          <p class="ts-sub">Cặp sản phẩm đồng mua để đưa vào script combo và cross-sell.</p>
        </div>
        <div class="card-body">
          <div class="table-responsive">
            <table class="table ts-table mb-0">
              <thead>
                <tr>
                  <th>Combo</th>
                  <th>Số lần</th>
                  <th>Doanh thu combo</th>
                </tr>
              </thead>
              <tbody>
                @forelse($bundlePairs as $bundle)
                  <tr>
                    <td>{{ $bundle['label'] }}</td>
                    <td>{{ number_format($bundle['bundle_count'], 0, ',', '.') }}</td>
                    <td>{{ number_format($bundle['bundle_revenue'], 0, ',', '.') }}đ</td>
                  </tr>
                @empty
                  <tr>
                    <td colspan="3" class="text-center text-muted py-4">Chưa đủ dữ liệu để tạo gợi ý combo.</td>
                  </tr>
                @endforelse
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
    <div class="col-xl-6 mb-4">
      <div class="card ts-panel h-100">
        <div class="card-header">
          <div class="ts-title">Talk track sản phẩm</div>
          <p class="ts-sub">Top sản phẩm có thể đưa vào talking point khi tư vấn kỹ thuật.</p>
        </div>
        <div class="card-body">
          <div class="table-responsive">
            <table class="table ts-table mb-0">
              <thead>
                <tr>
                  <th>Sản phẩm</th>
                  <th>Số lượng</th>
                  <th>Doanh thu</th>
                  <th>Tình trạng</th>
                </tr>
              </thead>
              <tbody>
                @forelse($productTalkTracks as $product)
                  <tr>
                    <td>
                      <div class="font-weight-bold text-dark">{{ $product->title }}</div>
                      <div class="small text-muted">Giá {{ number_format($product->price, 0, ',', '.') }}đ | Tồn kho {{ number_format($product->stock, 0, ',', '.') }}</div>
                    </td>
                    <td>{{ number_format($product->units_sold, 0, ',', '.') }}</td>
                    <td>{{ number_format($product->revenue, 0, ',', '.') }}đ</td>
                    <td><span class="ts-badge info">{{ ucfirst($product->condition) }}</span></td>
                  </tr>
                @empty
                  <tr>
                    <td colspan="4" class="text-center text-muted py-4">Chưa có talk track sản phẩm trong giai đoạn này.</td>
                  </tr>
                @endforelse
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="row mt-4">
    <div class="col-xl-6 mb-4">
      <div class="card ts-panel h-100">
        <div class="card-header">
          <div class="ts-title">Gợi ý trả lời lead</div>
          <p class="ts-sub">Mẫu phản hồi ngắn theo intent để đội sales trả lời nhanh hơn mà vẫn đúng ngữ cảnh.</p>
        </div>
        <div class="card-body">
          <div class="ts-list">
            @foreach($replySuggestions as $reply)
              @php $encodedReply = base64_encode($reply['copy_text']); @endphp
              <div class="ts-list-item">
                <div class="ts-list-icon"><i class="fas fa-reply"></i></div>
                <div class="w-100">
                  <div class="d-flex justify-content-between flex-wrap mb-2">
                    <div>
                      <div class="font-weight-bold text-dark">{{ $reply['name'] }}</div>
                      <div class="small text-muted">{{ $reply['intent_label'] }} | {{ $reply['priority_label'] }}</div>
                    </div>
                    <button type="button" class="btn btn-outline-primary btn-sm ts-copy-reply" data-copy="{{ $encodedReply }}">Sao chép mẫu</button>
                  </div>
                  <div class="small text-uppercase text-muted mb-1">Chủ đề</div>
                  <div class="text-dark mb-2">{{ $reply['subject'] }}</div>
                  <div class="small text-uppercase text-muted mb-1">Nội dung gợi ý</div>
                  <div class="text-muted small mb-2">{{ $reply['body'] }}</div>
                  <div class="small text-uppercase text-muted mb-1">Bước tiếp theo</div>
                  <div class="small text-dark">{{ $reply['next_step'] }}</div>
                </div>
              </div>
            @endforeach
          </div>
        </div>
      </div>
    </div>

    <div class="col-xl-6 mb-4">
      <div class="card ts-panel h-100">
        <div class="card-header">
          <div class="ts-title">Phiếu chốt sale nhanh</div>
          <p class="ts-sub">Chọn sản phẩm, shipping, coupon và combo để ra ngay đề xuất báo giá trên màn hình.</p>
        </div>
        <div class="card-body">
          <div class="form-row">
            <div class="form-group col-md-6">
              <label class="small font-weight-bold text-uppercase text-muted">Combo gợi ý</label>
              <select id="tsQuoteCombo" class="form-control">
                <option value="">Không chọn combo</option>
                @foreach($quoteCombos as $index => $combo)
                  <option value="{{ $index }}">{{ $combo['label'] }} ({{ number_format($combo['bundle_count'], 0, ',', '.') }} lần)</option>
                @endforeach
              </select>
            </div>
            <div class="form-group col-md-6">
              <label class="small font-weight-bold text-uppercase text-muted">Coupon</label>
              <select id="tsQuoteCoupon" class="form-control">
                <option value="">Không áp coupon</option>
                @foreach($quoteCoupons as $coupon)
                  <option value="{{ $coupon->id }}">{{ $coupon->code }} - {{ $coupon->type === 'percent' ? number_format($coupon->value, 0, ',', '.').'%' : number_format($coupon->value, 0, ',', '.').'đ' }}</option>
                @endforeach
              </select>
            </div>
          </div>

          <div class="form-row">
            <div class="form-group col-md-6">
              <label class="small font-weight-bold text-uppercase text-muted">Sản phẩm A</label>
              <select id="tsQuoteProductA" class="form-control">
                <option value="">Chọn sản phẩm</option>
                @foreach($quoteProducts as $product)
                  <option value="{{ $product->id }}">{{ $product->title }} - {{ number_format($product->price, 0, ',', '.') }}đ</option>
                @endforeach
              </select>
            </div>
            <div class="form-group col-md-2">
              <label class="small font-weight-bold text-uppercase text-muted">SL A</label>
              <input id="tsQuoteQtyA" type="number" min="0" value="1" class="form-control">
            </div>
            <div class="form-group col-md-4">
              <label class="small font-weight-bold text-uppercase text-muted">Shipping</label>
              <select id="tsQuoteShipping" class="form-control">
                <option value="">Chọn shipping</option>
                @foreach($quoteShippings as $shipping)
                  <option value="{{ $shipping->id }}">{{ $shipping->type }} - {{ number_format($shipping->price, 0, ',', '.') }}đ</option>
                @endforeach
              </select>
            </div>
          </div>

          <div class="form-row">
            <div class="form-group col-md-6">
              <label class="small font-weight-bold text-uppercase text-muted">Sản phẩm B</label>
              <select id="tsQuoteProductB" class="form-control">
                <option value="">Chọn sản phẩm</option>
                @foreach($quoteProducts as $product)
                  <option value="{{ $product->id }}">{{ $product->title }} - {{ number_format($product->price, 0, ',', '.') }}đ</option>
                @endforeach
              </select>
            </div>
            <div class="form-group col-md-2">
              <label class="small font-weight-bold text-uppercase text-muted">SL B</label>
              <input id="tsQuoteQtyB" type="number" min="0" value="0" class="form-control">
            </div>
          </div>

          <div class="ts-quote-summary">
            <div class="small font-weight-bold text-uppercase text-muted mb-2">Tóm tắt đề xuất</div>
            <div class="d-flex justify-content-between mb-2">
              <span class="text-muted">Tạm tính</span>
              <strong id="tsQuoteSubtotal">0đ</strong>
            </div>
            <div class="d-flex justify-content-between mb-2">
              <span class="text-muted">Giảm giá</span>
              <strong id="tsQuoteDiscount">0đ</strong>
            </div>
            <div class="d-flex justify-content-between mb-2">
              <span class="text-muted">Shipping</span>
              <strong id="tsQuoteShippingCost">0đ</strong>
            </div>
            <div class="d-flex justify-content-between border-top pt-2 mb-3">
              <span class="font-weight-bold text-dark">Tổng báo giá</span>
              <strong class="text-dark" id="tsQuoteTotal">0đ</strong>
            </div>
            <div class="small text-muted mb-1">Gợi ý chốt sale</div>
            <div class="small text-dark" id="tsQuoteHint">Chọn sản phẩm hoặc combo để tạo đề xuất.</div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script>
  const tsChartPayload = @json($chartData);

  function tsFormatVnd(value) {
    return new Intl.NumberFormat('vi-VN').format(Math.round(value)) + 'đ';
  }

  function buildTsIntentChart() {
    const ctx = document.getElementById('tsIntentChart');
    if (!ctx) {
      return;
    }

    new Chart(ctx, {
      type: 'doughnut',
      data: {
        labels: tsChartPayload.intentDistribution.labels,
        datasets: [{
          data: tsChartPayload.intentDistribution.counts,
          backgroundColor: ['#ea580c', '#2563eb', '#0f766e', '#8b5cf6', '#94a3b8'],
          borderColor: '#fff',
          borderWidth: 4
        }]
      },
      options: {
        maintainAspectRatio: false,
        legend: { position: 'bottom' },
        cutoutPercentage: 66
      }
    });
  }

  function buildTsPriceBandChart() {
    const ctx = document.getElementById('tsPriceBandChart');
    if (!ctx) {
      return;
    }

    new Chart(ctx, {
      type: 'bar',
      data: {
        labels: tsChartPayload.priceBands.labels,
        datasets: [{
          label: 'Doanh thu',
          data: tsChartPayload.priceBands.revenue,
          backgroundColor: ['#fdba74', '#fb923c', '#ea580c'],
          borderRadius: 10,
          yAxisID: 'revenue-axis'
        }, {
          label: 'Đơn hàng',
          data: tsChartPayload.priceBands.orders,
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
              callback: function(value) { return tsFormatVnd(value); }
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
                return dataset.label + ': ' + tsFormatVnd(tooltipItem.yLabel);
              }

              return dataset.label + ': ' + new Intl.NumberFormat('vi-VN').format(tooltipItem.yLabel);
            }
          }
        }
      }
    });
  }

  buildTsIntentChart();
  buildTsPriceBandChart();
</script>
<script>
  function tsFormatVndFallback(value) {
    return new Intl.NumberFormat('vi-VN').format(Math.round(value)) + 'đ';
  }

  function syncTsChartHeader(ctx) {
    const card = ctx.closest('.card');
    if (!card) {
      return;
    }

    const title = card.querySelector('.ts-title');
    const sub = card.querySelector('.ts-sub');

    if (title && ctx.dataset.chartTitle) {
      title.textContent = ctx.dataset.chartTitle;
    }

    if (sub && ctx.dataset.chartSub) {
      sub.textContent = ctx.dataset.chartSub;
    }
  }

  function createTsIntentChartFallback(ctx) {
    new Chart(ctx, {
      type: 'doughnut',
      data: {
        labels: tsChartPayload.intentDistribution.labels,
        datasets: [{
          data: tsChartPayload.intentDistribution.counts,
          backgroundColor: ['#ea580c', '#2563eb', '#0f766e', '#8b5cf6', '#94a3b8'],
          borderColor: '#fff',
          borderWidth: 4
        }]
      },
      options: {
        maintainAspectRatio: false,
        legend: { position: 'bottom' },
        cutoutPercentage: 66
      }
    });
  }

  function createTsPriceBandChartFallback(ctx) {
    new Chart(ctx, {
      type: 'bar',
      data: {
        labels: tsChartPayload.priceBands.labels,
        datasets: [{
          label: 'Doanh thu',
          data: tsChartPayload.priceBands.revenue,
          backgroundColor: ['#fdba74', '#fb923c', '#ea580c'],
          borderRadius: 10,
          yAxisID: 'revenue-axis'
        }, {
          label: 'Đơn hàng',
          data: tsChartPayload.priceBands.orders,
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
              callback: function(value) { return tsFormatVndFallback(value); }
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
                return dataset.label + ': ' + tsFormatVndFallback(tooltipItem.yLabel);
              }

              return dataset.label + ': ' + new Intl.NumberFormat('vi-VN').format(tooltipItem.yLabel);
            }
          }
        }
      }
    });
  }

  function createTsLeadPriorityChartFallback(ctx) {
    new Chart(ctx, {
      type: 'bar',
      data: {
        labels: tsChartPayload.leadPriority.labels,
        datasets: [{
          label: 'Lead',
          data: tsChartPayload.leadPriority.counts,
          backgroundColor: ['#dc2626', '#f59e0b', '#94a3b8'],
          borderRadius: 10
        }]
      },
      options: {
        maintainAspectRatio: false,
        legend: { display: false },
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

  function createTsBundleChartFallback(ctx) {
    new Chart(ctx, {
      type: 'horizontalBar',
      data: {
        labels: tsChartPayload.bundlePairs.labels,
        datasets: [{
          label: 'Số lần đồng mua',
          data: tsChartPayload.bundlePairs.counts,
          backgroundColor: '#ea580c'
        }, {
          label: 'Doanh thu combo',
          data: tsChartPayload.bundlePairs.revenue,
          type: 'line',
          fill: false,
          borderColor: '#0f766e',
          backgroundColor: '#0f766e',
          pointBackgroundColor: '#fff',
          pointBorderColor: '#0f766e',
          pointBorderWidth: 2,
          borderWidth: 3,
          yAxisID: 'revenue-axis'
        }]
      },
      options: {
        maintainAspectRatio: false,
        legend: { position: 'bottom' },
        scales: {
          xAxes: [{
            id: 'count-axis',
            ticks: { beginAtZero: true, precision: 0 },
            gridLines: { color: 'rgba(226, 232, 240, 0.8)', drawBorder: false }
          }, {
            id: 'revenue-axis',
            display: false,
            ticks: { beginAtZero: true }
          }],
          yAxes: [{ gridLines: { display: false, drawBorder: false } }]
        },
        tooltips: {
          callbacks: {
            label: function(tooltipItem, data) {
              const dataset = data.datasets[tooltipItem.datasetIndex];
              if (dataset.label === 'Doanh thu combo') {
                return dataset.label + ': ' + tsFormatVndFallback(tooltipItem.xLabel);
              }

              return dataset.label + ': ' + new Intl.NumberFormat('vi-VN').format(tooltipItem.xLabel);
            }
          }
        }
      }
    });
  }

  function createTsTalkTrackChartFallback(ctx) {
    new Chart(ctx, {
      type: 'bar',
      data: {
        labels: tsChartPayload.talkTracks.labels,
        datasets: [{
          label: 'Doanh thu',
          data: tsChartPayload.talkTracks.revenue,
          backgroundColor: ['#0f766e', '#14b8a6', '#2dd4bf', '#99f6e4', '#ccfbf1'],
          borderRadius: 10,
          yAxisID: 'revenue-axis'
        }, {
          label: 'Số lượng',
          data: tsChartPayload.talkTracks.units,
          type: 'line',
          fill: false,
          borderColor: '#1d4ed8',
          backgroundColor: '#1d4ed8',
          pointBackgroundColor: '#fff',
          pointBorderColor: '#1d4ed8',
          pointBorderWidth: 2,
          borderWidth: 3,
          yAxisID: 'unit-axis'
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
              callback: function(value) { return tsFormatVndFallback(value); }
            },
            gridLines: { color: 'rgba(226, 232, 240, 0.8)', drawBorder: false }
          }, {
            id: 'unit-axis',
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
                return dataset.label + ': ' + tsFormatVndFallback(tooltipItem.yLabel);
              }

              return dataset.label + ': ' + new Intl.NumberFormat('vi-VN').format(tooltipItem.yLabel);
            }
          }
        }
      }
    });
  }

  function createTsBundleChartFallback(ctx) {
    new Chart(ctx, {
      type: 'bar',
      data: {
        labels: tsChartPayload.bundlePairs.labels,
        datasets: [{
          label: 'Số lần đồng mua',
          data: tsChartPayload.bundlePairs.counts,
          backgroundColor: '#ea580c',
          borderRadius: 10,
          yAxisID: 'count-axis'
        }, {
          label: 'Doanh thu combo',
          data: tsChartPayload.bundlePairs.revenue,
          type: 'line',
          fill: false,
          borderColor: '#0f766e',
          backgroundColor: '#0f766e',
          pointBackgroundColor: '#fff',
          pointBorderColor: '#0f766e',
          pointBorderWidth: 2,
          borderWidth: 3,
          yAxisID: 'revenue-axis'
        }]
      },
      options: {
        maintainAspectRatio: false,
        legend: { position: 'bottom' },
        scales: {
          xAxes: [{ gridLines: { display: false, drawBorder: false } }],
          yAxes: [{
            id: 'count-axis',
            position: 'left',
            ticks: { beginAtZero: true, precision: 0 },
            gridLines: { color: 'rgba(226, 232, 240, 0.8)', drawBorder: false }
          }, {
            id: 'revenue-axis',
            position: 'right',
            ticks: {
              beginAtZero: true,
              callback: function(value) { return tsFormatVndFallback(value); }
            },
            gridLines: { display: false, drawBorder: false }
          }]
        },
        tooltips: {
          callbacks: {
            label: function(tooltipItem, data) {
              const dataset = data.datasets[tooltipItem.datasetIndex];
              if (dataset.yAxisID === 'revenue-axis') {
                return dataset.label + ': ' + tsFormatVndFallback(tooltipItem.yLabel);
              }

              return dataset.label + ': ' + new Intl.NumberFormat('vi-VN').format(tooltipItem.yLabel);
            }
          }
        }
      }
    });
  }

  function buildTsFallbackChart(canvasId) {
    const ctx = document.getElementById(canvasId);
    if (!ctx) {
      return;
    }

    syncTsChartHeader(ctx);

    if (ctx.dataset.chartMode === 'leadPriority') {
      createTsLeadPriorityChartFallback(ctx);
      return;
    }

    if (ctx.dataset.chartMode === 'talkTracks') {
      createTsTalkTrackChartFallback(ctx);
      return;
    }

    if (ctx.dataset.chartMode === 'bundlePairs') {
      createTsBundleChartFallback(ctx);
      return;
    }

    if (ctx.dataset.chartMode === 'priceBands') {
      createTsPriceBandChartFallback(ctx);
      return;
    }

    createTsIntentChartFallback(ctx);
  }

  buildTsFallbackChart('tsPrimaryChart');
  buildTsFallbackChart('tsSecondaryChart');
</script>
<script>
  const tsWorkbenchPayload = {
    products: @json($quoteProducts),
    shippings: @json($quoteShippings),
    coupons: @json($quoteCoupons),
    combos: @json($quoteCombos)
  };

  function tsFormatWorkbenchVnd(value) {
    return new Intl.NumberFormat('vi-VN').format(Math.round(value || 0)) + 'đ';
  }

  function tsFindProductById(id) {
    return tsWorkbenchPayload.products.find(function(product) {
      return String(product.id) === String(id);
    }) || null;
  }

  function tsFindShippingById(id) {
    return tsWorkbenchPayload.shippings.find(function(shipping) {
      return String(shipping.id) === String(id);
    }) || null;
  }

  function tsFindCouponById(id) {
    return tsWorkbenchPayload.coupons.find(function(coupon) {
      return String(coupon.id) === String(id);
    }) || null;
  }

  function tsDecodeBase64Utf8(value) {
    try {
      return decodeURIComponent(Array.prototype.map.call(atob(value), function(char) {
        return '%' + ('00' + char.charCodeAt(0).toString(16)).slice(-2);
      }).join(''));
    } catch (error) {
      return atob(value);
    }
  }

  function tsApplySelectedCombo() {
    const comboIndex = document.getElementById('tsQuoteCombo');
    if (!comboIndex) {
      return;
    }

    const combo = tsWorkbenchPayload.combos[comboIndex.value];
    if (!combo || !combo.products) {
      tsRecalculateQuote();
      return;
    }

    const firstProduct = tsWorkbenchPayload.products.find(function(product) {
      return product.title === combo.products[0];
    });
    const secondProduct = tsWorkbenchPayload.products.find(function(product) {
      return product.title === combo.products[1];
    });

    if (firstProduct) {
      document.getElementById('tsQuoteProductA').value = firstProduct.id;
      document.getElementById('tsQuoteQtyA').value = 1;
    }

    if (secondProduct) {
      document.getElementById('tsQuoteProductB').value = secondProduct.id;
      document.getElementById('tsQuoteQtyB').value = 1;
    }

    tsRecalculateQuote();
  }

  function tsRecalculateQuote() {
    const productA = tsFindProductById(document.getElementById('tsQuoteProductA').value);
    const productB = tsFindProductById(document.getElementById('tsQuoteProductB').value);
    const shipping = tsFindShippingById(document.getElementById('tsQuoteShipping').value);
    const coupon = tsFindCouponById(document.getElementById('tsQuoteCoupon').value);
    const combo = tsWorkbenchPayload.combos[document.getElementById('tsQuoteCombo').value] || null;
    const qtyA = Math.max(0, parseInt(document.getElementById('tsQuoteQtyA').value || '0', 10));
    const qtyB = Math.max(0, parseInt(document.getElementById('tsQuoteQtyB').value || '0', 10));

    const subtotal = ((productA ? Number(productA.price) : 0) * qtyA) + ((productB ? Number(productB.price) : 0) * qtyB);
    let discount = 0;
    if (coupon) {
      if (coupon.type === 'percent') {
        discount = subtotal * (Number(coupon.value) / 100);
      } else {
        discount = Number(coupon.value);
      }
    }

    discount = Math.min(discount, subtotal);
    const shippingCost = shipping ? Number(shipping.price) : 0;
    const total = Math.max(0, subtotal - discount + shippingCost);

    document.getElementById('tsQuoteSubtotal').textContent = tsFormatWorkbenchVnd(subtotal);
    document.getElementById('tsQuoteDiscount').textContent = tsFormatWorkbenchVnd(discount);
    document.getElementById('tsQuoteShippingCost').textContent = tsFormatWorkbenchVnd(shippingCost);
    document.getElementById('tsQuoteTotal').textContent = tsFormatWorkbenchVnd(total);

    const hints = [];
    if (combo) {
      hints.push('Có thể mở đầu bằng combo ' + combo.label + ' vì cặp này đã đồng mua ' + combo.bundle_count + ' lần.');
    }
    if (coupon) {
      hints.push('Nhắc rõ coupon ' + coupon.code + ' để tạo lý do chốt ngay trong cuộc gọi.');
    }

    const lowStockProducts = [productA, productB].filter(function(product, index) {
      const qty = index === 0 ? qtyA : qtyB;
      return product && Number(product.stock) > 0 && Number(product.stock) <= qty;
    });

    if (lowStockProducts.length) {
      hints.push('Lưu ý tồn kho của ' + lowStockProducts.map(function(product) { return product.title; }).join(', ') + ' đang sát số lượng chào bán.');
    }

    if (!hints.length) {
      hints.push('Chọn thêm combo, coupon hoặc shipping để hệ thống gợi ý cách chốt phù hợp hơn.');
    }

    document.getElementById('tsQuoteHint').textContent = hints.join(' ');
  }

  document.querySelectorAll('.ts-copy-reply').forEach(function(button) {
    button.addEventListener('click', function() {
      const text = tsDecodeBase64Utf8(button.dataset.copy || '');
      if (navigator.clipboard && navigator.clipboard.writeText) {
        navigator.clipboard.writeText(text);
      }
    });
  });

  ['tsQuoteProductA', 'tsQuoteProductB', 'tsQuoteShipping', 'tsQuoteCoupon', 'tsQuoteQtyA', 'tsQuoteQtyB'].forEach(function(id) {
    const element = document.getElementById(id);
    if (element) {
      element.addEventListener('change', tsRecalculateQuote);
      element.addEventListener('input', tsRecalculateQuote);
    }
  });

  const tsComboSelect = document.getElementById('tsQuoteCombo');
  if (tsComboSelect) {
    tsComboSelect.addEventListener('change', tsApplySelectedCombo);
  }

  tsRecalculateQuote();
</script>
@endpush
