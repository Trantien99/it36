@extends('backend.layouts.master')

@section('title', 'Web bán tai nghe || Quản lý người dùng')

@push('styles')
  <link href="{{ asset('backend/vendor/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.css" />
  <style>
    :root { --users-ink:#172554; --users-sky:#2563eb; --users-cyan:#0891b2; --users-emerald:#059669; --users-amber:#d97706; --users-border:#e2e8f0; --users-soft:#f8fbff; --users-shadow:0 18px 40px rgba(23,37,84,.08); }
    .users-page{padding-bottom:2rem}.users-hero,.users-card,.users-panel{border:0;border-radius:1.25rem;box-shadow:var(--users-shadow)}.users-hero{background:linear-gradient(135deg,#172554 0%,#1d4ed8 58%,#0891b2 100%);color:#fff;padding:1.75rem;position:relative;overflow:hidden}.users-hero:after{content:'';position:absolute;right:-4rem;top:-4rem;width:14rem;height:14rem;border-radius:999px;background:rgba(255,255,255,.08)}.users-copy,.users-focus{position:relative;z-index:1}.users-kicker{font-size:.78rem;font-weight:800;letter-spacing:.18em;text-transform:uppercase;margin-bottom:.8rem}.users-hero h1{font-size:2rem;font-weight:800;line-height:1.12;margin-bottom:.9rem;max-width:40rem}.users-hero p{color:rgba(255,255,255,.84);max-width:42rem;margin-bottom:0}.users-actions-top{display:flex;flex-wrap:wrap;gap:.75rem;margin-top:1.25rem}.users-actions-top .btn,.users-filter{border-radius:999px;font-weight:700}.users-focus{background:rgba(255,255,255,.12);border:1px solid rgba(255,255,255,.14);border-radius:1rem;height:100%;padding:1.15rem}.users-focus-label{font-size:.76rem;font-weight:800;letter-spacing:.08em;text-transform:uppercase;color:rgba(255,255,255,.72)}.users-focus-title{font-size:1.3rem;font-weight:800;margin:.3rem 0}.users-focus-sub{font-size:.9rem;color:rgba(255,255,255,.84)}.users-focus-grid{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:.75rem;margin-top:1rem}.users-focus-box{background:rgba(255,255,255,.08);border-radius:.95rem;padding:.85rem .95rem}.users-focus-box span{display:block;font-size:.75rem;color:rgba(255,255,255,.72);text-transform:uppercase}.users-focus-box strong{display:block;font-size:1.1rem;margin-top:.3rem}.users-card{color:#fff;height:100%;overflow:hidden;position:relative}.users-card:after{content:'';position:absolute;right:-1.5rem;top:-1.5rem;width:6.5rem;height:6.5rem;border-radius:999px;background:rgba(255,255,255,.08)}.users-card .card-body{position:relative;z-index:1}.users-card-primary{background:linear-gradient(140deg,#1d4ed8 0%,#2563eb 100%)}.users-card-success{background:linear-gradient(140deg,#047857 0%,#059669 100%)}.users-card-cyan{background:linear-gradient(140deg,#0f4c81 0%,#0891b2 100%)}.users-card-warning{background:linear-gradient(140deg,#b45309 0%,#d97706 100%)}.users-label{font-size:.76rem;font-weight:800;letter-spacing:.08em;text-transform:uppercase;margin-bottom:.7rem}.users-value{font-size:1.8rem;font-weight:800;line-height:1.1;margin-bottom:.55rem}.users-text{font-size:.88rem;opacity:.86;margin-bottom:.8rem}.users-chip,.users-badge,.users-pill,.users-action{display:inline-flex;align-items:center;font-weight:700}.users-chip{background:rgba(255,255,255,.15);border-radius:999px;padding:.35rem .7rem;font-size:.78rem}.users-panel{background:#fff;overflow:hidden}.users-panel .card-header{background:#fff;border-bottom:1px solid var(--users-border);padding:1.35rem 1.45rem 1rem}.users-panel .card-body{padding:1.35rem 1.45rem 1.45rem}.users-panel-title{font-size:1.08rem;font-weight:800;color:var(--users-ink)}.users-panel-sub{font-size:.9rem;color:#64748b;max-width:42rem;margin-bottom:0}.users-meta{display:flex;flex-wrap:wrap;gap:.6rem;justify-content:flex-end;margin-top:1rem}.users-badge{gap:.35rem;padding:.45rem .8rem;border-radius:999px;border:1px solid var(--users-border);background:var(--users-soft);font-size:.8rem;color:var(--users-ink)}.users-toolbar{display:flex;flex-wrap:wrap;justify-content:space-between;align-items:center;gap:1rem;margin-top:1.1rem}.users-filters{display:flex;flex-wrap:wrap;gap:.65rem}.users-filter{background:#fff;border:1px solid var(--users-border);color:#475569;padding:.65rem .95rem;display:inline-flex;gap:.45rem;align-items:center;font-size:.84rem}.users-filter span{background:#f1f5f9;border-radius:999px;padding:.12rem .45rem;min-width:1.7rem;text-align:center}.users-filter.active{background:linear-gradient(135deg,#172554 0%,#2563eb 100%);border-color:transparent;color:#fff}.users-filter.active span{background:rgba(255,255,255,.14);color:#fff}.users-search{position:relative;width:min(100%,24rem)}.users-search i{position:absolute;left:1rem;top:50%;transform:translateY(-50%);color:#64748b}.users-search .form-control{height:3rem;border-radius:999px;border:1px solid var(--users-border);padding-left:2.7rem;box-shadow:none}.users-search .form-control:focus{border-color:rgba(37,99,235,.45);box-shadow:0 0 0 .2rem rgba(37,99,235,.12)}.users-table thead th{border-top:0;border-bottom:1px solid var(--users-border);font-size:.76rem;font-weight:800;letter-spacing:.08em;text-transform:uppercase;color:#718096;padding:1rem}.users-table tbody td{border-color:var(--users-border);padding:1rem;vertical-align:top}.users-table tbody tr:hover{background:#fbfdff}.users-main{display:flex;gap:.9rem}.users-avatar{width:56px;height:56px;border-radius:999px;object-fit:cover;flex:0 0 56px;border:2px solid #e2e8f0}.users-title{font-size:.96rem;font-weight:800;color:var(--users-ink);margin-bottom:.25rem}.users-subtext{font-size:.84rem;color:#64748b;line-height:1.55}.users-pill-row,.users-row-actions{display:flex;flex-wrap:wrap;gap:.5rem}.users-pill{border-radius:999px;padding:.46rem .8rem;font-size:.78rem;line-height:1}.users-role-admin{background:rgba(37,99,235,.14);color:#1d4ed8}.users-role-user{background:rgba(8,145,178,.14);color:#0f766e}.users-status-active{background:rgba(16,185,129,.14);color:#047857}.users-status-inactive{background:rgba(148,163,184,.18);color:#475569}.users-source-local{background:rgba(37,99,235,.14);color:#1d4ed8}.users-source-social{background:rgba(217,119,6,.16);color:#b45309}.users-photo-ready{background:rgba(5,150,105,.14);color:#047857}.users-photo-missing{background:rgba(220,38,38,.14);color:#b91c1c}.users-action{justify-content:center;gap:.4rem;min-width:5rem;padding:.66rem .85rem;border-radius:.85rem;text-decoration:none;font-size:.82rem}.users-action:hover,.users-action:focus{text-decoration:none}.users-action-edit{background:rgba(37,99,235,.14);color:#1d4ed8}.users-action-delete{background:rgba(239,68,68,.14);color:#b91c1c;border:0}.users-empty{border:1px dashed var(--users-border);border-radius:1rem;padding:2rem 1.5rem;text-align:center;background:linear-gradient(180deg,#fff 0%,#f8fbff 100%)}.users-empty-icon{width:4rem;height:4rem;border-radius:999px;display:inline-flex;align-items:center;justify-content:center;background:rgba(37,99,235,.1);color:var(--users-sky);font-size:1.35rem;margin-bottom:1rem}.users-pagination{margin-top:1.25rem}.users-pagination .pagination{justify-content:flex-end;margin-bottom:0}div.dataTables_wrapper div.dataTables_filter,div.dataTables_wrapper div.dataTables_length,div.dataTables_wrapper div.dataTables_info,div.dataTables_wrapper div.dataTables_paginate{display:none}table.dataTable{border-collapse:collapse!important;margin-top:0!important;width:100%!important}@media (max-width:991.98px){.users-hero h1{font-size:1.65rem}.users-meta{justify-content:flex-start}}@media (max-width:767.98px){.users-focus-grid{grid-template-columns:1fr}.users-search{width:100%}.users-pagination .pagination{justify-content:center}}
  </style>
@endpush

@section('main-content')
  <div class="container-fluid users-page">
    @include('backend.layouts.notification')

    <div class="users-hero mb-4">
      <div class="row align-items-stretch">
        <div class="col-xl-7 mb-4 mb-xl-0">
          <div class="users-copy">
            <div class="users-kicker">Trung Tâm Điều Phối Thành Viên</div>
            <h1>Quản lý người dùng rõ hơn, xem nhanh vai trò, trạng thái và mức độ hoạt động của từng tài khoản.</h1>
            <p>Danh sách tài khoản được bố trí lại để admin tìm nhanh theo tên, email, vai trò, nhận biết tài khoản social và theo dõi lịch sử đặt hàng ngay trên bảng.</p>
            <div class="users-actions-top">
              <a href="{{ route('users.create') }}" class="btn btn-light btn-sm"><i class="fas fa-user-plus mr-1"></i> Thêm người dùng</a>
              <button type="button" id="usersQuickRefresh" class="btn btn-outline-light btn-sm"><i class="fas fa-sync-alt mr-1"></i> Làm mới</button>
            </div>
          </div>
        </div>
        <div class="col-xl-5">
          <div class="users-focus">
            <div class="users-focus-label">Tài khoản mới nhất</div>
            @if ($latestUser)
              <div class="users-focus-title">{{ $latestUser->name }}</div>
              <div class="users-focus-sub">{{ $latestUser->email }}@if ($latestUser->created_at) - tham gia {{ $latestUser->created_at->diffForHumans() }}@endif - {{ number_format($latestUser->orders_count, 0, ',', '.') }} đơn hàng.</div>
            @else
              <div class="users-focus-title">Chưa có tài khoản</div>
              <div class="users-focus-sub">Thông tin tài khoản mới nhất sẽ hiện ở đây khi hệ thống có dữ liệu người dùng.</div>
            @endif
            <div class="users-focus-grid">
              <div class="users-focus-box"><span>Có đơn hàng</span><strong>{{ number_format($usersWithOrders, 0, ',', '.') }}</strong></div>
              <div class="users-focus-box"><span>Có avatar</span><strong>{{ number_format($usersWithPhoto, 0, ',', '.') }}</strong></div>
              <div class="users-focus-box"><span>Social login</span><strong>{{ number_format($socialUsers, 0, ',', '.') }}</strong></div>
              <div class="users-focus-box"><span>Mới trong tháng</span><strong>{{ number_format($newUsersThisMonth, 0, ',', '.') }}</strong></div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="row">
      <div class="col-xl-3 col-md-6 mb-4">
        <div class="card users-card users-card-primary"><div class="card-body"><div class="users-label">Tổng người dùng</div><div class="users-value">{{ number_format($totalUsers, 0, ',', '.') }}</div><div class="users-text">Tổng số tài khoản admin và khách hàng trong hệ thống.</div><span class="users-chip">{{ number_format($inactiveUsers, 0, ',', '.') }} tài khoản tạm ẩn</span></div></div>
      </div>
      <div class="col-xl-3 col-md-6 mb-4">
        <div class="card users-card users-card-success"><div class="card-body"><div class="users-label">Đang hoạt động</div><div class="users-value">{{ number_format($activeUsers, 0, ',', '.') }}</div><div class="users-text">Tài khoản có thể đăng nhập và sử dụng hệ thống.</div><span class="users-chip">{{ number_format($usersWithOrders, 0, ',', '.') }} tài khoản có đơn</span></div></div>
      </div>
      <div class="col-xl-3 col-md-6 mb-4">
        <div class="card users-card users-card-cyan"><div class="card-body"><div class="users-label">Quản trị viên</div><div class="users-value">{{ number_format($adminUsers, 0, ',', '.') }}</div><div class="users-text">Nhóm tài khoản có quyền truy cập khu quản trị.</div><span class="users-chip">{{ number_format($customerUsers, 0, ',', '.') }} tài khoản user</span></div></div>
      </div>
      <div class="col-xl-3 col-md-6 mb-4">
        <div class="card users-card users-card-warning"><div class="card-body"><div class="users-label">Đăng nhập social</div><div class="users-value">{{ number_format($socialUsers, 0, ',', '.') }}</div><div class="users-text">Tài khoản đăng nhập qua nhà cung cấp bên thứ ba.</div><span class="users-chip">{{ number_format($usersWithPhoto, 0, ',', '.') }} tài khoản có avatar</span></div></div>
      </div>
    </div>

    <div class="card users-panel">
      <div class="card-header">
        <div class="row align-items-lg-center">
          <div class="col-lg-8">
            <div class="users-panel-title">Danh sách người dùng</div>
            <p class="users-panel-sub">Tìm nhanh theo tên, email, role và lọc riêng nhóm tài khoản social, admin hoặc inactive để xử lý gọn hơn.</p>
          </div>
          <div class="col-lg-4">
            <div class="users-meta">
              <span class="users-badge"><i class="fas fa-users"></i> Hiển thị {{ number_format($users->count(), 0, ',', '.') }} / {{ number_format($totalUsers, 0, ',', '.') }} tài khoản</span>
              <span class="users-badge"><i class="fas fa-user-shield"></i> {{ number_format($adminUsers, 0, ',', '.') }} admin</span>
            </div>
          </div>
        </div>
        <div class="users-toolbar">
          <div class="users-filters" role="group" aria-label="Lọc người dùng">
            <button type="button" class="users-filter active" data-filter="all">Tất cả <span>{{ number_format($totalUsers, 0, ',', '.') }}</span></button>
            <button type="button" class="users-filter" data-filter="active">Active <span>{{ number_format($activeUsers, 0, ',', '.') }}</span></button>
            <button type="button" class="users-filter" data-filter="inactive">Inactive <span>{{ number_format($inactiveUsers, 0, ',', '.') }}</span></button>
            <button type="button" class="users-filter" data-filter="admin">Admin <span>{{ number_format($adminUsers, 0, ',', '.') }}</span></button>
            <button type="button" class="users-filter" data-filter="user">User <span>{{ number_format($customerUsers, 0, ',', '.') }}</span></button>
            <button type="button" class="users-filter" data-filter="social">Social <span>{{ number_format($socialUsers, 0, ',', '.') }}</span></button>
          </div>
          <div class="users-search">
            <i class="fas fa-search"></i>
            <input type="text" id="usersSearchInput" class="form-control" placeholder="Tìm theo tên, email, role...">
          </div>
        </div>
      </div>
      <div class="card-body">
        @if ($users->count())
          <div class="table-responsive">
            <table class="table users-table" id="users-dataTable" width="100%" cellspacing="0">
              <thead>
                <tr>
                  <th>Người dùng</th>
                  <th>Liên hệ</th>
                  <th>Vai trò</th>
                  <th>Trạng thái</th>
                  <th>Đơn hàng</th>
                  <th>Nguồn</th>
                  <th>Thao tác</th>
                </tr>
              </thead>
              <tbody>
                @foreach ($users as $user)
                  @php
                    $avatarUrl = asset('backend/img/avatar.png');
                    if (!empty($user->photo)) {
                        $avatarUrl = \Illuminate\Support\Str::startsWith($user->photo, ['http://', 'https://']) ? $user->photo : asset(ltrim($user->photo, '/'));
                    }
                    $accountSource = !empty($user->provider) ? 'social' : 'local';
                  @endphp
                  <tr data-user-status="{{ $user->status }}" data-user-role="{{ $user->role }}" data-user-source="{{ $accountSource }}">
                    <td>
                      <div class="users-main">
                        <img src="{{ $avatarUrl }}" class="users-avatar" alt="{{ $user->name }}">
                        <div>
                          <div class="users-title">{{ $user->name }}</div>
                          <div class="users-subtext">#{{ $user->id }}@if ($user->created_at) - tham gia {{ $user->created_at->diffForHumans() }}@endif</div>
                        </div>
                      </div>
                    </td>
                    <td>
                      <div class="users-title" style="word-break:break-word;">{{ $user->email }}</div>
                      <div class="users-subtext">@if ($user->created_at) Tạo lúc {{ $user->created_at->format('d/m/Y H:i') }} @else Chưa có mốc thời gian tạo. @endif</div>
                    </td>
                    <td>
                      <div class="users-pill-row mb-2"><span class="users-pill {{ $user->role === 'admin' ? 'users-role-admin' : 'users-role-user' }}">{{ $user->role === 'admin' ? 'Admin' : 'User' }}</span></div>
                      <div class="users-subtext">{{ $user->role === 'admin' ? 'Có quyền truy cập khu quản trị.' : 'Tài khoản khách hàng thông thường.' }}</div>
                    </td>
                    <td>
                      <div class="users-pill-row mb-2"><span class="users-pill {{ $user->status === 'active' ? 'users-status-active' : 'users-status-inactive' }}">{{ $user->status === 'active' ? 'Đang hoạt động' : 'Tạm ẩn' }}</span></div>
                      <div class="users-subtext">{{ $user->status === 'active' ? 'Có thể đăng nhập và thao tác bình thường.' : 'Đang tạm khóa hoặc tạm ẩn.' }}</div>
                    </td>
                    <td>
                      <div class="users-title">{{ number_format($user->orders_count, 0, ',', '.') }} đơn</div>
                      <div class="users-subtext">{{ $user->orders_count > 0 ? 'Tài khoản đã có lịch sử mua hàng.' : 'Chưa phát sinh đơn hàng.' }}</div>
                    </td>
                    <td>
                      <div class="users-pill-row mb-2">
                        <span class="users-pill {{ $accountSource === 'social' ? 'users-source-social' : 'users-source-local' }}">{{ $accountSource === 'social' ? strtoupper($user->provider) : 'Local' }}</span>
                        <span class="users-pill {{ !empty($user->photo) ? 'users-photo-ready' : 'users-photo-missing' }}">{{ !empty($user->photo) ? 'Có avatar' : 'Chưa có avatar' }}</span>
                      </div>
                      <div class="users-subtext">{{ $accountSource === 'social' ? 'Tài khoản tạo qua nhà cung cấp đăng nhập bên thứ ba.' : 'Tài khoản local quản lý bằng email và password.' }}</div>
                    </td>
                    <td>
                      <div class="users-row-actions">
                        <a href="{{ route('users.edit', $user->id) }}" class="users-action users-action-edit" data-toggle="tooltip" title="Chỉnh sửa người dùng"><i class="fas fa-pen"></i> <span>Sửa</span></a>
                        <form method="POST" action="{{ route('users.destroy', [$user->id]) }}">
                          @csrf
                          @method('delete')
                          <button class="users-action users-action-delete dltBtn" data-id="{{ $user->id }}" data-toggle="tooltip" title="Xóa người dùng"><i class="fas fa-trash-alt"></i> <span>Xóa</span></button>
                        </form>
                      </div>
                    </td>
                  </tr>
                @endforeach
              </tbody>
            </table>
          </div>
          <div class="users-pagination">{{ $users->links() }}</div>
        @else
          <div class="users-empty">
            <div class="users-empty-icon"><i class="fas fa-users"></i></div>
            <h3>Chưa có tài khoản nào</h3>
            <p class="text-muted mb-4">Hãy tạo người dùng đầu tiên để bắt đầu quản lý thành viên trong hệ thống.</p>
            <a href="{{ route('users.create') }}" class="btn btn-primary btn-sm"><i class="fas fa-user-plus mr-1"></i> Thêm người dùng</a>
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
      const $table = $('#users-dataTable');
      const usersFilter = { value: 'all' };

      $.fn.dataTable.ext.search.push(function (settings, data, dataIndex) {
        if (settings.nTable.id !== 'users-dataTable') return true;
        if (usersFilter.value === 'all') return true;
        const rowNode = settings.aoData[dataIndex] && settings.aoData[dataIndex].nTr;
        if (!rowNode) return true;
        const status = rowNode.getAttribute('data-user-status');
        const role = rowNode.getAttribute('data-user-role');
        const source = rowNode.getAttribute('data-user-source');
        if (usersFilter.value === 'social') return source === 'social';
        if (usersFilter.value === 'admin' || usersFilter.value === 'user') return role === usersFilter.value;
        return status === usersFilter.value;
      });

      if ($table.length) {
        const usersTable = $table.DataTable({
          autoWidth: false,
          info: false,
          language: { emptyTable: 'Chưa có người dùng nào.', zeroRecords: 'Không tìm thấy người dùng phù hợp trên trang này.' },
          lengthChange: false,
          order: [],
          paging: false,
          searching: true,
          columnDefs: [{ orderable: false, targets: [2, 3, 5, 6] }]
        });

        $('#usersSearchInput').on('keyup', function () { usersTable.search(this.value).draw(); });
        $('.users-filter').on('click', function () {
          const $chip = $(this);
          usersFilter.value = $chip.data('filter');
          $('.users-filter').removeClass('active');
          $chip.addClass('active');
          usersTable.draw();
        });
      }

      $('#usersQuickRefresh').on('click', function () { window.location.reload(); });
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
