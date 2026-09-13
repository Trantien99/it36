@extends('backend.layouts.master')

@section('title', 'Web bán tai nghe || Quản lý bình luận')

@push('styles')
  <link href="{{ asset('backend/vendor/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.css" />
  <style>
    :root { --comment-ink:#1f2937; --comment-sky:#2563eb; --comment-mint:#059669; --comment-amber:#d97706; --comment-rose:#dc2626; --comment-border:#e2e8f0; --comment-soft:#f8fbff; --comment-shadow:0 18px 40px rgba(31,41,55,.08); }
    .comment-page{padding-bottom:2rem}.comment-hero,.comment-card,.comment-panel{border:0;border-radius:1.25rem;box-shadow:var(--comment-shadow)}.comment-hero{background:linear-gradient(135deg,#1f2937 0%,#1d4ed8 58%,#0f9d94 100%);color:#fff;padding:1.75rem;position:relative;overflow:hidden}.comment-hero:after{content:'';position:absolute;right:-4rem;top:-4rem;width:14rem;height:14rem;border-radius:999px;background:rgba(255,255,255,.08)}.comment-copy,.comment-focus{position:relative;z-index:1}.comment-kicker{font-size:.78rem;font-weight:800;letter-spacing:.18em;text-transform:uppercase;margin-bottom:.8rem}.comment-hero h1{font-size:2rem;font-weight:800;line-height:1.12;margin-bottom:.9rem;max-width:40rem}.comment-hero p{color:rgba(255,255,255,.84);max-width:43rem;margin-bottom:0}.comment-actions-top{display:flex;flex-wrap:wrap;gap:.75rem;margin-top:1.25rem}.comment-actions-top .btn,.comment-filter{border-radius:999px;font-weight:700}.comment-focus{background:rgba(255,255,255,.12);border:1px solid rgba(255,255,255,.14);border-radius:1rem;height:100%;padding:1.15rem}.comment-focus-label{font-size:.76rem;font-weight:800;letter-spacing:.08em;text-transform:uppercase;color:rgba(255,255,255,.72)}.comment-focus-title{font-size:1.3rem;font-weight:800;margin:.3rem 0}.comment-focus-sub{font-size:.9rem;color:rgba(255,255,255,.84)}.comment-focus-grid{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:.75rem;margin-top:1rem}.comment-focus-box{background:rgba(255,255,255,.08);border-radius:.95rem;padding:.85rem .95rem}.comment-focus-box span{display:block;font-size:.75rem;color:rgba(255,255,255,.72);text-transform:uppercase}.comment-focus-box strong{display:block;font-size:1.1rem;margin-top:.3rem}.comment-card{color:#fff;height:100%;overflow:hidden;position:relative}.comment-card:after{content:'';position:absolute;right:-1.5rem;top:-1.5rem;width:6.5rem;height:6.5rem;border-radius:999px;background:rgba(255,255,255,.08)}.comment-card .card-body{position:relative;z-index:1}.comment-card-primary{background:linear-gradient(140deg,#1d4ed8 0%,#2563eb 100%)}.comment-card-success{background:linear-gradient(140deg,#047857 0%,#059669 100%)}.comment-card-warning{background:linear-gradient(140deg,#b45309 0%,#d97706 100%)}.comment-card-dark{background:linear-gradient(140deg,#334155 0%,#475569 100%)}.comment-label{font-size:.76rem;font-weight:800;letter-spacing:.08em;text-transform:uppercase;margin-bottom:.7rem}.comment-value{font-size:1.8rem;font-weight:800;line-height:1.1;margin-bottom:.55rem}.comment-text{font-size:.88rem;opacity:.86;margin-bottom:.8rem}.comment-chip,.comment-badge,.comment-pill,.comment-action{display:inline-flex;align-items:center;font-weight:700}.comment-chip{background:rgba(255,255,255,.15);border-radius:999px;padding:.35rem .7rem;font-size:.78rem}.comment-panel{background:#fff;overflow:hidden}.comment-panel .card-header{background:#fff;border-bottom:1px solid var(--comment-border);padding:1.35rem 1.45rem 1rem}.comment-panel .card-body{padding:1.35rem 1.45rem 1.45rem}.comment-panel-title{font-size:1.08rem;font-weight:800;color:var(--comment-ink)}.comment-panel-sub{font-size:.9rem;color:#64748b;max-width:46rem;margin-bottom:0}.comment-meta{display:flex;flex-wrap:wrap;gap:.6rem;justify-content:flex-end;margin-top:1rem}.comment-badge{gap:.35rem;padding:.45rem .8rem;border-radius:999px;border:1px solid var(--comment-border);background:var(--comment-soft);font-size:.8rem;color:var(--comment-ink)}.comment-toolbar{display:flex;flex-wrap:wrap;justify-content:space-between;align-items:center;gap:1rem;margin-top:1.1rem}.comment-filters{display:flex;flex-wrap:wrap;gap:.65rem}.comment-filter{background:#fff;border:1px solid var(--comment-border);color:#475569;padding:.65rem .95rem;display:inline-flex;gap:.45rem;align-items:center;font-size:.84rem}.comment-filter span{background:#f1f5f9;border-radius:999px;padding:.12rem .45rem;min-width:1.7rem;text-align:center}.comment-filter.active{background:linear-gradient(135deg,#1f2937 0%,#2563eb 100%);border-color:transparent;color:#fff}.comment-filter.active span{background:rgba(255,255,255,.14);color:#fff}.comment-search{position:relative;width:min(100%,22rem)}.comment-search i{position:absolute;left:1rem;top:50%;transform:translateY(-50%);color:#64748b}.comment-search .form-control{height:3rem;border-radius:999px;border:1px solid var(--comment-border);padding-left:2.7rem;box-shadow:none}.comment-search .form-control:focus{border-color:rgba(37,99,235,.45);box-shadow:0 0 0 .2rem rgba(37,99,235,.12)}.comment-table thead th{border-top:0;border-bottom:1px solid var(--comment-border);font-size:.76rem;font-weight:800;letter-spacing:.08em;text-transform:uppercase;color:#718096;padding:1rem}.comment-table tbody td{border-color:var(--comment-border);padding:1rem;vertical-align:top}.comment-table tbody tr:hover{background:#fbfdff}.comment-main{display:flex;gap:.9rem}.comment-avatar{width:56px;height:56px;border-radius:999px;object-fit:cover;flex:0 0 56px;border:2px solid #e2e8f0}.comment-title{font-size:.96rem;font-weight:800;color:var(--comment-ink);margin-bottom:.25rem}.comment-subtext{font-size:.84rem;color:#64748b;line-height:1.55}.comment-pill-row,.comment-row-actions{display:flex;flex-wrap:wrap;gap:.5rem}.comment-pill{border-radius:999px;padding:.46rem .8rem;font-size:.78rem;line-height:1}.comment-kind-root{background:rgba(37,99,235,.14);color:#1d4ed8}.comment-kind-reply{background:rgba(217,119,6,.16);color:#b45309}.comment-post-linked{background:rgba(5,150,105,.14);color:#047857}.comment-post-orphan{background:rgba(220,38,38,.14);color:#b91c1c}.comment-status-active{background:rgba(16,185,129,.14);color:#047857}.comment-status-inactive{background:rgba(148,163,184,.18);color:#475569}.comment-action{justify-content:center;gap:.4rem;min-width:5rem;padding:.66rem .85rem;border-radius:.85rem;text-decoration:none;font-size:.82rem}.comment-action:hover,.comment-action:focus{text-decoration:none}.comment-action-edit{background:rgba(37,99,235,.14);color:#1d4ed8}.comment-action-delete{background:rgba(239,68,68,.14);color:#b91c1c;border:0}.comment-empty{border:1px dashed var(--comment-border);border-radius:1rem;padding:2rem 1.5rem;text-align:center;background:linear-gradient(180deg,#fff 0%,#f8fbff 100%)}.comment-empty-icon{width:4rem;height:4rem;border-radius:999px;display:inline-flex;align-items:center;justify-content:center;background:rgba(37,99,235,.1);color:var(--comment-sky);font-size:1.35rem;margin-bottom:1rem}.comment-pagination{margin-top:1.25rem}.comment-pagination .pagination{justify-content:flex-end;margin-bottom:0}div.dataTables_wrapper div.dataTables_filter,div.dataTables_wrapper div.dataTables_length,div.dataTables_wrapper div.dataTables_info,div.dataTables_wrapper div.dataTables_paginate{display:none}table.dataTable{border-collapse:collapse!important;margin-top:0!important;width:100%!important}@media (max-width:991.98px){.comment-hero h1{font-size:1.65rem}.comment-meta{justify-content:flex-start}}@media (max-width:767.98px){.comment-focus-grid{grid-template-columns:1fr}.comment-search{width:100%}.comment-pagination .pagination{justify-content:center}}
  </style>
@endpush

@section('main-content')
  <div class="container-fluid comment-page">
    @include('backend.layouts.notification')

    <div class="comment-hero mb-4">
      <div class="row align-items-stretch">
        <div class="col-xl-7 mb-4 mb-xl-0">
          <div class="comment-copy">
            <div class="comment-kicker">Trung Tâm Kiểm Duyệt Bình Luận</div>
            <h1>Theo dõi bình luận rõ hơn, tách nhanh bình luận gốc, phản hồi và các mục cần xử lý.</h1>
            <p>Trang này giúp admin duyệt bình luận nhanh hơn với bộ lọc theo trạng thái, loại bình luận, bài viết liên quan và preview nội dung ngay trên danh sách.</p>
            <div class="comment-actions-top">
              <button type="button" id="commentQuickRefresh" class="btn btn-light btn-sm"><i class="fas fa-sync-alt mr-1"></i> Làm mới danh sách</button>
            </div>
          </div>
        </div>
        <div class="col-xl-5">
          <div class="comment-focus">
            <div class="comment-focus-label">Bình luận mới nhất</div>
            @if ($latestComment)
              <div class="comment-focus-title">{{ optional($latestComment->user_info)->name ?: 'Người dùng không xác định' }}</div>
              <div class="comment-focus-sub">
                {{ \Illuminate\Support\Str::limit(strip_tags($latestComment->comment), 120) }}
                @if ($latestComment->created_at)
                  - {{ $latestComment->created_at->diffForHumans() }}.
                @endif
              </div>
            @else
              <div class="comment-focus-title">Chưa có bình luận</div>
              <div class="comment-focus-sub">Thông tin bình luận mới nhất sẽ hiển thị ở đây ngay khi hệ thống có dữ liệu.</div>
            @endif
            <div class="comment-focus-grid">
              <div class="comment-focus-box"><span>Đang hoạt động</span><strong>{{ number_format($activeComments, 0, ',', '.') }}</strong></div>
              <div class="comment-focus-box"><span>Tạm ẩn</span><strong>{{ number_format($inactiveComments, 0, ',', '.') }}</strong></div>
              <div class="comment-focus-box"><span>Bình luận gốc</span><strong>{{ number_format($rootComments, 0, ',', '.') }}</strong></div>
              <div class="comment-focus-box"><span>Phản hồi</span><strong>{{ number_format($replyComments, 0, ',', '.') }}</strong></div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="row">
      <div class="col-xl-3 col-md-6 mb-4">
        <div class="card comment-card comment-card-primary"><div class="card-body"><div class="comment-label">Tổng bình luận</div><div class="comment-value">{{ number_format($totalComments, 0, ',', '.') }}</div><div class="comment-text">Tổng số bình luận và phản hồi đã được ghi nhận trong hệ thống bài viết.</div><span class="comment-chip">{{ number_format($commentsToday, 0, ',', '.') }} bình luận hôm nay</span></div></div>
      </div>
      <div class="col-xl-3 col-md-6 mb-4">
        <div class="card comment-card comment-card-success"><div class="card-body"><div class="comment-label">Đang hoạt động</div><div class="comment-value">{{ number_format($activeComments, 0, ',', '.') }}</div><div class="comment-text">Những bình luận đang hiển thị hoặc sẵn sàng hiển thị trên bài viết.</div><span class="comment-chip">{{ number_format($inactiveComments, 0, ',', '.') }} bình luận tạm ẩn</span></div></div>
      </div>
      <div class="col-xl-3 col-md-6 mb-4">
        <div class="card comment-card comment-card-warning"><div class="card-body"><div class="comment-label">Bình luận gốc</div><div class="comment-value">{{ number_format($rootComments, 0, ',', '.') }}</div><div class="comment-text">Nhóm bình luận mở đầu cuộc thảo luận trên từng bài viết.</div><span class="comment-chip">{{ number_format($replyComments, 0, ',', '.') }} phản hồi</span></div></div>
      </div>
      <div class="col-xl-3 col-md-6 mb-4">
        <div class="card comment-card comment-card-dark"><div class="card-body"><div class="comment-label">Mồ côi bài viết</div><div class="comment-value">{{ number_format($orphanComments, 0, ',', '.') }}</div><div class="comment-text">Bình luận không còn liên kết tới bài viết gốc và nên được kiểm tra lại.</div><span class="comment-chip">{{ number_format($commentsWithPost, 0, ',', '.') }} bình luận còn liên kết</span></div></div>
      </div>
    </div>

    <div class="card comment-panel">
      <div class="card-header">
        <div class="row align-items-lg-center">
          <div class="col-lg-8">
            <div class="comment-panel-title">Danh sách bình luận</div>
            <p class="comment-panel-sub">Tìm theo tác giả, bài viết hoặc nội dung. Bạn cũng có thể lọc nhanh bình luận active, phản hồi hoặc các bình luận mồ côi để xử lý từng nhóm dễ hơn.</p>
          </div>
          <div class="col-lg-4">
            <div class="comment-meta">
              <span class="comment-badge"><i class="fas fa-comments"></i> Hiển thị {{ number_format($comments->count(), 0, ',', '.') }} / {{ number_format($totalComments, 0, ',', '.') }} bình luận</span>
              <span class="comment-badge"><i class="fas fa-comment-dots"></i> {{ number_format($replyComments, 0, ',', '.') }} phản hồi</span>
            </div>
          </div>
        </div>
        <div class="comment-toolbar">
          <div class="comment-filters" role="group" aria-label="Lọc bình luận">
            <button type="button" class="comment-filter active" data-filter="all">Tất cả <span>{{ number_format($totalComments, 0, ',', '.') }}</span></button>
            <button type="button" class="comment-filter" data-filter="active">Active <span>{{ number_format($activeComments, 0, ',', '.') }}</span></button>
            <button type="button" class="comment-filter" data-filter="inactive">Inactive <span>{{ number_format($inactiveComments, 0, ',', '.') }}</span></button>
            <button type="button" class="comment-filter" data-filter="root">Bình luận gốc <span>{{ number_format($rootComments, 0, ',', '.') }}</span></button>
            <button type="button" class="comment-filter" data-filter="reply">Phản hồi <span>{{ number_format($replyComments, 0, ',', '.') }}</span></button>
            <button type="button" class="comment-filter" data-filter="orphan">Mồ côi <span>{{ number_format($orphanComments, 0, ',', '.') }}</span></button>
          </div>
          <div class="comment-search">
            <i class="fas fa-search"></i>
            <input type="text" id="commentSearchInput" class="form-control" placeholder="Tìm theo tác giả, bài viết, nội dung...">
          </div>
        </div>
      </div>
      <div class="card-body">
        @if ($comments->count())
          <div class="table-responsive">
            <table class="table comment-table" id="comment-dataTable" width="100%" cellspacing="0">
              <thead>
                <tr>
                  <th>Tác giả</th>
                  <th>Bài viết</th>
                  <th>Loại</th>
                  <th>Nội dung</th>
                  <th>Trạng thái</th>
                  <th>Thời gian</th>
                  <th>Thao tác</th>
                </tr>
              </thead>
              <tbody>
                @foreach ($comments as $comment)
                  @php
                    $avatarUrl = asset('backend/img/avatar.png');
                    if (!empty(optional($comment->user_info)->photo)) {
                        $avatarUrl = \Illuminate\Support\Str::startsWith($comment->user_info->photo, ['http://', 'https://'])
                            ? $comment->user_info->photo
                            : asset(ltrim($comment->user_info->photo, '/'));
                    }
                    $commentKind = $comment->parent_id ? 'reply' : 'root';
                    $hasPost = !is_null($comment->post_id) && !is_null($comment->post);
                  @endphp
                  <tr data-comment-status="{{ $comment->status }}" data-comment-kind="{{ $commentKind }}" data-comment-post="{{ $hasPost ? 'linked' : 'orphan' }}">
                    <td>
                      <div class="comment-main">
                        <img src="{{ $avatarUrl }}" class="comment-avatar" alt="{{ optional($comment->user_info)->name ?: 'avatar' }}">
                        <div>
                          <div class="comment-title">{{ optional($comment->user_info)->name ?: 'Người dùng không xác định' }}</div>
                          <div class="comment-subtext">#{{ $comment->id }}@if ($comment->created_at) - gửi {{ $comment->created_at->diffForHumans() }}@endif</div>
                        </div>
                      </div>
                    </td>
                    <td>
                      <div class="comment-pill-row mb-2"><span class="comment-pill {{ $hasPost ? 'comment-post-linked' : 'comment-post-orphan' }}">{{ $hasPost ? 'Có bài viết' : 'Mồ côi' }}</span></div>
                      <div class="comment-title">{{ optional($comment->post)->title ?: 'Không còn bài viết liên kết' }}</div>
                      <div class="comment-subtext">{{ $hasPost ? 'Bình luận đang gắn với một bài viết hợp lệ.' : 'Nên kiểm tra lại vì bài viết gốc có thể đã bị xóa.' }}</div>
                    </td>
                    <td>
                      <div class="comment-pill-row mb-2"><span class="comment-pill {{ $commentKind === 'root' ? 'comment-kind-root' : 'comment-kind-reply' }}">{{ $commentKind === 'root' ? 'Bình luận gốc' : 'Phản hồi' }}</span></div>
                      <div class="comment-subtext">{{ $commentKind === 'root' ? 'Mở đầu một luồng thảo luận mới.' : 'Phản hồi nằm trong một chuỗi trao đổi.' }} {{ $comment->active_replies_count > 0 ? 'Có ' . $comment->active_replies_count . ' phản hồi active.' : '' }}</div>
                    </td>
                    <td>
                      <div class="comment-title">{{ \Illuminate\Support\Str::limit(strip_tags($comment->comment), 85) }}</div>
                      <div class="comment-subtext">{{ \Illuminate\Support\Str::limit(strip_tags($comment->replied_comment ?: $comment->comment), 140) }}</div>
                    </td>
                    <td>
                      <div class="comment-pill-row mb-2"><span class="comment-pill {{ $comment->status === 'active' ? 'comment-status-active' : 'comment-status-inactive' }}">{{ $comment->status === 'active' ? 'Đang hoạt động' : 'Tạm ẩn' }}</span></div>
                      <div class="comment-subtext">{{ $comment->status === 'active' ? 'Có thể hiển thị trên giao diện bài viết.' : 'Đã bị tạm ẩn khỏi khu vực thảo luận.' }}</div>
                    </td>
                    <td>
                      <div class="comment-title">{{ $comment->created_at ? $comment->created_at->format('d/m/Y') : 'Chưa rõ' }}</div>
                      <div class="comment-subtext">{{ $comment->created_at ? $comment->created_at->format('H:i') . ' - cập nhật ' . $comment->created_at->diffForHumans() : 'Chưa có dữ liệu thời gian.' }}</div>
                    </td>
                    <td>
                      <div class="comment-row-actions">
                        <a href="{{ route('comment.edit', $comment->id) }}" class="comment-action comment-action-edit" data-toggle="tooltip" title="Chỉnh sửa bình luận"><i class="fas fa-pen"></i> <span>Sửa</span></a>
                        <form method="POST" action="{{ route('comment.destroy', [$comment->id]) }}">
                          @csrf
                          @method('delete')
                          <button class="comment-action comment-action-delete dltBtn" data-id="{{ $comment->id }}" data-toggle="tooltip" title="Xóa bình luận"><i class="fas fa-trash-alt"></i> <span>Xóa</span></button>
                        </form>
                      </div>
                    </td>
                  </tr>
                @endforeach
              </tbody>
            </table>
          </div>
          <div class="comment-pagination">{{ $comments->links() }}</div>
        @else
          <div class="comment-empty">
            <div class="comment-empty-icon"><i class="fas fa-comments"></i></div>
            <h3>Chưa có bình luận nào</h3>
            <p class="text-muted mb-4">Danh sách sẽ xuất hiện khi bài viết bắt đầu có tương tác từ người dùng.</p>
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
      const $table = $('#comment-dataTable');
      const commentFilter = { value: 'all' };

      $.fn.dataTable.ext.search.push(function (settings, data, dataIndex) {
        if (settings.nTable.id !== 'comment-dataTable') return true;
        if (commentFilter.value === 'all') return true;
        const rowNode = settings.aoData[dataIndex] && settings.aoData[dataIndex].nTr;
        if (!rowNode) return true;
        const status = rowNode.getAttribute('data-comment-status');
        const kind = rowNode.getAttribute('data-comment-kind');
        const post = rowNode.getAttribute('data-comment-post');
        if (commentFilter.value === 'root' || commentFilter.value === 'reply') return kind === commentFilter.value;
        if (commentFilter.value === 'orphan') return post === 'orphan';
        return status === commentFilter.value;
      });

      if ($table.length) {
        const commentTable = $table.DataTable({
          autoWidth: false,
          info: false,
          language: { emptyTable: 'Chưa có bình luận nào.', zeroRecords: 'Không tìm thấy bình luận phù hợp trên trang này.' },
          lengthChange: false,
          order: [],
          paging: false,
          searching: true,
          columnDefs: [{ orderable: false, targets: [2, 4, 6] }]
        });

        $('#commentSearchInput').on('keyup', function () { commentTable.search(this.value).draw(); });
        $('.comment-filter').on('click', function () {
          const $chip = $(this);
          commentFilter.value = $chip.data('filter');
          $('.comment-filter').removeClass('active');
          $chip.addClass('active');
          commentTable.draw();
        });
      }

      $('#commentQuickRefresh').on('click', function () { window.location.reload(); });
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
