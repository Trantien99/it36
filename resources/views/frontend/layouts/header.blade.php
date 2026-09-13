@php
    $searchSuggestions = [
        [
            'label' => 'Sony chống ồn',
            'value' => 'tai nghe Sony chống ồn',
            'meta' => 'ANC, chụp tai cao cấp',
        ],
        [
            'label' => 'Tai nghe Bluetooth',
            'value' => 'tai nghe bluetooth',
            'meta' => 'Không dây, dùng hằng ngày',
        ],
        [
            'label' => 'Tai nghe gaming',
            'value' => 'tai nghe gaming',
            'meta' => 'Mic rõ, độ trễ thấp',
        ],
        [
            'label' => 'Dưới 1 triệu',
            'value' => 'tai nghe dưới 1 triệu',
            'meta' => 'Tìm nhanh theo ngân sách',
        ],
        [
            'label' => 'Tai nghe SoundPEATS',
            'value' => 'tai nghe SoundPEATS',
            'meta' => 'True wireless phổ biến',
        ],
        [
            'label' => 'DAC USB-C',
            'value' => 'dac usb-c tai nghe',
            'meta' => 'Phụ kiện âm thanh',
        ],
    ];
@endphp

<style>
    .search-suggestion-box{
        position: relative;
        width: 100%;
    }

    .header.shop .search-bar .search-suggestion-box{
        float: left;
        width: calc(100% - 150px);
    }

    .header.shop .search-bar .search-suggestion-box form{
        display: block;
        float: none;
        width: 100%;
        position: relative;
    }

    .header.shop .search-bar .search-suggestion-box input{
        width: 100%;
        padding-right: 78px;
    }

    .header.shop .search-bar .search-suggestion-box .btnn{
        right: 0;
        top: 0;
    }

    .search-suggestions{
        position: absolute;
        top: calc(100% + 12px);
        left: 0;
        width: 100%;
        padding: 18px;
        border-radius: 24px;
        border: 1px solid rgba(247, 148, 29, 0.14);
        background: #fff;
        box-shadow: 0 24px 50px rgba(15, 23, 42, 0.14);
        opacity: 0;
        visibility: hidden;
        transform: translateY(10px);
        transition: opacity 0.18s ease, transform 0.18s ease, visibility 0.18s ease;
        z-index: 30;
    }

    .search-suggestion-box.is-open .search-suggestions{
        opacity: 1;
        visibility: visible;
        transform: translateY(0);
    }

    .search-suggestions__eyebrow{
        display: block;
        margin-bottom: 6px;
        color: #f7941d;
        font-size: 11px;
        font-weight: 700;
        letter-spacing: 0.08em;
        text-transform: uppercase;
    }

    .search-suggestions__title{
        margin: 0;
        color: #2f2f2f;
        font-size: 15px;
        font-weight: 600;
    }

    .search-suggestions__grid{
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 10px;
        margin-top: 14px;
    }

    .search-suggestion{
        display: block;
        width: 100%;
        padding: 12px 14px;
        text-align: left;
        border: 1px solid rgba(226, 232, 240, 0.95);
        border-radius: 18px;
        background: linear-gradient(135deg, rgba(255, 255, 255, 0.98), rgba(248, 250, 252, 0.96));
        transition: transform 0.18s ease, box-shadow 0.18s ease, border-color 0.18s ease;
        cursor: pointer;
    }

    .search-suggestion:hover,
    .search-suggestion:focus{
        outline: none;
        transform: translateY(-1px);
        border-color: rgba(247, 148, 29, 0.32);
        box-shadow: 0 16px 28px rgba(247, 148, 29, 0.12);
    }

    .search-suggestion__label{
        display: block;
        color: #2f2f2f;
        font-size: 14px;
        font-weight: 600;
        line-height: 1.35;
    }

    .search-suggestion__meta{
        display: block;
        margin-top: 4px;
        color: #6b7280;
        font-size: 12px;
        line-height: 1.4;
    }

    .search-suggestions__hint,
    .search-suggestions__empty{
        margin-top: 14px;
        color: #6b7280;
        font-size: 12px;
        line-height: 1.5;
    }

    .search-suggestions__empty{
        display: none;
    }

    .search-top .search-suggestions{
        left: -128px;
        top: 96px;
        min-width: 280px;
        max-width: 340px;
    }

    .search-top .search-suggestions__grid{
        grid-template-columns: 1fr;
    }

    .action-inline-form{
        display: inline;
        margin: 0;
    }

    .action-button-reset{
        background: transparent;
        border: 0;
        padding: 0;
        margin: 0;
        color: inherit;
        font: inherit;
        cursor: pointer;
    }

    .header.shop .list-main li .action-button-reset{
        color: #333;
    }

    .header.shop .list-main li .action-button-reset:hover{
        color: #F7941D;
    }

    .single-product .product-img .product-action .action-inline-form{
        display: inline-block;
        margin-right: 15px;
    }

    .single-product .product-img .product-action .action-inline-form:last-child{
        margin-right: 0;
    }

    .single-product .product-img .product-action .action-button-reset{
        background-color: transparent;
        color: #333;
        display: inline-block;
        font-size: 16px;
        text-align: right;
        height: 52px;
        position: relative;
        top: 2px;
    }

    .single-product .product-img .product-action .action-button-reset i{
        line-height: 40px;
    }

    .single-product .product-img .product-action .action-button-reset span{
        visibility: hidden;
        position: absolute;
        background: #F7941D !important;
        color: #fff !important;
        text-align: center;
        padding: 5px 12px;
        z-index: 3;
        opacity: 0;
        transition: opacity .6s, margin .3s;
        font-size: 11px;
        right: 0;
        line-height: 14px;
        top: -12px;
        margin-top: -5px;
        margin-right: 0;
        display: inline-block;
        width: 120px;
        border-radius: 15px 0 0 15px;
    }

    .single-product .product-img .button-head .product-action .action-button-reset span::after{
        position: absolute;
        content: "";
        right: 0;
        bottom: -12px;
        border: 6px solid #F7941D;
        border-left: 0 solid transparent;
        border-right: 6px solid transparent;
        border-bottom: 6px solid transparent;
    }

    .single-product .product-img .product-action .action-button-reset:hover{
        color: #F7941D;
    }

    .single-product .product-img .product-action .action-button-reset:hover span{
        visibility: visible;
        opacity: 1;
        color: #333;
        background: #fff;
        margin-top: -12px;
    }

    .single-product .product-img .product-action-2 .action-inline-form{
        display: inline-block;
    }

    .single-product .product-img .product-action-2 .action-button-reset{
        display: inline-block;
        background-color: transparent;
        color: #333;
        text-align: left;
        font-size: 12px;
        font-weight: 600;
        text-transform: uppercase;
        line-height: 1;
    }

    .single-product .product-img .product-action-2 .action-button-reset:hover{
        color: #F7941D;
    }

    @media (max-width: 991.98px){
        .search-suggestions{
            width: 100%;
        }

        .search-suggestions__grid{
            grid-template-columns: 1fr;
        }
    }
