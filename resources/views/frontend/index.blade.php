@extends('frontend.layouts.master')
@section('title','Web bán tai nghe || Trang chủ')
@section('main-content')
@php
    $latestHomeProducts = $latest_home_products ?? collect();
@endphp
<style>
    .home-product-card{
        margin-top: 0;
    }

    .home-product-card .product-img{
        border-radius: 28px;
        background:
            radial-gradient(circle at top, rgba(255, 255, 255, 0.98), rgba(226, 232, 240, 0.92)),
            #eef2ff;
        border: 1px solid rgba(191, 219, 254, 0.82);
        box-shadow: 0 24px 44px rgba(148, 163, 184, 0.14);
    }

    .home-product-card__media-link{
        display: block;
        padding: 18px;
    }

    .home-product-card__image-shell{
        --home-card-image-width: 220px;
        --home-card-image: none;
        position: relative;
        display: flex;
        align-items: center;
        justify-content: center;
        min-height: 250px;
        border-radius: 22px;
        overflow: hidden;
        isolation: isolate;
    }

    .home-product-card__image-shell::before{
        content: "";
        position: absolute;
        inset: 12%;
        background-image: var(--home-card-image);
        background-position: center;
        background-repeat: no-repeat;
        background-size: contain;
        filter: blur(28px) saturate(1.08);
        opacity: 0.14;
        transform: scale(1.08);
    }

    .home-product-card__image{
        position: relative;
        z-index: 1;
        display: block;
        width: auto;
        max-width: min(100%, var(--home-card-image-width));
        max-height: 230px;
        object-fit: contain;
        filter: drop-shadow(0 18px 30px rgba(15, 23, 42, 0.18));
        transition: transform 0.24s ease, filter 0.24s ease;
    }

    .home-product-card:hover .home-product-card__image{
        transform: translateY(-4px);
        filter: drop-shadow(0 24px 34px rgba(15, 23, 42, 0.22));
    }

    .home-product-card__image--enhanced{
        max-width: min(100%, var(--home-card-image-width, 170px));
        border-radius: 18px;
        box-shadow:
            0 14px 24px rgba(15, 23, 42, 0.12),
            0 0 0 1px rgba(226, 232, 240, 0.95);
    }

    .home-product-card--low-res:hover .home-product-card__image{
        transform: translateY(-1px);
    }

    .shop-home-list .single-list{
        padding: 18px;
        border-radius: 28px;
        background: rgba(255, 255, 255, 0.96);
        border: 1px solid rgba(226, 232, 240, 0.88);
        box-shadow: 0 20px 38px rgba(148, 163, 184, 0.12);
    }

    .shop-home-list .single-list .list-image{
        border-radius: 22px;
        background:
            radial-gradient(circle at top, rgba(255, 255, 255, 0.98), rgba(226, 232, 240, 0.92)),
            #eef2ff;
        overflow: hidden;
    }

    .home-latest-card__media-link{
        display: flex;
        align-items: center;
        justify-content: center;
        min-height: 180px;
        padding: 16px;
    }

    .home-latest-card__image{
        width: auto;
        max-width: 100%;
        max-height: 160px;
        object-fit: contain;
        filter: drop-shadow(0 16px 24px rgba(15, 23, 42, 0.16));
    }

    .home-latest-card__image--enhanced{
        max-width: 150px;
        border-radius: 16px;
        box-shadow:
            0 12px 20px rgba(15, 23, 42, 0.1),
            0 0 0 1px rgba(226, 232, 240, 0.95);
    }

    @media (max-width: 575.98px){
        .home-product-card__image-shell{
            min-height: 220px;
        }

        .home-product-card__image{
            max-height: 200px;
        }

        .shop-home-list .single-list{
            padding: 14px;
            border-radius: 22px;
        }
    }
</style>
<!-- Slider Area -->
@if(count($banners)>0)
    <section id="Gslider" class="carousel slide" data-ride="carousel">
        <ol class="carousel-indicators">
            @foreach($banners as $key=>$banner)
        <li data-target="#Gslider" data-slide-to="{{$key}}" class="{{(($key==0)? 'active' : '')}}"></li>
            @endforeach

        </ol>
        <div class="carousel-inner" role="listbox">
                @foreach($banners as $key=>$banner)
                <div class="carousel-item {{(($key==0)? 'active' : '')}}">
                    <img class="first-slide" src="{{$banner->photo}}" alt="{{$banner->title}}">
                    <div class="carousel-caption text-left">
                        <div class="container">
                            <div class="gslider-content">
                                <h1 class="wow fadeInDown">{{$banner->title}}</h1>
                                <div class="gslider-copy">{!! html_entity_decode($banner->description) !!}</div>
                                <div class="gslider-actions">
                                    <a class="btn btn-lg ws-btn wow fadeInUpBig" href="{{route('product-grids')}}" role="button">Tìm hiểu ngay<i class="far fa-arrow-alt-circle-right"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        <a class="carousel-control-prev" href="#Gslider" role="button" data-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="sr-only">Previous</span>
        </a>
        <a class="carousel-control-next" href="#Gslider" role="button" data-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
        <span class="sr-only">Next</span>
        </a>
    </section>
