@extends('frontend.layouts.master')

@section('title', 'Đặt hàng thành công')

@section('main-content')
    <div class="breadcrumbs">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="bread-inner">
                        <ul class="bread-list">
                            <li><a href="{{ route('home') }}">Trang Chủ<i class="ti-arrow-right"></i></a></li>
                            <li class="active"><a href="javascript:void(0)">Đặt hàng thành công</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <section class="shop checkout section">
        <div class="container">
            <div class="order-success">
                <div class="order-success__icon">
                    <i class="ti-check"></i>
                </div>
                <h1>Thanh toán thành công!</h1>
                <p>Cảm ơn bạn đã đặt hàng. Đơn hàng của bạn đã được ghi nhận.</p>

                <div class="order-success__details">
                    <div>
                        <span>Mã đơn hàng</span>
                        <strong>{{ $order->order_number }}</strong>
                    </div>
                    <div>
                        <span>Tổng tiền</span>
                        <strong>{{ number_format($order->total_amount, 0, ',', '.') }}đ</strong>
                    </div>
                    <div>
                        <span>Phương thức thanh toán</span>
                        <strong>{{ strtoupper($order->payment_method) }}</strong>
                    </div>
                </div>

                <div class="order-success__actions">
                    <a href="{{ route('user.order.show', $order->id) }}" class="btn">Xem đơn hàng</a>
                    <a href="{{ route('home') }}" class="btn secondary">Tiếp tục mua sắm</a>
                </div>
            </div>
        </div>
    </section>

    <style>
        .order-success {
            max-width: 680px;
            margin: 0 auto;
            padding: 60px 30px;
            text-align: center;
            background: #fff;
            box-shadow: 0 0 15px rgba(0, 0, 0, .08);
        }

        .order-success__icon {
            width: 76px;
            height: 76px;
            margin: 0 auto 25px;
            border-radius: 50%;
            color: #fff;
            background: #28a745;
            font-size: 38px;
            line-height: 76px;
        }

        .order-success h1 {
            margin-bottom: 12px;
            color: #222;
            font-size: 30px;
        }

        .order-success p {
            margin-bottom: 30px;
        }

        .order-success__details {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 15px;
            margin-bottom: 30px;
            padding: 20px 0;
            border-top: 1px solid #eee;
            border-bottom: 1px solid #eee;
        }

        .order-success__details span,
        .order-success__details strong {
            display: block;
        }

        .order-success__details span {
            margin-bottom: 7px;
            color: #777;
            font-size: 13px;
        }

        .order-success__details strong {
            color: #222;
            word-break: break-word;
        }

        .order-success__actions {
            display: flex;
            justify-content: center;
            gap: 12px;
        }

        .order-success__actions .secondary {
            background: #333;
        }

        @media (max-width: 575px) {
            .order-success {
                padding: 40px 15px;
            }

            .order-success__details {
                grid-template-columns: 1fr;
            }

            .order-success__actions {
                flex-direction: column;
            }
        }
    </style>
@endsection