</style>

<header class="header shop">
    <!-- Topbar -->
    <div class="topbar">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 col-md-12 col-12">
                    <!-- Top Left -->
                    <div class="top-left">
                        <ul class="list-main">
                            @php
                                $settings=DB::table('settings')->get();

                            @endphp
                            <li><i class="ti-headphone-alt"></i>@foreach($settings as $data) {{$data->phone}} @endforeach</li>
                            <li><i class="ti-email"></i> @foreach($settings as $data) {{$data->email}} @endforeach</li>
                        </ul>
                    </div>
                    <!--/ End Top Left -->
                </div>
                <div class="col-lg-6 col-md-12 col-12">
                    <!-- Top Right -->
                    <div class="right-content">
                        <ul class="list-main">
                        <li><i class="ti-location-pin"></i> <a href="{{route('order.track')}}">Trạng thái đơn hàng</a></li>
                            {{-- <li><i class="ti-alarm-clock"></i> <a href="#">Daily deal</a></li> --}}
                            @auth
                                @if(Auth::user()->role=='admin')
                                    <li><i class="ti-user"></i> <a href="{{route('admin')}}"  target="_blank">Quản lý tài khoản</a></li>
                                @else
                                    <li><i class="ti-user"></i> <a href="{{route('user')}}"  target="_blank">Quản lý tài khoản</a></li>
                                @endif
                                <li><i class="ti-power-off"></i>
    <form action="{{route('user.logout')}}" method="POST" class="action-inline-form">
        @csrf
        <button type="submit" class="action-button-reset">&#272;&#259;ng xu&#7845;t</button>
    </form>
