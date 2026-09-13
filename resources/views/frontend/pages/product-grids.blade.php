@extends('frontend.layouts.master')

@section('title','Web bán tai nghe || Sản phẩm')

@section('main-content')
	@php
		$isSearchResults = request()->routeIs('product.search') && !empty(request('search'));
		$searchResultCount = $products instanceof \Illuminate\Pagination\AbstractPaginator ? $products->total() : count($products);
	@endphp
	<style>
		.shop .shop-top{
			padding: 24px 26px;
			border-radius: 28px;
			background:
				linear-gradient(135deg, rgba(15, 23, 42, 0.06), rgba(14, 165, 233, 0.08)),
				#f8fafc;
			box-shadow: 0 24px 44px rgba(148, 163, 184, 0.16);
		}

		.catalog-grid-card{
			margin-top: 0;
		}

		.catalog-grid-card .product-img{
			border-radius: 28px;
			background:
				radial-gradient(circle at top, rgba(255, 255, 255, 0.98), rgba(226, 232, 240, 0.92)),
				#eef2ff;
			border: 1px solid rgba(191, 219, 254, 0.88);
			box-shadow: 0 24px 44px rgba(148, 163, 184, 0.14);
		}

		.catalog-grid-card__media-link{
			display: block;
			padding: 18px;
		}

		.catalog-grid-card__image-shell{
			--grid-card-image-width: 220px;
			--grid-card-image: none;
			position: relative;
			display: flex;
			align-items: center;
			justify-content: center;
			min-height: 250px;
			border-radius: 22px;
			overflow: hidden;
			isolation: isolate;
		}

		.catalog-grid-card__image-shell::before{
			content: "";
			position: absolute;
			inset: 12%;
			background-image: var(--grid-card-image);
			background-position: center;
			background-repeat: no-repeat;
			background-size: contain;
			filter: blur(28px) saturate(1.08);
			opacity: 0.14;
			transform: scale(1.08);
		}

		.catalog-grid-card__image{
			position: relative;
			z-index: 1;
			display: block;
			width: auto;
			max-width: min(100%, var(--grid-card-image-width));
			max-height: 230px;
			object-fit: contain;
			filter: drop-shadow(0 18px 30px rgba(15, 23, 42, 0.18));
			transition: transform 0.24s ease, filter 0.24s ease;
		}

		.catalog-grid-card:hover .catalog-grid-card__image{
			transform: translateY(-4px);
			filter: drop-shadow(0 24px 34px rgba(15, 23, 42, 0.22));
		}

		.catalog-grid-card__image--enhanced{
			max-width: min(100%, var(--grid-card-image-width, 170px));
			border-radius: 18px;
			box-shadow:
				0 14px 24px rgba(15, 23, 42, 0.12),
				0 0 0 1px rgba(226, 232, 240, 0.95);
		}

		.catalog-grid-card--low-res:hover .catalog-grid-card__image{
			transform: translateY(-1px);
		}

		.shop-sidebar .single-post .image{
			width: 84px;
			height: 84px;
			border-radius: 24px;
			background:
				radial-gradient(circle at top, rgba(255, 255, 255, 0.98), rgba(226, 232, 240, 0.92)),
				#eef2ff;
			box-shadow: 0 16px 30px rgba(148, 163, 184, 0.14);
			overflow: hidden;
		}

		.shop-sidebar .single-post .image img{
			position: static;
			display: block;
			width: 100%;
			height: 100%;
			object-fit: contain;
			border-radius: inherit;
			padding: 10px;
		}

		.shop-sidebar .single-post .image img.recent-post-card__image--enhanced{
			padding: 14px;
			border-radius: 20px;
			box-shadow:
				0 10px 18px rgba(15, 23, 42, 0.08),
				0 0 0 1px rgba(226, 232, 240, 0.9);
		}

		@media (max-width: 575.98px){
			.shop .shop-top{
				padding: 18px;
				border-radius: 22px;
			}

			.catalog-grid-card__image-shell{
				min-height: 220px;
			}

			.catalog-grid-card__image{
				max-height: 200px;
			}
		}
	</style>
	<!-- Breadcrumbs -->
    <div class="breadcrumbs">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="bread-inner">
                        <ul class="bread-list">
                            <li><a href="index1.html">Trang Chủ<i class="ti-arrow-right"></i></a></li>
                            <li class="active"><a href="blog-single.html">Sản Phẩm</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End Breadcrumbs -->

    <!-- Product Style -->
    <form action="{{ $isSearchResults ? route('product.search') : route('shop.filter') }}" method="{{ $isSearchResults ? 'GET' : 'POST' }}">
        @if(!$isSearchResults)
            @csrf
        @endif
        @if($isSearchResults)
            <input type="hidden" name="search" value="{{ request('search') }}">
        @endif
        <section class="product-area shop-sidebar shop section">
            <div class="container">
                <div class="row">
                    <div class="col-lg-3 col-md-4 col-12">
                        <div class="shop-sidebar">
                                <!-- Single Widget -->
                                <div class="single-widget category">
                                    <h3 class="title">Danh Mục Sản Phẩm</h3>
                                    <ul class="categor-list">
										@php
											// $category = new Category();
											$menu=App\Models\Category::getAllParentWithChild();
										@endphp
										@if($menu)
										<li>
											@foreach($menu as $cat_info)
													@if($cat_info->child_cat->count()>0)
														<li><a href="{{route('product-cat',$cat_info->slug)}}">{{$cat_info->title}}</a>
															<ul>
																@foreach($cat_info->child_cat as $sub_menu)
																	<li><a href="{{route('product-sub-cat',[$cat_info->slug,$sub_menu->slug])}}">{{$sub_menu->title}}</a></li>
																@endforeach
															</ul>
														</li>
													@else
														<li><a href="{{route('product-cat',$cat_info->slug)}}">{{$cat_info->title}}</a></li>
													@endif
											@endforeach
										</li>
										@endif
                                        {{-- @foreach(Helper::productCategoryList('products') as $cat)
                                            @if($cat->is_parent==1)
												<li><a href="{{route('product-cat',$cat->slug)}}">{{$cat->title}}</a></li>
											@endif
                                        @endforeach --}}
                                    </ul>
                                </div>
                                <!--/ End Single Widget -->
                                <!-- Shop By Price -->
                                    <div class="single-widget range">
                                        <h3 class="title">Lọc Theo Giá</h3>
                                        <div class="price-filter">
                                            <div class="price-filter-inner">
                                                @php
                                                    $max=DB::table('products')->max('price');
                                                    // dd($max);
                                                @endphp
                                                <div id="slider-range" data-min="0" data-max="{{$max}}"></div>
                                                <div class="product_filter">
                                                <button type="submit" class="filter_button">Lọc</button>
                                                <div class="label-input">
                                                    <span>Khoảng Giá:</span>
                                                    <input style="" type="text" id="amount" readonly/>
                                                    <input type="hidden" name="{{ $isSearchResults ? 'price' : 'price_range' }}" id="price_range" value="@if(!empty($_GET['price'])){{$_GET['price']}}@endif"/>
                                                </div>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                    <!--/ End Shop By Price -->
                                <!-- Single Widget -->
                                <div class="single-widget recent-post">
                                    <h3 class="title">Sản Phẩm Mới Nhập</h3>
                                    {{-- {{dd($recent_products)}} --}}
                                    @foreach($recent_products as $product)
                                        <!-- Single Post -->
                                        @php
                                            $photo=explode(',',$product->photo);
                                            $recentMedia = $product->listing_media ?? [
                                                'display_url' => $photo[0] ?? '',
                                                'is_low_resolution' => false,
                                            ];
                                        @endphp
                                        <div class="single-post first d-flex flex-column align-items-center">
                                            <div class="image">
                                                <img class="{{ !empty($recentMedia['is_low_resolution']) ? 'recent-post-card__image--enhanced' : '' }}" src="{{$recentMedia['display_url']}}" alt="{{$product->title}}">
                                            </div>
                                            <div class="content ml-0 pl-0">
                                                <h5><a href="{{route('product-detail',$product->slug)}}">{{$product->title}}</a></h5>
                                                @php
                                                    $org=($product->price-($product->price*$product->discount)/100);
                                                @endphp
                                                <p class="price"><del class="text-muted">${{number_format($product->price,2)}}</del>   ${{number_format($org,2)}}  </p>

                                            </div>
                                        </div>
                                        <!-- End Single Post -->
                                    @endforeach
                                </div>
                                <!--/ End Single Widget -->
                                <!-- Single Widget -->
                                <div class="single-widget category">
                                    <h3 class="title">Thương Hiệu</h3>
                                    <ul class="categor-list">
                                        @php
                                            $brands=DB::table('brands')->orderBy('title','ASC')->where('status','active')->get();
                                        @endphp
                                        @foreach($brands as $brand)
                                            <li><a href="{{route('product-brand',$brand->slug)}}">{{$brand->title}}</a></li>
                                        @endforeach
                                    </ul>
                                </div>
                                <!--/ End Single Widget -->
                        </div>
                    </div>
                    <div class="col-lg-9 col-md-8 col-12">
                        <div class="row">
                            <div class="col-12">
                                <!-- Shop Top -->
                                <div class="shop-top d-flex justify-content-between mb-3">
                                    <div class="shop-shorter">
                                        <div class="single-shorter">
                                            <label>Hiển thị :</label>
                                            <select class="show" name="show" onchange="this.form.submit();">
                                                <option value="">Mặc định</option>
                                                <option value="9" @if(!empty($_GET['show']) && $_GET['show']=='9') selected @endif>09</option>
                                                <option value="15" @if(!empty($_GET['show']) && $_GET['show']=='15') selected @endif>15</option>
                                                <option value="21" @if(!empty($_GET['show']) && $_GET['show']=='21') selected @endif>21</option>
                                                <option value="30" @if(!empty($_GET['show']) && $_GET['show']=='30') selected @endif>30</option>
                                            </select>
                                        </div>
                                        <div class="single-shorter">
                                            <label>Xếp theo :</label>
                                            <select class='sortBy' name='sortBy' onchange="this.form.submit();">
                                                <option value="">Mặc định</option>
                                                <option value="title" @if(!empty($_GET['sortBy']) && $_GET['sortBy']=='title') selected @endif>Tên</option>
                                                <option value="price" @if(!empty($_GET['sortBy']) && $_GET['sortBy']=='price') selected @endif>Giá</option>
                                                <option value="category" @if(!empty($_GET['sortBy']) && $_GET['sortBy']=='category') selected @endif>Danh mục</option>
                                                <option value="brand" @if(!empty($_GET['sortBy']) && $_GET['sortBy']=='brand') selected @endif>Thương hiệu</option>
                                            </select>
                                        </div>
                                    </div>
                                    <ul class="view-mode">
                                        <li class="active"><a href="javascript:void(0)"><i class="fa fa-th-large"></i></a></li>
                                        <li><a href="{{ $isSearchResults ? route('product.search', request()->query()) : route('product-lists') }}"><i class="fa fa-th-list"></i></a></li>
                                    </ul>
                                </div>
                                <!--/ End Shop Top -->
                            </div>
                        </div>
                        @if($isSearchResults)
                            <div class="row">
                                <div class="col-12">
                                    <div class="alert alert-light border mb-4">
                                        <strong>K&#7871;t qu&#7843; cho:</strong> "{{ request('search') }}"
                                        <span class="d-block mt-1 text-muted">T&#236;m th&#7845;y {{ $searchResultCount }} s&#7843;n ph&#7849;m ph&#249; h&#7907;p.</span>
                                    </div>
                                </div>
                            </div>
                        @endif
                        <div class="row">
                            {{-- {{$products}} --}}
                            @if(count($products)>0)
                                @foreach($products as $product)
                                    <div class="col-lg-4 col-md-6 col-12">
                                        @php
                                            $photo=explode(',',$product->photo);
                                            $listingMedia = $product->listing_media ?? [
                                                'display_url' => $photo[0] ?? '',
                                                'display_width' => null,
                                                'is_low_resolution' => false,
                                            ];
                                            $imageShellStyle = "--grid-card-image:url('" . ($listingMedia['display_url'] ?? '') . "');";
                                            if(!empty($listingMedia['display_width'])){
                                                $imageShellStyle .= '--grid-card-image-width:' . $listingMedia['display_width'] . 'px;';
                                            }
                                        @endphp
                                        <div class="single-product catalog-grid-card{{ !empty($listingMedia['is_low_resolution']) ? ' catalog-grid-card--low-res' : '' }}">
                                            <div class="product-img">
                                                <a href="{{route('product-detail',$product->slug)}}" class="catalog-grid-card__media-link">
                                                    <div class="catalog-grid-card__image-shell" style="{{ $imageShellStyle }}">
                                                        <img class="catalog-grid-card__image{{ !empty($listingMedia['is_low_resolution']) ? ' catalog-grid-card__image--enhanced' : '' }}" src="{{$listingMedia['display_url']}}" alt="{{$product->title}}">
                                                    </div>
                                                    @if($product->discount)
                                                                <span class="price-dec">{{$product->discount}} % Off</span>
                                                    @endif
                                                </a>
                                                <div class="button-head">
                                                    <div class="product-action">
                                                        <a data-toggle="modal" data-target="#{{$product->id}}" title="Quick View" href="#"><i class=" ti-eye"></i><span>Mua ngay</span></a>
                                                        <button type="submit" form="grid-wishlist-{{$product->id}}" title="Wishlist" class="action-button-reset"><i class=" ti-heart "></i><span>Th&#234;m v&#224;o danh s&#225;ch y&#234;u th&#237;ch</span></button>
                                                    </div>
                                                    <div class="product-action-2">
                                                        <button type="submit" form="grid-cart-{{$product->id}}" title="Add to cart" class="action-button-reset">Th&#234;m v&#224;o gi&#7887; h&#224;ng</button>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="product-content">
                                                <h3><a href="{{route('product-detail',$product->slug)}}">{{$product->title}}</a></h3>
                                                @php
                                                    $after_discount=($product->price-($product->price*$product->discount)/100);
                                                @endphp
                                                <span>{{number_format($after_discount,0)}}đ</span>
                                                <del style="padding-left:4%;">{{number_format($product->price,0)}}đ</del>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                    <h4 class="text-warning" style="margin:100px auto;">Không có sản phẩm nào.</h4>
                            @endif



                        </div>
                        <div class="row">
                            <div class="col-md-12 justify-content-center d-flex">
                                {{$products->appends($_GET)->links()}}
                            </div>
                          </div>

                    </div>
                </div>
            </div>
        </section>
    </form>
    @if(count($products)>0)
        @foreach($products as $product)
            <form id="grid-wishlist-{{$product->id}}" action="{{route('add-to-wishlist',$product->slug)}}" method="POST" class="d-none">
                @csrf
            </form>
            <form id="grid-cart-{{$product->id}}" action="{{route('add-to-cart',$product->slug)}}" method="POST" class="d-none">
                @csrf
            </form>
        @endforeach
    @endif

    <!--/ End Product Style 1  -->



    <!-- Modal -->
    @if($products)
        @foreach($products as $key=>$product)
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
                                                    <span><i class="fa fa-times-circle-o text-danger"></i> {{$product->stock}} hết hàng</span>
                                                    @endif
                                                </div>
                                            </div>
                                            @php
                                                $after_discount=($product->price-($product->price*$product->discount)/100);
                                            @endphp
                                            <h3><small><del class="text-muted">{{number_format($product->price,0)}} đ</del></small>    {{number_format($after_discount,0)}} đ </h3>
                                            <div class="quickview-peragraph">
                                                <p>{!! html_entity_decode($product->summary) !!}</p>
                                            </div>
                                            @if($product->size)
                                                <div class="size">
                                                    <h4>Kích cỡ</h4>
                                                    <ul>
                                                        @php
                                                            $sizes=explode(',',$product->size);
                                                            // dd($sizes);
                                                        @endphp
                                                        @foreach($sizes as $size)
                                                        <li><a href="#" class="one">{{$size}}</a></li>
                                                        @endforeach
                                                    </ul>
                                                </div>
                                            @endif
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
                                            <form action="{{route('single-add-to-cart')}}" method="POST">
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