@endif

<!--/ End Slider Area -->

<section class="home-quiz-spotlight">
    <div class="container">
        <div class="home-quiz-card">
            <div class="row align-items-center">
                <div class="col-lg-8 col-12">
                    <span class="home-quiz-kicker">Quiz mới</span>
                    <h2>Chọn tai nghe trong 45 giây theo đúng nhu cầu thật.</h2>
                    <p>Trả lời 4 câu hỏi ngắn về mục đích sử dụng, dáng đeo, ưu tiên và tầm giá. Hệ thống sẽ trả ra 3 mẫu hợp nhất kèm 1 bài viết liên quan để bạn chốt nhanh hơn.</p>
                    <div class="home-quiz-actions">
                        <a href="{{route('headphone-quiz.show')}}" class="btn home-quiz-btn">Thử quiz ngay</a>
                        <a href="{{route('product-grids')}}" class="home-quiz-link">Hoặc tự lọc sản phẩm thủ công</a>
                    </div>
                </div>
                <div class="col-lg-4 col-12">
                    <div class="home-quiz-stat-grid">
                        <div class="home-quiz-stat-card">
                            <strong>45s</strong>
                            <span>Thời gian trung bình</span>
                        </div>
                        <div class="home-quiz-stat-card">
                            <strong>3 + 1</strong>
                            <span>Sản phẩm và bài blog</span>
                        </div>
                        <div class="home-quiz-stat-card">
                            <strong>4 bước</strong>
                            <span>Không cần đăng nhập</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Start Small Banner  -->
{{--<section class="small-banner section">--}}
{{--    <div class="container-fluid">--}}
{{--        <div class="row">--}}
{{--            @php--}}
{{--            $category_lists=DB::table('categories')->where('status','active')->limit(10)->get();--}}
{{--            @endphp--}}
{{--            @if($category_lists)--}}
{{--                @foreach($category_lists as $cat)--}}
{{--                    @if($cat->is_parent==1)--}}
{{--                        <!-- Single Banner  -->--}}
{{--                        <div class="col-lg-3 col-md-6 col-12">--}}
{{--                            <div class="single-banner">--}}
{{--                                @if($cat->photo)--}}
{{--                                    <img src="{{$cat->photo}}" alt="{{$cat->photo}}">--}}
{{--                                @else--}}
{{--                                    <img src="https://via.placeholder.com/600x370" alt="#">--}}
{{--                                @endif--}}
{{--                                <div class="content">--}}
{{--                                    <h3>{{$cat->title}}</h3>--}}
{{--                                        <a href="{{route('product-cat',$cat->slug)}}">Khám phá ngay</a>--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                    @endif--}}
{{--                    <!-- /End Single Banner  -->--}}
{{--                @endforeach--}}
{{--            @endif--}}
{{--        </div>--}}
{{--    </div>--}}
{{--</section>--}}
<!-- End Small Banner -->