</li>

                            @else
                                <li><i class="ti-power-off"></i><a href="{{route('login.form')}}">Đăng nhập /</a> <a href="{{route('register.form')}}">Đăng ký</a></li>
                            @endauth
                        </ul>
                    </div>
                    <!-- End Top Right -->
                </div>
            </div>
        </div>
    </div>
    <!-- End Topbar -->
    <div class="middle-inner">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-2 col-md-2 col-12">
                    <!-- Logo -->
                    <div class="logo">
                        @php
                            $settings=DB::table('settings')->get();
                        @endphp
                        <a href="{{route('home')}}"><img src="@foreach($settings as $data) {{$data->logo}} @endforeach" alt="logo"></a>
                    </div>
                    <!--/ End Logo -->
                    <!-- Search Form -->
                    <div class="search-top">
                        <div class="top-search"><a href="#0"><i class="ti-search"></i></a></div>
                        <!-- Search Form -->
                        <div class="search-top">
                            <div class="search-suggestion-box js-search-suggestion-box">
                                <form class="search-form" method="GET" action="{{route('product.search')}}">
                                    <input class="js-search-input" type="text" placeholder="Search here..." name="search" value="{{request('search')}}" autocomplete="off">
                                    <button value="search" type="submit"><i class="ti-search"></i></button>
                                </form>
                                <div class="search-suggestions js-search-suggestions" aria-hidden="true">
                                    <span class="search-suggestions__eyebrow">Gợi ý nhanh</span>
                                    <p class="search-suggestions__title">Click hoặc rê chuột để chọn tìm kiếm phổ biến</p>
                                    <div class="search-suggestions__grid">
                                        @foreach($searchSuggestions as $suggestion)
                                            <button
                                                class="search-suggestion js-search-suggestion"
                                                type="button"
                                                data-search-value="{{$suggestion['value']}}"
                                                data-search-text="{{ \Illuminate\Support\Str::lower(\Illuminate\Support\Str::ascii($suggestion['label'].' '.$suggestion['value'].' '.$suggestion['meta'])) }}"
                                            >
                                                <span class="search-suggestion__label">{{$suggestion['label']}}</span>
                                                <span class="search-suggestion__meta">{{$suggestion['meta']}}</span>
                                            </button>
                                        @endforeach
                                    </div>
                                    <div class="search-suggestions__hint">Mẹo: bạn có thể gõ như "Sony chống ồn", "bluetooth", "gaming", "dưới 1 triệu".</div>
                                    <div class="search-suggestions__empty js-search-empty">Chưa có gợi ý khớp. Bạn nhấn Enter để tìm trực tiếp.</div>
                                </div>
                            </div>
                        </div>
                        <!--/ End Search Form -->
                    </div>
                    <!--/ End Search Form -->
                    <div class="mobile-nav"></div>
                </div>
                <div class="col-lg-8 col-md-7 col-12">
                    <div class="search-bar-top">
                        <div class="search-bar">
                            <select>
                                <option >Tất cả</option>
                                @foreach(Helper::getAllCategory() as $cat)
                                    <option>{{$cat->title}}</option>
                                @endforeach
                            </select>
                            <div class="search-suggestion-box js-search-suggestion-box">
                                <form method="GET" action="{{route('product.search')}}">
                                    <input class="js-search-input" name="search" placeholder="Nhập tên sản phẩm......" type="search" value="{{request('search')}}" autocomplete="off">
                                    <button class="btnn" type="submit"><i class="ti-search"></i></button>
                                </form>
                                <div class="search-suggestions js-search-suggestions" aria-hidden="true">
                                    <span class="search-suggestions__eyebrow">Gợi ý thông minh</span>
                                    <p class="search-suggestions__title">Rê chuột hoặc click vào ô để tìm nhanh theo nhu cầu</p>
                                    <div class="search-suggestions__grid">
                                        @foreach($searchSuggestions as $suggestion)
                                            <button
                                                class="search-suggestion js-search-suggestion"
                                                type="button"
                                                data-search-value="{{$suggestion['value']}}"
                                                data-search-text="{{ \Illuminate\Support\Str::lower(\Illuminate\Support\Str::ascii($suggestion['label'].' '.$suggestion['value'].' '.$suggestion['meta'])) }}"
                                            >
                                                <span class="search-suggestion__label">{{$suggestion['label']}}</span>
                                                <span class="search-suggestion__meta">{{$suggestion['meta']}}</span>
                                            </button>
                                        @endforeach
                                    </div>
                                    <div class="search-suggestions__hint">Bạn cũng có thể gõ tự nhiên như "tai nghe Sony chống ồn", "tai nghe bluetooth" hoặc "tai nghe dưới 1 triệu".</div>
                                    <div class="search-suggestions__empty js-search-empty">Không có gợi ý gần giống. Bạn cứ nhấn Enter để tìm toàn bộ kho sản phẩm.</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-2 col-md-3 col-12">
                    <div class="right-bar">
                        <!-- Search Form -->
                        <div class="sinlge-bar shopping">
                            @php
                                $total_prod=0;
                                $total_amount=0;
                            @endphp
                           @if(session('wishlist'))
                                @foreach(session('wishlist') as $wishlist_items)
                                    @php
                                        $total_prod+=$wishlist_items['quantity'];
                                        $total_amount+=$wishlist_items['amount'];
                                    @endphp
                                @endforeach
                           @endif
                            <a href="{{route('wishlist')}}" class="single-icon"><i class="fa fa-heart-o"></i> <span class="total-count">{{Helper::wishlistCount()}}</span></a>
                            <!-- Shopping Item -->
                            @auth
                                <div class="shopping-item">
                                    <div class="dropdown-cart-header">
                                        <span>{{count(Helper::getAllProductFromWishlist())}} Sản phẩm</span>
                                        <a href="{{route('wishlist')}}">Xem danh sách yêu thích</a>
                                    </div>
                                    <ul class="shopping-list">
                                        {{-- {{Helper::getAllProductFromCart()}} --}}
                                            @foreach(Helper::getAllProductFromWishlist() as $data)
                                                    @php
                                                        $photo=explode(',',$data->product['photo']);
                                                    @endphp
                                                    <li>
                                                        <form action="{{route('wishlist-delete',$data->id)}}" method="POST" class="action-inline-form">
    @csrf
    @method('DELETE')
    <button type="submit" class="remove action-button-reset" title="Remove this item"><i class="fa fa-remove"></i></button>
