@extends('frontend.layouts.master')

@section('title','Web bán tai nghe || Giới thiệu')

@section('meta')
    <meta name="description" content="{{ optional($settings)->short_des ?: 'Trang giới thiệu về cửa hàng tai nghe chính hãng, tư vấn chọn mua theo đúng nhu cầu sử dụng.' }}">
@endsection

@section('main-content')
    @php
        $siteName = 'Audio Hub';
        $shortDescription = trim(optional($settings)->short_des ?: 'Chuyên tai nghe chính hãng, dễ chọn, dễ so sánh và dễ mua đúng nhu cầu.');
        $fullDescription = trim(strip_tags(optional($settings)->description ?: 'Chúng tôi tập trung vào trải nghiệm nghe nhạc, làm việc và giải trí với danh mục tai nghe được chọn lọc kỹ, nguồn gốc rõ ràng và dịch vụ hậu mãi minh bạch.'));
        $productCount = (int) ($aboutStats['product_count'] ?? 0);
        $brandCount = (int) ($aboutStats['brand_count'] ?? 0);
        $reviewCount = (int) ($aboutStats['review_count'] ?? 0);
        $stockCount = (int) ($aboutStats['stock_count'] ?? 0);
        $averageRating = (float) ($aboutStats['average_rating'] ?? 0);
        $contactPhone = trim(optional($settings)->phone ?: 'Đang cập nhật');
        $contactEmail = trim(optional($settings)->email ?: 'support@taingheshop.vn');
        $contactAddress = trim(optional($settings)->address ?: 'Hỗ trợ online toàn quốc');
    @endphp

    <div class="breadcrumbs">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="bread-inner">
                        <ul class="bread-list">
                            <li><a href="{{ route('home') }}">Trang chủ<i class="ti-arrow-right"></i></a></li>
                            <li class="active"><a href="javascript:void(0);">Giới thiệu</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="about-pro">
        <section class="about-pro-hero">
            <div class="container">
                <div class="about-pro-panel">
                    <div class="row align-items-center">
                        <div class="col-lg-7 col-12">
                            <span class="about-pro-kicker">Về chúng tôi</span>
                            <h1>{{ $siteName }} giúp bạn chọn đúng tai nghe cho đúng cách sử dụng.</h1>
                            <p class="about-pro-lead">
                                {{ $shortDescription }}
                                <span>{{ \Illuminate\Support\Str::limit($fullDescription, 170) }}</span>
                            </p>
                            <div class="about-pro-actions">
                                <a href="{{ route('product-grids') }}" class="btn">Khám phá sản phẩm</a>
                                <a href="{{ route('contact') }}" class="btn about-pro-btn-light">Nhận tư vấn nhanh</a>
                            </div>
                            <div class="about-pro-list">
                                <div><i class="ti-check-box"></i><span>Danh mục tai nghe chính hãng, ưu tiên mẫu đáng mua và dễ so sánh.</span></div>
                                <div><i class="ti-headphone-alt"></i><span>Gợi ý theo nhu cầu nghe nhạc, làm việc, gaming và di chuyển.</span></div>
                                <div><i class="ti-shield"></i><span>Thông tin giá, bảo hành và hỗ trợ sau mua được trình bày rõ ràng.</span></div>
                            </div>
                        </div>
                        <div class="col-lg-5 col-12">
                            <div class="about-pro-side">
                                <div class="about-pro-card about-pro-card-dark">
                                    <span class="about-pro-label">Điểm mạnh thương hiệu</span>
                                    <h3>Mua tai nghe theo nhu cầu, không phải theo cảm tính.</h3>
                                    <p>Chúng tôi ưu tiên trải nghiệm chọn mua đơn giản: mô tả dễ hiểu, phân loại rõ và tư vấn ngắn gọn để bạn ra quyết định nhanh hơn.</p>
                                </div>
                                <div class="about-pro-mini-grid">
                                    <div class="about-pro-card">
                                        <strong>{{ $averageRating > 0 ? number_format($averageRating, 1) : '5.0' }}/5</strong>
                                        <span>Điểm hài lòng từ đánh giá thực tế</span>
                                    </div>
                                    <div class="about-pro-card">
                                        <strong>24h</strong>
                                        <span>Hỗ trợ tư vấn và phản hồi nhanh</span>
                                    </div>
                                </div>
                                <div class="about-pro-contact">
                                    <span class="about-pro-label">Liên hệ trực tiếp</span>
                                    <a href="tel:{{ preg_replace('/\s+/', '', $contactPhone) }}">{{ $contactPhone }}</a>
                                    <p>{{ $contactEmail }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="about-pro-stats">
            <div class="container">
                <div class="row">
                    <div class="col-lg-3 col-sm-6 col-12"><div class="about-pro-stat"><strong>{{ number_format($productCount, 0, ',', '.') }}</strong><span>Mẫu tai nghe đang kinh doanh</span></div></div>
                    <div class="col-lg-3 col-sm-6 col-12"><div class="about-pro-stat"><strong>{{ number_format($brandCount, 0, ',', '.') }}</strong><span>Thương hiệu đang có mặt</span></div></div>
                    <div class="col-lg-3 col-sm-6 col-12"><div class="about-pro-stat"><strong>{{ number_format($reviewCount, 0, ',', '.') }}</strong><span>Lượt đánh giá từ khách hàng</span></div></div>
                    <div class="col-lg-3 col-sm-6 col-12"><div class="about-pro-stat"><strong>{{ number_format($stockCount, 0, ',', '.') }}</strong><span>Sản phẩm sẵn kho để giao nhanh</span></div></div>
                </div>
            </div>
        </section>

        <section class="about-pro-story section">
            <div class="container">
                <div class="row">
                    <div class="col-lg-5 col-12">
                        <div class="about-pro-copy">
                            <span class="about-pro-kicker about-pro-kicker-dark">Cách chúng tôi vận hành</span>
                            <h2>Một cửa hàng âm thanh hiện đại nên giúp khách chọn nhanh và mua yên tâm.</h2>
                            <p>{{ $fullDescription }}</p>
                            <p>Thay vì đưa ra quá nhiều lựa chọn gây rối, {{ $siteName }} tập trung vào các nhóm nhu cầu phổ biến, mức giá rõ ràng và phần mô tả đủ thực tế để bạn biết mẫu nào phù hợp với mình.</p>
                            <div class="about-pro-contact-line">
                                <i class="ti-location-pin"></i>
                                <span>{{ $contactAddress }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-7 col-12">
                        <div class="row">
                            <div class="col-md-6 col-12"><div class="about-pro-feature"><i class="ti-target"></i><h3>Tư vấn đúng bài toán</h3><p>Ưu tiên nhu cầu sử dụng, môi trường nghe và ngân sách trước khi gợi ý mẫu phù hợp.</p></div></div>
                            <div class="col-md-6 col-12"><div class="about-pro-feature"><i class="ti-package"></i><h3>Thông tin dễ hiểu</h3><p>Mô tả gọn, hiển thị giá minh bạch và nêu rõ các điểm mạnh thực tế của sản phẩm.</p></div></div>
                            <div class="col-md-6 col-12"><div class="about-pro-feature"><i class="ti-comments-smiley"></i><h3>Đồng hành sau mua</h3><p>Hỗ trợ trong suốt quá trình sử dụng, từ setup ban đầu đến các vấn đề bảo hành.</p></div></div>
                            <div class="col-md-6 col-12"><div class="about-pro-feature"><i class="ti-medall-alt"></i><h3>Danh mục có chọn lọc</h3><p>Tập trung vào những mẫu đáng mua thay vì làm khách hàng bị choáng bởi quá nhiều lựa chọn.</p></div></div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        @if($featuredProducts->count())
            <section class="about-pro-products section">
                <div class="container">
                    <div class="about-pro-heading">
                        <span class="about-pro-kicker about-pro-kicker-dark">Gợi ý nổi bật</span>
                        <h2>Một vài lựa chọn đang được quan tâm</h2>
                        <p>Những sản phẩm mới hoặc nổi bật giúp khách hàng hình dung rõ hơn về danh mục mà cửa hàng đang cung cấp.</p>
                    </div>
                    <div class="row">
                        @foreach($featuredProducts as $product)
                            @php
                                $photos = array_filter(array_map('trim', explode(',', $product->photo)));
                                $productPhoto = $photos ? reset($photos) : asset('backend/img/thumbnail-default.jpg');
                                $finalPrice = $product->discount ? $product->price - (($product->price * $product->discount) / 100) : $product->price;
                            @endphp
                            <div class="col-lg-4 col-md-6 col-12">
                                <div class="about-pro-product">
                                    <a href="{{ route('product-detail', $product->slug) }}" class="about-pro-product-image"><img src="{{ $productPhoto }}" alt="{{ $product->title }}"></a>
                                    <div class="about-pro-product-body">
                                        <span class="about-pro-label">{{ optional($product->brand)->title ?: 'Tai nghe chính hãng' }}</span>
                                        <h3><a href="{{ route('product-detail', $product->slug) }}">{{ $product->title }}</a></h3>
                                        <p>{{ \Illuminate\Support\Str::limit(trim(strip_tags($product->summary)), 105) }}</p>
                                        <div class="about-pro-product-footer">
                                            <strong>{{ number_format($finalPrice, 0, ',', '.') }} đ</strong>
                                            <a href="{{ route('product-detail', $product->slug) }}">Xem chi tiết</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </section>
        @endif

        <section class="about-pro-cta">
            <div class="container">
                <div class="about-pro-cta-panel">
                    <div class="row align-items-center">
                        <div class="col-lg-8 col-12">
                            <span class="about-pro-kicker">Sẵn sàng bắt đầu?</span>
                            <h2>Để {{ $siteName }} gợi ý mẫu tai nghe phù hợp với nhu cầu của bạn.</h2>
                            <p>Khám phá danh mục sản phẩm hoặc liên hệ ngay để được tư vấn nhanh theo ngân sách và mục đích sử dụng.</p>
                        </div>
                        <div class="col-lg-4 col-12">
                            <div class="about-pro-actions about-pro-actions-right">
                                <a href="{{ route('product-grids') }}" class="btn">Xem sản phẩm</a>
                                <a href="{{ route('contact') }}" class="btn about-pro-btn-light">Liên hệ ngay</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    @include('frontend.layouts.newsletter')
@endsection

@push('styles')
    <style>
        .about-pro {
            --about-accent: #f7941d;
            --about-dark: #0f172a;
            --about-copy: #52606d;
            --about-border: rgba(15, 23, 42, 0.08);
            background:
                radial-gradient(circle at top left, rgba(247, 148, 29, 0.1), transparent 24%),
                linear-gradient(180deg, #fffaf3 0%, #ffffff 32%, #f8fbff 100%);
            padding-bottom: 20px;
        }

        .about-pro-hero {
            padding: 28px 0 18px;
        }

        .about-pro-panel,
        .about-pro-cta-panel {
            border-radius: 30px;
            overflow: hidden;
            position: relative;
        }

        .about-pro-panel {
            background:
                radial-gradient(circle at top right, rgba(247, 148, 29, 0.28), transparent 30%),
                linear-gradient(135deg, #0f172a 0%, #162033 52%, #20314b 100%);
            box-shadow: 0 30px 80px rgba(15, 23, 42, 0.22);
            padding: 42px;
        }

        .about-pro-panel::before,
        .about-pro-cta-panel::before {
            background: rgba(255, 255, 255, 0.06);
            border-radius: 999px;
            content: "";
            height: 220px;
            position: absolute;
            right: -80px;
            top: -100px;
            width: 220px;
        }

        .about-pro-kicker {
            color: #ffd39a;
            display: inline-block;
            font-size: 0.8rem;
            font-weight: 700;
            letter-spacing: 0.2em;
            margin-bottom: 14px;
            text-transform: uppercase;
        }

        .about-pro-kicker-dark {
            color: #9c5c11;
        }

        .about-pro h1,
        .about-pro-cta-panel h2 {
            color: #fff;
            line-height: 1.08;
        }

        .about-pro h1 {
            font-size: clamp(2.1rem, 4vw, 3.8rem);
            margin-bottom: 18px;
        }

        .about-pro-lead,
        .about-pro-cta-panel p {
            color: rgba(255, 255, 255, 0.8);
            line-height: 1.8;
        }

        .about-pro-lead span {
            color: #fff;
            display: block;
            margin-top: 10px;
        }

        .about-pro-actions {
            align-items: center;
            display: flex;
            flex-wrap: wrap;
            gap: 14px;
            margin-top: 28px;
        }

        .about-pro .btn {
            border-radius: 999px;
            box-shadow: 0 16px 32px rgba(247, 148, 29, 0.22);
            padding: 14px 24px;
        }

        .about-pro-btn-light {
            background: rgba(255, 255, 255, 0.08);
            border: 1px solid rgba(255, 255, 255, 0.18);
            box-shadow: none !important;
            color: #fff;
        }

        .about-pro-list {
            display: grid;
            gap: 14px;
            margin-top: 28px;
        }

        .about-pro-list div,
        .about-pro-card,
        .about-pro-contact,
        .about-pro-stat,
        .about-pro-feature,
        .about-pro-product {
            border: 1px solid var(--about-border);
            border-radius: 22px;
            box-shadow: 0 20px 45px rgba(15, 23, 42, 0.08);
        }

        .about-pro-list div {
            align-items: center;
            background: rgba(255, 255, 255, 0.08);
            border-color: rgba(255, 255, 255, 0.1);
            display: flex;
            gap: 14px;
            padding: 16px 18px;
        }

        .about-pro-list i {
            color: #ffd39a;
        }

        .about-pro-list span {
            color: #fff;
            line-height: 1.6;
        }

        .about-pro-side {
            display: grid;
            gap: 16px;
        }

        .about-pro-card,
        .about-pro-contact,
        .about-pro-stat,
        .about-pro-feature,
        .about-pro-product {
            background: rgba(255, 255, 255, 0.96);
        }

        .about-pro-card,
        .about-pro-contact,
        .about-pro-stat,
        .about-pro-feature {
            padding: 24px;
        }

        .about-pro-card-dark {
            background:
                radial-gradient(circle at top right, rgba(247, 148, 29, 0.18), transparent 36%),
                linear-gradient(180deg, rgba(255, 255, 255, 0.99) 0%, rgba(248, 250, 252, 0.96) 100%);
        }

        .about-pro-label {
            color: #9c5c11;
            display: inline-block;
            font-size: 0.76rem;
            font-weight: 700;
            letter-spacing: 0.18em;
            margin-bottom: 12px;
            text-transform: uppercase;
        }

        .about-pro-card h3,
        .about-pro-copy h2,
        .about-pro-heading h2,
        .about-pro-product h3 a {
            color: var(--about-dark);
        }

        .about-pro-card p,
        .about-pro-card span,
        .about-pro-contact p,
        .about-pro-copy p,
        .about-pro-heading p,
        .about-pro-feature p,
        .about-pro-product p {
            color: var(--about-copy);
            line-height: 1.75;
        }

        .about-pro-mini-grid {
            display: grid;
            gap: 16px;
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }

        .about-pro-mini-grid strong,
        .about-pro-stat strong,
        .about-pro-product-footer strong {
            color: var(--about-accent);
        }

        .about-pro-mini-grid strong {
            display: block;
            font-size: 1.85rem;
            line-height: 1;
            margin-bottom: 10px;
        }

        .about-pro-contact {
            background: rgba(255, 255, 255, 0.08);
            border-color: rgba(255, 255, 255, 0.12);
        }

        .about-pro-contact a {
            color: #fff;
            display: inline-block;
            font-size: 1.3rem;
            font-weight: 700;
        }

        .about-pro-contact p {
            color: rgba(255, 255, 255, 0.78);
            margin-bottom: 0;
            margin-top: 6px;
        }

        .about-pro-stats {
            padding: 14px 0 12px;
        }

        .about-pro-stat {
            height: 100%;
        }

        .about-pro-stat strong {
            display: block;
            font-size: 2rem;
            line-height: 1;
            margin-bottom: 12px;
        }

        .about-pro-stat span {
            color: var(--about-copy);
            display: block;
            line-height: 1.7;
        }

        .about-pro-story,
        .about-pro-products {
            padding-top: 56px;
            padding-bottom: 56px;
        }

        .about-pro-copy h2,
        .about-pro-heading h2 {
            font-size: clamp(1.85rem, 3vw, 2.8rem);
            line-height: 1.15;
            margin-bottom: 14px;
        }

        .about-pro-contact-line {
            align-items: center;
            color: var(--about-dark);
            display: flex;
            gap: 10px;
            margin-top: 20px;
        }

        .about-pro-contact-line i,
        .about-pro-feature i {
            color: var(--about-accent);
        }

        .about-pro-feature {
            height: calc(100% - 24px);
            margin-bottom: 24px;
        }

        .about-pro-feature h3 {
            color: var(--about-dark);
            font-size: 1.15rem;
            margin: 16px 0 10px;
        }

        .about-pro-heading {
            margin: 0 auto 34px;
            max-width: 720px;
            text-align: center;
        }

        .about-pro-heading p {
            margin-bottom: 0;
        }

        .about-pro-product {
            height: 100%;
            overflow: hidden;
        }

        .about-pro-product-image {
            background: linear-gradient(180deg, #f8fafc 0%, #fff 100%);
            display: block;
            overflow: hidden;
        }

        .about-pro-product-image img {
            height: 260px;
            object-fit: cover;
            transition: transform 0.35s ease;
            width: 100%;
        }

        .about-pro-product:hover .about-pro-product-image img {
            transform: scale(1.04);
        }

        .about-pro-product-body {
            padding: 24px;
        }

        .about-pro-product h3 {
            font-size: 1.2rem;
            line-height: 1.45;
            margin-bottom: 10px;
        }

        .about-pro-product h3 a:hover,
        .about-pro-product-footer a:hover {
            color: var(--about-accent);
        }

        .about-pro-product-footer {
            align-items: center;
            display: flex;
            justify-content: space-between;
            gap: 16px;
        }

        .about-pro-product-footer a {
            color: var(--about-dark);
            font-weight: 600;
        }

        .about-pro-cta {
            padding: 8px 0 42px;
        }

        .about-pro-cta-panel {
            background:
                radial-gradient(circle at top right, rgba(247, 148, 29, 0.28), transparent 30%),
                linear-gradient(135deg, #111827 0%, #17263d 100%);
            box-shadow: 0 26px 64px rgba(15, 23, 42, 0.18);
            padding: 34px 38px;
        }

        .about-pro-cta-panel h2 {
            font-size: clamp(1.75rem, 3vw, 2.8rem);
            margin-bottom: 12px;
        }

        .about-pro-actions-right {
            justify-content: flex-end;
            margin-top: 0;
        }

        @media (max-width: 991.98px) {
            .about-pro-panel,
            .about-pro-cta-panel {
                border-radius: 24px;
                padding: 28px 24px;
            }

            .about-pro-side {
                margin-top: 24px;
            }

            .about-pro-actions-right {
                justify-content: flex-start;
                margin-top: 24px;
            }
        }

        @media (max-width: 767.98px) {
            .about-pro-hero {
                padding-top: 20px;
            }

            .about-pro-panel,
            .about-pro-cta-panel {
                border-radius: 20px;
                padding: 22px 18px;
            }

            .about-pro-actions {
                align-items: stretch;
                flex-direction: column;
            }

            .about-pro-actions .btn {
                text-align: center;
                width: 100%;
            }

            .about-pro-mini-grid {
                grid-template-columns: 1fr;
            }

            .about-pro-product-body,
            .about-pro-feature,
            .about-pro-stat,
            .about-pro-card,
            .about-pro-contact {
                padding-left: 18px;
                padding-right: 18px;
            }

            .about-pro-product-image img {
                height: 220px;
            }

            .about-pro-product-footer {
                align-items: flex-start;
                flex-direction: column;
            }

            .about-pro-story,
            .about-pro-products {
                padding-top: 42px;
                padding-bottom: 42px;
            }
        }
    </style>
@endpush
