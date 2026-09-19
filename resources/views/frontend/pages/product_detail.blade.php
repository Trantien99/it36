@extends('frontend.layouts.master')

@section('meta')
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name='copyright' content=''>
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<meta name="keywords" content="online shop, purchase, cart, ecommerce site, best online shopping">
	<meta name="description" content="{{$product_detail->summary}}">
	<meta property="og:url" content="{{route('product-detail',$product_detail->slug)}}">
	<meta property="og:type" content="article">
	<meta property="og:title" content="{{$product_detail->title}}">
	<meta property="og:image" content="{{$product_detail->photo}}">
	<meta property="og:description" content="{{$product_detail->description}}">
@endsection
@section('title','Web bán tai nghe || Chi tiết sản phẩm')
@section('main-content')
		@php
			$photo = array_values(array_filter(explode(',', $product_detail->photo)));
			$galleryMedia = !empty($galleryMedia) ? $galleryMedia : array_map(function ($imageUrl) {
				return [
					'original_url' => $imageUrl,
					'display_url' => $imageUrl,
					'thumb_url' => $imageUrl,
					'display_width' => null,
					'is_low_resolution' => false,
				];
			}, $photo);
			$after_discount = $product_detail->price - (($product_detail->price * $product_detail->discount) / 100);
			$conditionLabels = [
				'new' => 'Hàng mới',
				'hot' => 'Bán chạy',
				'default' => 'Đang mở bán',
			];
			$conditionLabel = $conditionLabels[$product_detail->condition] ?? 'Sản phẩm';
			$brandTitle = optional($product_detail->brand)->title ?: 'Audio Gear';
			$stockLabel = $product_detail->stock > 0 ? $product_detail->stock . ' sản phẩm' : 'Tạm hết hàng';
			$relatedProducts = collect($product_detail->rel_prods)->filter(function ($item) use ($product_detail) {
				return (int) $item->id !== (int) $product_detail->id;
			})->values();
			$useRelatedCarousel = $relatedProducts->count() > 2;
		@endphp

		<!-- Breadcrumbs -->
		<div class="breadcrumbs">
			<div class="container">
				<div class="row">
					<div class="col-12">
						<div class="bread-inner">
							<ul class="bread-list">
								<li><a href="{{route('home')}}">Trang Chủ<i class="ti-arrow-right"></i></a></li>
								<li class="active"><a href="">Chi Tiết Sản Phẩm</a></li>
							</ul>
						</div>
					</div>
				</div>
			</div>
		</div>
		<!-- End Breadcrumbs -->

		<!-- Shop Single -->
		<section class="shop single section">
					<div class="container">
						<div class="row">
							<div class="col-12">
								<div class="row">
									<div class="col-lg-6 col-12 product-detail__stage-col">
										<!-- Product Slider -->
										<div class="product-showcase">
											<span class="product-showcase__aura product-showcase__aura--cyan"></span>
											<span class="product-showcase__aura product-showcase__aura--orange"></span>
											<div class="product-showcase__panel js-product-tilt">
												<div class="product-showcase__grid"></div>
												<div class="product-showcase__topbar">
													<div class="product-showcase__label-group">
														<span class="product-showcase__label">3D Product Stage</span>
														@if((int) $product_detail->discount > 0)
															<span class="product-showcase__label product-showcase__label--accent">-{{$product_detail->discount}}%</span>
														@endif
													</div>
													<div class="product-showcase__signal" aria-hidden="true">
														<span></span>
														<span></span>
														<span></span>
													</div>
												</div>
												<div class="product-showcase__stats">
													<div class="product-showcase__stat">
														<strong>{{$conditionLabel}}</strong>
														<small>Trạng thái</small>
													</div>
													<div class="product-showcase__stat">
														<strong>{{$brandTitle}}</strong>
														<small>Thương hiệu</small>
													</div>
													<div class="product-showcase__stat">
														<strong>{{$stockLabel}}</strong>
														<small>Tồn kho</small>
													</div>
												</div>
												<div class="product-gallery product-gallery--immersive">
													<span class="product-gallery__orbit product-gallery__orbit--one"></span>
													<span class="product-gallery__orbit product-gallery__orbit--two"></span>
													<div class="product-gallery__caption">
														<span>AI depth render</span>
														<strong>{{$product_detail->title}}</strong>
													</div>
													<!-- Images slider -->
													<div class="flexslider-thumbnails">
														<ul class="slides">
															@foreach($galleryMedia as $media)
																@php
																	$imageShellStyle = "--gallery-image:url('" . $media['display_url'] . "');";
																	if(!empty($media['display_width'])){
																		$imageShellStyle .= '--gallery-image-display-width:' . $media['display_width'] . 'px;';
																	}
																@endphp
																<li data-thumb="{{$media['thumb_url']}}" rel="adjustX:10, adjustY:" class="{{ !empty($media['is_low_resolution']) ? 'is-low-resolution' : '' }}">
																	<div class="product-gallery__image-shell{{ !empty($media['is_low_resolution']) ? ' product-gallery__image-shell--enhanced' : '' }}" style="{{ $imageShellStyle }}">
																		<img
																			src="{{$media['display_url']}}"
																			alt="{{$product_detail->title}}"
																			class="product-gallery__image{{ !empty($media['is_low_resolution']) ? ' product-gallery__image--enhanced' : '' }}"
																			loading="eager"
																			decoding="async"
																		>
																	</div>
																</li>
															@endforeach
														</ul>
													</div>
													<!-- End Images slider -->
												</div>
											</div>
										</div>
										<!-- End Product slider -->
									</div>
									<div class="col-lg-6 col-12 product-detail__info-col">
										<div class="product-des product-detail__info-card">
											<!-- Description -->
											<div class="short">
												<h4>{{$product_detail->title}}</h4>
												<div class="rating-main">
													<ul class="rating">
														@php
															$rate=ceil($product_detail->getReview->avg('rate'))
														@endphp
															@for($i=1; $i<=5; $i++)
																@if($rate>=$i)
																	<li><i class="fa fa-star"></i></li>
																@else
																	<li><i class="fa fa-star-o"></i></li>
																@endif
															@endfor
													</ul>
													<a href="#" class="total-review">({{$product_detail['getReview']->count()}}) Đánh Giá</a>
                                                </div>
												<p class="price"><span class="discount">{{number_format($after_discount,0)}}đ</span><s>{{number_format($product_detail->price,0)}}đ</s> </p>
												<p class="description">{!!($product_detail->summary)!!}</p>
											</div>
											<!--/ End Description -->
											@if($product_detail->color_code)
												@php $colorCodes = array_filter(array_map('trim', explode(',', $product_detail->color_code))); @endphp
												<div class="color mt-4">
													<h4>Màu sắc</h4>
													<div class="d-flex flex-wrap">
														@foreach($colorCodes as $colorCode)
															<button type="button" class="product-color-button mr-2 mb-2 {{ $loop->first ? 'is-selected' : '' }}" data-color-code="{{e($colorCode)}}" title="Chọn màu {{e($colorCode)}}">
																<span style="background-color:{{e($colorCode)}}"></span>
																<strong>{{e($colorCode)}}</strong>
															</button>
														@endforeach
													</div>
												</div>
											@endif
											<!-- Size -->
											@if($product_detail->size)
												<div class="size mt-4">
													<h4>Kích cỡ</h4>
													@php
														$sizeEntries = array_values(array_filter(array_map('trim', explode(',', $product_detail->size))));
													@endphp
													<div class="product-specs">
														<div class="product-specs__header">
															<span class="product-specs__hint">Spec by model</span>
														</div>
														<div class="product-spec-grid">
															@foreach($sizeEntries as $sizeEntry)
																@php
																	$sizeParts = explode(':', $sizeEntry, 2);
																	$sizeLabel = trim($sizeParts[0] ?? '');
																	$sizeValue = trim($sizeParts[1] ?? '');
																@endphp
																@if($sizeValue !== '')
																	<div class="product-spec-card">
																		<span class="product-spec-card__label">{{ $sizeLabel }}</span>
																		<strong class="product-spec-card__value">{{ $sizeValue }}</strong>
																	</div>
																@else
																	<div class="product-spec-chip">{{ $sizeLabel }}</div>
																@endif
															@endforeach
														</div>
													</div>
												</div>
											@endif
											<!--/ End Size -->
											<!-- Product Buy -->
											<div class="product-buy">
												<form action="{{route('single-add-to-cart')}}" method="POST">
													@csrf
													<div class="quantity">
														<h6>Số lượng :</h6>
														<!-- Input Order -->
														<div class="input-group">
															<div class="button minus">
																<button type="button" class="btn btn-primary btn-number" disabled="disabled" data-type="minus" data-field="quant[1]">
																	<i class="ti-minus"></i>
																</button>
															</div>
															<input type="hidden" name="slug" value="{{$product_detail->slug}}">
															@if($product_detail->color_code)
																<input type="hidden" id="selected-color-code" name="color_code" value="{{e($colorCodes[0] ?? '')}}">
															@endif
															<input type="number" name="quant[1]" class="input-number" data-min="1" data-max="{{$product_detail->stock}}" value="1" id="quantity" min="1" max="{{$product_detail->stock}}">
															<div class="button plus">
																<button type="button" class="btn btn-primary btn-number" data-type="plus" data-field="quant[1]">
																	<i class="ti-plus"></i>
																</button>
															</div>
														</div>
													<!--/ End Input Order -->
													</div>
													<div class="add-to-cart mt-4">
														<button type="submit" class="btn">Thêm vào giỏ hàng</button>
														<button type="submit" formaction="{{route('add-to-wishlist',$product_detail->slug)}}" formmethod="POST" class="btn min"><i class="ti-heart"></i></button>
													</div>
												</form>

												<p class="cat">Danh mục sản phẩm :<a href="{{route('product-cat',$product_detail->cat_info['slug'])}}">{{$product_detail->cat_info['title']}}</a></p>
												@if($product_detail->sub_cat_info)
												<p class="cat mt-1">Danh mục nhánh :<a href="{{route('product-sub-cat',[$product_detail->cat_info['slug'],$product_detail->sub_cat_info['slug']])}}">{{$product_detail->sub_cat_info['title']}}</a></p>
												@endif
												<p class="availability">Kho : @if($product_detail->stock>0)<span class="badge badge-success">{{$product_detail->stock}}</span>@else <span class="badge badge-danger">{{$product_detail->stock}}</span>  @endif</p>
											</div>
											<!--/ End Product Buy -->
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-12">
										<div class="product-info">
											<div class="nav-main">
												<!-- Tab Nav -->
												<ul class="nav nav-tabs" id="myTab" role="tablist">
													<li class="nav-item"><a class="nav-link active" data-toggle="tab" href="#description" role="tab">Mô Tả</a></li>
													<li class="nav-item"><a class="nav-link" data-toggle="tab" href="#reviews" role="tab">Đánh Giá</a></li>
												</ul>
												<!--/ End Tab Nav -->
											</div>
											<div class="tab-content" id="myTabContent">
												<!-- Description Tab -->
												<div class="tab-pane fade show active" id="description" role="tabpanel">
													<div class="tab-single">
														<div class="row">
															<div class="col-12">
																<div class="single-des">
																	<p>{!! ($product_detail->description) !!}</p>
																</div>
															</div>
														</div>
													</div>
												</div>
												<!--/ End Description Tab -->
												<!-- Reviews Tab -->
												<div class="tab-pane fade" id="reviews" role="tabpanel">
													<div class="tab-single review-panel">
														<div class="row">
															<div class="col-12">

																<!-- Review -->
																<div class="comment-review">
																	<div class="add-review">
																		<h5>Thêm đánh giá</h5>
																		<p>Email của bạn sẽ không được công khai, bạn vui lòng đánh giá số sao nha.</p>
																	</div>
																	<h4>Đánh giá (sao) <span class="text-danger">*</span></h4>
																	<div class="review-inner">
																			<!-- Form -->
																@auth
																<form class="form" method="post" action="{{route('review.store',$product_detail->slug)}}">
                                                                    @csrf
                                                                    <div class="row">
                                                                        <div class="col-lg-12 col-12">
                                                                            <div class="rating_box">
                                                                                  <div class="star-rating">
                                                                                    <div class="star-rating__wrap">
                                                                                      <input class="star-rating__input" id="star-rating-5" type="radio" name="rate" value="5">
                                                                                      <label class="star-rating__ico fa fa-star-o" for="star-rating-5" title="5 out of 5 stars"></label>
                                                                                      <input class="star-rating__input" id="star-rating-4" type="radio" name="rate" value="4">
                                                                                      <label class="star-rating__ico fa fa-star-o" for="star-rating-4" title="4 out of 5 stars"></label>
                                                                                      <input class="star-rating__input" id="star-rating-3" type="radio" name="rate" value="3">
                                                                                      <label class="star-rating__ico fa fa-star-o" for="star-rating-3" title="3 out of 5 stars"></label>
                                                                                      <input class="star-rating__input" id="star-rating-2" type="radio" name="rate" value="2">
                                                                                      <label class="star-rating__ico fa fa-star-o" for="star-rating-2" title="2 out of 5 stars"></label>
                                                                                      <input class="star-rating__input" id="star-rating-1" type="radio" name="rate" value="1">
																					  <label class="star-rating__ico fa fa-star-o" for="star-rating-1" title="1 out of 5 stars"></label>
																					  @error('rate')
																						<span class="text-danger">{{$message}}</span>
																					  @enderror
                                                                                    </div>
                                                                                  </div>
                                                                            </div>
                                                                        </div>
																		<div class="col-lg-12 col-12">
																			<div class="form-group">
																				<label>Viết đánh giá</label>
																				<textarea name="review" rows="6" placeholder="" ></textarea>
																			</div>
																		</div>
																		<div class="col-lg-12 col-12">
																			<div class="form-group button5">
																				<button type="submit" class="btn">Đánh giá</button>
																			</div>
																		</div>
																	</div>
																</form>
																@else
																<p class="text-center p-5">
																	Bạn cần phải <a href="{{route('login.form')}}" style="color:rgb(54, 54, 204)">Đăng nhập</a> hoặc <a style="color:blue" href="{{route('register.form')}}">Đăng ký</a>

																</p>
																<!--/ End Form -->
																@endauth
																	</div>
																</div>

																<div class="ratting-main">
																	<div class="avg-ratting">
																		{{-- @php
																			$rate=0;
																			foreach($product_detail->rate as $key=>$rate){
																				$rate +=$rate
																			}
																		@endphp --}}
																		<h4>{{ceil($product_detail->getReview->avg('rate'))}} <span>(Tổng quan)</span></h4>
																		<span>Dựa trên {{$product_detail->getReview->count()}} bình luận</span>
																	</div>
																	@foreach($product_detail['getReview'] as $data)
																	<!-- Single Rating -->
																	<div class="single-rating">
																		<div class="rating-author">
																			@if($data->user_info['photo'])
																			<img src="{{$data->user_info['photo']}}" alt="{{$data->user_info['photo']}}">
																			@else
																			<img src="{{asset('backend/img/avatar.png')}}" alt="Profile.jpg">
																			@endif
																		</div>
																		<div class="rating-des">
																			<h6>{{$data->user_info['name']}}</h6>
																			<div class="ratings">

																				<ul class="rating">
																					@for($i=1; $i<=5; $i++)
																						@if($data->rate>=$i)
																							<li><i class="fa fa-star"></i></li>
																						@else
																							<li><i class="fa fa-star-o"></i></li>
																						@endif
																					@endfor
																				</ul>
																				<div class="rate-count">(<span>{{$data->rate}}</span>)</div>
																			</div>
																			<p>{{$data->review}}</p>
																		</div>
																	</div>
																	<!--/ End Single Rating -->
																	@endforeach
																</div>

																<!--/ End Review -->

															</div>
														</div>
													</div>
												</div>
												<!--/ End Reviews Tab -->
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
		</section>
		<!--/ End Shop Single -->

		<!-- Start Most Popular -->
	@if($relatedProducts->count() > 0)
	<div class="product-area most-popular related-product section">
        <div class="container">
            <div class="row">
				<div class="col-12">
					<div class="section-title">
						<h2>Sản Phẩm Liên Quan</h2>
					</div>
				</div>
            </div>
            <div class="row">
                {{-- {{$product_detail->rel_prods}} --}}
                <div class="col-12">
                    <div class="{{ $useRelatedCarousel ? 'owl-carousel popular-slider related-product__carousel' : 'related-product__static' }}">
                        @foreach($relatedProducts as $data)
                                <!-- Start Single Product -->
                                <div class="single-product">
                                    <div class="product-img">
										<a href="{{route('product-detail',$data->slug)}}">
											@php
												$photo=explode(',',$data->photo);
											@endphp
                                            <img class="default-img" src="{{$photo[0]}}" alt="{{$photo[0]}}">
                                            <img class="hover-img" src="{{$photo[0]}}" alt="{{$photo[0]}}">
                                            <span class="price-dec">{{$data->discount}} % Off</span>
                                                                    {{-- <span class="out-of-stock">Hot</span> --}}
                                        </a>
                                        <div class="button-head">
                                            <div class="product-action">
                                                <a data-toggle="modal" data-target="#modelExample" title="Quick View" href="#"><i class=" ti-eye"></i><span>Mua Ngay</span></a>
                                                <a title="Wishlist" href="#"><i class=" ti-heart "></i><span>Thêm vào danh sách yêu thích</span></a>
                                                <a title="Compare" href="#"><i class="ti-bar-chart-alt"></i><span>So Sánh</span></a>
                                            </div>
                                            <div class="product-action-2">
                                                <a title="Add to cart" href="#">Thêm vào giỏ hàng</a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="product-content">
                                        <h3><a href="{{route('product-detail',$data->slug)}}">{{$data->title}}</a></h3>
                                        <div class="product-price">
                                            @php
                                                $after_discount=($data->price-(($data->discount*$data->price)/100));
                                            @endphp
                                            <span class="old">{{number_format($data->price,0)}}đ</span>
                                            <span>{{number_format($after_discount,0)}}đ</span>
                                        </div>

                                    </div>
                                </div>
                                <!-- End Single Product -->

                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
	@endif
	<!-- End Most Popular Area -->


  <!-- Modal -->
  <div class="modal fade" id="modelExample" tabindex="-1" role="dialog">
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
                                    <div class="single-slider">
                                        <img src="images/modal1.png" alt="#">
                                    </div>
                                    <div class="single-slider">
                                        <img src="images/modal2.png" alt="#">
                                    </div>
                                    <div class="single-slider">
                                        <img src="images/modal3.png" alt="#">
                                    </div>
                                    <div class="single-slider">
                                        <img src="images/modal4.png" alt="#">
                                    </div>
                                </div>
                            </div>
                        <!-- End Product slider -->
                    </div>
                    <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
                        <div class="quickview-content">
                            <h2>Đầm suông xòe</h2>
                            <div class="quickview-ratting-review">
                                <div class="quickview-ratting-wrap">
                                    <div class="quickview-ratting">
                                        <i class="yellow fa fa-star"></i>
                                        <i class="yellow fa fa-star"></i>
                                        <i class="yellow fa fa-star"></i>
                                        <i class="yellow fa fa-star"></i>
                                        <i class="fa fa-star"></i>
                                    </div>
                                    <a href="#"> (1 customer review)</a>
                                </div>
                                <div class="quickview-stock">
                                    <span><i class="fa fa-check-circle-o"></i> in stock</span>
                                </div>
                            </div>
                            <h3>$29.00</h3>
                            <div class="quickview-peragraph">
                                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Mollitia iste laborum ad impedit pariatur esse optio tempora sint ullam autem deleniti nam in quos qui nemo ipsum numquam.</p>
                            </div>
                            <div class="size">
                                <div class="row">
                                    <div class="col-lg-6 col-12">
                                        <h5 class="title">Kích cỡ</h5>
                                        <select>
                                            <option selected="selected">1 &amp; 2: 40.5 x 16.5 x 18 mm</option>
                                            <option>3: 39.79 x 18.26 x 19.21 mm</option>
                                            <option>Pro 2: 30.9 x 21.8 x 24 mm</option>
                                            
                                        </select>
                                    </div>
                                    <div class="col-lg-6 col-12">
                                        <h5 class="title">Màu sắc</h5>
                                        <select>
                                            <option selected="selected">orange</option>
                                            <option>purple</option>
                                            <option>black</option>
                                            <option>pink</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="quantity">
                                <!-- Input Order -->
                                <div class="input-group">
                                    <div class="button minus">
                                        <button type="button" class="btn btn-primary btn-number" disabled="disabled" data-type="minus" data-field="quant[1]">
                                            <i class="ti-minus"></i>
                                        </button>
									</div>
                                    <input type="text" name="qty" class="input-number"  data-min="1" data-max="1000000" value="1">
                                    <div class="button plus">
                                        <button type="button" class="btn btn-primary btn-number" data-type="plus" data-field="quant[1]">
                                            <i class="ti-plus"></i>
                                        </button>
                                    </div>
                                </div>
                                <!--/ End Input Order -->
                            </div>
                            <div class="add-to-cart">
                                <a href="#" class="btn">Add to cart</a>
                                <a href="#" class="btn min"><i class="ti-heart"></i></a>
                                <a href="#" class="btn min"><i class="fa fa-compress"></i></a>
                            </div>
                            <div class="default-social">
                                <h4 class="share-now">Chia sẻ:</h4>
                                <ul>
                                    <li><a class="facebook" href="#"><i class="fa fa-facebook"></i></a></li>
                                    <li><a class="twitter" href="#"><i class="fa fa-twitter"></i></a></li>
                                    <li><a class="youtube" href="#"><i class="fa fa-pinterest-p"></i></a></li>
                                    <li><a class="dribbble" href="#"><i class="fa fa-google-plus"></i></a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Modal end -->