<!-- Start Product Area -->
<div class="product-area section">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="section-title">
                        <h2>Mẫu Thịnh Hành</h2>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="product-info">
                        <div class="nav-main mb-3">
                            <!-- Tab Nav -->
                            <ul class="nav nav-tabs filter-tope-group" id="myTab" role="tablist">
                                @php
                                    $categories=DB::table('categories')->where('status','active')->where('is_parent',1)->get();
                                    // dd($categories);
                                @endphp
                                @if($categories)
                                <button class="btn" style="background:black"data-filter="*">
                                    Tất Cả
                                </button>
                                    @foreach($categories as $key=>$cat)

                                    <button class="btn" style="background:none;color:black;"data-filter=".{{$cat->id}}">
                                        {{$cat->title}}
                                    </button>
                                    @endforeach
                                @endif
                            </ul>
                            <!--/ End Tab Nav -->
                        </div>
                        <div class="tab-content isotope-grid" id="myTabContent">
                             <!-- Start Single Tab -->
                            @if($product_lists)
                                @foreach($product_lists as $key=>$product)
                                <div class="col-sm-6 col-md-4 col-lg-3 p-b-35 isotope-item {{$product->cat_id}} mb-5">
                                    @php
                                        $photo=explode(',',$product->photo);
                                        $listingMedia = $product->listing_media ?? [
                                            'display_url' => $photo[0] ?? '',
                                            'display_width' => null,
                                            'is_low_resolution' => false,
                                        ];
                                        $imageShellStyle = "--home-card-image:url('" . ($listingMedia['display_url'] ?? '') . "');";
                                        if(!empty($listingMedia['display_width'])){
                                            $imageShellStyle .= '--home-card-image-width:' . $listingMedia['display_width'] . 'px;';
                                        }
                                    @endphp
                                    <div class="single-product home-product-card{{ !empty($listingMedia['is_low_resolution']) ? ' home-product-card--low-res' : '' }}">
                                        <div class="product-img">
                                            <a href="{{route('product-detail',$product->slug)}}" class="home-product-card__media-link">
                                                <div class="home-product-card__image-shell" style="{{ $imageShellStyle }}">
                                                    <img class="home-product-card__image{{ !empty($listingMedia['is_low_resolution']) ? ' home-product-card__image--enhanced' : '' }}" src="{{$listingMedia['display_url']}}" alt="{{$product->title}}">
                                                </div>
                                                @if($product->stock<=0)
                                                    <span class="out-of-stock">Sold out</span>
                                                @elseif($product->condition=='new')
                                                    <span class="new">New</span
                                                @elseif($product->condition=='hot')
                                                    <span class="hot">Hot</span>
                                                @else
                                                    <span class="price-dec">{{$product->discount}}% Off</span>
                                                @endif


                                            </a>
                                            <div class="button-head">
                                                <div class="product-action">
                                                    <a data-toggle="modal" data-target="#{{$product->id}}" title="Quick View" href="#"><i class=" ti-eye"></i><span>Xem qua sản phẩm</span></a>
                                                    <form action="{{route('add-to-wishlist',$product->slug)}}" method="POST" class="action-inline-form">
                                                        @csrf
                                                        <button type="submit" title="Wishlist" class="action-button-reset"><i class=" ti-heart "></i><span>Th&#234;m v&#224;o danh s&#225;ch y&#234;u th&#237;ch</span></button>
                                                    </form>
                                                </div>
                                                <div class="product-action-2">
                                                    <form action="{{route('add-to-cart',$product->slug)}}" method="POST" class="action-inline-form">
                                                        @csrf
                                                        <button type="submit" title="Add to cart" class="action-button-reset">Th&#234;m v&#224;o gi&#7887; h&#224;ng</button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="product-content">
                                            <h3><a href="{{route('product-detail',$product->slug)}}">{{$product->title}}</a></h3>
                                            <div class="product-price">
                                                @php
                                                    $after_discount=($product->price-($product->price*$product->discount)/100);
                                                @endphp
                                                <span>{{number_format($after_discount,0)}} đ</span>
                                                <del style="padding-left:4%;">{{number_format($product->price,0)}} đ</del>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endforeach

                             <!--/ End Single Tab -->
                            @endif

                        <!--/ End Single Tab -->

                        </div>
                    </div>
                </div>
            </div>
        </div>
</div>
<!-- End Product Area -->
{{-- @php
    $featured=DB::table('products')->where('is_featured',1)->where('status','active')->orderBy('id','DESC')->limit(1)->get();
@endphp --}}
<!-- Start Midium Banner  -->
{{--<section class="midium-banner">--}}
{{--    <div class="container">--}}
{{--        <div class="row">--}}
{{--            @if($featured)--}}
{{--                @foreach($featured as $data)--}}
{{--                    <!-- Single Banner  -->--}}
{{--                    <div class="col-lg-6 col-md-6 col-12">--}}
{{--                        <div class="single-banner">--}}
{{--                            @php--}}
{{--                                $photo=explode(',',$data->photo);--}}
{{--                            @endphp--}}
{{--                            <img src="{{$photo[0]}}" alt="{{$photo[0]}}">--}}
{{--                            <div class="content">--}}
{{--                                <p>{{$data->cat_info['title']}}</p>--}}
{{--                                <h3>{{$data->title}} <br>Giảm tới<span> {{$data->discount}}%</span></h3>--}}
{{--                                <a href="{{route('product-detail',$data->slug)}}">Mua Ngay</a>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                    <!-- /End Single Banner  -->--}}
{{--                @endforeach--}}
{{--            @endif--}}
{{--        </div>--}}
{{--    </div>--}}
{{--</section>--}}
<!-- End Midium Banner -->

