@extends('backend.layouts.master')

@section('title', 'Web bán tai nghe || Quản lý bài viết')

@push('styles')
  <link href="{{ asset('backend/vendor/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.css" />
  <style>
    :root { --post-ink:#152238; --post-sky:#2563eb; --post-mint:#059669; --post-amber:#d97706; --post-border:#e2e8f0; --post-soft:#f8fbff; --post-shadow:0 18px 40px rgba(21,34,56,.08); }
    .post-page{padding-bottom:2rem}.post-hero,.post-card,.post-panel{border:0;border-radius:1.25rem;box-shadow:var(--post-shadow)}.post-hero{background:linear-gradient(135deg,#152238 0%,#1d4ed8 56%,#0f9d94 100%);color:#fff;padding:1.75rem;position:relative;overflow:hidden}.post-hero:after{content:'';position:absolute;right:-4rem;top:-4rem;width:14rem;height:14rem;border-radius:999px;background:rgba(255,255,255,.08)}.post-copy,.post-focus{position:relative;z-index:1}.post-kicker{font-size:.78rem;font-weight:800;letter-spacing:.18em;text-transform:uppercase;margin-bottom:.8rem}.post-hero h1{font-size:2rem;font-weight:800;line-height:1.12;margin-bottom:.9rem;max-width:40rem}.post-hero p{color:rgba(255,255,255,.84);max-width:44rem;margin-bottom:0}.post-actions-top{display:flex;flex-wrap:wrap;gap:.75rem;margin-top:1.25rem}.post-actions-top .btn,.post-filter{border-radius:999px;font-weight:700}.post-focus{background:rgba(255,255,255,.12);border:1px solid rgba(255,255,255,.14);border-radius:1rem;height:100%;padding:1.15rem}.post-focus-label{font-size:.76rem;font-weight:800;letter-spacing:.08em;text-transform:uppercase;color:rgba(255,255,255,.72)}.post-focus-title{font-size:1.3rem;font-weight:800;margin:.3rem 0}.post-focus-sub{font-size:.9rem;color:rgba(255,255,255,.84)}.post-focus-grid{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:.75rem;margin-top:1rem}.post-focus-box{background:rgba(255,255,255,.08);border-radius:.95rem;padding:.85rem .95rem}.post-focus-box span{display:block;font-size:.75rem;color:rgba(255,255,255,.72);text-transform:uppercase}.post-focus-box strong{display:block;font-size:1.1rem;margin-top:.3rem}.post-card{color:#fff;height:100%;overflow:hidden;position:relative}.post-card:after{content:'';position:absolute;right:-1.5rem;top:-1.5rem;width:6.5rem;height:6.5rem;border-radius:999px;background:rgba(255,255,255,.08)}.post-card .card-body{position:relative;z-index:1}.post-card-primary{background:linear-gradient(140deg,#1d4ed8 0%,#2563eb 100%)}.post-card-success{background:linear-gradient(140deg,#047857 0%,#059669 100%)}.post-card-warning{background:linear-gradient(140deg,#b45309 0%,#d97706 100%)}.post-card-dark{background:linear-gradient(140deg,#334155 0%,#475569 100%)}.post-label{font-size:.76rem;font-weight:800;letter-spacing:.08em;text-transform:uppercase;margin-bottom:.7rem}.post-value{font-size:1.8rem;font-weight:800;line-height:1.1;margin-bottom:.55rem}.post-text{font-size:.88rem;opacity:.86;margin-bottom:.8rem}.post-chip,.post-badge,.post-pill,.post-action{display:inline-flex;align-items:center;font-weight:700}.post-chip{background:rgba(255,255,255,.15);border-radius:999px;padding:.35rem .7rem;font-size:.78rem}.post-panel{background:#fff;overflow:hidden}.post-panel .card-header{background:#fff;border-bottom:1px solid var(--post-border);padding:1.35rem 1.45rem 1rem}.post-panel .card-body{padding:1.35rem 1.45rem 1.45rem}.post-panel-title{font-size:1.08rem;font-weight:800;color:var(--post-ink)}.post-panel-sub{font-size:.9rem;color:#64748b;max-width:46rem;margin-bottom:0}.post-meta{display:flex;flex-wrap:wrap;gap:.6rem;justify-content:flex-end;margin-top:1rem}.post-badge{gap:.35rem;padding:.45rem .8rem;border-radius:999px;border:1px solid var(--post-border);background:var(--post-soft);font-size:.8rem;color:var(--post-ink)}.post-toolbar{display:flex;flex-wrap:wrap;justify-content:space-between;align-items:center;gap:1rem;margin-top:1.1rem}.post-filters{display:flex;flex-wrap:wrap;gap:.65rem}.post-filter{background:#fff;border:1px solid var(--post-border);color:#475569;padding:.65rem .95rem;display:inline-flex;gap:.45rem;align-items:center;font-size:.84rem}.post-filter span{background:#f1f5f9;border-radius:999px;padding:.12rem .45rem;min-width:1.7rem;text-align:center}.post-filter.active{background:linear-gradient(135deg,#152238 0%,#2563eb 100%);border-color:transparent;color:#fff}.post-filter.active span{background:rgba(255,255,255,.14);color:#fff}.post-search{position:relative;width:min(100%,24rem)}.post-search i{position:absolute;left:1rem;top:50%;transform:translateY(-50%);color:#64748b}.post-search .form-control{height:3rem;border-radius:999px;border:1px solid var(--post-border);padding-left:2.7rem;box-shadow:none}.post-search .form-control:focus{border-color:rgba(37,99,235,.45);box-shadow:0 0 0 .2rem rgba(37,99,235,.12)}.post-table thead th{border-top:0;border-bottom:1px solid var(--post-border);font-size:.76rem;font-weight:800;letter-spacing:.08em;text-transform:uppercase;color:#718096;padding:1rem}.post-table tbody td{border-color:var(--post-border);padding:1rem;vertical-align:top}.post-table tbody tr:hover{background:#fbfdff}.post-main{display:flex;gap:.9rem}.post-thumb{width:108px;height:72px;border-radius:1rem;object-fit:cover;flex:0 0 108px;border:1px solid #e2e8f0;background:#f8fafc}.post-title{font-size:.96rem;font-weight:800;color:var(--post-ink);margin-bottom:.25rem}.post-subtext{font-size:.84rem;color:#64748b;line-height:1.55}.post-pill-row,.post-row-actions{display:flex;flex-wrap:wrap;gap:.5rem}.post-pill{border-radius:999px;padding:.46rem .8rem;font-size:.78rem;line-height:1}.post-photo-ready{background:rgba(5,150,105,.14);color:#047857}.post-photo-missing{background:rgba(220,38,38,.14);color:#b91c1c}.post-comment-live{background:rgba(37,99,235,.14);color:#1d4ed8}.post-comment-idle{background:rgba(217,119,6,.16);color:#b45309}.post-status-active{background:rgba(16,185,129,.14);color:#047857}.post-status-inactive{background:rgba(148,163,184,.18);color:#475569}.post-tag-chip{display:inline-flex;align-items:center;padding:.28rem .6rem;border-radius:999px;background:#eef2ff;color:#1d4ed8;font-size:.75rem;font-weight:700}.post-tag-list{display:flex;flex-wrap:wrap;gap:.4rem}.post-action{justify-content:center;gap:.4rem;min-width:5rem;padding:.66rem .85rem;border-radius:.85rem;text-decoration:none;font-size:.82rem}.post-action:hover,.post-action:focus{text-decoration:none}.post-action-edit{background:rgba(37,99,235,.14);color:#1d4ed8}.post-action-delete{background:rgba(239,68,68,.14);color:#b91c1c;border:0}.post-empty{border:1px dashed var(--post-border);border-radius:1rem;padding:2rem 1.5rem;text-align:center;background:linear-gradient(180deg,#fff 0%,#f8fbff 100%)}.post-empty-icon{width:4rem;height:4rem;border-radius:999px;display:inline-flex;align-items:center;justify-content:center;background:rgba(37,99,235,.1);color:var(--post-sky);font-size:1.35rem;margin-bottom:1rem}.post-pagination{margin-top:1.25rem}.post-pagination .pagination{justify-content:flex-end;margin-bottom:0}div.dataTables_wrapper div.dataTables_filter,div.dataTables_wrapper div.dataTables_length,div.dataTables_wrapper div.dataTables_info,div.dataTables_wrapper div.dataTables_paginate{display:none}table.dataTable{border-collapse:collapse!important;margin-top:0!important;width:100%!important}@media (max-width:991.98px){.post-hero h1{font-size:1.65rem}.post-meta{justify-content:flex-start}}@media (max-width:767.98px){.post-focus-grid{grid-template-columns:1fr}.post-search{width:100%}.post-main{flex-direction:column}.post-thumb{width:100%;height:180px;flex:auto}.post-pagination .pagination{justify-content:center}}
  </style>
@endpush

@section('main-content')
  @php
    $focusPost = ($mostDiscussedPost && $mostDiscussedPost->comments_count > 0) ? $mostDiscussedPost : $latestPost;
  @endphp
  <div class="container-fluid post-page">
    @include('backend.layouts.notification')

    <div class="post-hero mb-4">
      <div class="row align-items-stretch">
        <div class="col-xl-7 mb-4 mb-xl-0">
          <div class="post-copy">
            <div class="post-kicker">Tòa Soạn Nội Dung</div>
            <h1>Quản lý bài viết rõ hơn, nhìn nhanh nội dung nào đang hoạt động và bài nào có tương tác.</h1>
            <p>Trang này gom ảnh đại diện, tóm tắt, danh mục, tag, tác giả và mức độ bình luận vào một bảng gọn để admin kiểm soát kho bài viết nhanh hơn.</p>
            <div class="post-actions-top">
              <a href="{{ route('post.create') }}" class="btn btn-light btn-sm"><i class="fas fa-plus mr-1"></i> Thêm bài viết</a>
              <button type="button" id="postQuickRefresh" class="btn btn-outline-light btn-sm"><i class="fas fa-sync-alt mr-1"></i> Làm mới</button>
            </div>
          </div>
        </div>
        <div class="col-xl-5">
          <div class="post-focus">
            <div class="post-focus-label">Bài viết nổi bật</div>
            @if ($focusPost)
              <div class="post-focus-title">{{ $focusPost->title }}</div>
              <div class="post-focus-sub">
                {{ \Illuminate\Support\Str::limit(strip_tags($focusPost->summary), 110) }}
                @if ($focusPost->comments_count > 0)
                  - có {{ number_format($focusPost->comments_count, 0, ',', '.') }} bình luận active.
                @elseif ($focusPost->created_at)
                  - tạo {{ $focusPost->created_at->diffForHumans() }}.
                @endif
              </div>
            @else
              <div class="post-focus-title">Chưa có bài viết</div>
              <div class="post-focus-sub">Thông tin bài viết nổi bật sẽ xuất hiện tại đây khi hệ thống có nội dung.</div>
            @endif
            <div class="post-focus-grid">
              <div class="post-focus-box"><span>Đang hoạt động</span><strong>{{ number_format($activePosts, 0, ',', '.') }}</strong></div>
              <div class="post-focus-box"><span>Có ảnh</span><strong>{{ number_format($postsWithPhoto, 0, ',', '.') }}</strong></div>
              <div class="post-focus-box"><span>Có tag</span><strong>{{ number_format($postsWithTags, 0, ',', '.') }}</strong></div>
              <div class="post-focus-box"><span>Có bình luận</span><strong>{{ number_format($postsWithComments, 0, ',', '.') }}</strong></div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="row">
      <div class="col-xl-3 col-md-6 mb-4">
        <div class="card post-card post-card-primary"><div class="card-body"><div class="post-label">Tổng bài viết</div><div class="post-value">{{ number_format($totalPosts, 0, ',', '.') }}</div><div class="post-text">Tổng số bài viết đang được quản lý trong kho nội dung.</div><span class="post-chip">{{ number_format($inactivePosts, 0, ',', '.') }} bài tạm ẩn</span></div></div>
      </div>
      <div class="col-xl-3 col-md-6 mb-4">
        <div class="card post-card post-card-success"><div class="card-body"><div class="post-label">Đang hoạt động</div><div class="post-value">{{ number_format($activePosts, 0, ',', '.') }}</div><div class="post-text">Những bài viết đang có thể hiển thị trên blog và chuyên mục tin tức.</div><span class="post-chip">{{ number_format($newPostsThisMonth, 0, ',', '.') }} bài mới tháng này</span></div></div>
      </div>
      <div class="col-xl-3 col-md-6 mb-4">
        <div class="card post-card post-card-warning"><div class="card-body"><div class="post-label">Có ảnh đại diện</div><div class="post-value">{{ number_format($postsWithPhoto, 0, ',', '.') }}</div><div class="post-text">Nhóm bài viết đã có ảnh minh họa sẵn sàng cho listing và chi tiết.</div><span class="post-chip">{{ number_format($postsWithoutPhoto, 0, ',', '.') }} bài thiếu ảnh</span></div></div>
      </div>
      <div class="col-xl-3 col-md-6 mb-4">
        <div class="card post-card post-card-dark"><div class="card-body"><div class="post-label">Có bình luận</div><div class="post-value">{{ number_format($postsWithComments, 0, ',', '.') }}</div><div class="post-text">Số bài viết đã phát sinh tương tác từ người đọc qua phần bình luận.</div><span class="post-chip">{{ number_format($postsWithTags, 0, ',', '.') }} bài đã gắn tag</span></div></div>
      </div>
    </div>

    <div class="card post-panel">
      <div class="card-header">
        <div class="row align-items-lg-center">
          <div class="col-lg-8">
            <div class="post-panel-title">Danh sách bài viết</div>
            <p class="post-panel-sub">Tìm theo tiêu đề, danh mục, tag hoặc tác giả. Bạn cũng có thể lọc nhanh bài active, bài có bình luận và các bài đang thiếu ảnh đại diện để xử lý đúng nhóm nội dung.</p>
          </div>
          <div class="col-lg-4">
            <div class="post-meta">
              <span class="post-badge"><i class="fas fa-newspaper"></i> Hiển thị {{ number_format($posts->count(), 0, ',', '.') }} / {{ number_format($totalPosts, 0, ',', '.') }} bài</span>
              <span class="post-badge"><i class="fas fa-comments"></i> {{ number_format($postsWithComments, 0, ',', '.') }} bài có bình luận</span>
            </div>
          </div>
        </div>
        <div class="post-toolbar">
          <div class="post-filters" role="group" aria-label="Lọc bài viết">
            <button type="button" class="post-filter active" data-filter="all">Tất cả <span>{{ number_format($totalPosts, 0, ',', '.') }}</span></button>
            <button type="button" class="post-filter" data-filter="active">Active <span>{{ number_format($activePosts, 0, ',', '.') }}</span></button>
            <button type="button" class="post-filter" data-filter="inactive">Inactive <span>{{ number_format($inactivePosts, 0, ',', '.') }}</span></button>
            <button type="button" class="post-filter" data-filter="commented">Có bình luận <span>{{ number_format($postsWithComments, 0, ',', '.') }}</span></button>
            <button type="button" class="post-filter" data-filter="missing-photo">Thiếu ảnh <span>{{ number_format($postsWithoutPhoto, 0, ',', '.') }}</span></button>
          </div>
          <div class="post-search">
            <i class="fas fa-search"></i>
            <input type="text" id="postSearchInput" class="form-control" placeholder="Tìm theo bài viết, danh mục, tag, tác giả...">
          </div>
        </div>
      </div>
      <div class="card-body">
        @if ($posts->count())
          <div class="table-responsive">
            <table class="table post-table" id="post-dataTable" width="100%" cellspacing="0">
              <thead>
                <tr>
                  <th>Bài viết</th>
                  <th>Phân loại</th>
                  <th>Tác giả</th>
                  <th>Tương tác</th>
                  <th>Trạng thái</th>
                  <th>Thao tác</th>
                </tr>
              </thead>
              <tbody>
                @foreach ($posts as $post)
                  @php
                    $photoUrl = null;
                    if (!empty($post->photo)) {
                        $photoUrl = \Illuminate\Support\Str::startsWith($post->photo, ['http://', 'https://'])
                            ? $post->photo
                            : asset(ltrim($post->photo, '/'));
                    }
                    $tagList = collect(explode(',', (string) $post->tags))->map(function ($tag) {
                        return trim($tag);
                    })->filter()->values();
                  @endphp
                  <tr data-post-status="{{ $post->status }}" data-post-photo="{{ $photoUrl ? 'yes' : 'missing' }}" data-post-comment="{{ $post->comments_count > 0 ? 'commented' : 'quiet' }}">
                    <td>
                      <div class="post-main">
                        <img src="{{ $photoUrl ?: asset('backend/img/thumbnail-default.jpg') }}" class="post-thumb" alt="{{ $post->title }}">
                        <div>
                          <div class="post-title">{{ $post->title }}</div>
                          <div class="post-subtext">#{{ $post->id }}@if ($post->created_at) - tạo {{ $post->created_at->diffForHumans() }}@endif</div>
                          <div class="post-subtext mt-2">{{ \Illuminate\Support\Str::limit(strip_tags($post->summary), 120) }}</div>
                          <div class="post-pill-row mt-2">
                            <span class="post-pill {{ $photoUrl ? 'post-photo-ready' : 'post-photo-missing' }}">{{ $photoUrl ? 'Có ảnh đại diện' : 'Thiếu ảnh đại diện' }}</span>
                          </div>
                        </div>
                      </div>
                    </td>
                    <td>
                      <div class="post-title">{{ optional($post->cat_info)->title ?: 'Chưa gán danh mục' }}</div>
                      <div class="post-subtext mb-2">{{ optional($post->cat_info)->slug ?: 'Danh mục chưa có slug hoặc chưa được gán.' }}</div>
                      @if ($tagList->count())
                        <div class="post-tag-list">
                          @foreach ($tagList->take(4) as $tag)
                            <span class="post-tag-chip">{{ $tag }}</span>
                          @endforeach
                          @if ($tagList->count() > 4)
                            <span class="post-tag-chip">+{{ $tagList->count() - 4 }}</span>
                          @endif
                        </div>
                      @else
                        <div class="post-subtext">Chưa gắn tag cho bài viết này.</div>
                      @endif
                    </td>
                    <td>
                      <div class="post-title">{{ optional($post->author_info)->name ?: 'Chưa gán tác giả' }}</div>
                      <div class="post-subtext">{{ optional($post->author_info)->email ?: 'Không có email tác giả để hiển thị.' }}</div>
                    </td>
                    <td>
                      <div class="post-pill-row mb-2"><span class="post-pill {{ $post->comments_count > 0 ? 'post-comment-live' : 'post-comment-idle' }}">{{ number_format($post->comments_count, 0, ',', '.') }} bình luận</span></div>
                      <div class="post-subtext">{{ $post->comments_count > 0 ? 'Bài viết đã có tương tác từ người đọc.' : 'Chưa phát sinh bình luận active nào.' }}</div>
                    </td>
                    <td>
                      <div class="post-pill-row mb-2"><span class="post-pill {{ $post->status === 'active' ? 'post-status-active' : 'post-status-inactive' }}">{{ $post->status === 'active' ? 'Đang hoạt động' : 'Tạm ẩn' }}</span></div>
                      <div class="post-subtext">{{ $post->status === 'active' ? 'Có thể hiển thị trên blog ngoài trang chủ.' : 'Đang tạm dừng khỏi khu vực hiển thị.' }}</div>
                    </td>
                    <td>
                      <div class="post-row-actions">
                        <a href="{{ route('post.edit', $post->id) }}" class="post-action post-action-edit" data-toggle="tooltip" title="Chỉnh sửa bài viết"><i class="fas fa-pen"></i> <span>Sửa</span></a>
                        <form method="POST" action="{{ route('post.destroy', [$post->id]) }}">
                          @csrf
                          @method('delete')
                          <button class="post-action post-action-delete dltBtn" data-id="{{ $post->id }}" data-toggle="tooltip" title="Xóa bài viết"><i class="fas fa-trash-alt"></i> <span>Xóa</span></button>
                        </form>
                      </div>
                    </td>
                  </tr>
                @endforeach
              </tbody>
            </table>
          </div>
          <div class="post-pagination">{{ $posts->links() }}</div>
        @else
          <div class="post-empty">
            <div class="post-empty-icon"><i class="fas fa-newspaper"></i></div>
            <h3>Chưa có bài viết nào</h3>
            <p class="text-muted mb-4">Hãy tạo bài viết đầu tiên để bắt đầu xây dựng khu nội dung và blog cho cửa hàng.</p>
            <a href="{{ route('post.create') }}" class="btn btn-primary btn-sm"><i class="fas fa-plus mr-1"></i> Thêm bài viết</a>
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
      const $table = $('#post-dataTable');
      const postFilter = { value: 'all' };

      $.fn.dataTable.ext.search.push(function (settings, data, dataIndex) {
        if (settings.nTable.id !== 'post-dataTable') return true;
        if (postFilter.value === 'all') return true;
        const rowNode = settings.aoData[dataIndex] && settings.aoData[dataIndex].nTr;
        if (!rowNode) return true;
        const status = rowNode.getAttribute('data-post-status');
        const photo = rowNode.getAttribute('data-post-photo');
        const comment = rowNode.getAttribute('data-post-comment');
        if (postFilter.value === 'missing-photo') return photo === 'missing';
        if (postFilter.value === 'commented') return comment === 'commented';
        return status === postFilter.value;
      });

      if ($table.length) {
        const postTable = $table.DataTable({
          autoWidth: false,
          info: false,
          language: { emptyTable: 'Chưa có bài viết nào.', zeroRecords: 'Không tìm thấy bài viết phù hợp trên trang này.' },
          lengthChange: false,
          order: [],
          paging: false,
          searching: true,
          columnDefs: [{ orderable: false, targets: [4, 5] }]
        });

        $('#postSearchInput').on('keyup', function () { postTable.search(this.value).draw(); });
        $('.post-filter').on('click', function () {
          const $chip = $(this);
          postFilter.value = $chip.data('filter');
          $('.post-filter').removeClass('active');
          $chip.addClass('active');
          postTable.draw();
        });
      }

      $('#postQuickRefresh').on('click', function () { window.location.reload(); });
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