@endsection
@push('styles')
	<style>
		.shop.single.section{
			background:
				radial-gradient(circle at top left, rgba(125, 211, 252, 0.12), transparent 32%),
				radial-gradient(circle at top right, rgba(247, 148, 29, 0.1), transparent 26%),
				linear-gradient(180deg, #f7fbff 0%, #fff7ef 100%);
			overflow: hidden;
		}

		.product-detail__stage-col,
		.product-detail__info-col{
			margin-bottom: 24px;
			min-width: 0;
		}

		.product-showcase{
			position: relative;
			padding: 18px 10px 10px 0;
		}

		.product-showcase__aura{
			position: absolute;
			border-radius: 50%;
			filter: blur(16px);
			opacity: 0.8;
			pointer-events: none;
		}

		.product-showcase__aura--cyan{
			width: 180px;
			height: 180px;
			top: 10px;
			left: 10px;
			background: rgba(34, 211, 238, 0.25);
		}

		.product-showcase__aura--orange{
			width: 200px;
			height: 200px;
			right: 20px;
			bottom: 30px;
			background: rgba(247, 148, 29, 0.18);
		}

		.product-showcase__panel{
			--tilt-x: 0deg;
			--tilt-y: 0deg;
			--glow-x: 50%;
			--glow-y: 32%;
			position: relative;
			padding: 24px;
			border-radius: 34px;
			background:
				radial-gradient(circle at var(--glow-x) var(--glow-y), rgba(125, 211, 252, 0.22), transparent 30%),
				linear-gradient(160deg, #07111f 0%, #12304e 52%, #f7fbff 52%, #fffdf9 100%);
			border: 1px solid rgba(125, 211, 252, 0.18);
			box-shadow:
				0 34px 70px rgba(15, 23, 42, 0.22),
				0 16px 36px rgba(148, 163, 184, 0.18);
			overflow: hidden;
			transform-style: preserve-3d;
			transform: perspective(1600px) rotateX(var(--tilt-x)) rotateY(var(--tilt-y));
			transition: transform 0.22s ease, box-shadow 0.22s ease;
		}

		.product-showcase__panel:hover{
			box-shadow:
				0 42px 86px rgba(15, 23, 42, 0.26),
				0 22px 44px rgba(34, 211, 238, 0.12);
		}

		.product-showcase__panel::before{
			content: '';
			position: absolute;
			inset: 0 0 auto 0;
			height: 52%;
			background:
				linear-gradient(180deg, rgba(125, 211, 252, 0.12), rgba(125, 211, 252, 0)),
				repeating-linear-gradient(
					90deg,
					rgba(125, 211, 252, 0.08) 0,
					rgba(125, 211, 252, 0.08) 1px,
					transparent 1px,
					transparent 28px
				),
				repeating-linear-gradient(
					180deg,
					rgba(125, 211, 252, 0.06) 0,
					rgba(125, 211, 252, 0.06) 1px,
					transparent 1px,
					transparent 24px
				);
			pointer-events: none;
		}

		.product-showcase__grid{
			position: absolute;
			inset: 0;
			background: linear-gradient(180deg, rgba(255,255,255,0.02), transparent 70%);
			pointer-events: none;
		}

		.product-showcase__topbar,
		.product-showcase__stats,
		.product-gallery--immersive{
			position: relative;
			z-index: 1;
		}

		.product-showcase__topbar{
			display: flex;
			align-items: center;
			justify-content: space-between;
			gap: 16px;
			margin-bottom: 18px;
		}

		.product-showcase__label-group{
			display: flex;
			flex-wrap: wrap;
			gap: 8px;
		}

		.product-showcase__label{
			padding: 8px 12px;
			border-radius: 999px;
			background: rgba(255, 255, 255, 0.1);
			border: 1px solid rgba(125, 211, 252, 0.16);
			color: #d8f7ff;
			font-size: 11px;
			font-weight: 700;
			letter-spacing: 0.14em;
			text-transform: uppercase;
		}

		.product-showcase__label--accent{
			background: rgba(247, 148, 29, 0.16);
			border-color: rgba(247, 148, 29, 0.26);
			color: #ffe3c1;
		}

		.product-showcase__signal{
			display: inline-flex;
			align-items: center;
			gap: 6px;
		}

		.product-showcase__signal span{
			width: 8px;
			height: 8px;
			border-radius: 50%;
			background: #22d3ee;
			box-shadow: 0 0 12px rgba(34, 211, 238, 0.4);
			animation: product-showcase-pulse 1.4s infinite ease-in-out;
		}

		.product-showcase__signal span:nth-child(2){
			animation-delay: 0.12s;
		}

		.product-showcase__signal span:nth-child(3){
			animation-delay: 0.24s;
		}

		.product-showcase__stats{
			display: grid;
			grid-template-columns: repeat(3, minmax(0, 1fr));
			gap: 12px;
			margin-bottom: 22px;
		}

		.product-showcase__stat{
			padding: 14px 14px 13px;
			border-radius: 18px;
			background: rgba(255, 255, 255, 0.9);
			border: 1px solid rgba(203, 213, 225, 0.9);
			box-shadow: 0 14px 24px rgba(148, 163, 184, 0.14);
		}

		.product-showcase__stat strong,
		.product-showcase__stat small{
			display: block;
		}

		.product-showcase__stat strong{
			color: #0f172a;
			font-size: 14px;
			line-height: 1.35;
		}

		.product-showcase__stat small{
			margin-top: 4px;
			color: #64748b;
			font-size: 11px;
			text-transform: uppercase;
			letter-spacing: 0.08em;
		}

		.product-gallery--immersive{
			padding: 26px 22px 18px;
			border-radius: 28px;
			background:
				radial-gradient(circle at 50% 34%, rgba(255,255,255,0.98), rgba(241, 245, 249, 0.94) 42%, rgba(226, 232, 240, 0.84) 100%);
			border: 1px solid rgba(226, 232, 240, 0.94);
			box-shadow:
				inset 0 1px 0 rgba(255, 255, 255, 0.7),
				0 28px 46px rgba(148, 163, 184, 0.18);
			transform: translateZ(26px);
		}

		.product-gallery__orbit{
			position: absolute;
			border-radius: 50%;
			border: 1px solid rgba(34, 211, 238, 0.2);
			pointer-events: none;
		}

		.product-gallery__orbit--one{
			width: 240px;
			height: 240px;
			top: 44px;
			left: 50%;
			transform: translateX(-50%);
		}

		.product-gallery__orbit--two{
			width: 300px;
			height: 300px;
			top: 14px;
			left: 50%;
			transform: translateX(-50%);
			opacity: 0.55;
		}

		.product-gallery__caption{
			display: flex;
			flex-direction: column;
			gap: 3px;
			margin-bottom: 18px;
			color: #0f172a;
			text-align: center;
		}

		.product-gallery__caption span{
			font-size: 11px;
			font-weight: 700;
			letter-spacing: 0.14em;
			text-transform: uppercase;
			color: #0f766e;
		}

		.product-gallery__caption strong{
			font-size: 16px;
			line-height: 1.35;
		}

		.product-gallery--immersive .flexslider-thumbnails{
			background: transparent;
			border: 0;
		}

		.product-gallery--immersive .slides li{
			background: transparent;
		}

		.product-gallery__image-shell{
			position: relative;
			display: flex;
			align-items: center;
			justify-content: center;
			min-height: 430px;
			padding: 24px 18px 16px;
			border-radius: 28px;
			overflow: hidden;
			isolation: isolate;
		}

		.product-gallery__image-shell::before{
			content: "";
			position: absolute;
			inset: 12% 10% 14%;
			background-image: var(--gallery-image);
			background-position: center;
			background-repeat: no-repeat;
			background-size: cover;
			filter: blur(30px) saturate(1.15);
			opacity: 0.18;
			transform: scale(1.08);
		}

		.product-gallery__image-shell::after{
			content: "";
			position: absolute;
			inset: 0;
			border-radius: inherit;
			background: radial-gradient(circle at center, rgba(255, 255, 255, 0.18), transparent 62%);
			opacity: 0.7;
			pointer-events: none;
		}

		.product-gallery__image-shell--enhanced::before{
			opacity: 0.24;
			filter: blur(34px) saturate(1.2);
		}

		.product-gallery__image-shell--enhanced{
			padding: 34px 24px 24px;
		}

		.product-gallery__image{
			position: relative;
			z-index: 1;
			display: block;
			width: auto;
			max-width: 100%;
			max-height: 430px;
			object-fit: contain;
			filter: drop-shadow(0 26px 38px rgba(15, 23, 42, 0.26));
			transform: translateY(0) scale(1);
			transition: transform 0.24s ease, filter 0.24s ease;
		}

		.product-gallery__image--enhanced{
			width: min(100%, var(--gallery-image-display-width, 280px));
			max-width: min(100%, var(--gallery-image-display-width, 280px));
			border-radius: 20px;
			box-shadow:
				0 18px 30px rgba(15, 23, 42, 0.18),
				0 0 0 1px rgba(226, 232, 240, 0.95);
			filter: contrast(1.04) saturate(1.03) drop-shadow(0 24px 34px rgba(15, 23, 42, 0.24));
		}

		.product-showcase__panel:hover .product-gallery__image{
			transform: translateY(-6px) scale(1.02);
			filter: drop-shadow(0 34px 46px rgba(15, 23, 42, 0.3));
		}

		.product-showcase__panel:hover .product-gallery__image--enhanced{
			transform: translateY(-2px) scale(1);
			filter: contrast(1.04) saturate(1.03) drop-shadow(0 26px 36px rgba(15, 23, 42, 0.24));
		}

		.product-gallery--immersive .flex-control-thumbs{
			display: flex;
			flex-wrap: wrap;
			justify-content: center;
			gap: 12px;
			margin-top: 18px;
		}

		.product-gallery--immersive .flex-control-thumbs li{
			float: none;
			width: 82px;
			margin: 0;
		}

		.product-gallery--immersive .flex-control-thumbs img{
			height: 82px;
			width: 100%;
			object-fit: cover;
			border-radius: 18px;
			border: 1px solid rgba(148, 163, 184, 0.32);
			background: #fff;
			box-shadow: 0 14px 24px rgba(148, 163, 184, 0.14);
			transition: transform 0.2s ease, box-shadow 0.2s ease, border-color 0.2s ease;
		}

		.product-gallery--immersive .flex-control-thumbs img:hover,
		.product-gallery--immersive .flex-control-thumbs .flex-active{
			transform: translateY(-4px);
			border-color: rgba(34, 211, 238, 0.46);
			box-shadow: 0 18px 28px rgba(34, 211, 238, 0.16);
		}

		.product-detail__info-card{
			height: 100%;
			min-width: 0;
			padding: 32px;
			border-radius: 34px;
			background: rgba(255, 255, 255, 0.94);
			border: 1px solid rgba(226, 232, 240, 0.95);
			box-shadow: 0 28px 52px rgba(148, 163, 184, 0.18);
		}

		.product-detail__info-card .short h4{
			font-size: 34px;
			line-height: 1.08;
			margin-bottom: 18px;
			letter-spacing: -0.03em;
		}

		.product-detail__info-card .price{
			margin-top: 14px;
			margin-bottom: 18px;
		}

		.product-detail__info-card .price .discount{
			font-size: 32px;
			font-weight: 700;
		}

		.product-detail__info-card .description{
			font-size: 16px;
			line-height: 1.8;
			color: #475569;
		}

		.product-detail__info-card .product-buy{
			margin-top: 24px;
			padding-top: 24px;
			border-top: 1px solid rgba(226, 232, 240, 0.9);
		}

		.product-color-button{
			display: inline-flex;
			align-items: center;
			gap: 8px;
			padding: 6px 10px;
			border: 1px solid #cbd5e1;
			border-radius: 8px;
			background: #fff;
			color: #334155;
			cursor: pointer;
			transition: border-color .15s ease, box-shadow .15s ease;
		}

		.product-color-button span{
			display: inline-block;
			width: 24px;
			height: 24px;
			border: 1px solid #cbd5e1;
			border-radius: 50%;
		}

		.product-color-button.is-selected{
			border-color: #0f766e;
			box-shadow: 0 0 0 2px rgba(15, 118, 110, .18);
		}

		.related-product__static{
			display: flex;
			flex-wrap: wrap;
			justify-content: center;
			gap: 28px;
		}

		.related-product__static .single-product{
			width: min(100%, 340px);
			margin-top: 0;
		}

		.related-product__carousel .owl-stage{
			display: flex;
		}

		.related-product__carousel .owl-item{
			display: flex;
			height: auto;
		}

		.related-product__carousel .single-product{
			width: 100%;
		}

		.product-specs{
			margin-top: 14px;
			width: min(100%, 560px);
			padding: 18px;
			border-radius: 24px;
			background:
				linear-gradient(135deg, rgba(15, 23, 42, 0.05), rgba(14, 165, 233, 0.08)),
				rgba(255, 255, 255, 0.86);
			border: 1px solid rgba(186, 230, 253, 0.95);
			box-shadow: 0 20px 40px rgba(148, 163, 184, 0.16);
		}

		.product-specs__header{
			display: flex;
			align-items: center;
			justify-content: flex-start;
			margin-bottom: 14px;
		}

		.product-specs__hint{
			display: inline-flex;
			align-items: center;
			padding: 8px 12px;
			border-radius: 999px;
			background: rgba(12, 74, 110, 0.08);
			color: #0f766e;
			font-size: 11px;
			font-weight: 700;
			letter-spacing: 0.14em;
			text-transform: uppercase;
		}

		.product-spec-grid{
			display: grid;
			grid-template-columns: repeat(2, minmax(0, 1fr));
			align-items: stretch;
			width: 100%;
			gap: 14px;
		}

		.product-spec-grid > *{
			min-width: 0;
		}

		.product-spec-card,
		.product-spec-chip{
			position: relative;
			overflow: hidden;
			border-radius: 18px;
			border: 1px solid rgba(191, 219, 254, 0.95);
			background: rgba(255, 255, 255, 0.96);
			box-shadow: 0 16px 34px rgba(148, 163, 184, 0.14);
		}

		.product-spec-card{
			padding: 16px 16px 18px;
		}

		.product-spec-card::before,
		.product-spec-chip::before{
			content: "";
			position: absolute;
			top: 0;
			left: 0;
			right: 0;
			height: 3px;
			background: linear-gradient(90deg, #06b6d4, #f59e0b);
		}

		.product-spec-card__label{
			display: block;
			margin-bottom: 8px;
			color: #0f766e;
			font-size: 11px;
			font-weight: 700;
			letter-spacing: 0.16em;
			text-transform: uppercase;
		}

		.product-spec-card__value{
			display: block;
			color: #0f172a;
			font-size: 17px;
			font-weight: 700;
			line-height: 1.55;
			word-break: break-word;
		}

		.product-spec-chip{
			display: flex;
			align-items: center;
			justify-content: center;
			padding: 16px;
			color: #0f172a;
			font-size: 15px;
			font-weight: 600;
			text-align: center;
		}

		/* Rating */
		.rating_box {
		display: inline-flex;
		}

		.star-rating {
		font-size: 0;
		padding-left: 10px;
		padding-right: 10px;
		}

		.star-rating__wrap {
		display: inline-block;
		font-size: 1rem;
		}

		.star-rating__wrap:after {
		content: "";
		display: table;
		clear: both;
		}

		.star-rating__ico {
		float: right;
		padding-left: 2px;
		cursor: pointer;
		color: #F7941D;
		font-size: 16px;
		margin-top: 5px;
		}

		.star-rating__ico:last-child {
		padding-left: 0;
		}

		.star-rating__input {
		display: none;
		}

		.star-rating__ico:hover:before,
		.star-rating__ico:hover ~ .star-rating__ico:before,
		.star-rating__input:checked ~ .star-rating__ico:before {
		content: "\F005";
		}

		@keyframes product-showcase-pulse {
			0%,
			80%,
			100% {
				transform: scale(0.7);
				opacity: 0.45;
			}

			40% {
				transform: scale(1);
				opacity: 1;
			}
		}

		@media (max-width: 1199.98px){
			.product-detail__info-card{
				padding: 28px;
			}

			.product-detail__info-card .short h4{
				font-size: 30px;
			}
		}

		@media (max-width: 991.98px){
			.product-showcase{
				padding-right: 0;
			}

			.product-showcase__stats{
				grid-template-columns: repeat(2, minmax(0, 1fr));
			}

			.product-detail__info-card{
				margin-top: 8px;
			}
		}

		@media (max-width: 575.98px){
			.product-showcase__panel{
				padding: 18px;
				border-radius: 26px;
			}

			.product-showcase__topbar{
				flex-direction: column;
				align-items: flex-start;
			}

			.product-showcase__stats{
				grid-template-columns: 1fr;
			}

			.product-gallery--immersive{
				padding: 20px 14px 16px;
			}

			.product-gallery__image-shell{
				min-height: 320px;
				padding: 16px 10px 10px;
			}

			.product-gallery__image-shell--enhanced{
				padding: 20px 12px 12px;
			}

			.product-gallery__image{
				max-height: 320px;
			}

			.product-gallery__image--enhanced{
				width: min(100%, var(--gallery-image-display-width, 220px));
				max-width: min(100%, var(--gallery-image-display-width, 220px));
			}

			.product-gallery--immersive .flex-control-thumbs li{
				width: 64px;
			}

			.product-gallery--immersive .flex-control-thumbs img{
				height: 64px;
				border-radius: 14px;
			}

			.product-detail__info-card{
				padding: 22px;
				border-radius: 24px;
			}

			.product-detail__info-card .short h4{
				font-size: 26px;
			}

			.product-detail__info-card .price .discount{
				font-size: 28px;
			}

			.product-specs{
				padding: 16px;
				border-radius: 20px;
			}

			.product-specs__header{
				justify-content: flex-start;
			}

			.product-spec-grid{
				gap: 12px;
			}

			.product-spec-grid > *{
				flex-basis: 210px;
			}

			.product-spec-card__value{
				font-size: 16px;
			}
		}

	</style>
@endpush
@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>

    {{-- <script>
        $('.cart').click(function(){
            var quantity=$('#quantity').val();
            var pro_id=$(this).data('id');
            // alert(quantity);
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
							document.location.href=document.location.href;
						});
                    }
                }
            })
        });
    </script> --}}