<!-- Start Most Popular -->
<div class="product-area most-popular section" style="padding-top: 0px">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="section-title" style="margin-bottom: 0px">
                    <h2>Mẫu Bán Chạy</h2>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="owl-carousel popular-slider">
                    @foreach($product_lists as $product)
                        @if($product->condition=='hot')
                            <!-- Start Single Product -->
                        @php
                            $photo=explode(',',$product->photo);
                            $listingMedia = $product->listing_media ?? [
                                'display_url' => $photo[0] ?? '',
                                'display_width' => null,
                                'is_low_resolution' => false,
                            ];
                            $imageShellStyle = "--home-card-image:url('" . ($listingMedia['display_url'] ?? '') . "');";
                            if(!empty($listingMedia['display_width'])){
                                $imageShellStyle .= '--home-card-image-width:' . $listingMedia['display_width'] . 'px;';
                            }
                        @endphp
                        <div class="single-product home-product-card{{ !empty($listingMedia['is_low_resolution']) ? ' home-product-card--low-res' : '' }}">
                            <div class="product-img">
                                <a href="{{route('product-detail',$product->slug)}}" class="home-product-card__media-link">
                                    <div class="home-product-card__image-shell" style="{{ $imageShellStyle }}">
                                        <img class="home-product-card__image{{ !empty($listingMedia['is_low_resolution']) ? ' home-product-card__image--enhanced' : '' }}" src="{{$listingMedia['display_url']}}" alt="{{$product->title}}">
                                    </div>
                                    {{-- <span class="out-of-stock">Hot</span> --}}
                                </a>
                                <div class="button-head">
                                    <div class="product-action">
                                        <a data-toggle="modal" data-target="#{{$product->id}}" title="Quick View" href="#"><i class=" ti-eye"></i><span>Xem qua sản phẩm</span></a>
                                        <form action="{{route('add-to-wishlist',$product->slug)}}" method="POST" class="action-inline-form">
                                            @csrf
                                            <button type="submit" title="Wishlist" class="action-button-reset"><i class=" ti-heart "></i><span>Th&#234;m v&#224;o danh s&#225;ch y&#234;u th&#237;ch</span></button>
                                        </form>
                                    </div>
                                    <div class="product-action-2">
                                        <form action="{{route('add-to-cart',$product->slug)}}" method="POST" class="action-inline-form">
                                            @csrf
                                            <button type="submit" class="action-button-reset">Th&#234;m v&#224;o gi&#7887; h&#224;ng</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <div class="product-content">
                                <h3><a href="{{route('product-detail',$product->slug)}}">{{$product->title}}</a></h3>
                                <div class="product-price">
                                    <span class="old">{{number_format($product->price,0)}} đ</span>
                                    @php
                                    $after_discount=($product->price-($product->price*$product->discount)/100)
                                    @endphp
                                    <span>{{number_format($after_discount,0)}} đ</span>
                                </div>
                            </div>
                        </div>
                        <!-- End Single Product -->
                        @endif
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
<!-- End Most Popular Area -->

<!-- Start Shop Home List  -->
<section class="shop-home-list section">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-12">
                <div class="row">
                    <div class="col-12">
                        <div class="section-title" style="margin-bottom: 0px">
                            <h2>Mẫu Mới Nhất</h2>
                        </div>
                    </div>
                </div>
                <div class="row">
                    @foreach($latestHomeProducts as $product)
                        <div class="col-md-4">
                            <!-- Start Single List  -->
                            @php
                                $photo=explode(',',$product->photo);
                                $listingMedia = $product->listing_media ?? [
                                    'display_url' => $photo[0] ?? '',
                                    'display_width' => null,
                                    'is_low_resolution' => false,
                                ];
                            @endphp
                            <div class="single-list home-latest-card">
                                <div class="row">
                                <div class="col-lg-6 col-md-6 col-12">
                                    <div class="list-image overlay">
                                        <a href="{{route('product-detail',$product->slug)}}" class="home-latest-card__media-link">
                                            <img class="home-latest-card__image{{ !empty($listingMedia['is_low_resolution']) ? ' home-latest-card__image--enhanced' : '' }}" src="{{$listingMedia['display_url']}}" alt="{{$product->title}}">
                                        </a>
                                        <form action="{{route('add-to-cart',$product->slug)}}" method="POST" class="action-inline-form">
                                            @csrf
                                            <button type="submit" class="action-button-reset buy" title="Add to cart"><i class="fa fa-shopping-bag"></i></button>
                                        </form>
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-6 col-12 no-padding">
                                    <div class="content">
                                        <h4 class="title"><a href="{{route('product-detail',$product->slug)}}">{{$product->title}}</a></h4>
                                        <p class="price with-discount"> Giảm {{number_format($product->discount,0)}} %</p>
                                    </div>
                                </div>
                                </div>
                            </div>
                            <!-- End Single List  -->
                        </div>
                    @endforeach

                </div>
            </div>
        </div>
    </div>
