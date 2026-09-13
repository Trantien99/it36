@extends('backend.layouts.master')

@section('title', 'Web bán tai nghe || Quản lý banner')

@push('styles')
  <link href="{{ asset('backend/vendor/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.css" />
  <style>
    :root {
      --banner-ink: #153243;
      --banner-sky: #2b6cb0;
      --banner-teal: #0f9d94;
      --banner-amber: #d97706;
      --banner-rose: #c53030;
      --banner-slate: #64748b;
      --banner-border: #e2e8f0;
      --banner-shadow: 0 20px 45px rgba(21, 50, 67, 0.08);
      --banner-soft-shadow: 0 14px 30px rgba(21, 50, 67, 0.06);
    }

    .banner-page { padding-bottom: 2rem; }

    .banner-hero {
      background:
        radial-gradient(circle at top right, rgba(96, 165, 250, .3), transparent 34%),
        linear-gradient(135deg, #17324d 0%, #255f73 52%, #0f9d94 100%);
      border-radius: 1.4rem;
      box-shadow: var(--banner-shadow);
      color: #fff;
      overflow: hidden;
      padding: 1.75rem;
      position: relative;
    }

    .banner-hero::after {
      background: rgba(255, 255, 255, .08);
      border-radius: 999px;
      content: '';
      height: 15rem;
      position: absolute;
      right: -4rem;
      top: -5rem;
      width: 15rem;
    }

    .banner-hero-copy,
    .banner-hero-focus { position: relative; z-index: 1; }

    .banner-kicker {
      font-size: .8rem;
      font-weight: 800;
      letter-spacing: .22em;
      margin-bottom: .85rem;
      text-transform: uppercase;
    }

    .banner-hero h1 {
      font-size: 2rem;
      font-weight: 800;
      line-height: 1.15;
      margin-bottom: .9rem;
      max-width: 38rem;
    }

    .banner-hero p {
      color: rgba(255, 255, 255, .84);
      margin-bottom: 0;
      max-width: 42rem;
    }

    .banner-hero-actions {
      display: flex;
      flex-wrap: wrap;
      gap: .75rem;
      margin-top: 1.4rem;
    }

    .banner-hero-actions .btn {
      border-radius: 999px;
      font-weight: 700;
      padding-left: 1rem;
      padding-right: 1rem;
    }

    .banner-hero-focus {
      background: rgba(255, 255, 255, .12);
      border: 1px solid rgba(255, 255, 255, .16);
      border-radius: 1.1rem;
      height: 100%;
      padding: 1.2rem;
    }

    .banner-focus-label {
      color: rgba(255, 255, 255, .74);
      font-size: .78rem;
      font-weight: 700;
      letter-spacing: .08em;
      margin-bottom: .35rem;
      text-transform: uppercase;
    }

    .banner-focus-title {
      font-size: 1.35rem;
      font-weight: 800;
      line-height: 1.2;
      margin-bottom: .35rem;
    }

    .banner-focus-subtitle {
      color: rgba(255, 255, 255, .84);
      font-size: .9rem;
      line-height: 1.55;
    }

    .banner-focus-grid {
      display: grid;
      gap: .75rem;
      grid-template-columns: repeat(2, minmax(0, 1fr));
      margin-top: 1rem;
    }

    .banner-focus-item {
      background: rgba(255, 255, 255, .08);
      border-radius: .95rem;
      padding: .9rem 1rem;
    }

    .banner-focus-item span {
      color: rgba(255, 255, 255, .72);
      display: block;
      font-size: .78rem;
      margin-bottom: .35rem;
      text-transform: uppercase;
    }

    .banner-focus-item strong {
      color: #fff;
      display: block;
      font-size: 1.1rem;
      font-weight: 800;
      line-height: 1.2;
    }

    .banner-card {
      border: 0;
      border-radius: 1rem;
      box-shadow: var(--banner-soft-shadow);
      color: #fff;
      height: 100%;
      overflow: hidden;
      position: relative;
    }

    .banner-card::after {
      background: rgba(255, 255, 255, .08);
      border-radius: 999px;
      content: '';
      height: 7rem;
      position: absolute;
      right: -1.5rem;
      top: -1.5rem;
      width: 7rem;
    }

    .banner-card .card-body { position: relative; z-index: 1; }
    .banner-card-primary { background: linear-gradient(140deg, #235789 0%, #2b6cb0 100%); }
    .banner-card-success { background: linear-gradient(140deg, #0f766e 0%, #0f9d94 100%); }
    .banner-card-warning { background: linear-gradient(140deg, #b45309 0%, #d97706 100%); }
    .banner-card-dark { background: linear-gradient(140deg, #334155 0%, #475569 100%); }

    .banner-card-label {
      font-size: .78rem;
      font-weight: 700;
      letter-spacing: .08em;
      margin-bottom: .7rem;
      opacity: .9;
      text-transform: uppercase;
    }

    .banner-card-value {
      font-size: 1.8rem;
      font-weight: 800;
      line-height: 1.1;
      margin-bottom: .6rem;
    }

    .banner-card-text {
      font-size: .88rem;
      margin-bottom: .85rem;
      opacity: .84;
    }

    .banner-chip {
      align-items: center;
      background: rgba(255, 255, 255, .16);
      border-radius: 999px;
      display: inline-flex;
      font-size: .78rem;
      font-weight: 700;
      padding: .35rem .7rem;
    }

    .banner-panel {
      background: #fff;
      border: 0;
      border-radius: 1.2rem;
      box-shadow: var(--banner-shadow);
      overflow: hidden;
    }

    .banner-panel .card-header {
      background: #fff;
      border-bottom: 1px solid rgba(226, 232, 240, .95);
      padding: 1.35rem 1.45rem 1rem;
    }

    .banner-panel .card-body {
      padding: 1.35rem 1.45rem 1.45rem;
    }

    .banner-panel-title {
      color: var(--banner-ink);
      font-size: 1.08rem;
      font-weight: 800;
      margin-bottom: .2rem;
    }

    .banner-panel-subtitle {
      color: #718096;
      font-size: .9rem;
      margin-bottom: 0;
      max-width: 42rem;
    }

    .banner-panel-meta {
      display: flex;
      flex-wrap: wrap;
      gap: .6rem;
      justify-content: flex-end;
      margin-top: 1rem;
    }

    .banner-panel-badge {
      align-items: center;
      background: #f8fbff;
      border: 1px solid var(--banner-border);
      border-radius: 999px;
      color: var(--banner-ink);
      display: inline-flex;
      font-size: .8rem;
      font-weight: 700;
      gap: .35rem;
      padding: .45rem .8rem;
    }

    .banner-toolbar {
      align-items: center;
      display: flex;
      flex-wrap: wrap;
      gap: 1rem;
      justify-content: space-between;
      margin-top: 1.15rem;
    }

    .banner-filter-group {
      display: flex;
      flex-wrap: wrap;
      gap: .65rem;
    }

    .banner-filter-chip {
      align-items: center;
      background: #fff;
      border: 1px solid var(--banner-border);
      border-radius: 999px;
      color: #475569;
      display: inline-flex;
      font-size: .85rem;
      font-weight: 700;
      gap: .45rem;
      padding: .7rem 1rem;
      transition: all .2s ease;
    }

    .banner-filter-chip span {
      background: #f1f5f9;
      border-radius: 999px;
      color: #334155;
      min-width: 1.75rem;
      padding: .15rem .45rem;
      text-align: center;
    }

    .banner-filter-chip:hover,
    .banner-filter-chip:focus {
      box-shadow: 0 10px 20px rgba(21, 50, 67, .08);
      color: #1e293b;
      outline: none;
      text-decoration: none;
      transform: translateY(-1px);
    }

    .banner-filter-chip.active {
      background: linear-gradient(135deg, #153243 0%, #2b6cb0 100%);
      border-color: transparent;
      color: #fff;
    }

    .banner-filter-chip.active span {
      background: rgba(255, 255, 255, .15);
      color: #fff;
    }

    .banner-search {
      position: relative;
      width: min(100%, 24rem);
    }

    .banner-search i {
      color: #64748b;
      left: 1rem;
      position: absolute;
      top: 50%;
      transform: translateY(-50%);
    }

    .banner-search .form-control {
      border: 1px solid var(--banner-border);
      border-radius: 999px;
      box-shadow: none;
      font-size: .92rem;
      height: 3rem;
      padding-left: 2.7rem;
    }

    .banner-search .form-control:focus {
      border-color: rgba(43, 108, 176, .5);
      box-shadow: 0 0 0 .2rem rgba(43, 108, 176, .12);
    }

    .banner-table { margin-bottom: 0; }

    .banner-table thead th {
      border-bottom: 1px solid var(--banner-border);
      border-top: 0;
      color: #718096;
      font-size: .76rem;
      font-weight: 800;
      letter-spacing: .08em;
      padding: 1rem;
      text-transform: uppercase;
      white-space: nowrap;
    }

    .banner-table tbody tr:hover { background: #fbfdff; }

    .banner-table tbody td {
      border-color: var(--banner-border);
      padding: 1rem;
      vertical-align: top;
    }

    .banner-main-cell {
      display: flex;
      gap: .9rem;
    }

    .banner-thumb {
      background: #f8fafc;
      border: 1px solid #e2e8f0;
      border-radius: 1rem;
      flex: 0 0 108px;
      height: 72px;
      object-fit: cover;
      width: 108px;
    }

    .banner-title {
      color: var(--banner-ink);
      font-size: .96rem;
      font-weight: 800;
      line-height: 1.35;
      margin-bottom: .25rem;
    }

    .banner-subtext {
      color: #64748b;
      font-size: .84rem;
      line-height: 1.55;
    }

    .banner-pill-row {
      display: flex;
      flex-wrap: wrap;
      gap: .5rem;
    }

    .banner-pill {
      align-items: center;
      border-radius: 999px;
      display: inline-flex;
      font-size: .78rem;
      font-weight: 700;
      line-height: 1;
      padding: .48rem .8rem;
      white-space: nowrap;
    }

    .status-active { background: rgba(16, 185, 129, .14); color: #047857; }
    .status-inactive { background: rgba(148, 163, 184, .18); color: #475569; }
    .asset-ready { background: rgba(15, 157, 148, .14); color: #0f766e; }
    .asset-missing { background: rgba(239, 68, 68, .14); color: #b91c1c; }
    .copy-ready { background: rgba(37, 99, 235, .16); color: #1d4ed8; }
    .copy-empty { background: rgba(217, 119, 6, .16); color: #b45309; }

    .banner-actions {
      display: flex;
      flex-wrap: wrap;
      gap: .5rem;
    }

    .banner-action-btn {
      align-items: center;
      border-radius: .85rem;
      display: inline-flex;
      font-size: .82rem;
      font-weight: 700;
      gap: .45rem;
      justify-content: center;
      min-width: 5rem;
      padding: .68rem .85rem;
      text-decoration: none;
      transition: all .2s ease;
    }

    .banner-action-btn:hover,
    .banner-action-btn:focus {
      text-decoration: none;
      transform: translateY(-1px);
    }

    .banner-action-edit { background: rgba(43, 108, 176, .14); color: #1d4ed8; }
    .banner-action-delete { background: rgba(239, 68, 68, .14); border: 0; color: #b91c1c; }

    .banner-empty {
      background: linear-gradient(180deg, #ffffff 0%, #f8fbff 100%);
      border: 1px dashed var(--banner-border);
      border-radius: 1rem;
      padding: 2rem 1.5rem;
      text-align: center;
    }

    .banner-empty-icon {
      align-items: center;
      background: rgba(43, 108, 176, .1);
      border-radius: 999px;
      color: var(--banner-sky);
      display: inline-flex;
      font-size: 1.35rem;
      height: 4rem;
      justify-content: center;
      margin-bottom: 1rem;
      width: 4rem;
    }

    .banner-pagination { margin-top: 1.25rem; }
    .banner-pagination .pagination { justify-content: flex-end; margin-bottom: 0; }

    div.dataTables_wrapper div.dataTables_filter,
    div.dataTables_wrapper div.dataTables_length,
    div.dataTables_wrapper div.dataTables_info,
    div.dataTables_wrapper div.dataTables_paginate { display: none; }

    table.dataTable {
      border-collapse: collapse !important;
      margin-top: 0 !important;
      width: 100% !important;
    }

    @media (max-width: 991.98px) {
      .banner-hero h1 { font-size: 1.65rem; }
      .banner-panel-meta { justify-content: flex-start; }
    }

    @media (max-width: 767.98px) {
      .banner-focus-grid { grid-template-columns: 1fr; }
      .banner-search { width: 100%; }
      .banner-pagination .pagination { justify-content: center; }
    }
  </style>
@endpush

@section('main-content')
  <div class="container-fluid banner-page">
    @include('backend.layouts.notification')

    <div class="banner-hero mb-4">
      <div class="row align-items-stretch">
        <div class="col-xl-7 mb-4 mb-xl-0">
          <div class="banner-hero-copy">
            <div class="banner-kicker">Campaign Visual Center</div>
            <h1>Quản lý banner rõ ràng hơn, nhìn nhanh được trạng thái và mức độ hoàn thiện nội dung</h1>
            <p>Trang này giúp admin theo dõi nhanh banner nào đang hoạt động, banner nào đã có ảnh sẵn sàng hiển thị và banner nào còn thiếu nội dung mô tả để hoàn thiện chiến dịch.</p>
            <div class="banner-hero-actions">
              <a href="{{ route('banner.create') }}" class="btn btn-light btn-sm">
                <i class="fas fa-plus mr-1"></i> Thêm banner
              </a>
              <button type="button" id="bannerQuickRefresh" class="btn btn-outline-light btn-sm">
                <i class="fas fa-sync-alt mr-1"></i> Làm mới danh sách
              </button>
            </div>
          </div>
        </div>

        <div class="col-xl-5">
          <div class="banner-hero-focus">
            <div class="banner-focus-label">Banner mới nhất</div>
            @if ($latestBanner)
              <div class="banner-focus-title">{{ $latestBanner->title }}</div>
              <div class="banner-focus-subtitle">
                {{ \Illuminate\Support\Str::limit(strip_tags($latestBanner->description ?: 'Banner này chưa có mô tả ngắn.'), 110) }}
              </div>
            @else
              <div class="banner-focus-title">Chưa có banner</div>
              <div class="banner-focus-subtitle">Banner mới nhất sẽ xuất hiện ở đây khi bạn tạo dữ liệu chiến dịch đầu tiên.</div>
            @endif

            <div class="banner-focus-grid">
              <div class="banner-focus-item">
                <span>Ready to launch</span>
                <strong>{{ number_format($readyBanners, 0, ',', '.') }}</strong>
              </div>
              <div class="banner-focus-item">
                <span>Có hình ảnh</span>
                <strong>{{ number_format($bannersWithPhoto, 0, ',', '.') }}</strong>
              </div>
              <div class="banner-focus-item">
                <span>Có mô tả</span>
                <strong>{{ number_format($bannersWithDescription, 0, ',', '.') }}</strong>
              </div>
              <div class="banner-focus-item">
                <span>Tạm ẩn</span>
                <strong>{{ number_format($inactiveBanners, 0, ',', '.') }}</strong>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="row">
      <div class="col-xl-3 col-md-6 mb-4">
        <div class="card banner-card banner-card-primary">
          <div class="card-body">
            <div class="banner-card-label">Tổng banner</div>
            <div class="banner-card-value">{{ number_format($totalBanners, 0, ',', '.') }}</div>
            <div class="banner-card-text">Tổng số visual/campaign banner hiện có trong hệ thống quản trị.</div>
            <span class="banner-chip">{{ number_format($inactiveBanners, 0, ',', '.') }} banner tạm ẩn</span>
          </div>
        </div>
      </div>

      <div class="col-xl-3 col-md-6 mb-4">
        <div class="card banner-card banner-card-success">
          <div class="card-body">
            <div class="banner-card-label">Đang hoạt động</div>
            <div class="banner-card-value">{{ number_format($activeBanners, 0, ',', '.') }}</div>
            <div class="banner-card-text">Các banner đang được phép hiển thị trên storefront hoặc landing page.</div>
            <span class="banner-chip">{{ number_format($readyBanners, 0, ',', '.') }} banner sẵn sàng</span>
          </div>
        </div>
      </div>

      <div class="col-xl-3 col-md-6 mb-4">
        <div class="card banner-card banner-card-warning">
          <div class="card-body">
            <div class="banner-card-label">Thiếu hình ảnh</div>
            <div class="banner-card-value">{{ number_format($bannersWithoutPhoto, 0, ',', '.') }}</div>
            <div class="banner-card-text">Những banner chưa có ảnh hiển thị, thường là nhóm cần bổ sung gấp trước khi publish.</div>
            <span class="banner-chip">{{ number_format($bannersWithPhoto, 0, ',', '.') }} banner có ảnh</span>
          </div>
        </div>
      </div>

      <div class="col-xl-3 col-md-6 mb-4">
        <div class="card banner-card banner-card-dark">
          <div class="card-body">
            <div class="banner-card-label">Có mô tả</div>
            <div class="banner-card-value">{{ number_format($bannersWithDescription, 0, ',', '.') }}</div>
            <div class="banner-card-text">Số banner đã có phần copy/mô tả để truyền tải ý tưởng chiến dịch rõ hơn.</div>
            <span class="banner-chip">{{ number_format($totalBanners - $bannersWithDescription, 0, ',', '.') }} banner chưa có copy</span>
          </div>
        </div>
      </div>
    </div>

    <div class="card banner-panel">
      <div class="card-header">
        <div class="row align-items-lg-center">
          <div class="col-lg-8">
            <div class="banner-panel-title">Danh sách banner</div>
            <p class="banner-panel-subtitle">Tìm nhanh theo tiêu đề hoặc slug, đồng thời lọc theo trạng thái, tình trạng ảnh và mức độ hoàn thiện nội dung để xử lý campaign gọn hơn.</p>
          </div>
          <div class="col-lg-4">
            <div class="banner-panel-meta">
              <span class="banner-panel-badge">
                <i class="fas fa-layer-group"></i>
                Hiển thị {{ number_format($banners->count(), 0, ',', '.') }} / {{ number_format($totalBanners, 0, ',', '.') }} banner
              </span>
              <span class="banner-panel-badge">
                <i class="fas fa-image"></i>
                {{ number_format($bannersWithPhoto, 0, ',', '.') }} banner có ảnh
              </span>
            </div>
          </div>
        </div>

        <div class="banner-toolbar">
          <div class="banner-filter-group" role="group" aria-label="Lọc banner">
            <button type="button" class="banner-filter-chip active" data-filter="all">
              Tất cả
              <span>{{ number_format($totalBanners, 0, ',', '.') }}</span>
            </button>
            <button type="button" class="banner-filter-chip" data-filter="active">
              Active
              <span>{{ number_format($activeBanners, 0, ',', '.') }}</span>
            </button>
            <button type="button" class="banner-filter-chip" data-filter="inactive">
              Inactive
              <span>{{ number_format($inactiveBanners, 0, ',', '.') }}</span>
            </button>
            <button type="button" class="banner-filter-chip" data-filter="missing-photo">
              Thiếu ảnh
              <span>{{ number_format($bannersWithoutPhoto, 0, ',', '.') }}</span>
            </button>
            <button type="button" class="banner-filter-chip" data-filter="missing-copy">
              Thiếu mô tả
              <span>{{ number_format($totalBanners - $bannersWithDescription, 0, ',', '.') }}</span>
            </button>
          </div>

          <div class="banner-search">
            <i class="fas fa-search"></i>
            <input
              type="text"
              id="bannerSearchInput"
              class="form-control"
              placeholder="Tìm theo tiêu đề, slug hoặc nội dung..."
            >
          </div>
        </div>
      </div>

      <div class="card-body">
        @if ($banners->count())
          <div class="table-responsive">
            <table class="table banner-table" id="banner-dataTable" width="100%" cellspacing="0">
              <thead>
                <tr>
                  <th>Banner</th>
                  <th>Slug</th>
                  <th>Hình ảnh</th>
                  <th>Nội dung</th>
                  <th>Trạng thái</th>
                  <th>Thao tác</th>
                </tr>
              </thead>
              <tbody>
                @foreach($banners as $banner)
                  @php
                    $photoUrl = null;
                    if (!empty($banner->photo)) {
                        $photoUrl = \Illuminate\Support\Str::startsWith($banner->photo, ['http://', 'https://'])
                            ? $banner->photo
                            : asset(ltrim($banner->photo, '/'));
                    }

                    $description = $banner->description
                        ? \Illuminate\Support\Str::limit(strip_tags($banner->description), 120)
                        : 'Banner này chưa có mô tả chiến dịch.';
                  @endphp
                  <tr
                    data-banner-status="{{ $banner->status }}"
                    data-banner-photo="{{ $photoUrl ? 'yes' : 'missing' }}"
                    data-banner-copy="{{ $banner->description ? 'ready' : 'missing' }}"
                  >
                    <td>
                      <div class="banner-main-cell">
                        <img
                          src="{{ $photoUrl ?: asset('backend/img/thumbnail-default.jpg') }}"
                          class="banner-thumb"
                          alt="{{ $banner->title }}"
                        >
                        <div>
                          <div class="banner-title">{{ $banner->title }}</div>
                          <div class="banner-subtext">#{{ $banner->id }} • {{ $description }}</div>
                        </div>
                      </div>
                    </td>
                    <td>
                      <div class="banner-title">{{ $banner->slug }}</div>
                      <div class="banner-subtext">URL-friendly identifier</div>
                    </td>
                    <td>
                      <div class="banner-pill-row mb-2">
                        <span class="banner-pill {{ $photoUrl ? 'asset-ready' : 'asset-missing' }}">
                          {{ $photoUrl ? 'Đã có ảnh' : 'Thiếu ảnh' }}
                        </span>
                      </div>
                      <div class="banner-subtext">
                        {{ $photoUrl ? 'Banner có thể preview trực tiếp trên storefront hoặc campaign page.' : 'Nên bổ sung hình ảnh trước khi kích hoạt chiến dịch.' }}
                      </div>
                    </td>
                    <td>
                      <div class="banner-pill-row mb-2">
                        <span class="banner-pill {{ $banner->description ? 'copy-ready' : 'copy-empty' }}">
                          {{ $banner->description ? 'Đã có mô tả' : 'Chưa có mô tả' }}
                        </span>
                      </div>
                      <div class="banner-subtext">{{ $description }}</div>
                    </td>
                    <td>
                      <span class="banner-pill {{ $banner->status === 'active' ? 'status-active' : 'status-inactive' }}">
                        {{ $banner->status === 'active' ? 'Đang hoạt động' : 'Tạm ẩn' }}
                      </span>
                    </td>
                    <td>
                      <div class="banner-actions">
                        <a href="{{ route('banner.edit', $banner->id) }}" class="banner-action-btn banner-action-edit" data-toggle="tooltip" title="Chỉnh sửa banner">
                          <i class="fas fa-pen"></i>
                          <span>Sửa</span>
                        </a>
                        <form method="POST" action="{{ route('banner.destroy', [$banner->id]) }}">
                          @csrf
                          @method('delete')
                          <button class="banner-action-btn banner-action-delete dltBtn" data-id="{{ $banner->id }}" data-toggle="tooltip" title="Xóa banner">
                            <i class="fas fa-trash-alt"></i>
                            <span>Xóa</span>
                          </button>
                        </form>
                      </div>
                    </td>
                  </tr>
                @endforeach
              </tbody>
            </table>
          </div>

          <div class="banner-pagination">
            {{ $banners->links() }}
          </div>
        @else
          <div class="banner-empty">
            <div class="banner-empty-icon">
              <i class="fas fa-images"></i>
            </div>
            <h3>Chưa có banner nào</h3>
            <p class="text-muted mb-4">Hãy tạo banner đầu tiên để bắt đầu quản lý visual cho trang chủ hoặc chiến dịch.</p>
            <a href="{{ route('banner.create') }}" class="btn btn-primary btn-sm">
              <i class="fas fa-plus mr-1"></i> Thêm banner
            </a>
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
      const $table = $('#banner-dataTable');
      const bannerFilter = { value: 'all' };

      $.fn.dataTable.ext.search.push(function (settings, data, dataIndex) {
        if (settings.nTable.id !== 'banner-dataTable') {
          return true;
        }

        if (bannerFilter.value === 'all') {
          return true;
        }

        const rowNode = settings.aoData[dataIndex] && settings.aoData[dataIndex].nTr;
        if (!rowNode) {
          return true;
        }

        const status = rowNode.getAttribute('data-banner-status');
        const photo = rowNode.getAttribute('data-banner-photo');
        const copy = rowNode.getAttribute('data-banner-copy');

        if (bannerFilter.value === 'missing-photo') {
          return photo === 'missing';
        }

        if (bannerFilter.value === 'missing-copy') {
          return copy === 'missing';
        }

        return status === bannerFilter.value;
      });

      if ($table.length) {
        const bannerTable = $table.DataTable({
          autoWidth: false,
          info: false,
          language: {
            emptyTable: 'Chưa có banner nào.',
            zeroRecords: 'Không tìm thấy banner phù hợp trên trang này.'
          },
          lengthChange: false,
          order: [],
          paging: false,
          searching: true,
          columnDefs: [
            {
              orderable: false,
              targets: [2, 5]
            }
          ]
        });

        $('#bannerSearchInput').on('keyup', function () {
          bannerTable.search(this.value).draw();
        });

        $('.banner-filter-chip').on('click', function () {
          const $chip = $(this);
          bannerFilter.value = $chip.data('filter');

          $('.banner-filter-chip').removeClass('active');
          $chip.addClass('active');
          bannerTable.draw();
        });
      }

      $('#bannerQuickRefresh').on('click', function () {
        window.location.reload();
      });

      $('[data-toggle="tooltip"]').tooltip();

      $('.dltBtn').click(function(e){
        const form = $(this).closest('form');
        e.preventDefault();

        swal({
          title: "Bạn có chắc không?",
          text: "Khi xóa sẽ không thể khôi phục dữ liệu!",
          icon: "warning",
          buttons: true,
          dangerMode: true,
        }).then((willDelete) => {
          if (willDelete) {
            form.submit();
          } else {
            swal("Dữ liệu an toàn!");
          }
        });
      });
    });
  </script>
@endpush