@endsection
@push('styles')
<style>
    .pagination{
        display:inline-flex;
    }
    .filter_button{
        /* height:20px; */
        text-align: center;
        background:#F7941D;
        padding:8px 16px;
        margin-top:10px;
        color: white;
    }
</style>
@endpush
@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
    {{-- <script>
        $('.cart').click(function(){
            var quantity=1;
            var pro_id=$(this).data('id');
            $.ajax({
                url:"{{route('add-to-cart')}}",
                type:"POST",
                data:{
                    _token:"{{csrf_token()}}",
                    quantity:quantity,
                    pro_id:pro_id
                },
                success:function(response){
                    console.log(response);
					if(typeof(response)!='object'){
						response=$.parseJSON(response);
					}
					if(response.status){
						swal('success',response.msg,'success').then(function(){
							document.location.href=document.location.href;
						});
					}
                    else{
                        swal('error',response.msg,'error').then(function(){
							// document.location.href=document.location.href;
						});
                    }
                }
            })
        });
    </script> --}}
    <script>
        $(document).ready(function(){
        /*----------------------------------------------------*/
        /*  Jquery Ui slider js
        /*----------------------------------------------------*/
        if ($("#slider-range").length > 0) {
            const max_value = parseInt( $("#slider-range").data('max') ) || 500;
            const min_value = parseInt($("#slider-range").data('min')) || 0;
            const currency = $("#slider-range").data('currency') || '';
            let price_range = min_value+'-'+max_value;
            if($("#price_range").length > 0 && $("#price_range").val()){
                price_range = $("#price_range").val().trim();
            }

            let price = price_range.split('-');
            $("#slider-range").slider({
                range: true,
                min: min_value,
                max: max_value,
                values: price,
                slide: function (event, ui) {
                    $("#amount").val(currency + ui.values[0] + " -  "+currency+ ui.values[1]);
                    $("#price_range").val(ui.values[0] + "-" + ui.values[1]);
                }
            });
            }
        if ($("#amount").length > 0) {
            const m_currency = $("#slider-range").data('currency') || '';
            $("#amount").val(m_currency + $("#slider-range").slider("values", 0) +
                "  -  "+m_currency + $("#slider-range").slider("values", 1));
            }
        })
    </script>
@endpush