</section>
<!-- End Shop Home List  -->

<!-- Start Shop Blog  -->
<section class="shop-blog section" style="padding-top: 0px; padding-bottom: 50px">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="section-title">
                    <h2>Bài Viết</h2>
                </div>
            </div>
        </div>
        <div class="row">
            @if($posts)
                @foreach($posts as $post)
                    <div class="col-lg-4 col-md-6 col-12">
                        <!-- Start Single Blog  -->
                        <div class="shop-single-blog">
                            <img src="{{$post->photo}}" alt="{{$post->photo}}">
                            <div class="content">
                                <p class="date">{{$post->created_at->format('d M , Y. D')}}</p>
                                <a href="{{route('blog.detail',$post->slug)}}" class="title">{{$post->title}}</a>
                                <a href="{{route('blog.detail',$post->slug)}}" class="more-btn" style="text-decoration: underline">Xem Thêm</a>
                            </div>
                        </div>
                        <!-- End Single Blog  -->
                    </div>
                @endforeach
            @endif

        </div>
    </div>
</section>
<!-- End Shop Blog  -->

<!-- Start Shop Services Area -->
<section class="shop-services section home" style="padding-top: 50px;padding-bottom: 50px; background-color: #eaeaea">
    <div class="container">
        <div class="row">
            <div class="col-lg-3 col-md-6 col-12">
                <!-- Start Single Service -->
                <div class="single-service">
                    <i class="ti-rocket"></i>
                    <h4>Miễn Phí Giao Hàng</h4>
                    <p>Cho đơn hàng trên 1.000.000 đ</p>
                </div>
                <!-- End Single Service -->
            </div>
            <div class="col-lg-3 col-md-6 col-12">
                <!-- Start Single Service -->
                <div class="single-service">
                    <i class="ti-reload"></i>
                    <h4>Miễn Phí Hoàn Trả</h4>
                    <p>Trong vòng 30 ngày</p>
                </div>
                <!-- End Single Service -->
            </div>
            <div class="col-lg-3 col-md-6 col-12">
                <!-- Start Single Service -->
                <div class="single-service">
                    <i class="ti-lock"></i>
                    <h4>Bảo Mật Thanh Toán</h4>
                    <p>100% Bảo Mật Thanh Toán</p>
                </div>
                <!-- End Single Service -->
            </div>
            <div class="col-lg-3 col-md-6 col-12">
                <!-- Start Single Service -->
                <div class="single-service">
                    <i class="ti-tag"></i>
                    <h4>Giá Tốt Nhất</h4>
                    <p>Đảm Bảo Giá Tốt Nhất</p>
                </div>
                <!-- End Single Service -->
            </div>
        </div>
    </div>
</section>
<!-- End Shop Services Area -->

@include('frontend.layouts.newsletter')

