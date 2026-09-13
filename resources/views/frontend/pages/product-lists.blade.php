@extends('frontend.layouts.master')

@section('title','Web bán tai nghe || Sản phẩm')

@section('main-content')

		<style>
			.shop-list .shop-top{
				padding: 24px 26px;
				border-radius: 28px;
				background:
					linear-gradient(135deg, rgba(15, 23, 42, 0.06), rgba(14, 165, 233, 0.08)),
					#f8fafc;
				box-shadow: 0 24px 44px rgba(148, 163, 184, 0.16);
			}

			.shop-list .product-list-entry{
				align-items: center;
				margin: 0 0 26px;
				padding: 22px;
				border-radius: 32px;
				background: rgba(255, 255, 255, 0.96);
				border: 1px solid rgba(226, 232, 240, 0.9);
				box-shadow: 0 26px 50px rgba(148, 163, 184, 0.14);
			}

			.shop-list .product-list-entry > [class*="col-"]{
				padding-top: 0;
				padding-bottom: 0;
			}

			.shop-list .product-list-card{
				margin-top: 0;
			}

			.shop-list .product-list-card__media{
				border-radius: 28px;
				background:
					radial-gradient(circle at top, rgba(255, 255, 255, 0.98), rgba(226, 232, 240, 0.92)),
					#eef2ff;
				border: 1px solid rgba(191, 219, 254, 0.9);
				box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.82);
			}

			.shop-list .product-list-card__media-link{
				display: block;
				padding: 24px;
			}

			.shop-list .product-list-card__image-shell{
				--listing-card-image-width: 220px;
				--listing-image: none;
				position: relative;
				display: flex;
				align-items: center;
				justify-content: center;
				min-height: 260px;
				border-radius: 24px;
				overflow: hidden;
				isolation: isolate;
			}

			.shop-list .product-list-card__image-shell::before{
				content: "";
				position: absolute;
				inset: 12%;
				background-image: var(--listing-image);
				background-position: center;
				background-repeat: no-repeat;
				background-size: contain;
				filter: blur(28px) saturate(1.08);
				opacity: 0.14;
				transform: scale(1.08);
			}

			.shop-list .product-list-card__image{
				position: relative;
				z-index: 1;
				display: block;
				width: auto;
				max-width: min(100%, var(--listing-card-image-width));
				max-height: 240px;
				object-fit: contain;
				filter: drop-shadow(0 18px 30px rgba(15, 23, 42, 0.18));
				transition: transform 0.24s ease, filter 0.24s ease;
			}

			.shop-list .product-list-card:hover .product-list-card__image{
				transform: translateY(-4px);
				filter: drop-shadow(0 24px 34px rgba(15, 23, 42, 0.22));
			}

			.shop-list .product-list-card__image--enhanced{
				max-width: min(100%, var(--listing-card-image-width, 170px));
				border-radius: 18px;
				box-shadow:
					0 14px 24px rgba(15, 23, 42, 0.12),
					0 0 0 1px rgba(226, 232, 240, 0.95);
			}

			.shop-list .product-list-card--low-res .product-list-card__image-shell{
				min-height: 250px;
			}

			.shop-list .product-list-card--low-res:hover .product-list-card__image{
				transform: translateY(-1px);
			}

			.shop-list .list-content{
				margin-top: 0;
				padding: 8px 6px 8px 24px;
			}

			.shop-list .list-content .title a{
				font-size: 26px;
				line-height: 1.2;
			}

			.shop-list .list-content .des{
				margin-top: 12px;
				color: #475569;
				line-height: 1.85;
			}

			.shop-list .list-content .btn{
				padding: 14px 30px;
				border-radius: 999px;
				border-color: rgba(15, 23, 42, 0.12);
				font-weight: 700;
				letter-spacing: 0.04em;
				text-transform: uppercase;
			}

			@media (max-width: 991.98px){
				.shop-list .product-list-entry{
					padding: 18px;
					border-radius: 26px;
				}

				.shop-list .list-content{
					padding: 20px 2px 4px;
				}
			}

			@media (max-width: 575.98px){
				.shop-list .shop-top{
					padding: 18px;
					border-radius: 22px;
				}

				.shop-list .product-list-entry{
					padding: 14px;
					border-radius: 22px;
				}

				.shop-list .product-list-card__media-link{
					padding: 16px;
				}

				.shop-list .product-list-card__image-shell{
					min-height: 220px;
				}

				.shop-list .product-list-card__image{
					max-height: 200px;
				}

				.shop-list .list-content .title a{
					font-size: 21px;
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
								<li><a href="{{route('home')}}">Trang Chủ<i class="ti-arrow-right"></i></a></li>
								<li class="active"><a href="javascript:void(0);">Sản Phẩm</a></li>
							</ul>
						</div>
					</div>
				</div>
			</div>
		</div>
		<!-- End Breadcrumbs -->
		<form action="{{route('shop.filter')}}" method="POST">
		@csrf
			<!-- Product Style 1 -->
			<section class="product-area shop-sidebar shop-list shop section">
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
											{{-- <div id="slider-range" data-min="10" data-max="2000" data-currency="%"></div>
												<div class="price_slider_amount">
												<div class="label-input">
													<span>Range:</span>
													<input type="text" id="amount" name="price_range" value='@if(!empty($_GET['price'])) {{$_GET['price']}} @endif' placeholder="Add Your Price"/>
												</div>
											</div> --}}
											@php
												$max=DB::table('products')->max('price');
												// dd($max);
											@endphp
											<div id="slider-range" data-min="0" data-max="{{$max}}"></div>
											<div class="product_filter">
											<button type="submit" class="filter_button">Lọc</button>
											<div class="label-input">
												<span>Khoảng giá:</span>
												<input style="" type="text" id="amount" readonly/>
												<input type="hidden" name="price_range" id="price_range" value="@if(!empty($_GET['price'])){{$_GET['price']}}@endif"/>
											</div>
											</div>
										</div>
									</div>
									{{-- <ul class="check-box-list">
										<li>
											<label class="checkbox-inline" for="1"><input name="news" id="1" type="checkbox">$20 - $50<span class="count">(3)</span></label>
										</li>
										<li>
											<label class="checkbox-inline" for="2"><input name="news" id="2" type="checkbox">$50 - $100<span class="count">(5)</span></label>
										</li>
										<li>
											<label class="checkbox-inline" for="3"><input name="news" id="3" type="checkbox">$100 - $250<span class="count">(8)</span></label>
										</li>
									</ul> --}}
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
                                        @endphp
                                        <div class="single-post first d-flex flex-column align-items-center">
                                            <div class="image">
                                                <img src="{{$photo[0]}}" alt="{{$photo[0]}}">
                                            </div>
                                            <div class="content ml-0 pl-0">
                                                <h5><a href="{{route('product-detail',$product->slug)}}">{{$product->title}}</a></h5>
                                                @php
                                                    $org=($product->price-($product->price*$product->discount)/100);
                                                @endphp
                                                <p class="price"><del class="text-muted">{{number_format($product->price,0)}}đ</del>   {{number_format($org,0)}}đ  </p>
                                            </div>
                                        </div>
                                        <!-- End Single Post -->
                                    @endforeach
                                </div>
                                <!--/ End Single Widget -->
                                <!-- Single Widget -->
                                <div class="single-widget category">
                                    <h3 class="title">Thương hiệu</h3>
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
											<li><a href="{{route('product-grids')}}"><i class="fa fa-th-large"></i></a></li>
											<li class="active"><a href="javascript:void(0)"><i class="fa fa-th-list"></i></a></li>
										</ul>
									</div>
									<!--/ End Shop Top -->
								</div>
							</div>
							<div class="row">
								@if(count($products))
									@foreach($products as $product)
									 	{{-- {{$product}} --}}
										<!-- Start Single List -->
										<div class="col-12">
											@php
												$photo=explode(',',$product->photo);
												$listingMedia = $product->listing_media ?? [
													'display_url' => $photo[0] ?? '',
													'display_width' => null,
													'is_low_resolution' => false,
												];
												$imageShellStyle = "--listing-image:url('" . ($listingMedia['display_url'] ?? '') . "');";
												if(!empty($listingMedia['display_width'])){
													$imageShellStyle .= '--listing-card-image-width:' . $listingMedia['display_width'] . 'px;';
												}
											@endphp
											<div class="row product-list-entry">
												<div class="col-lg-4 col-md-6 col-sm-6">
													<div class="single-product product-list-card{{ !empty($listingMedia['is_low_resolution']) ? ' product-list-card--low-res' : '' }}">
														<div class="product-img product-list-card__media">
															<a href="{{route('product-detail',$product->slug)}}" class="product-list-card__media-link">
																<div class="product-list-card__image-shell" style="{{ $imageShellStyle }}">
																	<img class="product-list-card__image{{ !empty($listingMedia['is_low_resolution']) ? ' product-list-card__image--enhanced' : '' }}" src="{{$listingMedia['display_url']}}" alt="{{$product->title}}">
																</div>
															</a>
															<div class="button-head">
																<div class="product-action">
																	<a data-toggle="modal" data-target="#{{$product->id}}" title="Quick View" href="#"><i class=" ti-eye"></i><span>Mua Nhanh</span></a>
																	<button type="submit" form="list-wishlist-{{$product->id}}" title="Wishlist" class="action-button-reset"><i class=" ti-heart "></i><span>Th&#234;m v&#224;o danh s&#225;ch y&#234;u th&#237;ch</span></button>
																</div>
																<div class="product-action-2">
																	<button type="submit" form="list-cart-{{$product->id}}" title="Add to cart" class="action-button-reset">Th&#234;m v&#224;o gi&#7887; h&#224;ng</button>
																</div>
															</div>
														</div>
													</div>
												</div>
												<div class="col-lg-8 col-md-6 col-12">
													<div class="list-content">
														<div class="product-content">
															<div class="product-price">
																@php
																	$after_discount=($product->price-($product->price*$product->discount)/100);
																@endphp
																<span>{{number_format($after_discount,0)}}đ</span>
																<del>{{number_format($product->price,0)}}đ</del>
															</div>
															<h3 class="title"><a href="{{route('product-detail',$product->slug)}}">{{$product->title}}</a></h3>
														{{-- <p>{!! html_entity_decode($product->summary) !!}</p> --}}
														</div>
														<p class="des pt-2">{!! html_entity_decode($product->summary) !!}</p>
														<button type="submit" form="list-cart-{{$product->id}}" class="btn cart">Mua Ngay!</button>
													</div>
												</div>
											</div>
										</div>
										<!-- End Single List -->
									@endforeach
								@else
									<h4 class="text-warning" style="margin:100px auto;">Không Có Sản Phẩm Nào.</h4>
								@endif
							</div>
							 <div class="row">
                            <div class="col-md-12 justify-content-center d-flex">
                                {{-- {{$products->appends($_GET)->links()}}  --}}
                            </div>
                          </div>
						</div>
					</div>
				</div>
			</section>
			<!--/ End Product Style 1  -->
		</form>
		@if(count($products))
		    @foreach($products as $product)
		        <form id="list-wishlist-{{$product->id}}" action="{{route('add-to-wishlist',$product->slug)}}" method="POST" class="d-none">
		            @csrf
		        </form>
		        <form id="list-cart-{{$product->id}}" action="{{route('add-to-cart',$product->slug)}}" method="POST" class="d-none">
		            @csrf
		        </form>
		    @endforeach
		@endif
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
														<a href="#"> ({{$rate_count}} customer review)</a>
													</div>
													<div class="quickview-stock">
														@if($product->stock >0)
														<span><i class="fa fa-check-circle-o"></i> {{$product->stock}} in stock</span>
														@else
														<span><i class="fa fa-times-circle-o text-danger"></i> {{$product->stock}} out stock</span>
														@endif
													</div>
												</div>
												@php
													$after_discount=($product->price-($product->price*$product->discount)/100);
												@endphp
												<h3><small><del class="text-muted">${{number_format($product->price,2)}}</del></small>    ${{number_format($after_discount,2)}}  </h3>
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
@push ('styles')
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
