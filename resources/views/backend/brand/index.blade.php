@extends('backend.layouts.master')

@section('title', 'Web bán tai nghe || Quản lý thương hiệu')

@push('styles')
  <link href="{{ asset('backend/vendor/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.css" />
  <style>
    :root { --brand-ink:#1b2430; --brand-sky:#2563eb; --brand-mint:#059669; --brand-amber:#d97706; --brand-border:#e2e8f0; --brand-soft:#f8fbff; --brand-shadow:0 18px 40px rgba(27,36,48,.08); }
    .brand-page{padding-bottom:2rem}.brand-hero,.brand-card,.brand-panel{border:0;border-radius:1.25rem;box-shadow:var(--brand-shadow)}.brand-hero{background:linear-gradient(135deg,#1b2430 0%,#243b53 56%,#2563eb 100%);color:#fff;padding:1.75rem;position:relative;overflow:hidden}.brand-hero:after{content:'';position:absolute;right:-4rem;top:-4rem;width:14rem;height:14rem;border-radius:999px;background:rgba(255,255,255,.08)}.brand-copy,.brand-focus{position:relative;z-index:1}.brand-kicker{font-size:.78rem;font-weight:800;letter-spacing:.18em;text-transform:uppercase;margin-bottom:.8rem}.brand-hero h1{font-size:2rem;font-weight:800;line-height:1.12;margin-bottom:.9rem;max-width:40rem}.brand-hero p{color:rgba(255,255,255,.84);max-width:42rem;margin-bottom:0}.brand-actions-top{display:flex;flex-wrap:wrap;gap:.75rem;margin-top:1.25rem}.brand-actions-top .btn,.brand-filter{border-radius:999px;font-weight:700}.brand-focus{background:rgba(255,255,255,.12);border:1px solid rgba(255,255,255,.14);border-radius:1rem;height:100%;padding:1.15rem}.brand-focus-label{font-size:.76rem;font-weight:800;letter-spacing:.08em;text-transform:uppercase;color:rgba(255,255,255,.72)}.brand-focus-title{font-size:1.3rem;font-weight:800;margin:.3rem 0}.brand-focus-sub{font-size:.9rem;color:rgba(255,255,255,.84)}.brand-focus-grid{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:.75rem;margin-top:1rem}.brand-focus-box{background:rgba(255,255,255,.08);border-radius:.95rem;padding:.85rem .95rem}.brand-focus-box span{display:block;font-size:.75rem;color:rgba(255,255,255,.72);text-transform:uppercase}.brand-focus-box strong{display:block;font-size:1.1rem;margin-top:.3rem}.brand-card{color:#fff;height:100%;overflow:hidden;position:relative}.brand-card:after{content:'';position:absolute;right:-1.5rem;top:-1.5rem;width:6.5rem;height:6.5rem;border-radius:999px;background:rgba(255,255,255,.08)}.brand-card .card-body{position:relative;z-index:1}.brand-card-primary{background:linear-gradient(140deg,#1d4ed8 0%,#2563eb 100%)}.brand-card-success{background:linear-gradient(140deg,#047857 0%,#059669 100%)}.brand-card-warning{background:linear-gradient(140deg,#b45309 0%,#d97706 100%)}.brand-card-dark{background:linear-gradient(140deg,#334155 0%,#475569 100%)}.brand-label{font-size:.76rem;font-weight:800;letter-spacing:.08em;text-transform:uppercase;margin-bottom:.7rem}.brand-value{font-size:1.8rem;font-weight:800;line-height:1.1;margin-bottom:.55rem}.brand-text{font-size:.88rem;opacity:.86;margin-bottom:.8rem}.brand-chip,.brand-badge,.brand-pill,.brand-action{display:inline-flex;align-items:center;font-weight:700}.brand-chip{background:rgba(255,255,255,.15);border-radius:999px;padding:.35rem .7rem;font-size:.78rem}.brand-panel{background:#fff;overflow:hidden}.brand-panel .card-header{background:#fff;border-bottom:1px solid var(--brand-border);padding:1.35rem 1.45rem 1rem}.brand-panel .card-body{padding:1.35rem 1.45rem 1.45rem}.brand-panel-title{font-size:1.08rem;font-weight:800;color:var(--brand-ink)}.brand-panel-sub{font-size:.9rem;color:#64748b;max-width:44rem;margin-bottom:0}.brand-meta{display:flex;flex-wrap:wrap;gap:.6rem;justify-content:flex-end;margin-top:1rem}.brand-badge{gap:.35rem;padding:.45rem .8rem;border-radius:999px;border:1px solid var(--brand-border);background:var(--brand-soft);font-size:.8rem;color:var(--brand-ink)}.brand-toolbar{display:flex;flex-wrap:wrap;justify-content:space-between;align-items:center;gap:1rem;margin-top:1.1rem}.brand-filters{display:flex;flex-wrap:wrap;gap:.65rem}.brand-filter{background:#fff;border:1px solid var(--brand-border);color:#475569;padding:.65rem .95rem;display:inline-flex;gap:.45rem;align-items:center;font-size:.84rem}.brand-filter span{background:#f1f5f9;border-radius:999px;padding:.12rem .45rem;min-width:1.7rem;text-align:center}.brand-filter.active{background:linear-gradient(135deg,#1b2430 0%,#2563eb 100%);border-color:transparent;color:#fff}.brand-filter.active span{background:rgba(255,255,255,.14);color:#fff}.brand-search{position:relative;width:min(100%,22rem)}.brand-search i{position:absolute;left:1rem;top:50%;transform:translateY(-50%);color:#64748b}.brand-search .form-control{height:3rem;border-radius:999px;border:1px solid var(--brand-border);padding-left:2.7rem;box-shadow:none}.brand-search .form-control:focus{border-color:rgba(37,99,235,.45);box-shadow:0 0 0 .2rem rgba(37,99,235,.12)}.brand-table thead th{border-top:0;border-bottom:1px solid var(--brand-border);font-size:.76rem;font-weight:800;letter-spacing:.08em;text-transform:uppercase;color:#718096;padding:1rem}.brand-table tbody td{border-color:var(--brand-border);padding:1rem;vertical-align:top}.brand-table tbody tr:hover{background:#fbfdff}.brand-title{font-size:.96rem;font-weight:800;color:var(--brand-ink);margin-bottom:.25rem}.brand-subtext{font-size:.84rem;color:#64748b;line-height:1.55}.brand-pill-row,.brand-row-actions{display:flex;flex-wrap:wrap;gap:.5rem}.brand-pill{border-radius:999px;padding:.46rem .8rem;font-size:.78rem;line-height:1}.brand-slug-ready{background:rgba(5,150,105,.14);color:#047857}.brand-slug-empty{background:rgba(220,38,38,.14);color:#b91c1c}.brand-usage-live{background:rgba(37,99,235,.14);color:#1d4ed8}.brand-usage-idle{background:rgba(217,119,6,.16);color:#b45309}.brand-status-active{background:rgba(16,185,129,.14);color:#047857}.brand-status-inactive{background:rgba(148,163,184,.18);color:#475569}.brand-action{justify-content:center;gap:.4rem;min-width:5rem;padding:.66rem .85rem;border-radius:.85rem;text-decoration:none;font-size:.82rem}.brand-action:hover,.brand-action:focus{text-decoration:none}.brand-action-edit{background:rgba(37,99,235,.14);color:#1d4ed8}.brand-action-delete{background:rgba(239,68,68,.14);color:#b91c1c;border:0}.brand-empty{border:1px dashed var(--brand-border);border-radius:1rem;padding:2rem 1.5rem;text-align:center;background:linear-gradient(180deg,#fff 0%,#f8fbff 100%)}.brand-empty-icon{width:4rem;height:4rem;border-radius:999px;display:inline-flex;align-items:center;justify-content:center;background:rgba(37,99,235,.1);color:var(--brand-sky);font-size:1.35rem;margin-bottom:1rem}.brand-pagination{margin-top:1.25rem}.brand-pagination .pagination{justify-content:flex-end;margin-bottom:0}div.dataTables_wrapper div.dataTables_filter,div.dataTables_wrapper div.dataTables_length,div.dataTables_wrapper div.dataTables_info,div.dataTables_wrapper div.dataTables_paginate{display:none}table.dataTable{border-collapse:collapse!important;margin-top:0!important;width:100%!important}@media (max-width:991.98px){.brand-hero h1{font-size:1.65rem}.brand-meta{justify-content:flex-start}}@media (max-width:767.98px){.brand-focus-grid{grid-template-columns:1fr}.brand-search{width:100%}.brand-pagination .pagination{justify-content:center}}
  </style>
@endpush

@section('main-content')
  @php
    $focusBrand = ($topBrand && $topBrand->products_count > 0) ? $topBrand : $latestBrand;
  @endphp
  <div class="container-fluid brand-page">
    @include('backend.layouts.notification')

    <div class="brand-hero mb-4">
      <div class="row align-items-stretch">
        <div class="col-xl-7 mb-4 mb-xl-0">
          <div class="brand-copy">
            <div class="brand-kicker">Thư Viện Thương Hiệu</div>
            <h1>Quản lý thương hiệu gọn gàng hơn, biết ngay brand nào đang có sản phẩm và brand nào cần bổ sung.</h1>
            <p>Trang này giúp admin kiểm soát bộ nhận diện thương hiệu, xem nhanh mức độ sử dụng trong catalog sản phẩm và rà lại các brand chưa được khai thác.</p>
            <div class="brand-actions-top">
              <a href="{{ route('brand.create') }}" class="btn btn-light btn-sm"><i class="fas fa-plus mr-1"></i> Thêm thương hiệu</a>
              <button type="button" id="brandQuickRefresh" class="btn btn-outline-light btn-sm"><i class="fas fa-sync-alt mr-1"></i> Làm mới</button>
            </div>
          </div>
        </div>
        <div class="col-xl-5">
          <div class="brand-focus">
            <div class="brand-focus-label">Thương hiệu tiêu biểu</div>
            @if ($focusBrand)
              <div class="brand-focus-title">{{ $focusBrand->title }}</div>
              <div class="brand-focus-sub">
                @if ($focusBrand->products_count > 0)
                  Hiện đang có {{ number_format($focusBrand->products_count, 0, ',', '.') }} sản phẩm active.
                @elseif ($focusBrand->created_at)
                  Đây là thương hiệu mới thêm gần nhất, tạo {{ $focusBrand->created_at->diffForHumans() }}.
                @else
                  Thương hiệu này đã sẵn sàng để bổ sung sản phẩm.
                @endif
              </div>
            @else
              <div class="brand-focus-title">Chưa có thương hiệu</div>
              <div class="brand-focus-sub">Thông tin thương hiệu nổi bật sẽ xuất hiện tại đây khi hệ thống có dữ liệu.</div>
            @endif
            <div class="brand-focus-grid">
              <div class="brand-focus-box"><span>Đang hoạt động</span><strong>{{ number_format($activeBrands, 0, ',', '.') }}</strong></div>
              <div class="brand-focus-box"><span>Có sản phẩm</span><strong>{{ number_format($brandsWithProducts, 0, ',', '.') }}</strong></div>
              <div class="brand-focus-box"><span>Slug sẵn sàng</span><strong>{{ number_format($brandsWithSlug, 0, ',', '.') }}</strong></div>
              <div class="brand-focus-box"><span>Mới trong tháng</span><strong>{{ number_format($newBrandsThisMonth, 0, ',', '.') }}</strong></div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="row">
      <div class="col-xl-3 col-md-6 mb-4">
        <div class="card brand-card brand-card-primary"><div class="card-body"><div class="brand-label">Tổng thương hiệu</div><div class="brand-value">{{ number_format($totalBrands, 0, ',', '.') }}</div><div class="brand-text">Tổng số thương hiệu đang được lưu trong hệ thống.</div><span class="brand-chip">{{ number_format($inactiveBrands, 0, ',', '.') }} thương hiệu tạm ẩn</span></div></div>
      </div>
      <div class="col-xl-3 col-md-6 mb-4">
        <div class="card brand-card brand-card-success"><div class="card-body"><div class="brand-label">Đang hoạt động</div><div class="brand-value">{{ number_format($activeBrands, 0, ',', '.') }}</div><div class="brand-text">Các thương hiệu đang sẵn sàng hiển thị trong catalog sản phẩm.</div><span class="brand-chip">{{ number_format($brandsWithProducts, 0, ',', '.') }} brand có sản phẩm</span></div></div>
      </div>
      <div class="col-xl-3 col-md-6 mb-4">
        <div class="card brand-card brand-card-warning"><div class="card-body"><div class="brand-label">Đang được dùng</div><div class="brand-value">{{ number_format($brandsWithProducts, 0, ',', '.') }}</div><div class="brand-text">Nhóm thương hiệu đã có ít nhất một sản phẩm active liên kết.</div><span class="brand-chip">{{ number_format($brandsWithoutProducts, 0, ',', '.') }} brand chưa dùng</span></div></div>
      </div>
      <div class="col-xl-3 col-md-6 mb-4">
        <div class="card brand-card brand-card-dark"><div class="card-body"><div class="brand-label">Slug sẵn sàng</div><div class="brand-value">{{ number_format($brandsWithSlug, 0, ',', '.') }}</div><div class="brand-text">Số thương hiệu đã có slug gọn để đồng bộ URL và bộ lọc.</div><span class="brand-chip">{{ number_format($newBrandsThisMonth, 0, ',', '.') }} brand mới tháng này</span></div></div>
      </div>
    </div>

    <div class="card brand-panel">
      <div class="card-header">
        <div class="row align-items-lg-center">
          <div class="col-lg-8">
            <div class="brand-panel-title">Danh sách thương hiệu</div>
            <p class="brand-panel-sub">Tìm nhanh theo tên thương hiệu hoặc slug, lọc theo trạng thái và mức độ sử dụng để làm sạch bộ brand trong cửa hàng nhanh hơn.</p>
          </div>
          <div class="col-lg-4">
            <div class="brand-meta">
              <span class="brand-badge"><i class="fas fa-copyright"></i> Hiển thị {{ number_format($brands->count(), 0, ',', '.') }} / {{ number_format($totalBrands, 0, ',', '.') }} thương hiệu</span>
              <span class="brand-badge"><i class="fas fa-box-open"></i> {{ number_format($brandsWithProducts, 0, ',', '.') }} brand có sản phẩm</span>
            </div>
          </div>
        </div>
        <div class="brand-toolbar">
          <div class="brand-filters" role="group" aria-label="Lọc thương hiệu">
            <button type="button" class="brand-filter active" data-filter="all">Tất cả <span>{{ number_format($totalBrands, 0, ',', '.') }}</span></button>
            <button type="button" class="brand-filter" data-filter="active">Active <span>{{ number_format($activeBrands, 0, ',', '.') }}</span></button>
            <button type="button" class="brand-filter" data-filter="inactive">Inactive <span>{{ number_format($inactiveBrands, 0, ',', '.') }}</span></button>
            <button type="button" class="brand-filter" data-filter="used">Đang dùng <span>{{ number_format($brandsWithProducts, 0, ',', '.') }}</span></button>
            <button type="button" class="brand-filter" data-filter="unused">Chưa dùng <span>{{ number_format($brandsWithoutProducts, 0, ',', '.') }}</span></button>
          </div>
          <div class="brand-search">
            <i class="fas fa-search"></i>
            <input type="text" id="brandSearchInput" class="form-control" placeholder="Tìm theo thương hiệu hoặc slug...">
          </div>
        </div>
      </div>
      <div class="card-body">
        @if ($brands->count())
          <div class="table-responsive">
            <table class="table brand-table" id="brand-dataTable" width="100%" cellspacing="0">
              <thead>
                <tr>
                  <th>Thương hiệu</th>
                  <th>Slug</th>
                  <th>Mức sử dụng</th>
                  <th>Trạng thái</th>
                  <th>Thời gian</th>
                  <th>Thao tác</th>
                </tr>
              </thead>
              <tbody>
                @foreach ($brands as $brand)
                  @php
                    $usageType = $brand->products_count > 0 ? 'used' : 'unused';
                  @endphp
                  <tr data-brand-status="{{ $brand->status }}" data-brand-usage="{{ $usageType }}">
                    <td>
                      <div class="brand-title">{{ $brand->title }}</div>
                      <div class="brand-subtext">#{{ $brand->id }}@if ($brand->created_at) - tạo {{ $brand->created_at->diffForHumans() }}@endif</div>
                    </td>
                    <td>
                      <div class="brand-pill-row mb-2"><span class="brand-pill {{ $brand->slug ? 'brand-slug-ready' : 'brand-slug-empty' }}">{{ $brand->slug ? 'Slug sẵn sàng' : 'Chưa có slug' }}</span></div>
                      <div class="brand-subtext" style="word-break:break-word;">{{ $brand->slug ?: 'Thương hiệu này cần bổ sung slug để đồng bộ URL.' }}</div>
                    </td>
                    <td>
                      <div class="brand-pill-row mb-2"><span class="brand-pill {{ $usageType === 'used' ? 'brand-usage-live' : 'brand-usage-idle' }}">{{ number_format($brand->products_count, 0, ',', '.') }} sản phẩm</span></div>
                      <div class="brand-subtext">{{ $usageType === 'used' ? 'Đang được liên kết với sản phẩm active trong catalog.' : 'Chưa có sản phẩm active nào gắn với thương hiệu này.' }}</div>
                    </td>
                    <td>
                      <div class="brand-pill-row mb-2"><span class="brand-pill {{ $brand->status === 'active' ? 'brand-status-active' : 'brand-status-inactive' }}">{{ $brand->status === 'active' ? 'Đang hoạt động' : 'Tạm ẩn' }}</span></div>
                      <div class="brand-subtext">{{ $brand->status === 'active' ? 'Sẵn sàng hiển thị trong khu vực quản trị và storefront.' : 'Đang tạm dừng khỏi các luồng hiển thị.' }}</div>
                    </td>
                    <td>
                      <div class="brand-title">{{ $brand->created_at ? $brand->created_at->format('d/m/Y') : 'Chưa rõ' }}</div>
                      <div class="brand-subtext">{{ $brand->created_at ? 'Cập nhật ' . $brand->created_at->diffForHumans() : 'Chưa có dữ liệu thời gian.' }}</div>
                    </td>
                    <td>
                      <div class="brand-row-actions">
                        <a href="{{ route('brand.edit', $brand->id) }}" class="brand-action brand-action-edit" data-toggle="tooltip" title="Chỉnh sửa thương hiệu"><i class="fas fa-pen"></i> <span>Sửa</span></a>
                        <form method="POST" action="{{ route('brand.destroy', [$brand->id]) }}">
                          @csrf
                          @method('delete')
                          <button class="brand-action brand-action-delete dltBtn" data-id="{{ $brand->id }}" data-toggle="tooltip" title="Xóa thương hiệu"><i class="fas fa-trash-alt"></i> <span>Xóa</span></button>
                        </form>
                      </div>
                    </td>
                  </tr>
                @endforeach
              </tbody>
            </table>
          </div>
          <div class="brand-pagination">{{ $brands->links() }}</div>
        @else
          <div class="brand-empty">
            <div class="brand-empty-icon"><i class="fas fa-copyright"></i></div>
            <h3>Chưa có thương hiệu nào</h3>
            <p class="text-muted mb-4">Hãy tạo thương hiệu đầu tiên để bắt đầu tổ chức danh mục sản phẩm theo nhãn hàng.</p>
            <a href="{{ route('brand.create') }}" class="btn btn-primary btn-sm"><i class="fas fa-plus mr-1"></i> Thêm thương hiệu</a>
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
      const $table = $('#brand-dataTable');
      const brandFilter = { value: 'all' };

      $.fn.dataTable.ext.search.push(function (settings, data, dataIndex) {
        if (settings.nTable.id !== 'brand-dataTable') return true;
        if (brandFilter.value === 'all') return true;
        const rowNode = settings.aoData[dataIndex] && settings.aoData[dataIndex].nTr;
        if (!rowNode) return true;
        const status = rowNode.getAttribute('data-brand-status');
        const usage = rowNode.getAttribute('data-brand-usage');
        if (brandFilter.value === 'used' || brandFilter.value === 'unused') return usage === brandFilter.value;
        return status === brandFilter.value;
      });

      if ($table.length) {
        const brandTable = $table.DataTable({
          autoWidth: false,
          info: false,
          language: { emptyTable: 'Chưa có thương hiệu nào.', zeroRecords: 'Không tìm thấy thương hiệu phù hợp trên trang này.' },
          lengthChange: false,
          order: [],
          paging: false,
          searching: true,
          columnDefs: [{ orderable: false, targets: [3, 5] }]
        });

        $('#brandSearchInput').on('keyup', function () { brandTable.search(this.value).draw(); });
        $('.brand-filter').on('click', function () {
          const $chip = $(this);
          brandFilter.value = $chip.data('filter');
          $('.brand-filter').removeClass('active');
          $chip.addClass('active');
          brandTable.draw();
        });
      }

      $('#brandQuickRefresh').on('click', function () { window.location.reload(); });
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