<!-- Modal -->
@if($product_lists)
    @foreach($product_lists as $key=>$product)
        <div class="modal fade" id="{{$product->id}}" tabindex="-1" role="dialog">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span class="ti-close" aria-hidden="true"></span></button>
                        </div>
                        <div class="modal-body">
                            <div class="row no-gutters">
                                <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
                                    <!-- Product Slider -->
                                        <div class="product-gallery">
                                            <div class="quickview-slider-active">
                                                @php
                                                    $photo=explode(',',$product->photo);
                                                // dd($photo);
                                                @endphp
                                                @foreach($photo as $data)
                                                    <div class="single-slider">
                                                        <img src="{{$data}}" alt="{{$data}}">
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    <!-- End Product slider -->
                                </div>
                                <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
                                    <div class="quickview-content">
                                        <h2>{{$product->title}}</h2>
                                        <div class="quickview-ratting-review">
                                            <div class="quickview-ratting-wrap">
                                                <div class="quickview-ratting">
                                                    {{-- <i class="yellow fa fa-star"></i>
                                                    <i class="yellow fa fa-star"></i>
                                                    <i class="yellow fa fa-star"></i>
                                                    <i class="yellow fa fa-star"></i>
                                                    <i class="fa fa-star"></i> --}}
                                                    @php
                                                        $rate=DB::table('product_reviews')->where('product_id',$product->id)->avg('rate');
                                                        $rate_count=DB::table('product_reviews')->where('product_id',$product->id)->count();
                                                    @endphp
                                                    @for($i=1; $i<=5; $i++)
                                                        @if($rate>=$i)
                                                            <i class="yellow fa fa-star"></i>
                                                        @else
                                                        <i class="fa fa-star"></i>
                                                        @endif
                                                    @endfor
                                                </div>
                                                <a href="#"> ({{$rate_count}} Khách hàng đánh giá)</a>
                                            </div>
                                            <div class="quickview-stock">
                                                @if($product->stock >0)
                                                <span><i class="fa fa-check-circle-o"></i> {{$product->stock}} sản phẩm trong kho</span>
                                                @else
                                                <span><i class="fa fa-times-circle-o text-danger"></i> {{$product->stock}} Hết hàng</span>
                                                @endif
                                            </div>
                                        </div>
                                        @php
                                            $after_discount=($product->price-($product->price*$product->discount)/100);
                                        @endphp
                                        <h3><small><del class="text-muted">{{number_format($product->price,0)}} đ</del></small>    {{number_format($after_discount,0)}} đ  </h3>
                                        <div class="quickview-peragraph">
                                            <p>{!! html_entity_decode($product->summary) !!}</p>
                                        </div>
                                        @if($product->size)
                                            <div class="size">
                                                <div class="row">
                                                    <div class="col-lg-6 col-12">
                                                        <h5 class="title">Kích cỡ</h5>
                                                        <select>
                                                            @php
                                                            $sizes=explode(',',$product->size);
                                                            // dd($sizes);
                                                            @endphp
                                                            @foreach($sizes as $size)
                                                                <option>{{$size}}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    {{-- <div class="col-lg-6 col-12">
                                                        <h5 class="title">Màu sắc</h5>
                                                        <select>
                                                            <option selected="selected">orange</option>
                                                            <option>purple</option>
                                                            <option>black</option>
                                                            <option>pink</option>
                                                        </select>
                                                    </div> --}}
                                                </div>
                                            </div>
                                        @endif
                                        <form action="{{route('single-add-to-cart')}}" method="POST" class="mt-4">
                                            @csrf
                                            <div class="quantity">
                                                <!-- Input Order -->
                                                <div class="input-group">
                                                    <div class="button minus">
                                                        <button type="button" class="btn btn-primary btn-number" disabled="disabled" data-type="minus" data-field="quant[1]">
                                                            <i class="ti-minus"></i>
                                                        </button>
                                                    </div>
													<input type="hidden" name="slug" value="{{$product->slug}}">
                                                    <input type="text" name="quant[1]" class="input-number"  data-min="1" data-max="1000000" value="1">
                                                    <div class="button plus">
                                                        <button type="button" class="btn btn-primary btn-number" data-type="plus" data-field="quant[1]">
                                                            <i class="ti-plus"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                                <!--/ End Input Order -->
                                            </div>
                                            <div class="add-to-cart">
                                                <button type="submit" class="btn">Thêm vào giỏ hàng</button>
                                                <button type="submit" formaction="{{route('add-to-wishlist',$product->slug)}}" formmethod="POST" class="btn min"><i class="ti-heart"></i></button>
                                            </div>
                                        </form>
                                        <div class="default-social">
                                        <!-- ShareThis BEGIN --><div class="sharethis-inline-share-buttons"></div><!-- ShareThis END -->
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
        </div>
    @endforeach
@endif
<!-- Modal end -->
@include('frontend.layouts.home-chatbot')
@endsection