<script>
	document.addEventListener('DOMContentLoaded', function () {
		var tiltCards = document.querySelectorAll('.js-product-tilt');

		Array.prototype.forEach.call(tiltCards, function (card) {
			function resetTilt() {
				card.style.setProperty('--tilt-x', '0deg');
				card.style.setProperty('--tilt-y', '0deg');
				card.style.setProperty('--glow-x', '50%');
				card.style.setProperty('--glow-y', '32%');
			}

			function updateTilt(event) {
				if (window.innerWidth < 992) {
					resetTilt();
					return;
				}

				var rect = card.getBoundingClientRect();
				var pointerX = (event.clientX - rect.left) / rect.width;
				var pointerY = (event.clientY - rect.top) / rect.height;
				var rotateY = (pointerX - 0.5) * 12;
				var rotateX = (0.5 - pointerY) * 10;

				card.style.setProperty('--tilt-x', rotateX.toFixed(2) + 'deg');
				card.style.setProperty('--tilt-y', rotateY.toFixed(2) + 'deg');
				card.style.setProperty('--glow-x', (pointerX * 100).toFixed(2) + '%');
				card.style.setProperty('--glow-y', (pointerY * 100).toFixed(2) + '%');
			}

			card.addEventListener('mousemove', updateTilt);
			card.addEventListener('mouseleave', resetTilt);
			card.addEventListener('blur', resetTilt);
			window.addEventListener('resize', resetTilt);

			resetTilt();
		});

		$('.product-color-button').on('click', function () {
			$('.product-color-button').removeClass('is-selected');
			$(this).addClass('is-selected');
			$('#selected-color-code').val($(this).data('color-code'));
		});
	});
</script>
@endpush
