<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class ExpandOrderAndPaymentStatuses extends Migration
{
    public function up()
    {
        DB::statement("ALTER TABLE orders MODIFY status ENUM('new', 'process', 'delivered', 'cancel', 'pending_confirmation', 'preparing', 'ready', 'shipping', 'delivery_failed', 'returning', 'returned', 'delivery_success', 'completed', 'cancelled', 'ended') NOT NULL DEFAULT 'pending_confirmation'");
        DB::statement("ALTER TABLE orders MODIFY payment_status ENUM('pending', 'unpaid', 'paid', 'refunded') NOT NULL DEFAULT 'unpaid'");
    }

    public function down()
    {
        DB::table('orders')->whereIn('status', ['pending_confirmation', 'preparing', 'ready', 'shipping', 'delivery_failed', 'returning', 'returned', 'delivery_success', 'completed', 'cancelled', 'ended'])->update(['status' => 'new']);
        DB::table('orders')->whereIn('payment_status', ['pending', 'refunded'])->update(['payment_status' => 'unpaid']);
        DB::statement("ALTER TABLE orders MODIFY status ENUM('new', 'process', 'delivered', 'cancel') NOT NULL DEFAULT 'new'");
        DB::statement("ALTER TABLE orders MODIFY payment_status ENUM('paid', 'unpaid') NOT NULL DEFAULT 'unpaid'");
    }
}
