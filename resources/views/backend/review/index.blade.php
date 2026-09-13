@extends('backend.layouts.master')

@section('title', 'Web bán tai nghe || Quản lý đánh giá sản phẩm')

@push('styles')
  <link href="{{ asset('backend/vendor/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.css" />
  <style>
    :root { --review-ink:#1f2937; --review-sky:#2563eb; --review-mint:#059669; --review-amber:#d97706; --review-rose:#dc2626; --review-border:#e2e8f0; --review-soft:#f8fbff; --review-shadow:0 18px 40px rgba(31,41,55,.08); }
    .review-page{padding-bottom:2rem}.review-hero,.review-card,.review-panel{border:0;border-radius:1.25rem;box-shadow:var(--review-shadow)}.review-hero{background:linear-gradient(135deg,#1f2937 0%,#1d4ed8 58%,#0f9d94 100%);color:#fff;padding:1.75rem;position:relative;overflow:hidden}.review-hero:after{content:'';position:absolute;right:-4rem;top:-4rem;width:14rem;height:14rem;border-radius:999px;background:rgba(255,255,255,.08)}.review-copy,.review-focus{position:relative;z-index:1}.review-kicker{font-size:.78rem;font-weight:800;letter-spacing:.18em;text-transform:uppercase;margin-bottom:.8rem}.review-hero h1{font-size:2rem;font-weight:800;line-height:1.12;margin-bottom:.9rem;max-width:40rem}.review-hero p{color:rgba(255,255,255,.84);max-width:44rem;margin-bottom:0}.review-actions-top{display:flex;flex-wrap:wrap;gap:.75rem;margin-top:1.25rem}.review-actions-top .btn,.review-filter{border-radius:999px;font-weight:700}.review-focus{background:rgba(255,255,255,.12);border:1px solid rgba(255,255,255,.14);border-radius:1rem;height:100%;padding:1.15rem}.review-focus-label{font-size:.76rem;font-weight:800;letter-spacing:.08em;text-transform:uppercase;color:rgba(255,255,255,.72)}.review-focus-title{font-size:1.3rem;font-weight:800;margin:.3rem 0}.review-focus-sub{font-size:.9rem;color:rgba(255,255,255,.84)}.review-focus-grid{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:.75rem;margin-top:1rem}.review-focus-box{background:rgba(255,255,255,.08);border-radius:.95rem;padding:.85rem .95rem}.review-focus-box span{display:block;font-size:.75rem;color:rgba(255,255,255,.72);text-transform:uppercase}.review-focus-box strong{display:block;font-size:1.1rem;margin-top:.3rem}.review-card{color:#fff;height:100%;overflow:hidden;position:relative}.review-card:after{content:'';position:absolute;right:-1.5rem;top:-1.5rem;width:6.5rem;height:6.5rem;border-radius:999px;background:rgba(255,255,255,.08)}.review-card .card-body{position:relative;z-index:1}.review-card-primary{background:linear-gradient(140deg,#1d4ed8 0%,#2563eb 100%)}.review-card-success{background:linear-gradient(140deg,#047857 0%,#059669 100%)}.review-card-warning{background:linear-gradient(140deg,#b45309 0%,#d97706 100%)}.review-card-dark{background:linear-gradient(140deg,#334155 0%,#475569 100%)}.review-label{font-size:.76rem;font-weight:800;letter-spacing:.08em;text-transform:uppercase;margin-bottom:.7rem}.review-value{font-size:1.8rem;font-weight:800;line-height:1.1;margin-bottom:.55rem}.review-text{font-size:.88rem;opacity:.86;margin-bottom:.8rem}.review-chip,.review-badge,.review-pill,.review-action{display:inline-flex;align-items:center;font-weight:700}.review-chip{background:rgba(255,255,255,.15);border-radius:999px;padding:.35rem .7rem;font-size:.78rem}.review-panel{background:#fff;overflow:hidden}.review-panel .card-header{background:#fff;border-bottom:1px solid var(--review-border);padding:1.35rem 1.45rem 1rem}.review-panel .card-body{padding:1.35rem 1.45rem 1.45rem}.review-panel-title{font-size:1.08rem;font-weight:800;color:var(--review-ink)}.review-panel-sub{font-size:.9rem;color:#64748b;max-width:46rem;margin-bottom:0}.review-meta{display:flex;flex-wrap:wrap;gap:.6rem;justify-content:flex-end;margin-top:1rem}.review-badge{gap:.35rem;padding:.45rem .8rem;border-radius:999px;border:1px solid var(--review-border);background:var(--review-soft);font-size:.8rem;color:var(--review-ink)}.review-toolbar{display:flex;flex-wrap:wrap;justify-content:space-between;align-items:center;gap:1rem;margin-top:1.1rem}.review-filters{display:flex;flex-wrap:wrap;gap:.65rem}.review-filter{background:#fff;border:1px solid var(--review-border);color:#475569;padding:.65rem .95rem;display:inline-flex;gap:.45rem;align-items:center;font-size:.84rem}.review-filter span{background:#f1f5f9;border-radius:999px;padding:.12rem .45rem;min-width:1.7rem;text-align:center}.review-filter.active{background:linear-gradient(135deg,#1f2937 0%,#2563eb 100%);border-color:transparent;color:#fff}.review-filter.active span{background:rgba(255,255,255,.14);color:#fff}.review-search{position:relative;width:min(100%,22rem)}.review-search i{position:absolute;left:1rem;top:50%;transform:translateY(-50%);color:#64748b}.review-search .form-control{height:3rem;border-radius:999px;border:1px solid var(--review-border);padding-left:2.7rem;box-shadow:none}.review-search .form-control:focus{border-color:rgba(37,99,235,.45);box-shadow:0 0 0 .2rem rgba(37,99,235,.12)}.review-table thead th{border-top:0;border-bottom:1px solid var(--review-border);font-size:.76rem;font-weight:800;letter-spacing:.08em;text-transform:uppercase;color:#718096;padding:1rem}.review-table tbody td{border-color:var(--review-border);padding:1rem;vertical-align:top}.review-table tbody tr:hover{background:#fbfdff}.review-main{display:flex;gap:.9rem}.review-avatar{width:56px;height:56px;border-radius:999px;object-fit:cover;flex:0 0 56px;border:2px solid #e2e8f0}.review-title{font-size:.96rem;font-weight:800;color:var(--review-ink);margin-bottom:.25rem}.review-subtext{font-size:.84rem;color:#64748b;line-height:1.55}.review-pill-row,.review-row-actions{display:flex;flex-wrap:wrap;gap:.5rem}.review-pill{border-radius:999px;padding:.46rem .8rem;font-size:.78rem;line-height:1}.review-rate-high{background:rgba(5,150,105,.14);color:#047857}.review-rate-mid{background:rgba(37,99,235,.14);color:#1d4ed8}.review-rate-low{background:rgba(217,119,6,.16);color:#b45309}.review-link-ok{background:rgba(5,150,105,.14);color:#047857}.review-link-orphan{background:rgba(220,38,38,.14);color:#b91c1c}.review-status-active{background:rgba(16,185,129,.14);color:#047857}.review-status-inactive{background:rgba(148,163,184,.18);color:#475569}.review-stars{display:flex;gap:.2rem;color:#f59e0b;font-size:.95rem;margin-bottom:.35rem}.review-action{justify-content:center;gap:.4rem;min-width:5rem;padding:.66rem .85rem;border-radius:.85rem;text-decoration:none;font-size:.82rem}.review-action:hover,.review-action:focus{text-decoration:none}.review-action-edit{background:rgba(37,99,235,.14);color:#1d4ed8}.review-action-delete{background:rgba(239,68,68,.14);color:#b91c1c;border:0}.review-empty{border:1px dashed var(--review-border);border-radius:1rem;padding:2rem 1.5rem;text-align:center;background:linear-gradient(180deg,#fff 0%,#f8fbff 100%)}.review-empty-icon{width:4rem;height:4rem;border-radius:999px;display:inline-flex;align-items:center;justify-content:center;background:rgba(37,99,235,.1);color:var(--review-sky);font-size:1.35rem;margin-bottom:1rem}.review-pagination{margin-top:1.25rem}.review-pagination .pagination{justify-content:flex-end;margin-bottom:0}div.dataTables_wrapper div.dataTables_filter,div.dataTables_wrapper div.dataTables_length,div.dataTables_wrapper div.dataTables_info,div.dataTables_wrapper div.dataTables_paginate{display:none}table.dataTable{border-collapse:collapse!important;margin-top:0!important;width:100%!important}@media (max-width:991.98px){.review-hero h1{font-size:1.65rem}.review-meta{justify-content:flex-start}}@media (max-width:767.98px){.review-focus-grid{grid-template-columns:1fr}.review-search{width:100%}.review-main{flex-direction:column}.review-pagination .pagination{justify-content:center}}
  </style>
@endpush

@section('main-content')
  <div class="container-fluid review-page">
    @include('backend.layouts.notification')

    <div class="review-hero mb-4">
      <div class="row align-items-stretch">
        <div class="col-xl-7 mb-4 mb-xl-0">
          <div class="review-copy">
            <div class="review-kicker">Bàn Kiểm Duyệt Đánh Giá</div>
            <h1>Quản lý đánh giá sản phẩm rõ hơn, phát hiện nhanh review tốt, review xấu và các mục cần xử lý.</h1>
            <p>Trang này giúp admin theo dõi chất lượng phản hồi của khách hàng, tách riêng nhóm đánh giá điểm thấp, kiểm tra review còn liên kết tới sản phẩm và duyệt trạng thái dễ hơn.</p>
            <div class="review-actions-top">
              <button type="button" id="reviewQuickRefresh" class="btn btn-light btn-sm"><i class="fas fa-sync-alt mr-1"></i> Làm mới danh sách</button>
            </div>
          </div>
        </div>
        <div class="col-xl-5">
          <div class="review-focus">
            <div class="review-focus-label">Đánh giá mới nhất</div>
            @if ($latestReview)
              <div class="review-focus-title">{{ optional($latestReview->user_info)->name ?: 'Người dùng không xác định' }}</div>
              <div class="review-focus-sub">
                {{ \Illuminate\Support\Str::limit(strip_tags($latestReview->review), 120) }}
                @if ($latestReview->created_at)
                  - gửi {{ $latestReview->created_at->diffForHumans() }}.
                @endif
              </div>
            @else
              <div class="review-focus-title">Chưa có đánh giá</div>
              <div class="review-focus-sub">Thông tin đánh giá mới nhất sẽ hiển thị ở đây khi hệ thống có dữ liệu phản hồi từ khách hàng.</div>
            @endif
            <div class="review-focus-grid">
              <div class="review-focus-box"><span>Điểm trung bình</span><strong>{{ rtrim(rtrim(number_format($averageRate, 1, '.', ''), '0'), '.') }}/5</strong></div>
              <div class="review-focus-box"><span>Điểm cao 4-5 sao</span><strong>{{ number_format($highRatedReviews, 0, ',', '.') }}</strong></div>
              <div class="review-focus-box"><span>Điểm thấp 1-2 sao</span><strong>{{ number_format($lowRatedReviews, 0, ',', '.') }}</strong></div>
              <div class="review-focus-box"><span>Hôm nay</span><strong>{{ number_format($reviewsToday, 0, ',', '.') }}</strong></div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="row">
      <div class="col-xl-3 col-md-6 mb-4">
        <div class="card review-card review-card-primary"><div class="card-body"><div class="review-label">Tổng đánh giá</div><div class="review-value">{{ number_format($totalReviews, 0, ',', '.') }}</div><div class="review-text">Tổng số đánh giá sản phẩm đã được ghi nhận trong hệ thống.</div><span class="review-chip">{{ number_format($reviewsToday, 0, ',', '.') }} đánh giá hôm nay</span></div></div>
      </div>
      <div class="col-xl-3 col-md-6 mb-4">
        <div class="card review-card review-card-success"><div class="card-body"><div class="review-label">Đang hoạt động</div><div class="review-value">{{ number_format($activeReviews, 0, ',', '.') }}</div><div class="review-text">Những đánh giá đang sẵn sàng hiển thị trong trang chi tiết sản phẩm.</div><span class="review-chip">{{ number_format($inactiveReviews, 0, ',', '.') }} đánh giá tạm ẩn</span></div></div>
      </div>
      <div class="col-xl-3 col-md-6 mb-4">
        <div class="card review-card review-card-warning"><div class="card-body"><div class="review-label">Cần chú ý</div><div class="review-value">{{ number_format($lowRatedReviews, 0, ',', '.') }}</div><div class="review-text">Nhóm đánh giá điểm thấp, nên ưu tiên xem xét để cải thiện trải nghiệm khách hàng.</div><span class="review-chip">{{ number_format($highRatedReviews, 0, ',', '.') }} đánh giá tích cực</span></div></div>
      </div>
      <div class="col-xl-3 col-md-6 mb-4">
        <div class="card review-card review-card-dark"><div class="card-body"><div class="review-label">Mồ côi sản phẩm</div><div class="review-value">{{ number_format($orphanReviews, 0, ',', '.') }}</div><div class="review-text">Đánh giá không còn liên kết tới sản phẩm hợp lệ và nên được kiểm tra lại.</div><span class="review-chip">{{ number_format($reviewsWithProduct, 0, ',', '.') }} đánh giá còn liên kết</span></div></div>
      </div>
    </div>

    <div class="card review-panel">
      <div class="card-header">
        <div class="row align-items-lg-center">
          <div class="col-lg-8">
            <div class="review-panel-title">Danh sách đánh giá</div>
            <p class="review-panel-sub">Tìm theo người đánh giá, sản phẩm hoặc nội dung review. Bạn cũng có thể lọc nhanh đánh giá active, điểm thấp, điểm cao hoặc các review mồ côi để xử lý đúng nhóm phản hồi.</p>
          </div>
          <div class="col-lg-4">
            <div class="review-meta">
              <span class="review-badge"><i class="fas fa-star"></i> Hiển thị {{ number_format($reviews->count(), 0, ',', '.') }} / {{ number_format($totalReviews, 0, ',', '.') }} đánh giá</span>
              <span class="review-badge"><i class="fas fa-exclamation-circle"></i> {{ number_format($lowRatedReviews, 0, ',', '.') }} đánh giá cần chú ý</span>
            </div>
          </div>
        </div>
        <div class="review-toolbar">
          <div class="review-filters" role="group" aria-label="Lọc đánh giá">
            <button type="button" class="review-filter active" data-filter="all">Tất cả <span>{{ number_format($totalReviews, 0, ',', '.') }}</span></button>
            <button type="button" class="review-filter" data-filter="active">Hoạt động <span>{{ number_format($activeReviews, 0, ',', '.') }}</span></button>
            <button type="button" class="review-filter" data-filter="inactive">Tạm ẩn <span>{{ number_format($inactiveReviews, 0, ',', '.') }}</span></button>
            <button type="button" class="review-filter" data-filter="high">4-5 sao <span>{{ number_format($highRatedReviews, 0, ',', '.') }}</span></button>
            <button type="button" class="review-filter" data-filter="low">1-2 sao <span>{{ number_format($lowRatedReviews, 0, ',', '.') }}</span></button>
            <button type="button" class="review-filter" data-filter="orphan">Mồ côi <span>{{ number_format($orphanReviews, 0, ',', '.') }}</span></button>
          </div>
          <div class="review-search">
            <i class="fas fa-search"></i>
            <input type="text" id="reviewSearchInput" class="form-control" placeholder="Tìm theo người dùng, sản phẩm, nội dung...">
          </div>
        </div>
      </div>
      <div class="card-body">
        @if ($reviews->count())
          <div class="table-responsive">
            <table class="table review-table" id="review-dataTable" width="100%" cellspacing="0">
              <thead>
                <tr>
                  <th>Người đánh giá</th>
                  <th>Sản phẩm</th>
                  <th>Nội dung</th>
                  <th>Điểm số</th>
                  <th>Trạng thái</th>
                  <th>Thời gian</th>
                  <th>Thao tác</th>
                </tr>
              </thead>
              <tbody>
                @foreach ($reviews as $review)
                  @php
                    $avatarUrl = asset('backend/img/avatar.png');
                    if (!empty(optional($review->user_info)->photo)) {
                        $avatarUrl = \Illuminate\Support\Str::startsWith($review->user_info->photo, ['http://', 'https://'])
                            ? $review->user_info->photo
                            : asset(ltrim($review->user_info->photo, '/'));
                    }
                    $ratingGroup = $review->rate >= 4 ? 'high' : ($review->rate <= 2 ? 'low' : 'mid');
                    $hasProduct = !is_null(optional($review->product)->id);
                  @endphp
                  <tr data-review-status="{{ $review->status }}" data-review-rating="{{ $ratingGroup }}" data-review-link="{{ $hasProduct ? 'linked' : 'orphan' }}">
                    <td>
                      <div class="review-main">
                        <img src="{{ $avatarUrl }}" class="review-avatar" alt="{{ optional($review->user_info)->name ?: 'avatar' }}">
                        <div>
                          <div class="review-title">{{ optional($review->user_info)->name ?: 'Người dùng không xác định' }}</div>
                          <div class="review-subtext">#{{ $review->id }}@if ($review->created_at) - gửi {{ $review->created_at->diffForHumans() }}@endif</div>
                        </div>
                      </div>
                    </td>
                    <td>
                      <div class="review-pill-row mb-2"><span class="review-pill {{ $hasProduct ? 'review-link-ok' : 'review-link-orphan' }}">{{ $hasProduct ? 'Có sản phẩm' : 'Mồ côi' }}</span></div>
                      <div class="review-title">{{ optional($review->product)->title ?: 'Không còn sản phẩm liên kết' }}</div>
                      <div class="review-subtext">{{ $hasProduct ? 'Đánh giá đang gắn với sản phẩm hợp lệ.' : 'Nên kiểm tra lại vì sản phẩm gốc có thể đã bị xóa.' }}</div>
                    </td>
                    <td>
                      <div class="review-title">{{ \Illuminate\Support\Str::limit(strip_tags($review->review), 85) }}</div>
                      <div class="review-subtext">{{ \Illuminate\Support\Str::limit(strip_tags($review->review), 140) }}</div>
                    </td>
                    <td>
                      <div class="review-stars">
                        @for ($i = 1; $i <= 5; $i++)
                          <i class="{{ $review->rate >= $i ? 'fa fa-star' : 'far fa-star' }}"></i>
                        @endfor
                      </div>
                      <div class="review-pill-row mb-2"><span class="review-pill {{ $ratingGroup === 'high' ? 'review-rate-high' : ($ratingGroup === 'low' ? 'review-rate-low' : 'review-rate-mid') }}">{{ rtrim(rtrim(number_format($review->rate, 1, '.', ''), '0'), '.') }}/5</span></div>
                      <div class="review-subtext">{{ $ratingGroup === 'high' ? 'Đánh giá tích cực từ khách hàng.' : ($ratingGroup === 'low' ? 'Đánh giá thấp, nên ưu tiên xem xét.' : 'Đánh giá trung tính, cần đọc thêm nội dung.') }}</div>
                    </td>
                    <td>
                      <div class="review-pill-row mb-2"><span class="review-pill {{ $review->status === 'active' ? 'review-status-active' : 'review-status-inactive' }}">{{ $review->status === 'active' ? 'Đang hoạt động' : 'Tạm ẩn' }}</span></div>
                      <div class="review-subtext">{{ $review->status === 'active' ? 'Có thể hiển thị trên trang sản phẩm.' : 'Đã bị tạm ẩn khỏi khu vực đánh giá.' }}</div>
                    </td>
                    <td>
                      <div class="review-title">{{ $review->created_at ? $review->created_at->format('d/m/Y') : 'Chưa rõ' }}</div>
                      <div class="review-subtext">{{ $review->created_at ? $review->created_at->format('H:i') . ' - cập nhật ' . $review->created_at->diffForHumans() : 'Chưa có dữ liệu thời gian.' }}</div>
                    </td>
                    <td>
                      <div class="review-row-actions">
                        <a href="{{ route('review.edit', $review->id) }}" class="review-action review-action-edit" data-toggle="tooltip" title="Chỉnh sửa đánh giá"><i class="fas fa-pen"></i> <span>Sửa</span></a>
                        <form method="POST" action="{{ route('review.destroy', [$review->id]) }}">
                          @csrf
                          @method('delete')
                          <button class="review-action review-action-delete dltBtn" data-id="{{ $review->id }}" data-toggle="tooltip" title="Xóa đánh giá"><i class="fas fa-trash-alt"></i> <span>Xóa</span></button>
                        </form>
                      </div>
                    </td>
                  </tr>
                @endforeach
              </tbody>
            </table>
          </div>
          <div class="review-pagination">{{ $reviews->links() }}</div>
        @else
          <div class="review-empty">
            <div class="review-empty-icon"><i class="fas fa-star"></i></div>
            <h3>Chưa có đánh giá nào</h3>
            <p class="text-muted mb-4">Danh sách sẽ xuất hiện khi khách hàng bắt đầu để lại phản hồi cho sản phẩm.</p>
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
      const $table = $('#review-dataTable');
      const reviewFilter = { value: 'all' };

      $.fn.dataTable.ext.search.push(function (settings, data, dataIndex) {
        if (settings.nTable.id !== 'review-dataTable') return true;
        if (reviewFilter.value === 'all') return true;
        const rowNode = settings.aoData[dataIndex] && settings.aoData[dataIndex].nTr;
        if (!rowNode) return true;
        const status = rowNode.getAttribute('data-review-status');
        const rating = rowNode.getAttribute('data-review-rating');
        const link = rowNode.getAttribute('data-review-link');
        if (reviewFilter.value === 'high' || reviewFilter.value === 'low') return rating === reviewFilter.value;
        if (reviewFilter.value === 'orphan') return link === 'orphan';
        return status === reviewFilter.value;
      });

      if ($table.length) {
        const reviewTable = $table.DataTable({
          autoWidth: false,
          info: false,
          language: { emptyTable: 'Chưa có đánh giá nào.', zeroRecords: 'Không tìm thấy đánh giá phù hợp trên trang này.' },
          lengthChange: false,
          order: [],
          paging: false,
          searching: true,
          columnDefs: [{ orderable: false, targets: [3, 4, 6] }]
        });

        $('#reviewSearchInput').on('keyup', function () { reviewTable.search(this.value).draw(); });
        $('.review-filter').on('click', function () {
          const $chip = $(this);
          reviewFilter.value = $chip.data('filter');
          $('.review-filter').removeClass('active');
          $chip.addClass('active');
          reviewTable.draw();
        });
      }

      $('#reviewQuickRefresh').on('click', function () { window.location.reload(); });
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
