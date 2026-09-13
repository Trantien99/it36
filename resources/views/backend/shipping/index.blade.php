@extends('backend.layouts.master')

@section('title', 'Web bán tai nghe || Quản lý phí giao hàng')

@push('styles')
  <link href="{{ asset('backend/vendor/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.css" />
  <style>
    :root { --ship-ink:#17324d; --ship-sky:#2563eb; --ship-mint:#059669; --ship-amber:#d97706; --ship-border:#e2e8f0; --ship-soft:#f8fbff; --ship-shadow:0 18px 40px rgba(23,50,77,.08); }
    .ship-page{padding-bottom:2rem}.ship-hero,.ship-card,.ship-panel{border:0;border-radius:1.25rem;box-shadow:var(--ship-shadow)}.ship-hero{background:linear-gradient(135deg,#17324d 0%,#255f73 56%,#0f9d94 100%);color:#fff;padding:1.75rem;position:relative;overflow:hidden}.ship-hero:after{content:'';position:absolute;right:-4rem;top:-4rem;width:14rem;height:14rem;border-radius:999px;background:rgba(255,255,255,.08)}.ship-copy,.ship-focus{position:relative;z-index:1}.ship-kicker{font-size:.78rem;font-weight:800;letter-spacing:.18em;text-transform:uppercase;margin-bottom:.8rem}.ship-hero h1{font-size:2rem;font-weight:800;line-height:1.12;margin-bottom:.9rem;max-width:40rem}.ship-hero p{color:rgba(255,255,255,.84);max-width:42rem;margin-bottom:0}.ship-actions-top{display:flex;flex-wrap:wrap;gap:.75rem;margin-top:1.25rem}.ship-actions-top .btn,.ship-filter{border-radius:999px;font-weight:700}.ship-focus{background:rgba(255,255,255,.12);border:1px solid rgba(255,255,255,.14);border-radius:1rem;height:100%;padding:1.15rem}.ship-focus-label{font-size:.76rem;font-weight:800;letter-spacing:.08em;text-transform:uppercase;color:rgba(255,255,255,.72)}.ship-focus-title{font-size:1.3rem;font-weight:800;margin:.3rem 0}.ship-focus-sub{font-size:.9rem;color:rgba(255,255,255,.84)}.ship-focus-grid{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:.75rem;margin-top:1rem}.ship-focus-box{background:rgba(255,255,255,.08);border-radius:.95rem;padding:.85rem .95rem}.ship-focus-box span{display:block;font-size:.75rem;color:rgba(255,255,255,.72);text-transform:uppercase}.ship-focus-box strong{display:block;font-size:1.1rem;margin-top:.3rem}.ship-card{color:#fff;height:100%;overflow:hidden;position:relative}.ship-card:after{content:'';position:absolute;right:-1.5rem;top:-1.5rem;width:6.5rem;height:6.5rem;border-radius:999px;background:rgba(255,255,255,.08)}.ship-card .card-body{position:relative;z-index:1}.ship-card-primary{background:linear-gradient(140deg,#1d4ed8 0%,#2563eb 100%)}.ship-card-success{background:linear-gradient(140deg,#047857 0%,#059669 100%)}.ship-card-warning{background:linear-gradient(140deg,#b45309 0%,#d97706 100%)}.ship-card-dark{background:linear-gradient(140deg,#334155 0%,#475569 100%)}.ship-label{font-size:.76rem;font-weight:800;letter-spacing:.08em;text-transform:uppercase;margin-bottom:.7rem}.ship-value{font-size:1.8rem;font-weight:800;line-height:1.1;margin-bottom:.55rem}.ship-text{font-size:.88rem;opacity:.86;margin-bottom:.8rem}.ship-chip,.ship-badge,.ship-pill,.ship-action{display:inline-flex;align-items:center;font-weight:700}.ship-chip{background:rgba(255,255,255,.15);border-radius:999px;padding:.35rem .7rem;font-size:.78rem}.ship-panel{background:#fff;overflow:hidden}.ship-panel .card-header{background:#fff;border-bottom:1px solid var(--ship-border);padding:1.35rem 1.45rem 1rem}.ship-panel .card-body{padding:1.35rem 1.45rem 1.45rem}.ship-panel-title{font-size:1.08rem;font-weight:800;color:var(--ship-ink)}.ship-panel-sub{font-size:.9rem;color:#64748b;max-width:44rem;margin-bottom:0}.ship-meta{display:flex;flex-wrap:wrap;gap:.6rem;justify-content:flex-end;margin-top:1rem}.ship-badge{gap:.35rem;padding:.45rem .8rem;border-radius:999px;border:1px solid var(--ship-border);background:var(--ship-soft);font-size:.8rem;color:var(--ship-ink)}.ship-toolbar{display:flex;flex-wrap:wrap;justify-content:space-between;align-items:center;gap:1rem;margin-top:1.1rem}.ship-filters{display:flex;flex-wrap:wrap;gap:.65rem}.ship-filter{background:#fff;border:1px solid var(--ship-border);color:#475569;padding:.65rem .95rem;display:inline-flex;gap:.45rem;align-items:center;font-size:.84rem}.ship-filter span{background:#f1f5f9;border-radius:999px;padding:.12rem .45rem;min-width:1.7rem;text-align:center}.ship-filter.active{background:linear-gradient(135deg,#17324d 0%,#2563eb 100%);border-color:transparent;color:#fff}.ship-filter.active span{background:rgba(255,255,255,.14);color:#fff}.ship-search{position:relative;width:min(100%,22rem)}.ship-search i{position:absolute;left:1rem;top:50%;transform:translateY(-50%);color:#64748b}.ship-search .form-control{height:3rem;border-radius:999px;border:1px solid var(--ship-border);padding-left:2.7rem;box-shadow:none}.ship-search .form-control:focus{border-color:rgba(37,99,235,.45);box-shadow:0 0 0 .2rem rgba(37,99,235,.12)}.ship-table thead th{border-top:0;border-bottom:1px solid var(--ship-border);font-size:.76rem;font-weight:800;letter-spacing:.08em;text-transform:uppercase;color:#718096;padding:1rem}.ship-table tbody td{border-color:var(--ship-border);padding:1rem;vertical-align:top}.ship-table tbody tr:hover{background:#fbfdff}.ship-title{font-size:.96rem;font-weight:800;color:var(--ship-ink);margin-bottom:.25rem}.ship-subtext{font-size:.84rem;color:#64748b;line-height:1.55}.ship-pill-row,.ship-row-actions{display:flex;flex-wrap:wrap;gap:.5rem}.ship-pill{border-radius:999px;padding:.46rem .8rem;font-size:.78rem;line-height:1}.ship-price-free{background:rgba(5,150,105,.14);color:#047857}.ship-price-paid{background:rgba(37,99,235,.14);color:#1d4ed8}.ship-usage-live{background:rgba(37,99,235,.14);color:#1d4ed8}.ship-usage-idle{background:rgba(217,119,6,.16);color:#b45309}.ship-status-active{background:rgba(16,185,129,.14);color:#047857}.ship-status-inactive{background:rgba(148,163,184,.18);color:#475569}.ship-action{justify-content:center;gap:.4rem;min-width:5rem;padding:.66rem .85rem;border-radius:.85rem;text-decoration:none;font-size:.82rem}.ship-action:hover,.ship-action:focus{text-decoration:none}.ship-action-edit{background:rgba(37,99,235,.14);color:#1d4ed8}.ship-action-delete{background:rgba(239,68,68,.14);color:#b91c1c;border:0}.ship-empty{border:1px dashed var(--ship-border);border-radius:1rem;padding:2rem 1.5rem;text-align:center;background:linear-gradient(180deg,#fff 0%,#f8fbff 100%)}.ship-empty-icon{width:4rem;height:4rem;border-radius:999px;display:inline-flex;align-items:center;justify-content:center;background:rgba(37,99,235,.1);color:var(--ship-sky);font-size:1.35rem;margin-bottom:1rem}.ship-pagination{margin-top:1.25rem}.ship-pagination .pagination{justify-content:flex-end;margin-bottom:0}div.dataTables_wrapper div.dataTables_filter,div.dataTables_wrapper div.dataTables_length,div.dataTables_wrapper div.dataTables_info,div.dataTables_wrapper div.dataTables_paginate{display:none}table.dataTable{border-collapse:collapse!important;margin-top:0!important;width:100%!important}@media (max-width:991.98px){.ship-hero h1{font-size:1.65rem}.ship-meta{justify-content:flex-start}}@media (max-width:767.98px){.ship-focus-grid{grid-template-columns:1fr}.ship-search{width:100%}.ship-pagination .pagination{justify-content:center}}
  </style>
@endpush

@section('main-content')
  @php
    $focusShipping = ($mostUsedShipping && $mostUsedShipping->orders_count > 0) ? $mostUsedShipping : $latestShipping;
  @endphp
  <div class="container-fluid ship-page">
    @include('backend.layouts.notification')

    <div class="ship-hero mb-4">
      <div class="row align-items-stretch">
        <div class="col-xl-7 mb-4 mb-xl-0">
          <div class="ship-copy">
            <div class="ship-kicker">Bảng Điều Phối Giao Hàng</div>
            <h1>Quản lý phí giao hàng rõ hơn, biết nhanh loại nào đang dùng nhiều và mức phí hiện hành.</h1>
            <p>Trang này giúp admin theo dõi các phương thức giao hàng, tách rõ nhóm miễn phí và có phí, đồng thời kiểm soát mức độ sử dụng của từng lựa chọn vận chuyển.</p>
            <div class="ship-actions-top">
              <a href="{{ route('shipping.create') }}" class="btn btn-light btn-sm"><i class="fas fa-plus mr-1"></i> Thêm phí giao hàng</a>
              <button type="button" id="shippingQuickRefresh" class="btn btn-outline-light btn-sm"><i class="fas fa-sync-alt mr-1"></i> Làm mới</button>
            </div>
          </div>
        </div>
        <div class="col-xl-5">
          <div class="ship-focus">
            <div class="ship-focus-label">Tuyến giao hàng nổi bật</div>
            @if ($focusShipping)
              <div class="ship-focus-title">{{ $focusShipping->type }}</div>
              <div class="ship-focus-sub">
                {{ (float) $focusShipping->price > 0 ? 'Mức phí ' . number_format((float) $focusShipping->price, 0, ',', '.') . 'đ' : 'Miễn phí giao hàng' }}
                @if ($focusShipping->orders_count > 0)
                  - đã xuất hiện trong {{ number_format($focusShipping->orders_count, 0, ',', '.') }} đơn hàng.
                @elseif ($focusShipping->created_at)
                  - tạo {{ $focusShipping->created_at->diffForHumans() }}.
                @endif
              </div>
            @else
              <div class="ship-focus-title">Chưa có phương thức giao hàng</div>
              <div class="ship-focus-sub">Thông tin nổi bật sẽ xuất hiện tại đây khi bạn thêm dữ liệu phí giao hàng đầu tiên.</div>
            @endif
            <div class="ship-focus-grid">
              <div class="ship-focus-box"><span>Đang hoạt động</span><strong>{{ number_format($activeShippings, 0, ',', '.') }}</strong></div>
              <div class="ship-focus-box"><span>Miễn phí</span><strong>{{ number_format($freeShippings, 0, ',', '.') }}</strong></div>
              <div class="ship-focus-box"><span>Đã dùng</span><strong>{{ number_format($usedShippings, 0, ',', '.') }}</strong></div>
              <div class="ship-focus-box"><span>Phí cao nhất</span><strong>{{ number_format($highestShippingPrice, 0, ',', '.') }}đ</strong></div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="row">
      <div class="col-xl-3 col-md-6 mb-4">
        <div class="card ship-card ship-card-primary"><div class="card-body"><div class="ship-label">Tổng tuyến giao hàng</div><div class="ship-value">{{ number_format($totalShippings, 0, ',', '.') }}</div><div class="ship-text">Tổng số cấu hình phí giao hàng đang có trong hệ thống.</div><span class="ship-chip">{{ number_format($inactiveShippings, 0, ',', '.') }} tuyến tạm ẩn</span></div></div>
      </div>
      <div class="col-xl-3 col-md-6 mb-4">
        <div class="card ship-card ship-card-success"><div class="card-body"><div class="ship-label">Đang hoạt động</div><div class="ship-value">{{ number_format($activeShippings, 0, ',', '.') }}</div><div class="ship-text">Những lựa chọn vận chuyển đang sẵn sàng áp dụng khi đặt hàng.</div><span class="ship-chip">{{ number_format($usedShippings, 0, ',', '.') }} tuyến đã có đơn</span></div></div>
      </div>
      <div class="col-xl-3 col-md-6 mb-4">
        <div class="card ship-card ship-card-warning"><div class="card-body"><div class="ship-label">Miễn phí giao hàng</div><div class="ship-value">{{ number_format($freeShippings, 0, ',', '.') }}</div><div class="ship-text">Nhóm cấu hình không phát sinh phí giao hàng cho khách.</div><span class="ship-chip">{{ number_format($paidShippings, 0, ',', '.') }} tuyến có phí</span></div></div>
      </div>
      <div class="col-xl-3 col-md-6 mb-4">
        <div class="card ship-card ship-card-dark"><div class="card-body"><div class="ship-label">Phí trung bình</div><div class="ship-value">{{ number_format($averageShippingPrice, 0, ',', '.') }}đ</div><div class="ship-text">Mức phí giao hàng trung bình trên toàn bộ cấu hình hiện có.</div><span class="ship-chip">{{ number_format($unusedShippings, 0, ',', '.') }} tuyến chưa dùng</span></div></div>
      </div>
    </div>

    <div class="card ship-panel">
      <div class="card-header">
        <div class="row align-items-lg-center">
          <div class="col-lg-8">
            <div class="ship-panel-title">Danh sách phí giao hàng</div>
            <p class="ship-panel-sub">Tìm nhanh theo tên tuyến giao hàng, lọc theo trạng thái, loại miễn phí hoặc các tuyến đã được dùng trong đơn hàng để quản trị vận chuyển gọn hơn.</p>
          </div>
          <div class="col-lg-4">
            <div class="ship-meta">
              <span class="ship-badge"><i class="fas fa-shipping-fast"></i> Hiển thị {{ number_format($shippings->count(), 0, ',', '.') }} / {{ number_format($totalShippings, 0, ',', '.') }} tuyến</span>
              <span class="ship-badge"><i class="fas fa-receipt"></i> {{ number_format($usedShippings, 0, ',', '.') }} tuyến đã có đơn</span>
            </div>
          </div>
        </div>
        <div class="ship-toolbar">
          <div class="ship-filters" role="group" aria-label="Lọc phí giao hàng">
            <button type="button" class="ship-filter active" data-filter="all">Tất cả <span>{{ number_format($totalShippings, 0, ',', '.') }}</span></button>
            <button type="button" class="ship-filter" data-filter="active">Hoạt động <span>{{ number_format($activeShippings, 0, ',', '.') }}</span></button>
            <button type="button" class="ship-filter" data-filter="inactive">Tạm ẩn <span>{{ number_format($inactiveShippings, 0, ',', '.') }}</span></button>
            <button type="button" class="ship-filter" data-filter="free">Miễn phí <span>{{ number_format($freeShippings, 0, ',', '.') }}</span></button>
            <button type="button" class="ship-filter" data-filter="paid">Có phí <span>{{ number_format($paidShippings, 0, ',', '.') }}</span></button>
            <button type="button" class="ship-filter" data-filter="used">Đã dùng <span>{{ number_format($usedShippings, 0, ',', '.') }}</span></button>
          </div>
          <div class="ship-search">
            <i class="fas fa-search"></i>
            <input type="text" id="shippingSearchInput" class="form-control" placeholder="Tìm theo tên tuyến giao hàng...">
          </div>
        </div>
      </div>
      <div class="card-body">
        @if ($shippings->count())
          <div class="table-responsive">
            <table class="table ship-table" id="shipping-dataTable" width="100%" cellspacing="0">
              <thead>
                <tr>
                  <th>Phương thức</th>
                  <th>Giá</th>
                  <th>Mức sử dụng</th>
                  <th>Trạng thái</th>
                  <th>Thời gian</th>
                  <th>Thao tác</th>
                </tr>
              </thead>
              <tbody>
                @foreach ($shippings as $shipping)
                  @php
                    $shippingPrice = (float) $shipping->price;
                    $priceType = $shippingPrice > 0 ? 'paid' : 'free';
                    $usageType = $shipping->orders_count > 0 ? 'used' : 'unused';
                  @endphp
                  <tr data-shipping-status="{{ $shipping->status }}" data-shipping-price="{{ $priceType }}" data-shipping-usage="{{ $usageType }}">
                    <td>
                      <div class="ship-title">{{ $shipping->type }}</div>
                      <div class="ship-subtext">#{{ $shipping->id }}@if ($shipping->created_at) - tạo {{ $shipping->created_at->diffForHumans() }}@endif</div>
                    </td>
                    <td>
                      <div class="ship-pill-row mb-2"><span class="ship-pill {{ $priceType === 'free' ? 'ship-price-free' : 'ship-price-paid' }}">{{ $priceType === 'free' ? 'Miễn phí' : number_format($shippingPrice, 0, ',', '.') . 'đ' }}</span></div>
                      <div class="ship-subtext">{{ $priceType === 'free' ? 'Không cộng thêm phí giao hàng cho đơn hàng.' : 'Áp dụng mức phí giao hàng cố định cho tuyến này.' }}</div>
                    </td>
                    <td>
                      <div class="ship-pill-row mb-2"><span class="ship-pill {{ $usageType === 'used' ? 'ship-usage-live' : 'ship-usage-idle' }}">{{ number_format($shipping->orders_count, 0, ',', '.') }} đơn hàng</span></div>
                      <div class="ship-subtext">{{ $usageType === 'used' ? 'Đã từng được khách chọn trong đơn hàng.' : 'Chưa xuất hiện trong đơn hàng nào.' }}</div>
                    </td>
                    <td>
                      <div class="ship-pill-row mb-2"><span class="ship-pill {{ $shipping->status === 'active' ? 'ship-status-active' : 'ship-status-inactive' }}">{{ $shipping->status === 'active' ? 'Đang hoạt động' : 'Tạm ẩn' }}</span></div>
                      <div class="ship-subtext">{{ $shipping->status === 'active' ? 'Có thể áp dụng ở bước checkout.' : 'Đang tạm dừng khỏi luồng đặt hàng.' }}</div>
                    </td>
                    <td>
                      <div class="ship-title">{{ $shipping->created_at ? $shipping->created_at->format('d/m/Y') : 'Chưa rõ' }}</div>
                      <div class="ship-subtext">{{ $shipping->created_at ? 'Cập nhật ' . $shipping->created_at->diffForHumans() : 'Chưa có dữ liệu thời gian.' }}</div>
                    </td>
                    <td>
                      <div class="ship-row-actions">
                        <a href="{{ route('shipping.edit', $shipping->id) }}" class="ship-action ship-action-edit" data-toggle="tooltip" title="Chỉnh sửa phí giao hàng"><i class="fas fa-pen"></i> <span>Sửa</span></a>
                        <form method="POST" action="{{ route('shipping.destroy', [$shipping->id]) }}">
                          @csrf
                          @method('delete')
                          <button class="ship-action ship-action-delete dltBtn" data-id="{{ $shipping->id }}" data-toggle="tooltip" title="Xóa phí giao hàng"><i class="fas fa-trash-alt"></i> <span>Xóa</span></button>
                        </form>
                      </div>
                    </td>
                  </tr>
                @endforeach
              </tbody>
            </table>
          </div>
          <div class="ship-pagination">{{ $shippings->links() }}</div>
        @else
          <div class="ship-empty">
            <div class="ship-empty-icon"><i class="fas fa-shipping-fast"></i></div>
            <h3>Chưa có phí giao hàng nào</h3>
            <p class="text-muted mb-4">Hãy tạo phương thức giao hàng đầu tiên để bắt đầu cấu hình vận chuyển cho cửa hàng.</p>
            <a href="{{ route('shipping.create') }}" class="btn btn-primary btn-sm"><i class="fas fa-plus mr-1"></i> Thêm phí giao hàng</a>
          </div>
        @endif
      </div>
    </div>
  </div>
@endsection

@push('scripts')
  <script src="{{ asset('backend/vendor/datatables/jquery.dataTables.min.js') }}"></script>
  <script src="{{ asset('backend/vendor/datatables/dataTables.bootstrap4.min.js') }}"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
  <script>
    $(function () {
      const $table = $('#shipping-dataTable');
      const shippingFilter = { value: 'all' };

      $.fn.dataTable.ext.search.push(function (settings, data, dataIndex) {
        if (settings.nTable.id !== 'shipping-dataTable') return true;
        if (shippingFilter.value === 'all') return true;
        const rowNode = settings.aoData[dataIndex] && settings.aoData[dataIndex].nTr;
        if (!rowNode) return true;
        const status = rowNode.getAttribute('data-shipping-status');
        const price = rowNode.getAttribute('data-shipping-price');
        const usage = rowNode.getAttribute('data-shipping-usage');
        if (shippingFilter.value === 'free' || shippingFilter.value === 'paid') return price === shippingFilter.value;
        if (shippingFilter.value === 'used') return usage === 'used';
        return status === shippingFilter.value;
      });

      if ($table.length) {
        const shippingTable = $table.DataTable({
          autoWidth: false,
          info: false,
          language: { emptyTable: 'Chưa có tuyến giao hàng nào.', zeroRecords: 'Không tìm thấy tuyến giao hàng phù hợp trên trang này.' },
          lengthChange: false,
          order: [],
          paging: false,
          searching: true,
          columnDefs: [{ orderable: false, targets: [3, 5] }]
        });

        $('#shippingSearchInput').on('keyup', function () { shippingTable.search(this.value).draw(); });
        $('.ship-filter').on('click', function () {
          const $chip = $(this);
          shippingFilter.value = $chip.data('filter');
          $('.ship-filter').removeClass('active');
          $chip.addClass('active');
          shippingTable.draw();
        });
      }

      $('#shippingQuickRefresh').on('click', function () { window.location.reload(); });
      $('[data-toggle="tooltip"]').tooltip();
      $('.dltBtn').click(function (e) {
        const form = $(this).closest('form');
        e.preventDefault();
        swal({
          title: "Bạn có chắc không?",
          text: "Khi xóa sẽ không thể khôi phục dữ liệu!",
          icon: "warning",
          buttons: true,
          dangerMode: true,
        }).then((willDelete) => { if (willDelete) { form.submit(); } else { swal("Dữ liệu an toàn!"); } });
      });
    });
  </script>
@endpush