</form>
                                                        <a class="cart-img" href="#"><img src="{{$photo[0]}}" alt="{{$photo[0]}}"></a>
                                                        <h4><a href="{{route('product-detail',$data->product['slug'])}}" target="_blank">{{$data->product['title']}}</a></h4>
                                                        <p class="quantity">{{$data->quantity}} x - <span class="amount">${{number_format($data->price,2)}}</span></p>
                                                    </li>
                                            @endforeach
                                    </ul>
                                    <div class="bottom">
                                        <div class="total">
                                            <span>Tổng số tiền</span>
                                            <span class="total-amount">{{number_format(Helper::totalWishlistPrice(),0)}} đ</span>
                                        </div>
                                        <a href="{{route('cart')}}" class="btn animate">Giỏ Hàng</a>
                                    </div>
                                </div>
                            @endauth
                            <!--/ End Shopping Item -->
                        </div>
                        {{-- <div class="sinlge-bar">
                            <a href="{{route('wishlist')}}" class="single-icon"><i class="fa fa-heart-o" aria-hidden="true"></i></a>
                        </div> --}}
                        <div class="sinlge-bar shopping">
                            <a href="{{route('cart')}}" class="single-icon"><i class="ti-bag"></i> <span class="total-count">{{Helper::cartCount()}}</span></a>
                            <!-- Shopping Item -->
                            @auth
                                <div class="shopping-item">
                                    <div class="dropdown-cart-header">
                                        <span>{{count(Helper::getAllProductFromCart())}} Sản Phẩm</span>
                                        <a href="{{route('cart')}}">Xem Giỏ Hàng</a>
                                    </div>
                                    <ul class="shopping-list">
                                        {{-- {{Helper::getAllProductFromCart()}} --}}
                                            @foreach(Helper::getAllProductFromCart() as $data)
                                                    @php
                                                        $photo=explode(',',$data->product['photo']);
                                                    @endphp
                                                    <li>
                                                        <form action="{{route('cart-delete',$data->id)}}" method="POST" class="action-inline-form">
    @csrf
    @method('DELETE')
    <button type="submit" class="remove action-button-reset" title="Remove this item"><i class="fa fa-remove"></i></button>
