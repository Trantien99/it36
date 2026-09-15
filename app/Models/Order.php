<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    public const ORDER_STATUS_LABELS = [
        'pending_confirmation' => 'Chờ xác nhận',
        'preparing' => 'Đang chuẩn bị hàng',
        'ready' => 'Đơn hàng đã sẵn sàng',
        'shipping' => 'Đang giao hàng',
        'delivery_failed' => 'Giao hàng thất bại',
        'returning' => 'Đang hoàn hàng',
        'returned' => 'Hoàn hàng thành công',
        'delivery_success' => 'Giao hàng thành công',
        'completed' => 'Hoàn thành',
        'cancelled' => 'Đã hủy',
        'ended' => 'Kết thúc',
    ];

    public const PAYMENT_STATUS_LABELS = [
        'pending' => 'Chờ thanh toán',
        'unpaid' => 'Chưa thanh toán',
        'paid' => 'Đã thanh toán',
        'refunded' => 'Đã hoàn tiền',
    ];

    public const ORDER_STATUS_TRANSITIONS = [
        'pending_confirmation' => ['pending_confirmation', 'preparing', 'cancelled'],
        'preparing' => ['preparing', 'ready', 'cancelled'],
        'ready' => ['ready', 'shipping', 'cancelled'],
        'shipping' => ['shipping', 'delivery_success', 'delivery_failed'],
        'delivery_failed' => ['delivery_failed', 'shipping', 'returning'],
        'returning' => ['returning', 'returned'],
        'returned' => ['returned', 'ended'],
        'delivery_success' => ['delivery_success', 'completed'],
        'completed' => ['completed', 'ended'],
        'cancelled' => ['cancelled', 'ended'],
        'ended' => ['ended'],
    ];

    public const STOCK_DECREMENT_STATUSES = ['delivery_success'];

    protected $fillable=['user_id','order_number','sub_total','quantity','delivery_charge','status','total_amount','first_name','last_name','country','post_code','address1','address2','phone','email','payment_method','payment_status','shipping_id','coupon'];

    public function cart_info(){
        return $this->hasMany('App\Models\Cart','order_id','id');
    }

    public function statusHistory()
    {
        return $this->hasMany(OrderStatusHistory::class)->orderBy('created_at');
    }
    public static function getAllOrder($id){
        return Order::with('cart_info')->find($id);
    }
    public static function countActiveOrder(){
        $data=Order::count();
        if($data){
            return $data;
        }
        return 0;
    }
    public function cart(){
        return $this->hasMany(Cart::class);
    }

    public function shipping(){
        return $this->belongsTo(Shipping::class,'shipping_id');
    }
    public function user()
    {
        return $this->belongsTo('App\User', 'user_id');
    }

    public static function countNewOrder(){
        $data=Order::where('status','pending_confirmation')->count();
        if($data){
            return $data;
        }
        return 0;
    }
    public static function countCancelOrder(){
        $data=Order::where('status','cancelled')->count();
        if($data){
            return $data;
        }
        return 0;
    }


}