@push('styles')
    <script type='text/javascript' src='https://platform-api.sharethis.com/js/sharethis.js#property=5f2e5abf393162001291e431&product=inline-share-buttons' async='async'></script>
    <script type='text/javascript' src='https://platform-api.sharethis.com/js/sharethis.js#property=5f2e5abf393162001291e431&product=inline-share-buttons' async='async'></script>
    <style>
        .home-quiz-spotlight {
            padding: 26px 0 14px;
        }

        .home-quiz-card {
            background:
                radial-gradient(circle at top right, rgba(247, 148, 29, 0.24), transparent 32%),
                linear-gradient(135deg, #fff7ea 0%, #ffffff 48%, #f8fbff 100%);
            border: 1px solid rgba(15, 23, 42, 0.08);
            border-radius: 28px;
            box-shadow: 0 24px 48px rgba(15, 23, 42, 0.08);
            overflow: hidden;
            padding: 30px 34px;
            position: relative;
        }

        .home-quiz-card::after {
            background: rgba(15, 23, 42, 0.04);
            border-radius: 999px;
            content: "";
            height: 200px;
            position: absolute;
            right: -70px;
            top: -80px;
            width: 200px;
        }

        .home-quiz-kicker {
            color: #9c5c11;
            display: inline-block;
            font-size: 0.82rem;
            font-weight: 700;
            letter-spacing: 0.18em;
            margin-bottom: 14px;
            text-transform: uppercase;
        }

        .home-quiz-card h2 {
            color: #102033;
            font-size: clamp(1.8rem, 3vw, 2.8rem);
            line-height: 1.08;
            margin-bottom: 14px;
        }

        .home-quiz-card p {
            color: #52606d;
            font-size: 1rem;
            margin-bottom: 0;
            max-width: 680px;
        }

        .home-quiz-actions {
            align-items: center;
            display: flex;
            flex-wrap: wrap;
            gap: 14px;
            margin-top: 24px;
        }

        .home-quiz-btn {
            background: #f7941d;
            border-radius: 999px;
            color: #fff;
            font-weight: 700;
            padding: 14px 24px;
        }

        .home-quiz-btn:hover,
        .home-quiz-btn:focus {
            background: #ff8b00;
            color: #fff;
        }

        .home-quiz-link {
            color: #102033;
            font-weight: 600;
            text-decoration: underline;
        }

        .home-quiz-stat-grid {
            display: grid;
            gap: 14px;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            position: relative;
            z-index: 1;
        }

        .home-quiz-stat-card {
            background: rgba(255, 255, 255, 0.8);
            border: 1px solid rgba(15, 23, 42, 0.08);
            border-radius: 20px;
            padding: 18px;
        }

        .home-quiz-stat-card strong {
            color: #102033;
            display: block;
            font-size: 1.6rem;
            line-height: 1;
            margin-bottom: 8px;
        }

        .home-quiz-stat-card span {
            color: #64748b;
            display: block;
            line-height: 1.5;
        }

        .home-quiz-stat-card:last-child {
            grid-column: span 2;
        }

        /* Banner Sliding */
        #Gslider .carousel-inner {
            background: #0f172a;
            /* border-radius: 28px; */
            overflow: hidden;
        }

        #Gslider .carousel-item {
            position: relative;
            min-height: clamp(360px, 52vw, 560px);
        }

        #Gslider .carousel-item::before {
            content: "";
            position: absolute;
            inset: 0;
            background: linear-gradient(90deg, rgba(15, 23, 42, 0.86) 0%, rgba(15, 23, 42, 0.66) 38%, rgba(15, 23, 42, 0.2) 72%, rgba(15, 23, 42, 0.08) 100%);
            z-index: 1;
        }

        #Gslider .carousel-inner img {
            width: 100% !important;
            height: clamp(360px, 52vw, 560px);
            object-fit: cover;
            object-position: center;
            transform: scale(1.01);
        }

        #Gslider .carousel-inner .carousel-caption {
            inset: 0;
            z-index: 2;
            padding: clamp(24px, 4vw, 48px) 0;
            display: flex;
            align-items: center;
            text-align: left;
        }

        #Gslider .carousel-inner .carousel-caption .container {
            width: 100%;
        }

        #Gslider .gslider-content {
            max-width: 620px;
            padding: clamp(24px, 3vw, 36px);
            background: rgba(15, 23, 42, 0.38);
            border: 1px solid rgba(255, 255, 255, 0.14);
            border-radius: 24px;
            box-shadow: 0 24px 60px rgba(15, 23, 42, 0.3);
            backdrop-filter: blur(6px);
        }

        #Gslider .gslider-content h1 {
            margin: 0 0 16px;
            font-size: clamp(2rem, 4vw, 3.6rem);
            font-weight: 700;
            line-height: 1.08;
            color: #ffb13b;
            text-shadow: 0 12px 24px rgba(15, 23, 42, 0.28);
        }

        #Gslider .gslider-copy {
            color: #f8fafc;
        }

        #Gslider .gslider-copy > * {
            margin: 0 0 12px;
            color: inherit;
            max-width: 100%;
        }

        #Gslider .gslider-copy > *:last-child {
            margin-bottom: 0;
        }

        #Gslider .gslider-copy h2,
        #Gslider .gslider-copy h3,
        #Gslider .gslider-copy h4,
        #Gslider .gslider-copy p {
            font-size: clamp(1rem, 2vw, 1.6rem);
            font-weight: 600;
            line-height: 1.4;
        }

        #Gslider .gslider-copy ul,
        #Gslider .gslider-copy ol {
            margin: 0;
            padding-left: 20px;
        }

        #Gslider .gslider-copy li {
            font-size: clamp(0.95rem, 1.4vw, 1.05rem);
            line-height: 1.6;
        }

        #Gslider .gslider-actions {
            margin-top: 28px;
        }

        #Gslider .ws-btn {
            background: #f7941d;
            border-radius: 999px;
            padding: 14px 28px;
            box-shadow: 0 16px 30px rgba(247, 148, 29, 0.28);
        }

        #Gslider .ws-btn i {
            margin-left: 10px;
        }

        #Gslider .ws-btn:hover {
            background: #ffffff;
            color: #111827;
        }

        #Gslider .carousel-indicators {
            bottom: 18px;
            margin-bottom: 0;
        }

        #Gslider .carousel-indicators li {
            width: 12px;
            height: 12px;
            border: 0;
            border-radius: 999px;
            background: rgba(255, 255, 255, 0.42);
        }

        #Gslider .carousel-indicators .active {
            background: #f7941d;
        }

        #Gslider .carousel-control-prev,
        #Gslider .carousel-control-next {
            width: 8%;
            z-index: 3;
        }

        #Gslider .carousel-control-prev-icon,
        #Gslider .carousel-control-next-icon {
            width: 48px;
            height: 48px;
            border-radius: 999px;
            background-color: rgba(15, 23, 42, 0.58);
            background-size: 44% 44%;
        }

        @media (max-width: 991px) {
            .home-quiz-card {
                padding: 24px;
            }

            .home-quiz-stat-grid {
                margin-top: 22px;
            }

            #Gslider .carousel-inner .carousel-caption {
                padding: 24px 0 72px;
            }

            #Gslider .gslider-content {
                max-width: 100%;
            }
        }

        @media (max-width: 767.98px) {
            .home-quiz-spotlight {
                padding-top: 18px;
            }

            .home-quiz-card {
                border-radius: 22px;
                padding: 22px 18px;
            }

            .home-quiz-actions {
                align-items: stretch;
                flex-direction: column;
            }

            .home-quiz-btn {
                text-align: center;
                width: 100%;
            }

            #Gslider .carousel-inner {
                border-radius: 18px;
            }

            #Gslider .carousel-item::before {
                background: linear-gradient(180deg, rgba(15, 23, 42, 0.24) 0%, rgba(15, 23, 42, 0.68) 48%, rgba(15, 23, 42, 0.94) 100%);
            }

            #Gslider .carousel-inner .carousel-caption {
                align-items: flex-end;
                padding: 20px 0 64px;
            }

            #Gslider .gslider-content {
                padding: 18px;
                border-radius: 18px;
            }

            #Gslider .gslider-copy h2,
            #Gslider .gslider-copy h3,
            #Gslider .gslider-copy h4,
            #Gslider .gslider-copy p {
                font-size: 1rem;
            }

            #Gslider .gslider-copy li {
                font-size: 0.92rem;
            }

            #Gslider .ws-btn {
                width: 100%;
                display: inline-flex;
                justify-content: center;
                align-items: center;
            }

            #Gslider .carousel-control-prev,
            #Gslider .carousel-control-next {
                display: none;
            }
        }
    </style>
