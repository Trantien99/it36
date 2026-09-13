@extends('backend.layouts.master')

@section('title', 'Web bán tai nghe || Quản lý sản phẩm')

@push('styles')
    <link href="{{ asset('backend/vendor/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.css" />
    <style>
        :root {
            --catalog-ink: #153243;
            --catalog-sky: #2b6cb0;
            --catalog-teal: #0f9d94;
            --catalog-amber: #d97706;
            --catalog-rose: #c53030;
            --catalog-slate: #64748b;
            --catalog-border: #e2e8f0;
            --catalog-surface: #f8fafc;
            --catalog-shadow: 0 20px 45px rgba(21, 50, 67, 0.08);
            --catalog-soft-shadow: 0 14px 30px rgba(21, 50, 67, 0.06);
        }

        .catalog-page {
            padding-bottom: 2rem;
        }

        .catalog-hero {
            background:
                radial-gradient(circle at top right, rgba(96, 165, 250, 0.32), transparent 34%),
                linear-gradient(135deg, #17324d 0%, #235a70 52%, #0f9d94 100%);
            border-radius: 1.4rem;
            box-shadow: var(--catalog-shadow);
            color: #fff;
            overflow: hidden;
            padding: 1.75rem;
            position: relative;
        }

        .catalog-hero::after {
            background: rgba(255, 255, 255, 0.08);
            border-radius: 999px;
            content: '';
            height: 15rem;
            position: absolute;
            right: -4rem;
            top: -5rem;
            width: 15rem;
        }

        .catalog-hero-copy,
        .catalog-hero-focus {
            position: relative;
            z-index: 1;
        }

        .catalog-kicker {
            font-size: .8rem;
            font-weight: 800;
            letter-spacing: .22em;
            margin-bottom: .85rem;
            text-transform: uppercase;
        }

        .catalog-hero h1 {
            font-size: 2rem;
            font-weight: 800;
            line-height: 1.15;
            margin-bottom: .9rem;
            max-width: 38rem;
        }

        .catalog-hero p {
            color: rgba(255, 255, 255, .84);
            margin-bottom: 0;
            max-width: 42rem;
        }

        .catalog-hero-actions {
            display: flex;
            flex-wrap: wrap;
            gap: .75rem;
            margin-top: 1.4rem;
        }

        .catalog-hero-actions .btn {
            border-radius: 999px;
            font-weight: 700;
            padding-left: 1rem;
            padding-right: 1rem;
        }

        .catalog-hero-focus {
            background: rgba(255, 255, 255, .12);
            border: 1px solid rgba(255, 255, 255, .16);
            border-radius: 1.1rem;
            height: 100%;
            padding: 1.2rem;
        }

        .catalog-focus-label {
            color: rgba(255, 255, 255, .74);
            font-size: .78rem;
            font-weight: 700;
            letter-spacing: .08em;
            margin-bottom: .35rem;
            text-transform: uppercase;
        }

        .catalog-focus-title {
            font-size: 1.35rem;
            font-weight: 800;
            line-height: 1.2;
            margin-bottom: .35rem;
        }

        .catalog-focus-subtitle {
            color: rgba(255, 255, 255, .84);
            font-size: .9rem;
            line-height: 1.55;
        }

        .catalog-focus-grid {
            display: grid;
            gap: .75rem;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            margin-top: 1rem;
        }

        .catalog-focus-item {
            background: rgba(255, 255, 255, .08);
            border-radius: .95rem;
            padding: .9rem 1rem;
        }

        .catalog-focus-item span {
            color: rgba(255, 255, 255, .72);
            display: block;
            font-size: .78rem;
            margin-bottom: .35rem;
            text-transform: uppercase;
        }

        .catalog-focus-item strong {
            color: #fff;
            display: block;
            font-size: 1.1rem;
            font-weight: 800;
            line-height: 1.2;
        }

        .catalog-card {
            border: 0;
            border-radius: 1rem;
            box-shadow: var(--catalog-soft-shadow);
            color: #fff;
            height: 100%;
            overflow: hidden;
            position: relative;
        }

        .catalog-card::after {
            background: rgba(255, 255, 255, .08);
            border-radius: 999px;
            content: '';
            height: 7rem;
            position: absolute;
            right: -1.5rem;
            top: -1.5rem;
            width: 7rem;
        }

        .catalog-card .card-body {
            position: relative;
            z-index: 1;
        }

        .catalog-card-primary {
            background: linear-gradient(140deg, #235789 0%, #2b6cb0 100%);
        }

        .catalog-card-success {
            background: linear-gradient(140deg, #0f766e 0%, #0f9d94 100%);
        }

        .catalog-card-warning {
            background: linear-gradient(140deg, #b45309 0%, #d97706 100%);
        }

        .catalog-card-dark {
            background: linear-gradient(140deg, #334155 0%, #475569 100%);
        }

        .catalog-card-label {
            font-size: .78rem;
            font-weight: 700;
            letter-spacing: .08em;
            margin-bottom: .7rem;
            opacity: .9;
            text-transform: uppercase;
        }

        .catalog-card-value {
            font-size: 1.8rem;
            font-weight: 800;
            line-height: 1.1;
            margin-bottom: .6rem;
        }

        .catalog-card-text {
            font-size: .88rem;
            margin-bottom: .85rem;
            opacity: .84;
        }

        .catalog-chip {
            align-items: center;
            background: rgba(255, 255, 255, .16);
            border-radius: 999px;
            display: inline-flex;
            font-size: .78rem;
            font-weight: 700;
            padding: .35rem .7rem;
        }

        .catalog-panel {
            background: #fff;
            border: 0;
            border-radius: 1.2rem;
            box-shadow: var(--catalog-shadow);
            overflow: hidden;
        }

        .catalog-panel .card-header {
            background: #fff;
            border-bottom: 1px solid rgba(226, 232, 240, .95);
            padding: 1.35rem 1.45rem 1rem;
        }

        .catalog-panel .card-body {
            padding: 1.35rem 1.45rem 1.45rem;
        }

        .catalog-panel-title {
            color: var(--catalog-ink);
            font-size: 1.08rem;
            font-weight: 800;
            margin-bottom: .2rem;
        }

        .catalog-panel-subtitle {
            color: #718096;
            font-size: .9rem;
            margin-bottom: 0;
            max-width: 42rem;
        }

        .catalog-panel-meta {
            display: flex;
            flex-wrap: wrap;
            gap: .6rem;
            justify-content: flex-end;
            margin-top: 1rem;
        }

        .catalog-panel-badge {
            align-items: center;
            background: #f8fbff;
            border: 1px solid var(--catalog-border);
            border-radius: 999px;
            color: var(--catalog-ink);
            display: inline-flex;
            font-size: .8rem;
            font-weight: 700;
            gap: .35rem;
            padding: .45rem .8rem;
        }

        .catalog-toolbar {
            align-items: center;
            display: flex;
            flex-wrap: wrap;
            gap: 1rem;
            justify-content: space-between;
            margin-top: 1.15rem;
        }

        .catalog-filter-group {
            display: flex;
            flex-wrap: wrap;
            gap: .65rem;
        }

        .catalog-filter-chip {
            align-items: center;
            background: #fff;
            border: 1px solid var(--catalog-border);
            border-radius: 999px;
            color: #475569;
            display: inline-flex;
            font-size: .85rem;
            font-weight: 700;
            gap: .45rem;
            padding: .7rem 1rem;
            transition: all .2s ease;
        }

        .catalog-filter-chip span {
            background: #f1f5f9;
            border-radius: 999px;
            color: #334155;
            min-width: 1.75rem;
            padding: .15rem .45rem;
            text-align: center;
        }

        .catalog-filter-chip:hover,
        .catalog-filter-chip:focus {
            box-shadow: 0 10px 20px rgba(21, 50, 67, .08);
            color: #1e293b;
            outline: none;
            text-decoration: none;
            transform: translateY(-1px);
        }

        .catalog-filter-chip.active {
            background: linear-gradient(135deg, #153243 0%, #2b6cb0 100%);
            border-color: transparent;
            color: #fff;
        }

        .catalog-filter-chip.active span {
            background: rgba(255, 255, 255, .15);
            color: #fff;
        }

        .catalog-search {
            position: relative;
            width: min(100%, 24rem);
        }

        .catalog-search i {
            color: #64748b;
            left: 1rem;
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
        }

        .catalog-search .form-control {
            border: 1px solid var(--catalog-border);
            border-radius: 999px;
            box-shadow: none;
            font-size: .92rem;
            height: 3rem;
            padding-left: 2.7rem;
        }

        .catalog-search .form-control:focus {
            border-color: rgba(43, 108, 176, .5);
            box-shadow: 0 0 0 .2rem rgba(43, 108, 176, .12);
        }

        .catalog-table {
            margin-bottom: 0;
        }

        .catalog-table thead th {
            border-bottom: 1px solid var(--catalog-border);
            border-top: 0;
            color: #718096;
            font-size: .76rem;
            font-weight: 800;
            letter-spacing: .08em;
            padding: 1rem;
            text-transform: uppercase;
            white-space: nowrap;
        }

        .catalog-table tbody tr:hover {
            background: #fbfdff;
        }

        .catalog-table tbody td {
            border-color: var(--catalog-border);
            padding: 1rem;
            vertical-align: top;
        }

        .catalog-product-cell {
            display: flex;
            gap: .9rem;
        }

        .catalog-product-thumb {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 1rem;
            flex: 0 0 74px;
            height: 74px;
            object-fit: cover;
            width: 74px;
        }

        .catalog-product-title {
            color: var(--catalog-ink);
            font-size: .96rem;
            font-weight: 800;
            line-height: 1.35;
            margin-bottom: .25rem;
        }

        .catalog-product-subtext {
            color: #64748b;
            font-size: .84rem;
            line-height: 1.55;
        }

        .catalog-price {
            color: var(--catalog-ink);
            font-size: 1rem;
            font-weight: 800;
            line-height: 1.25;
        }

        .catalog-price-muted {
            color: #64748b;
            font-size: .83rem;
            margin-top: .3rem;
        }

        .catalog-pill-row {
            display: flex;
            flex-wrap: wrap;
            gap: .5rem;
        }

        .catalog-pill {
            align-items: center;
            border-radius: 999px;
            display: inline-flex;
            font-size: .78rem;
            font-weight: 700;
            line-height: 1;
            padding: .48rem .8rem;
            white-space: nowrap;
        }

        .status-active {
            background: rgba(16, 185, 129, .14);
            color: #047857;
        }

        .status-inactive {
            background: rgba(148, 163, 184, .18);
            color: #475569;
        }

        .condition-default {
            background: rgba(148, 163, 184, .18);
            color: #475569;
        }

        .condition-new {
            background: rgba(59, 130, 246, .14);
            color: #1d4ed8;
        }

        .condition-hot {
            background: rgba(239, 68, 68, .14);
            color: #b91c1c;
        }

        .stock-healthy {
            background: rgba(16, 185, 129, .14);
            color: #047857;
        }

        .stock-low {
            background: rgba(217, 119, 6, .16);
            color: #b45309;
        }

        .stock-out {
            background: rgba(239, 68, 68, .14);
            color: #b91c1c;
        }

        .featured-yes {
            background: rgba(37, 99, 235, .16);
            color: #1d4ed8;
        }

        .featured-no {
            background: rgba(148, 163, 184, .18);
            color: #475569;
        }

        .catalog-actions {
            display: flex;
            flex-wrap: wrap;
            gap: .5rem;
        }

        .catalog-action-btn {
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

        .catalog-action-btn:hover,
        .catalog-action-btn:focus {
            text-decoration: none;
            transform: translateY(-1px);
        }

        .catalog-action-edit {
            background: rgba(43, 108, 176, .14);
            color: #1d4ed8;
        }

        .catalog-action-delete {
            background: rgba(239, 68, 68, .14);
            border: 0;
            color: #b91c1c;
        }

        .catalog-empty {
            background: linear-gradient(180deg, #ffffff 0%, #f8fbff 100%);
            border: 1px dashed var(--catalog-border);
            border-radius: 1rem;
            padding: 2rem 1.5rem;
            text-align: center;
        }

        .catalog-empty-icon {
            align-items: center;
            background: rgba(43, 108, 176, .1);
            border-radius: 999px;
            color: var(--catalog-sky);
            display: inline-flex;
            font-size: 1.35rem;
            height: 4rem;
            justify-content: center;
            margin-bottom: 1rem;
            width: 4rem;
        }

        .catalog-pagination {
            margin-top: 1.25rem;
        }

        .catalog-pagination .pagination {
            justify-content: flex-end;
            margin-bottom: 0;
        }

        div.dataTables_wrapper div.dataTables_filter,
        div.dataTables_wrapper div.dataTables_length,
        div.dataTables_wrapper div.dataTables_info,
        div.dataTables_wrapper div.dataTables_paginate {
            display: none;
        }

        table.dataTable {
            border-collapse: collapse !important;
            margin-top: 0 !important;
            width: 100% !important;
        }

        @media (max-width: 991.98px) {
            .catalog-hero h1 {
                font-size: 1.65rem;
            }

            .catalog-panel-meta {
                justify-content: flex-start;
            }
        }

        @media (max-width: 767.98px) {
            .catalog-focus-grid {
                grid-template-columns: 1fr;
            }

            .catalog-search {
                width: 100%;
            }

            .catalog-pagination .pagination {
                justify-content: center;
            }
        }
    </style>
@endpush

@section('main-content')
    @php
        $conditionMeta = [
            'default' => ['label' => 'Tiêu chuẩn', 'class' => 'condition-default'],
            'new' => ['label' => 'Mới', 'class' => 'condition-new'],
            'hot' => ['label' => 'Hot', 'class' => 'condition-hot'],
        ];
    @endphp

    <div class="container-fluid catalog-page">
        @include('backend.layouts.notification')

        <div class="catalog-hero mb-4">
            <div class="row align-items-stretch">
                <div class="col-xl-7 mb-4 mb-xl-0">
                    <div class="catalog-hero-copy">
                        <div class="catalog-kicker">Catalog Control Center</div>
                        <h1>Quản lý sản phẩm trực quan hơn, nhìn nhanh được tồn kho và tín hiệu bán hàng</h1>
                        <p>Trang này gom các chỉ số quan trọng của catalogue như số sản phẩm đang bán, hàng nổi bật, tồn kho thấp và cấu trúc danh mục để admin xử lý nhanh mà không phải mở từng sản phẩm.</p>
                        <div class="catalog-hero-actions">
                            <a href="{{ route('product.create') }}" class="btn btn-light btn-sm">
                                <i class="fas fa-plus mr-1"></i> Thêm sản phẩm
                            </a>
                            <button type="button" id="productQuickRefresh" class="btn btn-outline-light btn-sm">
                                <i class="fas fa-sync-alt mr-1"></i> Làm mới danh sách
                            </button>
                        </div>
                    </div>
                </div>

                <div class="col-xl-5">
                    <div class="catalog-hero-focus">
                        <div class="catalog-focus-label">Sản phẩm mới nhất</div>
                        @if ($latestProduct)
                            <div class="catalog-focus-title">{{ $latestProduct->title }}</div>
                            <div class="catalog-focus-subtitle">
                                {{ optional($latestProduct->cat_info)->title ?: 'Chưa phân loại' }}
                                @if(optional($latestProduct->brand)->title)
                                    <span class="mx-2">•</span>{{ optional($latestProduct->brand)->title }}
                                @endif
                            </div>
                        @else
                            <div class="catalog-focus-title">Chưa có sản phẩm</div>
                            <div class="catalog-focus-subtitle">Sản phẩm mới nhất sẽ xuất hiện ở đây khi catalogue có dữ liệu.</div>
                        @endif

                        <div class="catalog-focus-grid">
                            <div class="catalog-focus-item">
                                <span>Tổng tồn kho</span>
                                <strong>{{ number_format($totalStock, 0, ',', '.') }}</strong>
                            </div>
                            <div class="catalog-focus-item">
                                <span>Featured</span>
                                <strong>{{ number_format($featuredProducts, 0, ',', '.') }}</strong>
                            </div>
                            <div class="catalog-focus-item">
                                <span>Sắp hết hàng</span>
                                <strong>{{ number_format($lowStockProducts, 0, ',', '.') }}</strong>
                            </div>
                            <div class="catalog-focus-item">
                                <span>Giá trung bình</span>
                                <strong>{{ number_format($averagePrice, 0, ',', '.') }}đ</strong>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card catalog-card catalog-card-primary">
                    <div class="card-body">
                        <div class="catalog-card-label">Tổng sản phẩm</div>
                        <div class="catalog-card-value">{{ number_format($totalProducts, 0, ',', '.') }}</div>
                        <div class="catalog-card-text">Tổng số sản phẩm đang có trong catalogue, gồm cả mặt hàng đang bán và tạm ẩn.</div>
                        <span class="catalog-chip">{{ number_format($inactiveProducts, 0, ',', '.') }} sản phẩm tạm ẩn</span>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card catalog-card catalog-card-success">
                    <div class="card-body">
                        <div class="catalog-card-label">Đang hoạt động</div>
                        <div class="catalog-card-value">{{ number_format($activeProducts, 0, ',', '.') }}</div>
                        <div class="catalog-card-text">Những sản phẩm đang được phép hiển thị và sẵn sàng bán trên storefront.</div>
                        <span class="catalog-chip">{{ number_format($featuredProducts, 0, ',', '.') }} sản phẩm nổi bật</span>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card catalog-card catalog-card-warning">
                    <div class="card-body">
                        <div class="catalog-card-label">Cảnh báo tồn kho</div>
                        <div class="catalog-card-value">{{ number_format($lowStockProducts + $outOfStockProducts, 0, ',', '.') }}</div>
                        <div class="catalog-card-text">Bao gồm sản phẩm sắp hết và sản phẩm đã hết hàng để admin ưu tiên bổ sung tồn.</div>
                        <span class="catalog-chip">{{ number_format($outOfStockProducts, 0, ',', '.') }} hết hàng hoàn toàn</span>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card catalog-card catalog-card-dark">
                    <div class="card-body">
                        <div class="catalog-card-label">Phân loại bán hàng</div>
                        <div class="catalog-card-value">{{ number_format((int) $conditionSummary->get('hot', 0), 0, ',', '.') }}</div>
                        <div class="catalog-card-text">Số sản phẩm đang được gắn condition “hot”, hữu ích để theo dõi nhóm cần ưu tiên trình bày.</div>
                        <span class="catalog-chip">{{ number_format((int) $conditionSummary->get('new', 0), 0, ',', '.') }} sản phẩm mới</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="card catalog-panel">
            <div class="card-header">
                <div class="row align-items-lg-center">
                    <div class="col-lg-8">
                        <div class="catalog-panel-title">Danh sách sản phẩm</div>
                        <p class="catalog-panel-subtitle">Tìm nhanh theo tên, danh mục hoặc thương hiệu; đồng thời lọc theo tình trạng hoạt động và sức khỏe tồn kho ngay trên màn hình.</p>
                    </div>
                    <div class="col-lg-4">
                        <div class="catalog-panel-meta">
                            <span class="catalog-panel-badge">
                                <i class="fas fa-layer-group"></i>
                                Hiển thị {{ number_format($products->count(), 0, ',', '.') }} / {{ number_format($totalProducts, 0, ',', '.') }} sản phẩm
                            </span>
                            <span class="catalog-panel-badge">
                                <i class="fas fa-warehouse"></i>
                                {{ number_format($totalStock, 0, ',', '.') }} đơn vị tồn
                            </span>
                        </div>
                    </div>
                </div>

                <div class="catalog-toolbar">
                    <div class="catalog-filter-group" role="group" aria-label="Lọc sản phẩm">
                        <button type="button" class="catalog-filter-chip active" data-filter="all">
                            Tất cả
                            <span>{{ number_format($totalProducts, 0, ',', '.') }}</span>
                        </button>
                        <button type="button" class="catalog-filter-chip" data-filter="active">
                            Active
                            <span>{{ number_format($activeProducts, 0, ',', '.') }}</span>
                        </button>
                        <button type="button" class="catalog-filter-chip" data-filter="inactive">
                            Inactive
                            <span>{{ number_format($inactiveProducts, 0, ',', '.') }}</span>
                        </button>
                        <button type="button" class="catalog-filter-chip" data-filter="featured">
                            Featured
                            <span>{{ number_format($featuredProducts, 0, ',', '.') }}</span>
                        </button>
                        <button type="button" class="catalog-filter-chip" data-filter="low">
                            Sắp hết
                            <span>{{ number_format($lowStockProducts, 0, ',', '.') }}</span>
                        </button>
                        <button type="button" class="catalog-filter-chip" data-filter="out">
                            Hết hàng
                            <span>{{ number_format($outOfStockProducts, 0, ',', '.') }}</span>
                        </button>
                    </div>

                    <div class="catalog-search">
                        <i class="fas fa-search"></i>
                        <input
                            type="text"
                            id="productSearchInput"
                            class="form-control"
                            placeholder="Tìm theo tên sản phẩm, danh mục, thương hiệu..."
                        >
                    </div>
                </div>
            </div>

            <div class="card-body">
                @if ($products->count())
                    <div class="table-responsive">
                        <table class="table catalog-table" id="product-dataTable" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>Sản phẩm</th>
                                    <th>Danh mục</th>
                                    <th>Giá bán</th>
                                    <th>Tồn kho</th>
                                    <th>Tín hiệu</th>
                                    <th>Trạng thái</th>
                                    <th>Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($products as $product)
                                    @php
                                        $photos = $product->photo ? explode(',', $product->photo) : [];
                                        $thumbnail = !empty($photos[0]) ? trim($photos[0]) : asset('backend/img/thumbnail-default.jpg');
                                        $condition = $conditionMeta[$product->condition] ?? ['label' => ucfirst($product->condition), 'class' => 'condition-default'];

                                        if ($product->stock <= 0) {
                                            $stockClass = 'stock-out';
                                            $stockLabel = 'Hết hàng';
                                            $stockHealth = 'out';
                                        } elseif ($product->stock <= 5) {
                                            $stockClass = 'stock-low';
                                            $stockLabel = 'Sắp hết';
                                            $stockHealth = 'low';
                                        } else {
                                            $stockClass = 'stock-healthy';
                                            $stockLabel = 'Ổn định';
                                            $stockHealth = 'healthy';
                                        }
                                    @endphp
                                    <tr
                                        data-product-status="{{ $product->status }}"
                                        data-product-featured="{{ (int) $product->is_featured }}"
                                        data-product-stock="{{ $stockHealth }}"
                                    >
                                        <td>
                                            <div class="catalog-product-cell">
                                                <img src="{{ $thumbnail }}" class="catalog-product-thumb" alt="{{ $product->title }}">
                                                <div>
                                                    <div class="catalog-product-title">{{ $product->title }}</div>
                                                    <div class="catalog-product-subtext">#{{ $product->id }} • {{ \Illuminate\Support\Str::limit(strip_tags($product->summary), 90) }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="catalog-product-title">{{ optional($product->cat_info)->title ?? 'Chưa có danh mục' }}</div>
                                            <div class="catalog-product-subtext">
                                                @if(optional($product->sub_cat_info)->title)
                                                    {{ optional($product->sub_cat_info)->title }}
                                                @else
                                                    Không có danh mục con
                                                @endif
                                            </div>
                                            <div class="catalog-product-subtext">{{ optional($product->brand)->title ?? 'Chưa gán thương hiệu' }}</div>
                                        </td>
                                        <td>
                                            <div class="catalog-price">{{ number_format($product->price, 0, ',', '.') }}đ</div>
                                            <div class="catalog-price-muted">
                                                @if($product->discount)
                                                    Giảm giá {{ number_format($product->discount, 0, ',', '.') }}%
                                                @else
                                                    Chưa có giảm giá
                                                @endif
                                            </div>
                                        </td>
                                        <td>
                                            <div class="catalog-price">{{ number_format($product->stock, 0, ',', '.') }}</div>
                                            <div class="catalog-pill-row mt-2">
                                                <span class="catalog-pill {{ $stockClass }}">{{ $stockLabel }}</span>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="catalog-pill-row">
                                                <span class="catalog-pill {{ $condition['class'] }}">{{ $condition['label'] }}</span>
                                                <span class="catalog-pill {{ $product->is_featured ? 'featured-yes' : 'featured-no' }}">
                                                    {{ $product->is_featured ? 'Featured' : 'Thường' }}
                                                </span>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="catalog-pill {{ $product->status === 'active' ? 'status-active' : 'status-inactive' }}">
                                                {{ $product->status === 'active' ? 'Đang hoạt động' : 'Tạm ẩn' }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="catalog-actions">
                                                <a href="{{ route('product.edit', $product->id) }}" class="catalog-action-btn catalog-action-edit" data-toggle="tooltip" title="Chỉnh sửa sản phẩm">
                                                    <i class="fas fa-pen"></i>
                                                    <span>Sửa</span>
                                                </a>
                                                <form method="POST" action="{{ route('product.destroy', [$product->id]) }}">
                                                    @csrf
                                                    @method('delete')
                                                    <button class="catalog-action-btn catalog-action-delete dltBtn" data-id="{{ $product->id }}" data-toggle="tooltip" title="Xóa sản phẩm">
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

                    <div class="catalog-pagination">
                        {{ $products->links() }}
                    </div>
                @else
                    <div class="catalog-empty">
                        <div class="catalog-empty-icon">
                            <i class="fas fa-headphones"></i>
                        </div>
                        <h3>Chưa có sản phẩm nào</h3>
                        <p class="text-muted mb-4">Hãy thêm sản phẩm đầu tiên để bắt đầu quản lý catalogue và tồn kho.</p>
                        <a href="{{ route('product.create') }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-plus mr-1"></i> Thêm sản phẩm
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
            const $table = $('#product-dataTable');
            const productFilter = { value: 'all' };

            $.fn.dataTable.ext.search.push(function (settings, data, dataIndex) {
                if (settings.nTable.id !== 'product-dataTable') {
                    return true;
                }

                if (productFilter.value === 'all') {
                    return true;
                }

                const rowNode = settings.aoData[dataIndex] && settings.aoData[dataIndex].nTr;
                if (!rowNode) {
                    return true;
                }

                const status = rowNode.getAttribute('data-product-status');
                const featured = rowNode.getAttribute('data-product-featured');
                const stock = rowNode.getAttribute('data-product-stock');

                if (productFilter.value === 'featured') {
                    return featured === '1';
                }

                if (productFilter.value === 'low' || productFilter.value === 'out') {
                    return stock === productFilter.value;
                }

                return status === productFilter.value;
            });

            if ($table.length) {
                const productTable = $table.DataTable({
                    autoWidth: false,
                    info: false,
                    language: {
                        emptyTable: 'Chưa có sản phẩm nào.',
                        zeroRecords: 'Không tìm thấy sản phẩm phù hợp trên trang này.'
                    },
                    lengthChange: false,
                    order: [],
                    paging: false,
                    searching: true,
                    columnDefs: [
                        {
                            orderable: false,
                            targets: [6]
                        }
                    ]
                });

                $('#productSearchInput').on('keyup', function () {
                    productTable.search(this.value).draw();
                });

                $('.catalog-filter-chip').on('click', function () {
                    const $chip = $(this);
                    productFilter.value = $chip.data('filter');

                    $('.catalog-filter-chip').removeClass('active');
                    $chip.addClass('active');
                    productTable.draw();
                });
            }

            $('#productQuickRefresh').on('click', function () {
                window.location.reload();
            });

            $('[data-toggle="tooltip"]').tooltip();

            $('.dltBtn').click(function(e) {
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
