@extends('backend.layouts.master')

@section('title', 'Web bán tai nghe || Quản lý danh mục')

@push('styles')
  <link href="{{ asset('backend/vendor/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.css" />
  <style>
    :root {
      --taxonomy-ink: #153243;
      --taxonomy-sky: #2b6cb0;
      --taxonomy-teal: #0f9d94;
      --taxonomy-amber: #d97706;
      --taxonomy-rose: #c53030;
      --taxonomy-slate: #64748b;
      --taxonomy-border: #e2e8f0;
      --taxonomy-surface: #f8fafc;
      --taxonomy-shadow: 0 20px 45px rgba(21, 50, 67, 0.08);
      --taxonomy-soft-shadow: 0 14px 30px rgba(21, 50, 67, 0.06);
    }

    .taxonomy-page { padding-bottom: 2rem; }

    .taxonomy-hero {
      background:
        radial-gradient(circle at top right, rgba(96, 165, 250, .32), transparent 34%),
        linear-gradient(135deg, #17324d 0%, #235a70 52%, #0f9d94 100%);
      border-radius: 1.4rem;
      box-shadow: var(--taxonomy-shadow);
      color: #fff;
      overflow: hidden;
      padding: 1.75rem;
      position: relative;
    }

    .taxonomy-hero::after {
      background: rgba(255, 255, 255, .08);
      border-radius: 999px;
      content: '';
      height: 15rem;
      position: absolute;
      right: -4rem;
      top: -5rem;
      width: 15rem;
    }

    .taxonomy-hero-copy,
    .taxonomy-hero-focus { position: relative; z-index: 1; }

    .taxonomy-kicker {
      font-size: .8rem;
      font-weight: 800;
      letter-spacing: .22em;
      margin-bottom: .85rem;
      text-transform: uppercase;
    }

    .taxonomy-hero h1 {
      font-size: 2rem;
      font-weight: 800;
      line-height: 1.15;
      margin-bottom: .9rem;
      max-width: 38rem;
    }

    .taxonomy-hero p {
      color: rgba(255, 255, 255, .84);
      margin-bottom: 0;
      max-width: 42rem;
    }

    .taxonomy-hero-actions {
      display: flex;
      flex-wrap: wrap;
      gap: .75rem;
      margin-top: 1.4rem;
    }

    .taxonomy-hero-actions .btn {
      border-radius: 999px;
      font-weight: 700;
      padding-left: 1rem;
      padding-right: 1rem;
    }

    .taxonomy-hero-focus {
      background: rgba(255, 255, 255, .12);
      border: 1px solid rgba(255, 255, 255, .16);
      border-radius: 1.1rem;
      height: 100%;
      padding: 1.2rem;
    }

    .taxonomy-focus-label {
      color: rgba(255, 255, 255, .74);
      font-size: .78rem;
      font-weight: 700;
      letter-spacing: .08em;
      margin-bottom: .35rem;
      text-transform: uppercase;
    }

    .taxonomy-focus-title {
      font-size: 1.35rem;
      font-weight: 800;
      line-height: 1.2;
      margin-bottom: .35rem;
    }

    .taxonomy-focus-subtitle {
      color: rgba(255, 255, 255, .84);
      font-size: .9rem;
      line-height: 1.55;
    }

    .taxonomy-focus-grid {
      display: grid;
      gap: .75rem;
      grid-template-columns: repeat(2, minmax(0, 1fr));
      margin-top: 1rem;
    }

    .taxonomy-focus-item {
      background: rgba(255, 255, 255, .08);
      border-radius: .95rem;
      padding: .9rem 1rem;
    }

    .taxonomy-focus-item span {
      color: rgba(255, 255, 255, .72);
      display: block;
      font-size: .78rem;
      margin-bottom: .35rem;
      text-transform: uppercase;
    }

    .taxonomy-focus-item strong {
      color: #fff;
      display: block;
      font-size: 1.1rem;
      font-weight: 800;
      line-height: 1.2;
    }

    .taxonomy-card {
      border: 0;
      border-radius: 1rem;
      box-shadow: var(--taxonomy-soft-shadow);
      color: #fff;
      height: 100%;
      overflow: hidden;
      position: relative;
    }

    .taxonomy-card::after {
      background: rgba(255, 255, 255, .08);
      border-radius: 999px;
      content: '';
      height: 7rem;
      position: absolute;
      right: -1.5rem;
      top: -1.5rem;
      width: 7rem;
    }

    .taxonomy-card .card-body { position: relative; z-index: 1; }
    .taxonomy-card-primary { background: linear-gradient(140deg, #235789 0%, #2b6cb0 100%); }
    .taxonomy-card-success { background: linear-gradient(140deg, #0f766e 0%, #0f9d94 100%); }
    .taxonomy-card-warning { background: linear-gradient(140deg, #b45309 0%, #d97706 100%); }
    .taxonomy-card-dark { background: linear-gradient(140deg, #334155 0%, #475569 100%); }

    .taxonomy-card-label {
      font-size: .78rem;
      font-weight: 700;
      letter-spacing: .08em;
      margin-bottom: .7rem;
      opacity: .9;
      text-transform: uppercase;
    }

    .taxonomy-card-value {
      font-size: 1.8rem;
      font-weight: 800;
      line-height: 1.1;
      margin-bottom: .6rem;
    }

    .taxonomy-card-text {
      font-size: .88rem;
      margin-bottom: .85rem;
      opacity: .84;
    }

    .taxonomy-chip {
      align-items: center;
      background: rgba(255, 255, 255, .16);
      border-radius: 999px;
      display: inline-flex;
      font-size: .78rem;
      font-weight: 700;
      padding: .35rem .7rem;
    }

    .taxonomy-panel {
      background: #fff;
      border: 0;
      border-radius: 1.2rem;
      box-shadow: var(--taxonomy-shadow);
      overflow: hidden;
    }

    .taxonomy-panel .card-header {
      background: #fff;
      border-bottom: 1px solid rgba(226, 232, 240, .95);
      padding: 1.35rem 1.45rem 1rem;
    }

    .taxonomy-panel .card-body {
      padding: 1.35rem 1.45rem 1.45rem;
    }

    .taxonomy-panel-title {
      color: var(--taxonomy-ink);
      font-size: 1.08rem;
      font-weight: 800;
      margin-bottom: .2rem;
    }

    .taxonomy-panel-subtitle {
      color: #718096;
      font-size: .9rem;
      margin-bottom: 0;
      max-width: 42rem;
    }

    .taxonomy-panel-meta {
      display: flex;
      flex-wrap: wrap;
      gap: .6rem;
      justify-content: flex-end;
      margin-top: 1rem;
    }

    .taxonomy-panel-badge {
      align-items: center;
      background: #f8fbff;
      border: 1px solid var(--taxonomy-border);
      border-radius: 999px;
      color: var(--taxonomy-ink);
      display: inline-flex;
      font-size: .8rem;
      font-weight: 700;
      gap: .35rem;
      padding: .45rem .8rem;
    }

    .taxonomy-toolbar {
      align-items: center;
      display: flex;
      flex-wrap: wrap;
      gap: 1rem;
      justify-content: space-between;
      margin-top: 1.15rem;
    }

    .taxonomy-filter-group {
      display: flex;
      flex-wrap: wrap;
      gap: .65rem;
    }

    .taxonomy-filter-chip {
      align-items: center;
      background: #fff;
      border: 1px solid var(--taxonomy-border);
      border-radius: 999px;
      color: #475569;
      display: inline-flex;
      font-size: .85rem;
      font-weight: 700;
      gap: .45rem;
      padding: .7rem 1rem;
      transition: all .2s ease;
    }

    .taxonomy-filter-chip span {
      background: #f1f5f9;
      border-radius: 999px;
      color: #334155;
      min-width: 1.75rem;
      padding: .15rem .45rem;
      text-align: center;
    }

    .taxonomy-filter-chip:hover,
    .taxonomy-filter-chip:focus {
      box-shadow: 0 10px 20px rgba(21, 50, 67, .08);
      color: #1e293b;
      outline: none;
      text-decoration: none;
      transform: translateY(-1px);
    }

    .taxonomy-filter-chip.active {
      background: linear-gradient(135deg, #153243 0%, #2b6cb0 100%);
      border-color: transparent;
      color: #fff;
    }

    .taxonomy-filter-chip.active span {
      background: rgba(255, 255, 255, .15);
      color: #fff;
    }

    .taxonomy-search {
      position: relative;
      width: min(100%, 24rem);
    }

    .taxonomy-search i {
      color: #64748b;
      left: 1rem;
      position: absolute;
      top: 50%;
      transform: translateY(-50%);
    }

    .taxonomy-search .form-control {
      border: 1px solid var(--taxonomy-border);
      border-radius: 999px;
      box-shadow: none;
      font-size: .92rem;
      height: 3rem;
      padding-left: 2.7rem;
    }

    .taxonomy-search .form-control:focus {
      border-color: rgba(43, 108, 176, .5);
      box-shadow: 0 0 0 .2rem rgba(43, 108, 176, .12);
    }

    .taxonomy-table { margin-bottom: 0; }

    .taxonomy-table thead th {
      border-bottom: 1px solid var(--taxonomy-border);
      border-top: 0;
      color: #718096;
      font-size: .76rem;
      font-weight: 800;
      letter-spacing: .08em;
      padding: 1rem;
      text-transform: uppercase;
      white-space: nowrap;
    }

    .taxonomy-table tbody tr:hover { background: #fbfdff; }
    .taxonomy-table tbody td {
      border-color: var(--taxonomy-border);
      padding: 1rem;
      vertical-align: top;
    }

    .taxonomy-category-cell {
      display: flex;
      gap: .9rem;
    }

    .taxonomy-thumb {
      background: #f8fafc;
      border: 1px solid #e2e8f0;
      border-radius: 1rem;
      flex: 0 0 72px;
      height: 72px;
      object-fit: cover;
      width: 72px;
    }

    .taxonomy-title {
      color: var(--taxonomy-ink);
      font-size: .96rem;
      font-weight: 800;
      line-height: 1.35;
      margin-bottom: .25rem;
    }

    .taxonomy-subtext {
      color: #64748b;
      font-size: .84rem;
      line-height: 1.55;
    }

    .taxonomy-pill-row {
      display: flex;
      flex-wrap: wrap;
      gap: .5rem;
    }

    .taxonomy-pill {
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
    .type-parent { background: rgba(37, 99, 235, .16); color: #1d4ed8; }
    .type-child { background: rgba(217, 119, 6, .16); color: #b45309; }
    .photo-yes { background: rgba(15, 157, 148, .14); color: #0f766e; }
    .photo-no { background: rgba(239, 68, 68, .14); color: #b91c1c; }

    .taxonomy-actions {
      display: flex;
      flex-wrap: wrap;
      gap: .5rem;
    }

    .taxonomy-action-btn {
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

    .taxonomy-action-btn:hover,
    .taxonomy-action-btn:focus {
      text-decoration: none;
      transform: translateY(-1px);
    }

    .taxonomy-action-edit { background: rgba(43, 108, 176, .14); color: #1d4ed8; }
    .taxonomy-action-delete { background: rgba(239, 68, 68, .14); border: 0; color: #b91c1c; }

    .taxonomy-empty {
      background: linear-gradient(180deg, #ffffff 0%, #f8fbff 100%);
      border: 1px dashed var(--taxonomy-border);
      border-radius: 1rem;
      padding: 2rem 1.5rem;
      text-align: center;
    }

    .taxonomy-empty-icon {
      align-items: center;
      background: rgba(43, 108, 176, .1);
      border-radius: 999px;
      color: var(--taxonomy-sky);
      display: inline-flex;
      font-size: 1.35rem;
      height: 4rem;
      justify-content: center;
      margin-bottom: 1rem;
      width: 4rem;
    }

    .taxonomy-pagination { margin-top: 1.25rem; }
    .taxonomy-pagination .pagination { justify-content: flex-end; margin-bottom: 0; }

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
      .taxonomy-hero h1 { font-size: 1.65rem; }
      .taxonomy-panel-meta { justify-content: flex-start; }
    }

    @media (max-width: 767.98px) {
      .taxonomy-focus-grid { grid-template-columns: 1fr; }
      .taxonomy-search { width: 100%; }
      .taxonomy-pagination .pagination { justify-content: center; }
    }
  </style>
@endpush

@section('main-content')
  <div class="container-fluid taxonomy-page">
    @include('backend.layouts.notification')

    <div class="taxonomy-hero mb-4">
      <div class="row align-items-stretch">
        <div class="col-xl-7 mb-4 mb-xl-0">
          <div class="taxonomy-hero-copy">
            <div class="taxonomy-kicker">Category Structure Center</div>
            <h1>Quản lý cây danh mục rõ ràng hơn, nhìn nhanh được phân cấp và độ phủ nội dung</h1>
            <p>Trang này giúp admin nắm nhanh danh mục cha, danh mục con, nhóm đang hoạt động và những nhánh chưa có ảnh hoặc chưa gắn sản phẩm để tối ưu cấu trúc catalogue.</p>
            <div class="taxonomy-hero-actions">
              <a href="{{ route('category.create') }}" class="btn btn-light btn-sm">
                <i class="fas fa-plus mr-1"></i> Thêm danh mục
              </a>
              <button type="button" id="categoryQuickRefresh" class="btn btn-outline-light btn-sm">
                <i class="fas fa-sync-alt mr-1"></i> Làm mới danh sách
              </button>
            </div>
          </div>
        </div>

        <div class="col-xl-5">
          <div class="taxonomy-hero-focus">
            <div class="taxonomy-focus-label">Danh mục mới nhất</div>
            @if ($latestCategory)
              <div class="taxonomy-focus-title">{{ $latestCategory->title }}</div>
              <div class="taxonomy-focus-subtitle">
                @if($latestCategory->is_parent)
                  Danh mục cha độc lập
                @else
                  Thuộc: {{ optional($latestCategory->parent_info)->title ?: 'Chưa gán danh mục cha' }}
                @endif
              </div>
            @else
              <div class="taxonomy-focus-title">Chưa có danh mục</div>
              <div class="taxonomy-focus-subtitle">Danh mục mới nhất sẽ xuất hiện ở đây khi hệ thống có dữ liệu.</div>
            @endif

            <div class="taxonomy-focus-grid">
              <div class="taxonomy-focus-item">
                <span>Danh mục cha</span>
                <strong>{{ number_format($parentCategories, 0, ',', '.') }}</strong>
              </div>
              <div class="taxonomy-focus-item">
                <span>Danh mục con</span>
                <strong>{{ number_format($childCategories, 0, ',', '.') }}</strong>
              </div>
              <div class="taxonomy-focus-item">
                <span>Có nhánh con</span>
                <strong>{{ number_format($categoriesWithChildren, 0, ',', '.') }}</strong>
              </div>
              <div class="taxonomy-focus-item">
                <span>Thiếu ảnh</span>
                <strong>{{ number_format($categoriesWithoutPhoto, 0, ',', '.') }}</strong>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="row">
      <div class="col-xl-3 col-md-6 mb-4">
        <div class="card taxonomy-card taxonomy-card-primary">
          <div class="card-body">
            <div class="taxonomy-card-label">Tổng danh mục</div>
            <div class="taxonomy-card-value">{{ number_format($totalCategories, 0, ',', '.') }}</div>
            <div class="taxonomy-card-text">Toàn bộ nhánh danh mục hiện có trong admin, bao gồm cả cha và con.</div>
            <span class="taxonomy-chip">{{ number_format($inactiveCategories, 0, ',', '.') }} nhánh tạm ẩn</span>
          </div>
        </div>
      </div>

      <div class="col-xl-3 col-md-6 mb-4">
        <div class="card taxonomy-card taxonomy-card-success">
          <div class="card-body">
            <div class="taxonomy-card-label">Đang hoạt động</div>
            <div class="taxonomy-card-value">{{ number_format($activeCategories, 0, ',', '.') }}</div>
            <div class="taxonomy-card-text">Những danh mục đang sẵn sàng dùng cho storefront và hiển thị phân loại sản phẩm.</div>
            <span class="taxonomy-chip">{{ number_format($categoriesWithProducts, 0, ',', '.') }} danh mục có sản phẩm</span>
          </div>
        </div>
      </div>

      <div class="col-xl-3 col-md-6 mb-4">
        <div class="card taxonomy-card taxonomy-card-warning">
          <div class="card-body">
            <div class="taxonomy-card-label">Cấu trúc phân cấp</div>
            <div class="taxonomy-card-value">{{ number_format($childCategories, 0, ',', '.') }}</div>
            <div class="taxonomy-card-text">Số lượng danh mục con giúp nhìn nhanh độ sâu của cây phân loại hiện tại.</div>
            <span class="taxonomy-chip">{{ number_format($parentCategories, 0, ',', '.') }} danh mục gốc</span>
          </div>
        </div>
      </div>

      <div class="col-xl-3 col-md-6 mb-4">
        <div class="card taxonomy-card taxonomy-card-dark">
          <div class="card-body">
            <div class="taxonomy-card-label">Nội dung cần bổ sung</div>
            <div class="taxonomy-card-value">{{ number_format($categoriesWithoutPhoto, 0, ',', '.') }}</div>
            <div class="taxonomy-card-text">Các danh mục chưa có ảnh, thường là nhóm cần hoàn thiện để catalogue đồng bộ hơn.</div>
            <span class="taxonomy-chip">{{ number_format($categoriesWithChildren, 0, ',', '.') }} danh mục có nhánh con</span>
          </div>
        </div>
      </div>
    </div>

    <div class="card taxonomy-panel">
      <div class="card-header">
        <div class="row align-items-lg-center">
          <div class="col-lg-8">
            <div class="taxonomy-panel-title">Danh sách danh mục</div>
            <p class="taxonomy-panel-subtitle">Tìm nhanh theo tên hoặc slug, đồng thời lọc theo trạng thái, loại danh mục và mức độ hoàn thiện hình ảnh để xử lý đúng việc cần ưu tiên.</p>
          </div>
          <div class="col-lg-4">
            <div class="taxonomy-panel-meta">
              <span class="taxonomy-panel-badge">
                <i class="fas fa-layer-group"></i>
                Hiển thị {{ number_format($categories->count(), 0, ',', '.') }} / {{ number_format($totalCategories, 0, ',', '.') }} danh mục
              </span>
              <span class="taxonomy-panel-badge">
                <i class="fas fa-sitemap"></i>
                {{ number_format($parentCategories, 0, ',', '.') }} gốc • {{ number_format($childCategories, 0, ',', '.') }} con
              </span>
            </div>
          </div>
        </div>

        <div class="taxonomy-toolbar">
          <div class="taxonomy-filter-group" role="group" aria-label="Lọc danh mục">
            <button type="button" class="taxonomy-filter-chip active" data-filter="all">
              Tất cả
              <span>{{ number_format($totalCategories, 0, ',', '.') }}</span>
            </button>
            <button type="button" class="taxonomy-filter-chip" data-filter="active">
              Active
              <span>{{ number_format($activeCategories, 0, ',', '.') }}</span>
            </button>
            <button type="button" class="taxonomy-filter-chip" data-filter="inactive">
              Inactive
              <span>{{ number_format($inactiveCategories, 0, ',', '.') }}</span>
            </button>
            <button type="button" class="taxonomy-filter-chip" data-filter="parent">
              Danh mục cha
              <span>{{ number_format($parentCategories, 0, ',', '.') }}</span>
            </button>
            <button type="button" class="taxonomy-filter-chip" data-filter="child">
              Danh mục con
              <span>{{ number_format($childCategories, 0, ',', '.') }}</span>
            </button>
            <button type="button" class="taxonomy-filter-chip" data-filter="photo-missing">
              Thiếu ảnh
              <span>{{ number_format($categoriesWithoutPhoto, 0, ',', '.') }}</span>
            </button>
          </div>

          <div class="taxonomy-search">
            <i class="fas fa-search"></i>
            <input
              type="text"
              id="categorySearchInput"
              class="form-control"
              placeholder="Tìm theo tiêu đề, slug hoặc danh mục cha..."
            >
          </div>
        </div>
      </div>

      <div class="card-body">
        @if ($categories->count())
          <div class="table-responsive">
            <table class="table taxonomy-table" id="category-dataTable" width="100%" cellspacing="0">
              <thead>
                <tr>
                  <th>Danh mục</th>
                  <th>Phân cấp</th>
                  <th>Slug</th>
                  <th>Hình ảnh</th>
                  <th>Liên kết</th>
                  <th>Trạng thái</th>
                  <th>Thao tác</th>
                </tr>
              </thead>
              <tbody>
                @foreach($categories as $category)
                  @php
                    $photoUrl = null;
                    if (!empty($category->photo)) {
                        $photoUrl = \Illuminate\Support\Str::startsWith($category->photo, ['http://', 'https://'])
                            ? $category->photo
                            : asset(ltrim($category->photo, '/'));
                    }

                    $summary = $category->summary
                        ? \Illuminate\Support\Str::limit(strip_tags($category->summary), 110)
                        : 'Chưa có mô tả ngắn cho danh mục này.';

                    $relatedProducts = (int) $category->active_products_count + (int) $category->active_sub_products_count;
                  @endphp
                  <tr
                    data-category-status="{{ $category->status }}"
                    data-category-parent="{{ $category->is_parent ? 'parent' : 'child' }}"
                    data-category-photo="{{ $photoUrl ? 'yes' : 'missing' }}"
                  >
                    <td>
                      <div class="taxonomy-category-cell">
                        <img
                          src="{{ $photoUrl ?: asset('backend/img/thumbnail-default.jpg') }}"
                          class="taxonomy-thumb"
                          alt="{{ $category->title }}"
                        >
                        <div>
                          <div class="taxonomy-title">{{ $category->title }}</div>
                          <div class="taxonomy-subtext">#{{ $category->id }} • {{ $summary }}</div>
                        </div>
                      </div>
                    </td>
                    <td>
                      <div class="taxonomy-pill-row mb-2">
                        <span class="taxonomy-pill {{ $category->is_parent ? 'type-parent' : 'type-child' }}">
                          {{ $category->is_parent ? 'Danh mục cha' : 'Danh mục con' }}
                        </span>
                      </div>
                      <div class="taxonomy-subtext">
                        @if($category->is_parent)
                          Nhánh gốc của cây phân loại
                        @else
                          Thuộc: {{ optional($category->parent_info)->title ?: 'Chưa gán danh mục cha' }}
                        @endif
                      </div>
                    </td>
                    <td>
                      <div class="taxonomy-title">{{ $category->slug }}</div>
                      <div class="taxonomy-subtext">URL-friendly identifier</div>
                    </td>
                    <td>
                      <div class="taxonomy-pill-row mb-2">
                        <span class="taxonomy-pill {{ $photoUrl ? 'photo-yes' : 'photo-no' }}">
                          {{ $photoUrl ? 'Đã có ảnh' : 'Thiếu ảnh' }}
                        </span>
                      </div>
                      <div class="taxonomy-subtext">
                        {{ $photoUrl ? 'Có thể dùng ngay cho card danh mục và menu hiển thị.' : 'Nên bổ sung ảnh để giao diện storefront đồng bộ hơn.' }}
                      </div>
                    </td>
                    <td>
                      <div class="taxonomy-title">{{ number_format($relatedProducts, 0, ',', '.') }} sản phẩm</div>
                      <div class="taxonomy-subtext">{{ number_format($category->active_children_count, 0, ',', '.') }} nhánh con active</div>
                    </td>
                    <td>
                      <span class="taxonomy-pill {{ $category->status === 'active' ? 'status-active' : 'status-inactive' }}">
                        {{ $category->status === 'active' ? 'Đang hoạt động' : 'Tạm ẩn' }}
                      </span>
                    </td>
                    <td>
                      <div class="taxonomy-actions">
                        <a href="{{ route('category.edit', $category->id) }}" class="taxonomy-action-btn taxonomy-action-edit" data-toggle="tooltip" title="Chỉnh sửa danh mục">
                          <i class="fas fa-pen"></i>
                          <span>Sửa</span>
                        </a>
                        <form method="POST" action="{{ route('category.destroy', [$category->id]) }}">
                          @csrf
                          @method('delete')
                          <button class="taxonomy-action-btn taxonomy-action-delete dltBtn" data-id="{{ $category->id }}" data-toggle="tooltip" title="Xóa danh mục">
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

          <div class="taxonomy-pagination">
            {{ $categories->links() }}
          </div>
        @else
          <div class="taxonomy-empty">
            <div class="taxonomy-empty-icon">
              <i class="fas fa-sitemap"></i>
            </div>
            <h3>Chưa có danh mục nào</h3>
            <p class="text-muted mb-4">Hãy tạo danh mục đầu tiên để bắt đầu tổ chức cấu trúc catalogue sản phẩm.</p>
            <a href="{{ route('category.create') }}" class="btn btn-primary btn-sm">
              <i class="fas fa-plus mr-1"></i> Thêm danh mục
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
      const $table = $('#category-dataTable');
      const categoryFilter = { value: 'all' };

      $.fn.dataTable.ext.search.push(function (settings, data, dataIndex) {
        if (settings.nTable.id !== 'category-dataTable') {
          return true;
        }

        if (categoryFilter.value === 'all') {
          return true;
        }

        const rowNode = settings.aoData[dataIndex] && settings.aoData[dataIndex].nTr;
        if (!rowNode) {
          return true;
        }

        const status = rowNode.getAttribute('data-category-status');
        const parentType = rowNode.getAttribute('data-category-parent');
        const photo = rowNode.getAttribute('data-category-photo');

        if (categoryFilter.value === 'parent' || categoryFilter.value === 'child') {
          return parentType === categoryFilter.value;
        }

        if (categoryFilter.value === 'photo-missing') {
          return photo === 'missing';
        }

        return status === categoryFilter.value;
      });

      if ($table.length) {
        const categoryTable = $table.DataTable({
          autoWidth: false,
          info: false,
          language: {
            emptyTable: 'Chưa có danh mục nào.',
            zeroRecords: 'Không tìm thấy danh mục phù hợp trên trang này.'
          },
          lengthChange: false,
          order: [],
          paging: false,
          searching: true,
          columnDefs: [
            {
              orderable: false,
              targets: [3, 6]
            }
          ]
        });

        $('#categorySearchInput').on('keyup', function () {
          categoryTable.search(this.value).draw();
        });

        $('.taxonomy-filter-chip').on('click', function () {
          const $chip = $(this);
          categoryFilter.value = $chip.data('filter');

          $('.taxonomy-filter-chip').removeClass('active');
          $chip.addClass('active');
          categoryTable.draw();
        });
      }

      $('#categoryQuickRefresh').on('click', function () {
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