@endpush

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
    <script>

        /*==================================================================
        [ Isotope ]*/
        var $topeContainer = $('.isotope-grid');
        var $filter = $('.filter-tope-group');

        // filter items on button click
        $filter.each(function () {
            $filter.on('click', 'button', function () {
                var filterValue = $(this).attr('data-filter');
                $topeContainer.isotope({filter: filterValue});
            });

        });

        // init Isotope
        $(window).on('load', function () {
            var $grid = $topeContainer.each(function () {
                $(this).isotope({
                    itemSelector: '.isotope-item',
                    layoutMode: 'fitRows',
                    percentPosition: true,
                    animationEngine : 'best-available',
                    masonry: {
                        columnWidth: '.isotope-item'
                    }
                });
            });
        });

        var isotopeButton = $('.filter-tope-group button');

        $(isotopeButton).each(function(){
            $(this).on('click', function(){
                for(var i=0; i<isotopeButton.length; i++) {
                    $(isotopeButton[i]).removeClass('how-active1');
                }

                $(this).addClass('how-active1');
            });
        });
    </script>
    <script>
         function cancelFullScreen(el) {
            var requestMethod = el.cancelFullScreen||el.webkitCancelFullScreen||el.mozCancelFullScreen||el.exitFullscreen;
            if (requestMethod) { // cancel full screen.
                requestMethod.call(el);
            } else if (typeof window.ActiveXObject !== "undefined") { // Older IE.
                var wscript = new ActiveXObject("WScript.Shell");
                if (wscript !== null) {
                    wscript.SendKeys("{F11}");
                }
            }
        }

        function requestFullScreen(el) {
            // Supports most browsers and their versions.
            var requestMethod = el.requestFullScreen || el.webkitRequestFullScreen || el.mozRequestFullScreen || el.msRequestFullscreen;

            if (requestMethod) { // Native full screen.
                requestMethod.call(el);
            } else if (typeof window.ActiveXObject !== "undefined") { // Older IE.
                var wscript = new ActiveXObject("WScript.Shell");
                if (wscript !== null) {
                    wscript.SendKeys("{F11}");
                }
            }
            return false
        }
    </script>

@endpush
