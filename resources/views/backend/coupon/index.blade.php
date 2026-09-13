@extends('backend.layouts.master')

@section('title', 'Web bán tai nghe || Quản lý mã giảm giá')

@push('styles')
  <link href="{{ asset('backend/vendor/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.css" />
  <style>
    :root { --coupon-ink:#17324d; --coupon-sky:#2563eb; --coupon-mint:#059669; --coupon-amber:#d97706; --coupon-rose:#dc2626; --coupon-border:#e2e8f0; --coupon-soft:#f8fbff; --coupon-shadow:0 18px 40px rgba(23,50,77,.08); }
    .coupon-page{padding-bottom:2rem}.coupon-hero,.coupon-card,.coupon-panel{border:0;border-radius:1.25rem;box-shadow:var(--coupon-shadow)}.coupon-hero{background:linear-gradient(135deg,#17324d 0%,#255f73 56%,#0f9d94 100%);color:#fff;padding:1.75rem;position:relative;overflow:hidden}.coupon-hero:after{content:'';position:absolute;right:-4rem;top:-4rem;width:14rem;height:14rem;border-radius:999px;background:rgba(255,255,255,.08)}.coupon-copy,.coupon-focus{position:relative;z-index:1}.coupon-kicker{font-size:.78rem;font-weight:800;letter-spacing:.18em;text-transform:uppercase;margin-bottom:.8rem}.coupon-hero h1{font-size:2rem;font-weight:800;line-height:1.12;margin-bottom:.9rem;max-width:40rem}.coupon-hero p{color:rgba(255,255,255,.84);max-width:42rem;margin-bottom:0}.coupon-actions-top{display:flex;flex-wrap:wrap;gap:.75rem;margin-top:1.25rem}.coupon-actions-top .btn,.coupon-filter{border-radius:999px;font-weight:700}.coupon-focus{background:rgba(255,255,255,.12);border:1px solid rgba(255,255,255,.14);border-radius:1rem;height:100%;padding:1.15rem}.coupon-focus-label{font-size:.76rem;font-weight:800;letter-spacing:.08em;text-transform:uppercase;color:rgba(255,255,255,.72)}.coupon-focus-title{font-size:1.3rem;font-weight:800;margin:.3rem 0}.coupon-focus-sub{font-size:.9rem;color:rgba(255,255,255,.84)}.coupon-focus-grid{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:.75rem;margin-top:1rem}.coupon-focus-box{background:rgba(255,255,255,.08);border-radius:.95rem;padding:.85rem .95rem}.coupon-focus-box span{display:block;font-size:.75rem;color:rgba(255,255,255,.72);text-transform:uppercase}.coupon-focus-box strong{display:block;font-size:1.1rem;margin-top:.3rem}.coupon-card{color:#fff;height:100%;overflow:hidden;position:relative}.coupon-card:after{content:'';position:absolute;right:-1.5rem;top:-1.5rem;width:6.5rem;height:6.5rem;border-radius:999px;background:rgba(255,255,255,.08)}.coupon-card .card-body{position:relative;z-index:1}.coupon-card-primary{background:linear-gradient(140deg,#1d4ed8 0%,#2563eb 100%)}.coupon-card-success{background:linear-gradient(140deg,#047857 0%,#059669 100%)}.coupon-card-warning{background:linear-gradient(140deg,#b45309 0%,#d97706 100%)}.coupon-card-dark{background:linear-gradient(140deg,#334155 0%,#475569 100%)}.coupon-label{font-size:.76rem;font-weight:800;letter-spacing:.08em;text-transform:uppercase;margin-bottom:.7rem}.coupon-value{font-size:1.8rem;font-weight:800;line-height:1.1;margin-bottom:.55rem}.coupon-text{font-size:.88rem;opacity:.86;margin-bottom:.8rem}.coupon-chip,.coupon-badge,.coupon-pill,.coupon-action{display:inline-flex;align-items:center;font-weight:700}.coupon-chip{background:rgba(255,255,255,.15);border-radius:999px;padding:.35rem .7rem;font-size:.78rem}.coupon-panel{background:#fff;overflow:hidden}.coupon-panel .card-header{background:#fff;border-bottom:1px solid var(--coupon-border);padding:1.35rem 1.45rem 1rem}.coupon-panel .card-body{padding:1.35rem 1.45rem 1.45rem}.coupon-panel-title{font-size:1.08rem;font-weight:800;color:var(--coupon-ink)}.coupon-panel-sub{font-size:.9rem;color:#64748b;max-width:44rem;margin-bottom:0}.coupon-meta{display:flex;flex-wrap:wrap;gap:.6rem;justify-content:flex-end;margin-top:1rem}.coupon-badge{gap:.35rem;padding:.45rem .8rem;border-radius:999px;border:1px solid var(--coupon-border);background:var(--coupon-soft);font-size:.8rem;color:var(--coupon-ink)}.coupon-toolbar{display:flex;flex-wrap:wrap;justify-content:space-between;align-items:center;gap:1rem;margin-top:1.1rem}.coupon-filters{display:flex;flex-wrap:wrap;gap:.65rem}.coupon-filter{background:#fff;border:1px solid var(--coupon-border);color:#475569;padding:.65rem .95rem;display:inline-flex;gap:.45rem;align-items:center;font-size:.84rem}.coupon-filter span{background:#f1f5f9;border-radius:999px;padding:.12rem .45rem;min-width:1.7rem;text-align:center}.coupon-filter.active{background:linear-gradient(135deg,#17324d 0%,#2563eb 100%);border-color:transparent;color:#fff}.coupon-filter.active span{background:rgba(255,255,255,.14);color:#fff}.coupon-search{position:relative;width:min(100%,22rem)}.coupon-search i{position:absolute;left:1rem;top:50%;transform:translateY(-50%);color:#64748b}.coupon-search .form-control{height:3rem;border-radius:999px;border:1px solid var(--coupon-border);padding-left:2.7rem;box-shadow:none}.coupon-search .form-control:focus{border-color:rgba(37,99,235,.45);box-shadow:0 0 0 .2rem rgba(37,99,235,.12)}.coupon-table thead th{border-top:0;border-bottom:1px solid var(--coupon-border);font-size:.76rem;font-weight:800;letter-spacing:.08em;text-transform:uppercase;color:#718096;padding:1rem}.coupon-table tbody td{border-color:var(--coupon-border);padding:1rem;vertical-align:top}.coupon-table tbody tr:hover{background:#fbfdff}.coupon-title{font-size:.96rem;font-weight:800;color:var(--coupon-ink);margin-bottom:.25rem}.coupon-subtext{font-size:.84rem;color:#64748b;line-height:1.55}.coupon-pill-row,.coupon-row-actions{display:flex;flex-wrap:wrap;gap:.5rem}.coupon-pill{border-radius:999px;padding:.46rem .8rem;font-size:.78rem;line-height:1}.coupon-type-fixed{background:rgba(37,99,235,.14);color:#1d4ed8}.coupon-type-percent{background:rgba(217,119,6,.16);color:#b45309}.coupon-status-active{background:rgba(16,185,129,.14);color:#047857}.coupon-status-inactive{background:rgba(148,163,184,.18);color:#475569}.coupon-value-fixed{background:rgba(5,150,105,.14);color:#047857}.coupon-value-percent{background:rgba(37,99,235,.14);color:#1d4ed8}.coupon-action{justify-content:center;gap:.4rem;min-width:5rem;padding:.66rem .85rem;border-radius:.85rem;text-decoration:none;font-size:.82rem}.coupon-action:hover,.coupon-action:focus{text-decoration:none}.coupon-action-edit{background:rgba(37,99,235,.14);color:#1d4ed8}.coupon-action-delete{background:rgba(239,68,68,.14);color:#b91c1c;border:0}.coupon-empty{border:1px dashed var(--coupon-border);border-radius:1rem;padding:2rem 1.5rem;text-align:center;background:linear-gradient(180deg,#fff 0%,#f8fbff 100%)}.coupon-empty-icon{width:4rem;height:4rem;border-radius:999px;display:inline-flex;align-items:center;justify-content:center;background:rgba(37,99,235,.1);color:var(--coupon-sky);font-size:1.35rem;margin-bottom:1rem}.coupon-pagination{margin-top:1.25rem}.coupon-pagination .pagination{justify-content:flex-end;margin-bottom:0}div.dataTables_wrapper div.dataTables_filter,div.dataTables_wrapper div.dataTables_length,div.dataTables_wrapper div.dataTables_info,div.dataTables_wrapper div.dataTables_paginate{display:none}table.dataTable{border-collapse:collapse!important;margin-top:0!important;width:100%!important}@media (max-width:991.98px){.coupon-hero h1{font-size:1.65rem}.coupon-meta{justify-content:flex-start}}@media (max-width:767.98px){.coupon-focus-grid{grid-template-columns:1fr}.coupon-search{width:100%}.coupon-pagination .pagination{justify-content:center}}
  </style>
@endpush

@section('main-content')
  <div class="container-fluid coupon-page">
    @include('backend.layouts.notification')

    <div class="coupon-hero mb-4">
      <div class="row align-items-stretch">
        <div class="col-xl-7 mb-4 mb-xl-0">
          <div class="coupon-copy">
            <div class="coupon-kicker">Bàn Điều Phối Ưu Đãi</div>
            <h1>Quản lý mã giảm giá rõ ràng hơn, nhìn nhanh loại ưu đãi và trạng thái kích hoạt.</h1>
            <p>Trang này giúp admin theo dõi nhanh kho coupon theo loại tiền mặt hay phần trăm, kiểm tra mã nào đang hoạt động và xử lý danh sách khuyến mãi gọn hơn.</p>
            <div class="coupon-actions-top">
              <a href="{{ route('coupon.create') }}" class="btn btn-light btn-sm"><i class="fas fa-plus mr-1"></i> Thêm mã giảm giá</a>
              <button type="button" id="couponQuickRefresh" class="btn btn-outline-light btn-sm"><i class="fas fa-sync-alt mr-1"></i> Làm mới</button>
            </div>
          </div>
        </div>
        <div class="col-xl-5">
          <div class="coupon-focus">
            <div class="coupon-focus-label">Mã mới nhất</div>
            @if ($latestCoupon)
              <div class="coupon-focus-title">{{ $latestCoupon->code }}</div>
              <div class="coupon-focus-sub">
                {{ $latestCoupon->type === 'fixed' ? 'Giảm trực tiếp ' . number_format($latestCoupon->value, 0, ',', '.') . 'đ' : 'Giảm ' . rtrim(rtrim(number_format($latestCoupon->value, 2, '.', ''), '0'), '.') . '%' }}
                @if ($latestCoupon->created_at)
                  - tạo {{ $latestCoupon->created_at->diffForHumans() }}.
                @endif
              </div>
            @else
              <div class="coupon-focus-title">Chưa có coupon</div>
              <div class="coupon-focus-sub">Thông tin mã giảm giá mới nhất sẽ hiển thị ở đây ngay khi hệ thống có dữ liệu.</div>
            @endif
            <div class="coupon-focus-grid">
              <div class="coupon-focus-box"><span>Đang hoạt động</span><strong>{{ number_format($activeCoupons, 0, ',', '.') }}</strong></div>
              <div class="coupon-focus-box"><span>Tạm ẩn</span><strong>{{ number_format($inactiveCoupons, 0, ',', '.') }}</strong></div>
              <div class="coupon-focus-box"><span>Giảm tiền cao nhất</span><strong>{{ number_format($highestFixedValue, 0, ',', '.') }}đ</strong></div>
              <div class="coupon-focus-box"><span>Phần trăm cao nhất</span><strong>{{ rtrim(rtrim(number_format($highestPercentValue, 2, '.', ''), '0'), '.') }}%</strong></div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="row">
      <div class="col-xl-3 col-md-6 mb-4">
        <div class="card coupon-card coupon-card-primary"><div class="card-body"><div class="coupon-label">Tổng coupon</div><div class="coupon-value">{{ number_format($totalCoupons, 0, ',', '.') }}</div><div class="coupon-text">Tổng số mã giảm giá đang có trong hệ thống khuyến mãi.</div><span class="coupon-chip">{{ number_format($inactiveCoupons, 0, ',', '.') }} mã tạm ẩn</span></div></div>
      </div>
      <div class="col-xl-3 col-md-6 mb-4">
        <div class="card coupon-card coupon-card-success"><div class="card-body"><div class="coupon-label">Đang hoạt động</div><div class="coupon-value">{{ number_format($activeCoupons, 0, ',', '.') }}</div><div class="coupon-text">Những mã đang có thể áp dụng cho giỏ hàng và thanh toán.</div><span class="coupon-chip">{{ number_format($newCouponsThisMonth, 0, ',', '.') }} mã mới tháng này</span></div></div>
      </div>
      <div class="col-xl-3 col-md-6 mb-4">
        <div class="card coupon-card coupon-card-warning"><div class="card-body"><div class="coupon-label">Giảm trực tiếp</div><div class="coupon-value">{{ number_format($fixedCoupons, 0, ',', '.') }}</div><div class="coupon-text">Nhóm coupon giảm số tiền cố định theo giá trị khai báo.</div><span class="coupon-chip">Tối đa {{ number_format($highestFixedValue, 0, ',', '.') }}đ</span></div></div>
      </div>
      <div class="col-xl-3 col-md-6 mb-4">
        <div class="card coupon-card coupon-card-dark"><div class="card-body"><div class="coupon-label">Giảm phần trăm</div><div class="coupon-value">{{ number_format($percentCoupons, 0, ',', '.') }}</div><div class="coupon-text">Nhóm coupon giảm theo tỉ lệ phần trăm trên tổng đơn hàng.</div><span class="coupon-chip">Tối đa {{ rtrim(rtrim(number_format($highestPercentValue, 2, '.', ''), '0'), '.') }}%</span></div></div>
      </div>
    </div>

    <div class="card coupon-panel">
      <div class="card-header">
        <div class="row align-items-lg-center">
          <div class="col-lg-8">
            <div class="coupon-panel-title">Danh sách mã giảm giá</div>
            <p class="coupon-panel-sub">Tìm nhanh theo mã coupon, lọc theo trạng thái hoặc loại giảm giá để dọn dẹp danh sách ưu đãi và kiểm soát chương trình khuyến mãi dễ hơn.</p>
          </div>
          <div class="col-lg-4">
            <div class="coupon-meta">
              <span class="coupon-badge"><i class="fas fa-ticket-alt"></i> Hiển thị {{ number_format($coupons->count(), 0, ',', '.') }} / {{ number_format($totalCoupons, 0, ',', '.') }} mã</span>
              <span class="coupon-badge"><i class="fas fa-bolt"></i> {{ number_format($activeCoupons, 0, ',', '.') }} mã đang hoạt động</span>
            </div>
          </div>
        </div>
        <div class="coupon-toolbar">
          <div class="coupon-filters" role="group" aria-label="Lọc coupon">
            <button type="button" class="coupon-filter active" data-filter="all">Tất cả <span>{{ number_format($totalCoupons, 0, ',', '.') }}</span></button>
            <button type="button" class="coupon-filter" data-filter="active">Active <span>{{ number_format($activeCoupons, 0, ',', '.') }}</span></button>
            <button type="button" class="coupon-filter" data-filter="inactive">Inactive <span>{{ number_format($inactiveCoupons, 0, ',', '.') }}</span></button>
            <button type="button" class="coupon-filter" data-filter="fixed">Giảm tiền <span>{{ number_format($fixedCoupons, 0, ',', '.') }}</span></button>
            <button type="button" class="coupon-filter" data-filter="percent">Phần trăm <span>{{ number_format($percentCoupons, 0, ',', '.') }}</span></button>
          </div>
          <div class="coupon-search">
            <i class="fas fa-search"></i>
            <input type="text" id="couponSearchInput" class="form-control" placeholder="Tìm theo mã coupon...">
          </div>
        </div>
      </div>
      <div class="card-body">
        @if ($coupons->count())
          <div class="table-responsive">
            <table class="table coupon-table" id="coupon-dataTable" width="100%" cellspacing="0">
              <thead>
                <tr>
                  <th>Mã coupon</th>
                  <th>Loại</th>
                  <th>Giá trị</th>
                  <th>Trạng thái</th>
                  <th>Thời gian</th>
                  <th>Thao tác</th>
                </tr>
              </thead>
              <tbody>
                @foreach ($coupons as $coupon)
                  @php
                    $couponValue = $coupon->type === 'fixed'
                        ? number_format($coupon->value, 0, ',', '.') . 'đ'
                        : rtrim(rtrim(number_format($coupon->value, 2, '.', ''), '0'), '.') . '%';
                  @endphp
                  <tr data-coupon-status="{{ $coupon->status }}" data-coupon-type="{{ $coupon->type }}">
                    <td>
                      <div class="coupon-title">{{ $coupon->code }}</div>
                      <div class="coupon-subtext">#{{ $coupon->id }}@if ($coupon->created_at) - tạo {{ $coupon->created_at->diffForHumans() }}@endif</div>
                    </td>
                    <td>
                      <div class="coupon-pill-row mb-2"><span class="coupon-pill {{ $coupon->type === 'fixed' ? 'coupon-type-fixed' : 'coupon-type-percent' }}">{{ $coupon->type === 'fixed' ? 'Giảm trực tiếp' : 'Giảm phần trăm' }}</span></div>
                      <div class="coupon-subtext">{{ $coupon->type === 'fixed' ? 'Phù hợp khi muốn khống chế ngân sách ưu đãi cố định.' : 'Phù hợp cho chiến dịch khuyến mãi theo tỉ lệ đơn hàng.' }}</div>
                    </td>
                    <td>
                      <div class="coupon-pill-row mb-2"><span class="coupon-pill {{ $coupon->type === 'fixed' ? 'coupon-value-fixed' : 'coupon-value-percent' }}">{{ $couponValue }}</span></div>
                      <div class="coupon-subtext">{{ $coupon->type === 'fixed' ? 'Giảm số tiền cố định trên đơn hàng hợp lệ.' : 'Giảm theo phần trăm trên tổng giá trị giỏ hàng.' }}</div>
                    </td>
                    <td>
                      <div class="coupon-pill-row mb-2"><span class="coupon-pill {{ $coupon->status === 'active' ? 'coupon-status-active' : 'coupon-status-inactive' }}">{{ $coupon->status === 'active' ? 'Đang hoạt động' : 'Tạm ẩn' }}</span></div>
                      <div class="coupon-subtext">{{ $coupon->status === 'active' ? 'Có thể áp dụng ngay trong giỏ hàng.' : 'Đang tắt, chưa cho phép sử dụng.' }}</div>
                    </td>
                    <td>
                      <div class="coupon-title">{{ $coupon->created_at ? $coupon->created_at->format('d/m/Y') : 'Chưa rõ' }}</div>
                      <div class="coupon-subtext">{{ $coupon->created_at ? 'Cập nhật ' . $coupon->created_at->diffForHumans() : 'Chưa có dữ liệu thời gian.' }}</div>
                    </td>
                    <td>
                      <div class="coupon-row-actions">
                        <a href="{{ route('coupon.edit', $coupon->id) }}" class="coupon-action coupon-action-edit" data-toggle="tooltip" title="Chỉnh sửa coupon"><i class="fas fa-pen"></i> <span>Sửa</span></a>
                        <form method="POST" action="{{ route('coupon.destroy', [$coupon->id]) }}">
                          @csrf
                          @method('delete')
                          <button class="coupon-action coupon-action-delete dltBtn" data-id="{{ $coupon->id }}" data-toggle="tooltip" title="Xóa coupon"><i class="fas fa-trash-alt"></i> <span>Xóa</span></button>
                        </form>
                      </div>
                    </td>
                  </tr>
                @endforeach
              </tbody>
            </table>
          </div>
          <div class="coupon-pagination">{{ $coupons->links() }}</div>
        @else
          <div class="coupon-empty">
            <div class="coupon-empty-icon"><i class="fas fa-ticket-alt"></i></div>
            <h3>Chưa có mã giảm giá nào</h3>
            <p class="text-muted mb-4">Hãy tạo coupon đầu tiên để bắt đầu triển khai chương trình khuyến mãi cho cửa hàng.</p>
            <a href="{{ route('coupon.create') }}" class="btn btn-primary btn-sm"><i class="fas fa-plus mr-1"></i> Thêm mã giảm giá</a>
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
      const $table = $('#coupon-dataTable');
      const couponFilter = { value: 'all' };

      $.fn.dataTable.ext.search.push(function (settings, data, dataIndex) {
        if (settings.nTable.id !== 'coupon-dataTable') return true;
        if (couponFilter.value === 'all') return true;
        const rowNode = settings.aoData[dataIndex] && settings.aoData[dataIndex].nTr;
        if (!rowNode) return true;
        const status = rowNode.getAttribute('data-coupon-status');
        const type = rowNode.getAttribute('data-coupon-type');
        if (couponFilter.value === 'fixed' || couponFilter.value === 'percent') return type === couponFilter.value;
        return status === couponFilter.value;
      });

      if ($table.length) {
        const couponTable = $table.DataTable({
          autoWidth: false,
          info: false,
          language: { emptyTable: 'Chưa có coupon nào.', zeroRecords: 'Không tìm thấy coupon phù hợp trên trang này.' },
          lengthChange: false,
          order: [],
          paging: false,
          searching: true,
          columnDefs: [{ orderable: false, targets: [3, 5] }]
        });

        $('#couponSearchInput').on('keyup', function () { couponTable.search(this.value).draw(); });
        $('.coupon-filter').on('click', function () {
          const $chip = $(this);
          couponFilter.value = $chip.data('filter');
          $('.coupon-filter').removeClass('active');
          $chip.addClass('active');
          couponTable.draw();
        });
      }

      $('#couponQuickRefresh').on('click', function () { window.location.reload(); });
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
