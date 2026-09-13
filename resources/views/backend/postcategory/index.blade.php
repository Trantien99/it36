@extends('backend.layouts.master')

@section('title', 'Web bán tai nghe || Quản lý danh mục bài viết')

@push('styles')
  <link href="{{ asset('backend/vendor/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.css" />
  <style>
    :root { --pcat-ink:#16213e; --pcat-sky:#2563eb; --pcat-mint:#059669; --pcat-amber:#d97706; --pcat-border:#e2e8f0; --pcat-soft:#f8fbff; --pcat-shadow:0 18px 40px rgba(22,33,62,.08); }
    .pcat-page{padding-bottom:2rem}.pcat-hero,.pcat-card,.pcat-panel{border:0;border-radius:1.25rem;box-shadow:var(--pcat-shadow)}.pcat-hero{background:linear-gradient(135deg,#16213e 0%,#1d4ed8 58%,#0891b2 100%);color:#fff;padding:1.75rem;position:relative;overflow:hidden}.pcat-hero:after{content:'';position:absolute;right:-4rem;top:-4rem;width:14rem;height:14rem;border-radius:999px;background:rgba(255,255,255,.08)}.pcat-copy,.pcat-focus{position:relative;z-index:1}.pcat-kicker{font-size:.78rem;font-weight:800;letter-spacing:.18em;text-transform:uppercase;margin-bottom:.8rem}.pcat-hero h1{font-size:2rem;font-weight:800;line-height:1.12;margin-bottom:.9rem;max-width:40rem}.pcat-hero p{color:rgba(255,255,255,.84);max-width:42rem;margin-bottom:0}.pcat-actions-top{display:flex;flex-wrap:wrap;gap:.75rem;margin-top:1.25rem}.pcat-actions-top .btn,.pcat-filter{border-radius:999px;font-weight:700}.pcat-focus{background:rgba(255,255,255,.12);border:1px solid rgba(255,255,255,.14);border-radius:1rem;height:100%;padding:1.15rem}.pcat-focus-label{font-size:.76rem;font-weight:800;letter-spacing:.08em;text-transform:uppercase;color:rgba(255,255,255,.72)}.pcat-focus-title{font-size:1.3rem;font-weight:800;margin:.3rem 0}.pcat-focus-sub{font-size:.9rem;color:rgba(255,255,255,.84)}.pcat-focus-grid{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:.75rem;margin-top:1rem}.pcat-focus-box{background:rgba(255,255,255,.08);border-radius:.95rem;padding:.85rem .95rem}.pcat-focus-box span{display:block;font-size:.75rem;color:rgba(255,255,255,.72);text-transform:uppercase}.pcat-focus-box strong{display:block;font-size:1.1rem;margin-top:.3rem}.pcat-card{color:#fff;height:100%;overflow:hidden;position:relative}.pcat-card:after{content:'';position:absolute;right:-1.5rem;top:-1.5rem;width:6.5rem;height:6.5rem;border-radius:999px;background:rgba(255,255,255,.08)}.pcat-card .card-body{position:relative;z-index:1}.pcat-card-primary{background:linear-gradient(140deg,#1d4ed8 0%,#2563eb 100%)}.pcat-card-success{background:linear-gradient(140deg,#047857 0%,#059669 100%)}.pcat-card-warning{background:linear-gradient(140deg,#b45309 0%,#d97706 100%)}.pcat-card-dark{background:linear-gradient(140deg,#334155 0%,#475569 100%)}.pcat-label{font-size:.76rem;font-weight:800;letter-spacing:.08em;text-transform:uppercase;margin-bottom:.7rem}.pcat-value{font-size:1.8rem;font-weight:800;line-height:1.1;margin-bottom:.55rem}.pcat-text{font-size:.88rem;opacity:.86;margin-bottom:.8rem}.pcat-chip,.pcat-badge,.pcat-pill,.pcat-action{display:inline-flex;align-items:center;font-weight:700}.pcat-chip{background:rgba(255,255,255,.15);border-radius:999px;padding:.35rem .7rem;font-size:.78rem}.pcat-panel{background:#fff;overflow:hidden}.pcat-panel .card-header{background:#fff;border-bottom:1px solid var(--pcat-border);padding:1.35rem 1.45rem 1rem}.pcat-panel .card-body{padding:1.35rem 1.45rem 1.45rem}.pcat-panel-title{font-size:1.08rem;font-weight:800;color:var(--pcat-ink)}.pcat-panel-sub{font-size:.9rem;color:#64748b;max-width:44rem;margin-bottom:0}.pcat-meta{display:flex;flex-wrap:wrap;gap:.6rem;justify-content:flex-end;margin-top:1rem}.pcat-badge{gap:.35rem;padding:.45rem .8rem;border-radius:999px;border:1px solid var(--pcat-border);background:var(--pcat-soft);font-size:.8rem;color:var(--pcat-ink)}.pcat-toolbar{display:flex;flex-wrap:wrap;justify-content:space-between;align-items:center;gap:1rem;margin-top:1.1rem}.pcat-filters{display:flex;flex-wrap:wrap;gap:.65rem}.pcat-filter{background:#fff;border:1px solid var(--pcat-border);color:#475569;padding:.65rem .95rem;display:inline-flex;gap:.45rem;align-items:center;font-size:.84rem}.pcat-filter span{background:#f1f5f9;border-radius:999px;padding:.12rem .45rem;min-width:1.7rem;text-align:center}.pcat-filter.active{background:linear-gradient(135deg,#16213e 0%,#2563eb 100%);border-color:transparent;color:#fff}.pcat-filter.active span{background:rgba(255,255,255,.14);color:#fff}.pcat-search{position:relative;width:min(100%,22rem)}.pcat-search i{position:absolute;left:1rem;top:50%;transform:translateY(-50%);color:#64748b}.pcat-search .form-control{height:3rem;border-radius:999px;border:1px solid var(--pcat-border);padding-left:2.7rem;box-shadow:none}.pcat-search .form-control:focus{border-color:rgba(37,99,235,.45);box-shadow:0 0 0 .2rem rgba(37,99,235,.12)}.pcat-table thead th{border-top:0;border-bottom:1px solid var(--pcat-border);font-size:.76rem;font-weight:800;letter-spacing:.08em;text-transform:uppercase;color:#718096;padding:1rem}.pcat-table tbody td{border-color:var(--pcat-border);padding:1rem;vertical-align:top}.pcat-table tbody tr:hover{background:#fbfdff}.pcat-title{font-size:.96rem;font-weight:800;color:var(--pcat-ink);margin-bottom:.25rem}.pcat-subtext{font-size:.84rem;color:#64748b;line-height:1.55}.pcat-pill-row,.pcat-row-actions{display:flex;flex-wrap:wrap;gap:.5rem}.pcat-pill{border-radius:999px;padding:.46rem .8rem;font-size:.78rem;line-height:1}.pcat-slug-ready{background:rgba(5,150,105,.14);color:#047857}.pcat-slug-empty{background:rgba(220,38,38,.14);color:#b91c1c}.pcat-usage-live{background:rgba(37,99,235,.14);color:#1d4ed8}.pcat-usage-idle{background:rgba(217,119,6,.16);color:#b45309}.pcat-status-active{background:rgba(16,185,129,.14);color:#047857}.pcat-status-inactive{background:rgba(148,163,184,.18);color:#475569}.pcat-action{justify-content:center;gap:.4rem;min-width:5rem;padding:.66rem .85rem;border-radius:.85rem;text-decoration:none;font-size:.82rem}.pcat-action:hover,.pcat-action:focus{text-decoration:none}.pcat-action-edit{background:rgba(37,99,235,.14);color:#1d4ed8}.pcat-action-delete{background:rgba(239,68,68,.14);color:#b91c1c;border:0}.pcat-empty{border:1px dashed var(--pcat-border);border-radius:1rem;padding:2rem 1.5rem;text-align:center;background:linear-gradient(180deg,#fff 0%,#f8fbff 100%)}.pcat-empty-icon{width:4rem;height:4rem;border-radius:999px;display:inline-flex;align-items:center;justify-content:center;background:rgba(37,99,235,.1);color:var(--pcat-sky);font-size:1.35rem;margin-bottom:1rem}.pcat-pagination{margin-top:1.25rem}.pcat-pagination .pagination{justify-content:flex-end;margin-bottom:0}div.dataTables_wrapper div.dataTables_filter,div.dataTables_wrapper div.dataTables_length,div.dataTables_wrapper div.dataTables_info,div.dataTables_wrapper div.dataTables_paginate{display:none}table.dataTable{border-collapse:collapse!important;margin-top:0!important;width:100%!important}@media (max-width:991.98px){.pcat-hero h1{font-size:1.65rem}.pcat-meta{justify-content:flex-start}}@media (max-width:767.98px){.pcat-focus-grid{grid-template-columns:1fr}.pcat-search{width:100%}.pcat-pagination .pagination{justify-content:center}}
  </style>
@endpush

@section('main-content')
  @php
    $focusCategory = ($topPostCategory && $topPostCategory->posts_count > 0) ? $topPostCategory : $latestPostCategory;
  @endphp
  <div class="container-fluid pcat-page">
    @include('backend.layouts.notification')

    <div class="pcat-hero mb-4">
      <div class="row align-items-stretch">
        <div class="col-xl-7 mb-4 mb-xl-0">
          <div class="pcat-copy">
            <div class="pcat-kicker">Bản Đồ Chuyên Mục Bài Viết</div>
            <h1>Quản lý danh mục bài viết gọn hơn, nhìn nhanh chuyên mục nào đang có nội dung và chuyên mục nào còn trống.</h1>
            <p>Trang này giúp admin tổ chức thư viện blog theo chuyên mục, kiểm tra slug và rà lại các danh mục chưa được dùng trước khi mở rộng nội dung.</p>
            <div class="pcat-actions-top">
              <a href="{{ route('post-category.create') }}" class="btn btn-light btn-sm"><i class="fas fa-plus mr-1"></i> Thêm danh mục bài viết</a>
              <button type="button" id="postCategoryQuickRefresh" class="btn btn-outline-light btn-sm"><i class="fas fa-sync-alt mr-1"></i> Làm mới</button>
            </div>
          </div>
        </div>
        <div class="col-xl-5">
          <div class="pcat-focus">
            <div class="pcat-focus-label">Chuyên mục tiêu biểu</div>
            @if ($focusCategory)
              <div class="pcat-focus-title">{{ $focusCategory->title }}</div>
              <div class="pcat-focus-sub">
                @if ($focusCategory->posts_count > 0)
                  Hiện đang có {{ number_format($focusCategory->posts_count, 0, ',', '.') }} bài viết active.
                @elseif ($focusCategory->created_at)
                  Đây là danh mục mới thêm gần nhất, tạo {{ $focusCategory->created_at->diffForHumans() }}.
                @else
                  Danh mục này đã sẵn sàng để bổ sung nội dung.
                @endif
              </div>
            @else
              <div class="pcat-focus-title">Chưa có danh mục bài viết</div>
              <div class="pcat-focus-sub">Thông tin nổi bật sẽ xuất hiện tại đây khi hệ thống có dữ liệu chuyên mục.</div>
            @endif
            <div class="pcat-focus-grid">
              <div class="pcat-focus-box"><span>Đang hoạt động</span><strong>{{ number_format($activePostCategories, 0, ',', '.') }}</strong></div>
              <div class="pcat-focus-box"><span>Có bài viết</span><strong>{{ number_format($categoriesWithPosts, 0, ',', '.') }}</strong></div>
              <div class="pcat-focus-box"><span>Slug sẵn sàng</span><strong>{{ number_format($categoriesWithSlug, 0, ',', '.') }}</strong></div>
              <div class="pcat-focus-box"><span>Mới trong tháng</span><strong>{{ number_format($newPostCategoriesThisMonth, 0, ',', '.') }}</strong></div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="row">
      <div class="col-xl-3 col-md-6 mb-4">
        <div class="card pcat-card pcat-card-primary"><div class="card-body"><div class="pcat-label">Tổng chuyên mục</div><div class="pcat-value">{{ number_format($totalPostCategories, 0, ',', '.') }}</div><div class="pcat-text">Tổng số danh mục bài viết đang được quản lý trong hệ thống.</div><span class="pcat-chip">{{ number_format($inactivePostCategories, 0, ',', '.') }} mục tạm ẩn</span></div></div>
      </div>
      <div class="col-xl-3 col-md-6 mb-4">
        <div class="card pcat-card pcat-card-success"><div class="card-body"><div class="pcat-label">Đang hoạt động</div><div class="pcat-value">{{ number_format($activePostCategories, 0, ',', '.') }}</div><div class="pcat-text">Các chuyên mục đang sẵn sàng hiển thị trong phần bài viết và blog.</div><span class="pcat-chip">{{ number_format($categoriesWithPosts, 0, ',', '.') }} mục đã có nội dung</span></div></div>
      </div>
      <div class="col-xl-3 col-md-6 mb-4">
        <div class="card pcat-card pcat-card-warning"><div class="card-body"><div class="pcat-label">Đang được dùng</div><div class="pcat-value">{{ number_format($categoriesWithPosts, 0, ',', '.') }}</div><div class="pcat-text">Nhóm danh mục đã có ít nhất một bài viết active liên kết.</div><span class="pcat-chip">{{ number_format($categoriesWithoutPosts, 0, ',', '.') }} mục chưa dùng</span></div></div>
      </div>
      <div class="col-xl-3 col-md-6 mb-4">
        <div class="card pcat-card pcat-card-dark"><div class="card-body"><div class="pcat-label">Slug sẵn sàng</div><div class="pcat-value">{{ number_format($categoriesWithSlug, 0, ',', '.') }}</div><div class="pcat-text">Số chuyên mục đã có slug gọn để dùng cho URL và điều hướng blog.</div><span class="pcat-chip">{{ number_format($newPostCategoriesThisMonth, 0, ',', '.') }} mục mới tháng này</span></div></div>
      </div>
    </div>

    <div class="card pcat-panel">
      <div class="card-header">
        <div class="row align-items-lg-center">
          <div class="col-lg-8">
            <div class="pcat-panel-title">Danh sách danh mục bài viết</div>
            <p class="pcat-panel-sub">Tìm nhanh theo tên hoặc slug, lọc chuyên mục active hay chưa được dùng để dọn cấu trúc blog và giữ thư viện nội dung gọn gàng hơn.</p>
          </div>
          <div class="col-lg-4">
            <div class="pcat-meta">
              <span class="pcat-badge"><i class="fas fa-folder-open"></i> Hiển thị {{ number_format($postCategories->count(), 0, ',', '.') }} / {{ number_format($totalPostCategories, 0, ',', '.') }} mục</span>
              <span class="pcat-badge"><i class="fas fa-newspaper"></i> {{ number_format($categoriesWithPosts, 0, ',', '.') }} mục đã có bài viết</span>
            </div>
          </div>
        </div>
        <div class="pcat-toolbar">
          <div class="pcat-filters" role="group" aria-label="Lọc danh mục bài viết">
            <button type="button" class="pcat-filter active" data-filter="all">Tất cả <span>{{ number_format($totalPostCategories, 0, ',', '.') }}</span></button>
            <button type="button" class="pcat-filter" data-filter="active">Active <span>{{ number_format($activePostCategories, 0, ',', '.') }}</span></button>
            <button type="button" class="pcat-filter" data-filter="inactive">Inactive <span>{{ number_format($inactivePostCategories, 0, ',', '.') }}</span></button>
            <button type="button" class="pcat-filter" data-filter="used">Đang dùng <span>{{ number_format($categoriesWithPosts, 0, ',', '.') }}</span></button>
            <button type="button" class="pcat-filter" data-filter="unused">Chưa dùng <span>{{ number_format($categoriesWithoutPosts, 0, ',', '.') }}</span></button>
          </div>
          <div class="pcat-search">
            <i class="fas fa-search"></i>
            <input type="text" id="postCategorySearchInput" class="form-control" placeholder="Tìm theo chuyên mục hoặc slug...">
          </div>
        </div>
      </div>
      <div class="card-body">
        @if ($postCategories->count())
          <div class="table-responsive">
            <table class="table pcat-table" id="post-category-dataTable" width="100%" cellspacing="0">
              <thead>
                <tr>
                  <th>Danh mục</th>
                  <th>Slug</th>
                  <th>Mức sử dụng</th>
                  <th>Trạng thái</th>
                  <th>Thời gian</th>
                  <th>Thao tác</th>
                </tr>
              </thead>
              <tbody>
                @foreach ($postCategories as $category)
                  @php
                    $usageType = $category->posts_count > 0 ? 'used' : 'unused';
                  @endphp
                  <tr data-category-status="{{ $category->status }}" data-category-usage="{{ $usageType }}">
                    <td>
                      <div class="pcat-title">{{ $category->title }}</div>
                      <div class="pcat-subtext">#{{ $category->id }}@if ($category->created_at) - tạo {{ $category->created_at->diffForHumans() }}@endif</div>
                    </td>
                    <td>
                      <div class="pcat-pill-row mb-2"><span class="pcat-pill {{ $category->slug ? 'pcat-slug-ready' : 'pcat-slug-empty' }}">{{ $category->slug ? 'Slug sẵn sàng' : 'Chưa có slug' }}</span></div>
                      <div class="pcat-subtext" style="word-break:break-word;">{{ $category->slug ?: 'Danh mục này cần bổ sung slug để đồng bộ URL.' }}</div>
                    </td>
                    <td>
                      <div class="pcat-pill-row mb-2"><span class="pcat-pill {{ $usageType === 'used' ? 'pcat-usage-live' : 'pcat-usage-idle' }}">{{ number_format($category->posts_count, 0, ',', '.') }} bài viết</span></div>
                      <div class="pcat-subtext">{{ $usageType === 'used' ? 'Đã có bài viết active thuộc chuyên mục này.' : 'Chưa có bài viết active nào gắn với chuyên mục.' }}</div>
                    </td>
                    <td>
                      <div class="pcat-pill-row mb-2"><span class="pcat-pill {{ $category->status === 'active' ? 'pcat-status-active' : 'pcat-status-inactive' }}">{{ $category->status === 'active' ? 'Đang hoạt động' : 'Tạm ẩn' }}</span></div>
                      <div class="pcat-subtext">{{ $category->status === 'active' ? 'Có thể dùng để phân loại bài viết trên blog.' : 'Đang tạm dừng khỏi danh sách chuyên mục hiển thị.' }}</div>
                    </td>
                    <td>
                      <div class="pcat-title">{{ $category->created_at ? $category->created_at->format('d/m/Y') : 'Chưa rõ' }}</div>
                      <div class="pcat-subtext">{{ $category->created_at ? 'Cập nhật ' . $category->created_at->diffForHumans() : 'Chưa có dữ liệu thời gian.' }}</div>
                    </td>
                    <td>
                      <div class="pcat-row-actions">
                        <a href="{{ route('post-category.edit', $category->id) }}" class="pcat-action pcat-action-edit" data-toggle="tooltip" title="Chỉnh sửa danh mục"><i class="fas fa-pen"></i> <span>Sửa</span></a>
                        <form method="POST" action="{{ route('post-category.destroy', [$category->id]) }}">
                          @csrf
                          @method('delete')
                          <button class="pcat-action pcat-action-delete dltBtn" data-id="{{ $category->id }}" data-toggle="tooltip" title="Xóa danh mục"><i class="fas fa-trash-alt"></i> <span>Xóa</span></button>
                        </form>
                      </div>
                    </td>
                  </tr>
                @endforeach
              </tbody>
            </table>
          </div>
          <div class="pcat-pagination">{{ $postCategories->links() }}</div>
        @else
          <div class="pcat-empty">
            <div class="pcat-empty-icon"><i class="fas fa-folder-open"></i></div>
            <h3>Chưa có danh mục bài viết nào</h3>
            <p class="text-muted mb-4">Hãy tạo danh mục đầu tiên để bắt đầu tổ chức thư viện bài viết của cửa hàng.</p>
            <a href="{{ route('post-category.create') }}" class="btn btn-primary btn-sm"><i class="fas fa-plus mr-1"></i> Thêm danh mục bài viết</a>
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
      const $table = $('#post-category-dataTable');
      const categoryFilter = { value: 'all' };

      $.fn.dataTable.ext.search.push(function (settings, data, dataIndex) {
        if (settings.nTable.id !== 'post-category-dataTable') return true;
        if (categoryFilter.value === 'all') return true;
        const rowNode = settings.aoData[dataIndex] && settings.aoData[dataIndex].nTr;
        if (!rowNode) return true;
        const status = rowNode.getAttribute('data-category-status');
        const usage = rowNode.getAttribute('data-category-usage');
        if (categoryFilter.value === 'used' || categoryFilter.value === 'unused') return usage === categoryFilter.value;
        return status === categoryFilter.value;
      });

      if ($table.length) {
        const categoryTable = $table.DataTable({
          autoWidth: false,
          info: false,
          language: { emptyTable: 'Chưa có danh mục bài viết nào.', zeroRecords: 'Không tìm thấy danh mục phù hợp trên trang này.' },
          lengthChange: false,
          order: [],
          paging: false,
          searching: true,
          columnDefs: [{ orderable: false, targets: [3, 5] }]
        });

        $('#postCategorySearchInput').on('keyup', function () { categoryTable.search(this.value).draw(); });
        $('.pcat-filter').on('click', function () {
          const $chip = $(this);
          categoryFilter.value = $chip.data('filter');
          $('.pcat-filter').removeClass('active');
          $chip.addClass('active');
          categoryTable.draw();
        });
      }

      $('#postCategoryQuickRefresh').on('click', function () { window.location.reload(); });
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