</form>
                                                        <a class="cart-img" href="#"><img src="{{$photo[0]}}" alt="{{$photo[0]}}"></a>
                                                        <h4><a href="{{route('product-detail',$data->product['slug'])}}" target="_blank">{{$data->product['title']}}</a></h4>
                                                        <p class="quantity">{{$data->quantity}} x - <span class="amount">{{number_format($data->price,0)}}đ</span></p>
                                                    </li>
                                            @endforeach
                                    </ul>
                                    <div class="bottom">
                                        <div class="total">
                                            <span>Tổng số tiền</span>
                                            <span class="total-amount">{{number_format(Helper::totalCartPrice(),0)}} đ</span>
                                        </div>
                                        <a href="{{route('checkout')}}" class="btn animate">Thanh Toán</a>
                                    </div>
                                </div>
                            @endauth
                            <!--/ End Shopping Item -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Header Inner -->
    <div class="header-inner">
        <div class="container">
            <div class="cat-nav-head">
                <div class="row">
                    <div class="col-lg-12 col-12">
                        <div class="menu-area">
                            <!-- Main Menu -->
                            <nav class="navbar navbar-expand-lg">
                                <div class="navbar-collapse">
                                    <div class="nav-inner">
                                        <ul class="nav main-menu menu navbar-nav">
                                            <li class="{{Request::path()=='home' ? 'active' : ''}}"><a href="{{route('home')}}">Trang Chủ</a></li>
                                            <li class="{{Request::path()=='about-us' ? 'active' : ''}}"><a href="{{route('about-us')}}">Giới Thiệu</a></li>
                                            <li class="{{ request()->routeIs('product-grids', 'product-lists', 'product-cat', 'product-sub-cat', 'product-brand', 'product-detail', 'product.search') ? 'active' : '' }}"><a href="{{route('product-grids')}}">Sản Phẩm</a><span class="new">New</span></li>
                                                {{Helper::getHeaderCategory()}}
                                            <li class="{{ request()->routeIs('headphone-quiz.show', 'headphone-quiz.submit') ? 'active' : '' }}"><a href="{{route('headphone-quiz.show')}}">Quiz 45s</a></li>
                                            <li class="{{ request()->routeIs('blog', 'blog.detail', 'blog.category', 'blog.tag') ? 'active' : '' }}"><a href="{{route('blog')}}">Bài Viết</a></li>

                                            <li class="{{Request::path()=='contact' ? 'active' : ''}}"><a href="{{route('contact')}}">Liên Hệ</a></li>
                                        </ul>
                                    </div>
                                </div>
                            </nav>
                            <!--/ End Main Menu -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--/ End Header Inner -->
</header>

@push('scripts')
<script>
    $(function () {
        function normalizeSearchText(text) {
            if (!text) {
                return '';
            }

            return text
                .toString()
                .toLowerCase()
                .normalize('NFD')
                .replace(/[\u0300-\u036f]/g, '')
                .replace(/[^a-z0-9\s]/g, ' ')
                .replace(/\s+/g, ' ')
                .trim();
        }

        $('.js-search-suggestion-box').each(function () {
            var $box = $(this);
            var $form = $box.find('form').first();
            var $input = $box.find('.js-search-input').first();
            var $panel = $box.find('.js-search-suggestions').first();
            var $items = $box.find('.js-search-suggestion');
            var $empty = $box.find('.js-search-empty').first();
            var hideTimer = null;

            function openPanel() {
                clearTimeout(hideTimer);
                $box.addClass('is-open');
                $panel.attr('aria-hidden', 'false');
                filterSuggestions();
            }

            function closePanel() {
                clearTimeout(hideTimer);
                hideTimer = setTimeout(function () {
                    $box.removeClass('is-open');
                    $panel.attr('aria-hidden', 'true');
                }, 120);
            }

            function closePanelImmediately() {
                clearTimeout(hideTimer);
                $box.removeClass('is-open');
                $panel.attr('aria-hidden', 'true');
            }

            function filterSuggestions() {
                var query = normalizeSearchText($input.val());
                var visibleCount = 0;

                $items.each(function () {
                    var $item = $(this);
                    var searchText = normalizeSearchText($item.data('searchText'));
                    var shouldShow = query === '' || searchText.indexOf(query) !== -1;

                    $item.toggle(shouldShow);

                    if (shouldShow) {
                        visibleCount++;
                    }
                });

                $empty.toggle(visibleCount === 0);
            }

            $box.on('mouseenter', openPanel);
            $box.on('mouseleave', closePanel);

            $input.on('focus click', openPanel);
            $input.on('input', filterSuggestions);
            $input.on('keydown', function (event) {
                if (event.key === 'Escape') {
                    closePanelImmediately();
                }
            });

            $items.on('click', function () {
                var searchValue = $(this).data('searchValue') || '';
                $input.val(searchValue);
                closePanelImmediately();
                $form.trigger('submit');
            });

            $(document).on('click', function (event) {
                if (!$box.is(event.target) && $box.has(event.target).length === 0) {
                    closePanelImmediately();
                }
            });
        });
    });
</script>
@endpush



