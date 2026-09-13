<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class MarkDeliveredOrdersAsPaid extends Migration
{
    public function up()
    {
        DB::table('orders')
            ->where('status', 'delivered')
            ->where(function ($query) {
                $query->whereNull('payment_status')
                    ->orWhere('payment_status', '!=', 'paid');
            })
            ->update([
                'payment_status' => 'paid',
            ]);
    }

    public function down()
    {
        // Intentionally left blank because the previous payment state is unknown.
    }
}
