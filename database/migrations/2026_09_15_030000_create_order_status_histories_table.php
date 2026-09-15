<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateOrderStatusHistoriesTable extends Migration
{
    public function up()
    {
        Schema::create('order_status_histories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('order_id');
            $table->string('status');
            $table->string('payment_status')->nullable();
            $table->unsignedBigInteger('changed_by')->nullable();
            $table->timestamps();

            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
            $table->foreign('changed_by')->references('id')->on('users')->onDelete('set null');
            $table->index(['order_id', 'status']);
        });

        DB::table('orders')->select('id', 'status', 'payment_status', 'created_at')->orderBy('id')->get()->each(function ($order) {
            DB::table('order_status_histories')->insert([
                'order_id' => $order->id,
                'status' => $order->status,
                'payment_status' => $order->payment_status,
                'changed_by' => null,
                'created_at' => $order->created_at ?: now(),
                'updated_at' => $order->created_at ?: now(),
            ]);
        });
    }

    public function down()
    {
        Schema::dropIfExists('order_status_histories');
    }
}
