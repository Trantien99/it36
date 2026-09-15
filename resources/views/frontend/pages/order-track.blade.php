@extends('frontend.layouts.master')

@section('title','Web bán tai nghe || Trạng thái đơn hàng')

@section('main-content')
    <!-- Breadcrumbs -->
    <div class="breadcrumbs">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="bread-inner">
                        <ul class="bread-list">
                            <li><a href="{{route('home')}}">Home<i class="ti-arrow-right"></i></a></li>
                            <li class="active"><a href="javascript:void(0);">Trạng Thái Đơn Hàng</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End Breadcrumbs -->
    <section class="tracking_box_area section_gap py-5">
        <div class="container">
            <div class="tracking_box_inner">
                <p>Để theo dõi trạng thái đơn hàng của bạn vui lòng nhập mã đơn hàng vào ô bên dưới và nhấn nút "Kiểm tra".</p>
                @auth
                    <form class="row tracking_form my-4" action="{{route('product.track.order')}}" method="post" novalidate="novalidate">
                        @csrf
                        <div class="col-md-8 form-group">
                            <input type="text" class="form-control p-2" name="order_number" value="{{ old('order_number') }}" placeholder="Nhập mã đơn hàng" required>
                        </div>
                        <div class="col-md-8 form-group">
                            <button type="submit" value="submit" class="btn submit_btn">Kiểm tra</button>
                        </div>
                    </form>

                    @if(isset($searched) && !empty($order))
                        @php
                            $statusLabelMap = [
                                'new' => 'Mới tạo',
                                'process' => 'Đang xử lý',
                                'delivered' => 'Đã giao',
                                'cancel' => 'Đã hủy',
                            ];
                            $statusLabel = $statusLabelMap[$order->status] ?? ucfirst($order->status);
                        @endphp
                        <div class="alert alert-info my-4">
                            <strong>Mã đơn hàng:</strong> {{ $order->order_number }}
                        </div>
                        <div class="card shadow-sm border-0 my-4">
                            <div class="card-body">
                                <h4 class="mb-3">Trạng thái đơn hàng</h4>
                                <p class="mb-2"><strong>Trạng thái:</strong> {{ $statusLabel }}</p>
                                <p class="mb-2"><strong>Thông báo:</strong> {{ $statusText }}</p>
                                <p class="mb-0"><strong>Tổng tiền:</strong> {{ number_format((float) $order->total_amount, 0, ',', '.') }}đ</p>
                            </div>
                        </div>
                    @endif
                @else
                    <div class="alert alert-warning my-4">
                        Vui lòng <a href="{{route('login.form')}}">đăng nhập</a> để theo dõi trạng thái đơn hàng của bạn.
                    </div>
                @endauth
            </div>
        </div>
    </section>
@endsection
