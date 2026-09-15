<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class MigrateLegacyOrderStatuses extends Migration
{
    public function up()
    {
        DB::table('orders')->where('status', 'new')->update(['status' => 'pending_confirmation']);
        DB::table('orders')->where('status', 'process')->update(['status' => 'preparing']);
        DB::table('orders')->where('status', 'cancel')->update(['status' => 'cancelled']);
        DB::table('orders')->where('status', 'delivered')->where('payment_status', 'paid')->update(['status' => 'completed']);
        DB::table('orders')->where('status', 'delivered')->where('payment_status', '!=', 'paid')->update(['status' => 'delivery_success']);

        DB::statement("ALTER TABLE orders MODIFY status ENUM('pending_confirmation', 'preparing', 'ready', 'shipping', 'delivery_failed', 'returning', 'returned', 'delivery_success', 'completed', 'cancelled', 'ended') NOT NULL DEFAULT 'pending_confirmation'");
    }

    public function down()
    {
        DB::table('orders')->where('status', 'pending_confirmation')->update(['status' => 'new']);
        DB::table('orders')->where('status', 'preparing')->update(['status' => 'process']);
        DB::table('orders')->whereIn('status', ['ready', 'shipping', 'delivery_failed', 'returning', 'returned', 'delivery_success', 'completed', 'ended'])->update(['status' => 'delivered']);
        DB::table('orders')->where('status', 'cancelled')->update(['status' => 'cancel']);

        DB::statement("ALTER TABLE orders MODIFY status ENUM('new', 'process', 'delivered', 'cancel') NOT NULL DEFAULT 'new'");
    }
}
