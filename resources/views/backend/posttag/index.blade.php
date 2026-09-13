@extends('backend.layouts.master')

@section('title', 'Web bán tai nghe || Quản lý thẻ bài viết')

@push('styles')
  <link href="{{ asset('backend/vendor/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.css" />
  <style>
    :root { --tag-ink:#14213d; --tag-sky:#2563eb; --tag-mint:#059669; --tag-amber:#d97706; --tag-border:#e2e8f0; --tag-soft:#f8fbff; --tag-shadow:0 18px 40px rgba(20,33,61,.08); }
    .tag-page{padding-bottom:2rem}.tag-hero,.tag-card,.tag-panel{border:0;border-radius:1.25rem;box-shadow:var(--tag-shadow)}.tag-hero{background:linear-gradient(135deg,#14213d 0%,#1d4ed8 55%,#059669 100%);color:#fff;padding:1.75rem;position:relative;overflow:hidden}.tag-hero:after{content:'';position:absolute;right:-4rem;top:-4rem;width:14rem;height:14rem;border-radius:999px;background:rgba(255,255,255,.08)}.tag-copy,.tag-focus{position:relative;z-index:1}.tag-kicker{font-size:.78rem;font-weight:800;letter-spacing:.18em;text-transform:uppercase;margin-bottom:.8rem}.tag-hero h1{font-size:2rem;font-weight:800;line-height:1.12;margin-bottom:.9rem;max-width:39rem}.tag-hero p{color:rgba(255,255,255,.84);max-width:42rem;margin-bottom:0}.tag-actions-top{display:flex;flex-wrap:wrap;gap:.75rem;margin-top:1.25rem}.tag-actions-top .btn,.tag-filter{border-radius:999px;font-weight:700}.tag-focus{background:rgba(255,255,255,.12);border:1px solid rgba(255,255,255,.14);border-radius:1rem;height:100%;padding:1.15rem}.tag-focus-label{font-size:.76rem;font-weight:800;letter-spacing:.08em;text-transform:uppercase;color:rgba(255,255,255,.72)}.tag-focus-title{font-size:1.3rem;font-weight:800;margin:.3rem 0}.tag-focus-sub{font-size:.9rem;color:rgba(255,255,255,.84)}.tag-focus-grid{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:.75rem;margin-top:1rem}.tag-focus-box{background:rgba(255,255,255,.08);border-radius:.95rem;padding:.85rem .95rem}.tag-focus-box span{display:block;font-size:.75rem;color:rgba(255,255,255,.72);text-transform:uppercase}.tag-focus-box strong{display:block;font-size:1.1rem;margin-top:.3rem}.tag-card{color:#fff;height:100%;overflow:hidden;position:relative}.tag-card:after{content:'';position:absolute;right:-1.5rem;top:-1.5rem;width:6.5rem;height:6.5rem;border-radius:999px;background:rgba(255,255,255,.08)}.tag-card .card-body{position:relative;z-index:1}.tag-card-primary{background:linear-gradient(140deg,#1d4ed8 0%,#2563eb 100%)}.tag-card-success{background:linear-gradient(140deg,#047857 0%,#059669 100%)}.tag-card-warning{background:linear-gradient(140deg,#b45309 0%,#d97706 100%)}.tag-card-dark{background:linear-gradient(140deg,#334155 0%,#475569 100%)}.tag-label{font-size:.76rem;font-weight:800;letter-spacing:.08em;text-transform:uppercase;margin-bottom:.7rem}.tag-value{font-size:1.8rem;font-weight:800;line-height:1.1;margin-bottom:.55rem}.tag-text{font-size:.88rem;opacity:.86;margin-bottom:.8rem}.tag-chip,.tag-badge,.tag-pill,.tag-action{display:inline-flex;align-items:center;font-weight:700}.tag-chip{background:rgba(255,255,255,.15);border-radius:999px;padding:.35rem .7rem;font-size:.78rem}.tag-panel{background:#fff;overflow:hidden}.tag-panel .card-header{background:#fff;border-bottom:1px solid var(--tag-border);padding:1.35rem 1.45rem 1rem}.tag-panel .card-body{padding:1.35rem 1.45rem 1.45rem}.tag-panel-title{font-size:1.08rem;font-weight:800;color:var(--tag-ink)}.tag-panel-sub{font-size:.9rem;color:#64748b;max-width:42rem;margin-bottom:0}.tag-meta{display:flex;flex-wrap:wrap;gap:.6rem;justify-content:flex-end;margin-top:1rem}.tag-badge{gap:.35rem;padding:.45rem .8rem;border-radius:999px;border:1px solid var(--tag-border);background:var(--tag-soft);font-size:.8rem;color:var(--tag-ink)}.tag-toolbar{display:flex;flex-wrap:wrap;justify-content:space-between;align-items:center;gap:1rem;margin-top:1.1rem}.tag-filters{display:flex;flex-wrap:wrap;gap:.65rem}.tag-filter{background:#fff;border:1px solid var(--tag-border);color:#475569;padding:.65rem .95rem;display:inline-flex;gap:.45rem;align-items:center;font-size:.84rem}.tag-filter span{background:#f1f5f9;border-radius:999px;padding:.12rem .45rem;min-width:1.7rem;text-align:center}.tag-filter.active{background:linear-gradient(135deg,#14213d 0%,#2563eb 100%);border-color:transparent;color:#fff}.tag-filter.active span{background:rgba(255,255,255,.14);color:#fff}.tag-search{position:relative;width:min(100%,22rem)}.tag-search i{position:absolute;left:1rem;top:50%;transform:translateY(-50%);color:#64748b}.tag-search .form-control{height:3rem;border-radius:999px;border:1px solid var(--tag-border);padding-left:2.7rem;box-shadow:none}.tag-search .form-control:focus{border-color:rgba(37,99,235,.45);box-shadow:0 0 0 .2rem rgba(37,99,235,.12)}.tag-table thead th{border-top:0;border-bottom:1px solid var(--tag-border);font-size:.76rem;font-weight:800;letter-spacing:.08em;text-transform:uppercase;color:#718096;padding:1rem}.tag-table tbody td{border-color:var(--tag-border);padding:1rem;vertical-align:top}.tag-table tbody tr:hover{background:#fbfdff}.tag-title{font-size:.96rem;font-weight:800;color:var(--tag-ink);margin-bottom:.25rem}.tag-subtext{font-size:.84rem;color:#64748b;line-height:1.55}.tag-pill-row,.tag-row-actions{display:flex;flex-wrap:wrap;gap:.5rem}.tag-pill{border-radius:999px;padding:.46rem .8rem;font-size:.78rem;line-height:1}.tag-status-active{background:rgba(16,185,129,.14);color:#047857}.tag-status-inactive{background:rgba(148,163,184,.18);color:#475569}.tag-usage-live{background:rgba(37,99,235,.14);color:#1d4ed8}.tag-usage-idle{background:rgba(217,119,6,.16);color:#b45309}.tag-slug-ready{background:rgba(5,150,105,.14);color:#047857}.tag-slug-empty{background:rgba(220,38,38,.14);color:#b91c1c}.tag-action{justify-content:center;gap:.4rem;min-width:5rem;padding:.66rem .85rem;border-radius:.85rem;text-decoration:none;font-size:.82rem}.tag-action:hover,.tag-action:focus{text-decoration:none}.tag-action-edit{background:rgba(37,99,235,.14);color:#1d4ed8}.tag-action-delete{background:rgba(239,68,68,.14);color:#b91c1c;border:0}.tag-empty{border:1px dashed var(--tag-border);border-radius:1rem;padding:2rem 1.5rem;text-align:center;background:linear-gradient(180deg,#fff 0%,#f8fbff 100%)}.tag-empty-icon{width:4rem;height:4rem;border-radius:999px;display:inline-flex;align-items:center;justify-content:center;background:rgba(37,99,235,.1);color:var(--tag-sky);font-size:1.35rem;margin-bottom:1rem}.tag-pagination{margin-top:1.25rem}.tag-pagination .pagination{justify-content:flex-end;margin-bottom:0}div.dataTables_wrapper div.dataTables_filter,div.dataTables_wrapper div.dataTables_length,div.dataTables_wrapper div.dataTables_info,div.dataTables_wrapper div.dataTables_paginate{display:none}table.dataTable{border-collapse:collapse!important;margin-top:0!important;width:100%!important}@media (max-width:991.98px){.tag-hero h1{font-size:1.65rem}.tag-meta{justify-content:flex-start}}@media (max-width:767.98px){.tag-focus-grid{grid-template-columns:1fr}.tag-search{width:100%}.tag-pagination .pagination{justify-content:center}}
  </style>
@endpush

@section('main-content')
  <div class="container-fluid tag-page">
    @include('backend.layouts.notification')

    <div class="tag-hero mb-4">
      <div class="row align-items-stretch">
        <div class="col-xl-7 mb-4 mb-xl-0">
          <div class="tag-copy">
            <div class="tag-kicker">Bàn Điều Phối Tag</div>
            <h1>Quản lý thẻ bài viết gọn hơn, biết ngay tag nào đang được dùng và tag nào cần dọn lại.</h1>
            <p>Thêm một lớp tổng quan nhỏ gọn để admin lọc nhanh tag active, tag không còn sử dụng và kiểm tra slug trước khi tổ chức lại nội dung blog.</p>
            <div class="tag-actions-top">
              <a href="{{ route('post-tag.create') }}" class="btn btn-light btn-sm"><i class="fas fa-plus mr-1"></i> Thêm thẻ</a>
              <button type="button" id="postTagQuickRefresh" class="btn btn-outline-light btn-sm"><i class="fas fa-sync-alt mr-1"></i> Làm mới</button>
            </div>
          </div>
        </div>
        <div class="col-xl-5">
          <div class="tag-focus">
            <div class="tag-focus-label">Tag nổi bật</div>
            @if ($mostUsedTag)
              <div class="tag-focus-title">{{ $mostUsedTag->title }}</div>
              <div class="tag-focus-sub">
                Đang gắn với {{ number_format($mostUsedTag->posts_count, 0, ',', '.') }} bài viết active.
                @if ($latestTag)
                  Tag mới nhất: {{ $latestTag->title }}{{ $latestTag->created_at ? ' - ' . $latestTag->created_at->diffForHumans() : '' }}.
                @endif
              </div>
            @else
              <div class="tag-focus-title">Chưa có dữ liệu nổi bật</div>
              <div class="tag-focus-sub">Thông tin tóm tắt sẽ hiện tại đây khi kho tag bắt đầu có dữ liệu sử dụng.</div>
            @endif
            <div class="tag-focus-grid">
              <div class="tag-focus-box"><span>Đang sử dụng</span><strong>{{ number_format($usedTags, 0, ',', '.') }}</strong></div>
              <div class="tag-focus-box"><span>Không dùng</span><strong>{{ number_format($unusedTags, 0, ',', '.') }}</strong></div>
              <div class="tag-focus-box"><span>Slug sẵn sàng</span><strong>{{ number_format($tagsWithSlug, 0, ',', '.') }}</strong></div>
              <div class="tag-focus-box"><span>Mới trong tháng</span><strong>{{ number_format($newTagsThisMonth, 0, ',', '.') }}</strong></div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="row">
      <div class="col-xl-3 col-md-6 mb-4">
        <div class="card tag-card tag-card-primary"><div class="card-body"><div class="tag-label">Tổng thẻ</div><div class="tag-value">{{ number_format($totalTags, 0, ',', '.') }}</div><div class="tag-text">Tổng số tag đang có trong hệ thống bài viết.</div><span class="tag-chip">{{ number_format($inactiveTags, 0, ',', '.') }} tag tạm ẩn</span></div></div>
      </div>
      <div class="col-xl-3 col-md-6 mb-4">
        <div class="card tag-card tag-card-success"><div class="card-body"><div class="tag-label">Đang hoạt động</div><div class="tag-value">{{ number_format($activeTags, 0, ',', '.') }}</div><div class="tag-text">Tag đang có thể được sử dụng cho bài viết hiển thị.</div><span class="tag-chip">{{ number_format($usedTags, 0, ',', '.') }} tag đã có bài viết</span></div></div>
      </div>
      <div class="col-xl-3 col-md-6 mb-4">
        <div class="card tag-card tag-card-warning"><div class="card-body"><div class="tag-label">Chưa được dùng</div><div class="tag-value">{{ number_format($unusedTags, 0, ',', '.') }}</div><div class="tag-text">Những tag cần xem lại vì chưa gắn với bài viết active.</div><span class="tag-chip">{{ number_format($usedTags, 0, ',', '.') }} tag đang hoạt động</span></div></div>
      </div>
      <div class="col-xl-3 col-md-6 mb-4">
        <div class="card tag-card tag-card-dark"><div class="card-body"><div class="tag-label">Slug sẵn sàng</div><div class="tag-value">{{ number_format($tagsWithSlug, 0, ',', '.') }}</div><div class="tag-text">Số tag đã có slug gọn và sẵn sàng cho điều hướng.</div><span class="tag-chip">{{ number_format($newTagsThisMonth, 0, ',', '.') }} tag mới tháng này</span></div></div>
      </div>
    </div>

    <div class="card tag-panel">
      <div class="card-header">
        <div class="row align-items-lg-center">
          <div class="col-lg-8">
            <div class="tag-panel-title">Danh sách thẻ bài viết</div>
            <p class="tag-panel-sub">Tìm nhanh theo tên tag hoặc slug, lọc tag active và tag chưa được dùng để dọn kho nội dung nhanh hơn.</p>
          </div>
          <div class="col-lg-4">
            <div class="tag-meta">
              <span class="tag-badge"><i class="fas fa-tags"></i> Hiển thị {{ number_format($postTags->count(), 0, ',', '.') }} / {{ number_format($totalTags, 0, ',', '.') }} tag</span>
              <span class="tag-badge"><i class="fas fa-feather-alt"></i> {{ number_format($usedTags, 0, ',', '.') }} tag đang được dùng</span>
            </div>
          </div>
        </div>
        <div class="tag-toolbar">
          <div class="tag-filters" role="group" aria-label="Lọc tag">
            <button type="button" class="tag-filter active" data-filter="all">Tất cả <span>{{ number_format($totalTags, 0, ',', '.') }}</span></button>
            <button type="button" class="tag-filter" data-filter="active">Active <span>{{ number_format($activeTags, 0, ',', '.') }}</span></button>
            <button type="button" class="tag-filter" data-filter="inactive">Inactive <span>{{ number_format($inactiveTags, 0, ',', '.') }}</span></button>
            <button type="button" class="tag-filter" data-filter="used">Đang dùng <span>{{ number_format($usedTags, 0, ',', '.') }}</span></button>
            <button type="button" class="tag-filter" data-filter="unused">Chưa dùng <span>{{ number_format($unusedTags, 0, ',', '.') }}</span></button>
          </div>
          <div class="tag-search">
            <i class="fas fa-search"></i>
            <input type="text" id="postTagSearchInput" class="form-control" placeholder="Tìm theo tên tag hoặc slug...">
          </div>
        </div>
      </div>
      <div class="card-body">
        @if ($postTags->count())
          <div class="table-responsive">
            <table class="table tag-table" id="post-tag-dataTable" width="100%" cellspacing="0">
              <thead>
                <tr>
                  <th>Thẻ</th>
                  <th>Slug</th>
                  <th>Mức sử dụng</th>
                  <th>Trạng thái</th>
                  <th>Thao tác</th>
                </tr>
              </thead>
              <tbody>
                @foreach ($postTags as $tag)
                  <tr data-tag-status="{{ $tag->status }}" data-tag-usage="{{ $tag->posts_count > 0 ? 'used' : 'unused' }}">
                    <td>
                      <div class="tag-title">{{ $tag->title }}</div>
                      <div class="tag-subtext">#{{ $tag->id }}@if ($tag->created_at) - tạo {{ $tag->created_at->diffForHumans() }}@endif</div>
                    </td>
                    <td>
                      <div class="tag-pill-row mb-2"><span class="tag-pill {{ $tag->slug ? 'tag-slug-ready' : 'tag-slug-empty' }}">{{ $tag->slug ? 'Slug sẵn sàng' : 'Chưa có slug' }}</span></div>
                      <div class="tag-subtext" style="word-break:break-word;">{{ $tag->slug ?: 'Tag này cần bổ sung slug để quản lý gọn hơn.' }}</div>
                    </td>
                    <td>
                      <div class="tag-pill-row mb-2"><span class="tag-pill {{ $tag->posts_count > 0 ? 'tag-usage-live' : 'tag-usage-idle' }}">{{ number_format($tag->posts_count, 0, ',', '.') }} bài viết</span></div>
                      <div class="tag-subtext">{{ $tag->posts_count > 0 ? 'Tag đang được sử dụng trong bài viết active.' : 'Tag này chưa gắn với bài viết active nào.' }}</div>
                    </td>
                    <td><span class="tag-pill {{ $tag->status === 'active' ? 'tag-status-active' : 'tag-status-inactive' }}">{{ $tag->status === 'active' ? 'Đang hoạt động' : 'Tạm ẩn' }}</span></td>
                    <td>
                      <div class="tag-row-actions">
                        <a href="{{ route('post-tag.edit', $tag->id) }}" class="tag-action tag-action-edit" data-toggle="tooltip" title="Chỉnh sửa tag"><i class="fas fa-pen"></i> <span>Sửa</span></a>
                        <form method="POST" action="{{ route('post-tag.destroy', [$tag->id]) }}">
                          @csrf
                          @method('delete')
                          <button class="tag-action tag-action-delete dltBtn" data-id="{{ $tag->id }}" data-toggle="tooltip" title="Xóa tag"><i class="fas fa-trash-alt"></i> <span>Xóa</span></button>
                        </form>
                      </div>
                    </td>
                  </tr>
                @endforeach
              </tbody>
            </table>
          </div>
          <div class="tag-pagination">{{ $postTags->links() }}</div>
        @else
          <div class="tag-empty">
            <div class="tag-empty-icon"><i class="fas fa-tags"></i></div>
            <h3>Chưa có thẻ bài viết nào</h3>
            <p class="text-muted mb-4">Hãy tạo tag đầu tiên để bắt đầu phân loại blog, tin tức và các bài viết cẩm nang.</p>
            <a href="{{ route('post-tag.create') }}" class="btn btn-primary btn-sm"><i class="fas fa-plus mr-1"></i> Thêm thẻ mới</a>
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
      const $table = $('#post-tag-dataTable');
      const tagFilter = { value: 'all' };

      $.fn.dataTable.ext.search.push(function (settings, data, dataIndex) {
        if (settings.nTable.id !== 'post-tag-dataTable') return true;
        if (tagFilter.value === 'all') return true;
        const rowNode = settings.aoData[dataIndex] && settings.aoData[dataIndex].nTr;
        if (!rowNode) return true;
        const status = rowNode.getAttribute('data-tag-status');
        const usage = rowNode.getAttribute('data-tag-usage');
        if (tagFilter.value === 'used' || tagFilter.value === 'unused') return usage === tagFilter.value;
        return status === tagFilter.value;
      });

      if ($table.length) {
        const postTagTable = $table.DataTable({
          autoWidth: false,
          info: false,
          language: { emptyTable: 'Chưa có tag nào.', zeroRecords: 'Không tìm thấy tag phù hợp trên trang này.' },
          lengthChange: false,
          order: [],
          paging: false,
          searching: true,
          columnDefs: [{ orderable: false, targets: [3, 4] }]
        });

        $('#postTagSearchInput').on('keyup', function () { postTagTable.search(this.value).draw(); });
        $('.tag-filter').on('click', function () {
          const $chip = $(this);
          tagFilter.value = $chip.data('filter');
          $('.tag-filter').removeClass('active');
          $chip.addClass('active');
          postTagTable.draw();
        });
      }

      $('#postTagQuickRefresh').on('click', function () { window.location.reload(); });
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
