<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class AddMomoToOrdersPaymentMethodEnum extends Migration
{
    public function up()
    {
        DB::statement("ALTER TABLE orders MODIFY payment_method ENUM('cod', 'paypal', 'momo') NOT NULL DEFAULT 'cod'");
    }

    public function down()
    {
        DB::table('orders')->where('payment_method', 'momo')->update([
            'payment_method' => 'cod',
        ]);

        DB::statement("ALTER TABLE orders MODIFY payment_method ENUM('cod', 'paypal') NOT NULL DEFAULT 'cod'");
    }
}
