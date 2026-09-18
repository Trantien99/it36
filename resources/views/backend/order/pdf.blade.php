<!DOCTYPE html>
<html>
<head>
  <title>Đơn hàng @if($order)- {{$order->order_number}} @endif</title>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
</head>
<body>

@if($order)
<style type="text/css">
  body, body * {
    font-family: 'DejaVu Sans';
  }
  .invoice-right-top h3 {
    padding-right: 20px;
    margin-top: 20px;
    color: green;
    font-size: 30px!important;
  }
  .invoice-left-top {
    border-left: 4px solid green;
    padding-left: 20px;
    padding-top: 20px;
  }
  .invoice-left-top p {
    margin: 0;
    line-height: 20px;
    font-size: 16px;
    margin-bottom: 3px;
  }
  thead {
    background: green;
    color: #FFF;
  }
  .authority h5 {
    margin-top: -10px;
    color: green;
  }
  .thanks h4 {
    color: green;
    font-size: 25px;
    font-weight: normal;
    margin-top: 20px;
  }
  .table tfoot .empty {
    border: none;
  }
  .table-bordered {
    border: none;
  }
  .table-header {
    padding: .75rem 1.25rem;
    margin-bottom: 0;
    background-color: rgba(0,0,0,.03);
    border-bottom: 1px solid rgba(0,0,0,.125);
  }
  .table td, .table th {
    padding: .30rem;
  }
</style>
  <div class="invoice-description">
    <div class="invoice-left-top float-left">
      <h6>Hóa đơn của</h6>
       <h3>{{$order->first_name}} {{$order->last_name}}</h3>
       <div class="address">
        <p>
          <strong>Quốc gia: </strong>
          {{$order->country}}
        </p>
        <p>
          <strong>Địa chỉ: </strong>
          {{ $order->address1 }} hoặc {{ $order->address2}}
        </p>
         <p><strong>Số điện thoại:</strong> {{ $order->phone }}</p>
         <p><strong>Email:</strong> {{ $order->email }}</p>
       </div>
    </div>
    <div class="invoice-right-top float-right" class="text-right">
      <h3>Hóa đơn #{{$order->order_number}}</h3>
      <p>{{ $order->created_at->format('D d m Y') }}</p>

    </div>
    <div class="clearfix"></div>
  </div>
  <section class="order_details pt-3">
    <div class="table-header">
      <h5>Chi tiết đơn hàng</h5>
    </div>
    <table class="table table-bordered table-stripe">
      <thead>
        <tr>
          <th scope="col" class="col-6">Sản phẩm</th>
          <th scope="col" class="col-3">Số lượng</th>
          <th scope="col" class="col-3">Tổng</th>
        </tr>
      </thead>
      <tbody>
      @foreach($order->cart_info as $cart)
        <tr>
          <td><span>
              {{$cart->product->title ?? 'Sản phẩm không còn tồn tại'}}
            </span></td>
          <td>x{{$cart->quantity}}</td>
          <td><span>{{number_format($cart->price,0)}}đ</span></td>
        </tr>
      @endforeach
      </tbody>
      <tfoot>
        <tr>
          <th scope="col" class="empty"></th>
          <th scope="col" class="text-right">Tạm tính:</th>
          <th scope="col"> <span>{{number_format($order->sub_total,0)}}đ</span></th>
        </tr>
      {{-- @if(!empty($order->coupon))
        <tr>
          <th scope="col" class="empty"></th>
          <th scope="col" class="text-right">Discount:</th>
          <th scope="col"><span>-{{$order->coupon->discount(Helper::orderPrice($order->id, $order->user->id))}}{{Helper::base_currency()}}</span></th>
        </tr>
      @endif --}}
        <tr>
          <th scope="col" class="empty"></th>
          <th scope="col" class="text-right ">Phí vận chuyển:</th>
          <th><span>{{number_format(optional($order->shipping)->price ?? 0,0)}}đ</span></th>
        </tr>
        <tr>
          <th scope="col" class="empty"></th>
          <th scope="col" class="text-right">Tổng:</th>
          <th>
            <span>
                {{number_format($order->total_amount,0)}}đ
            </span>
          </th>
        </tr>
      </tfoot>
    </table>
  </section>
  <div class="thanks mt-3">
    <h4>Cảm ơn vì bạn đã mua hàng !!</h4>
  </div>

  <div class="clearfix"></div>
@else
  <h5 class="text-danger">Không hợp lệ</h5>
@endif
</body>
</html>
