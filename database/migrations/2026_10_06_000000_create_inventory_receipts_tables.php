<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInventoryReceiptsTables extends Migration
{
    public function up()
    {
        Schema::create('inventory_receipts', function (Blueprint $table) {
            $table->id();
            $table->string('receipt_number')->unique();
            $table->string('supplier_name')->nullable();
            $table->string('reference_number')->nullable();
            $table->text('notes')->nullable();
            $table->unsignedInteger('total_quantity');
            $table->unsignedBigInteger('received_by')->nullable();
            $table->foreign('received_by')->references('id')->on('users')->onDelete('SET NULL');
            $table->timestamps();
        });

        Schema::create('inventory_receipt_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('inventory_receipt_id');
            $table->unsignedBigInteger('product_id')->nullable();
            $table->string('product_name');
            $table->unsignedInteger('quantity');
            $table->decimal('unit_cost', 12, 2)->default(0);
            $table->decimal('line_total', 14, 2)->default(0);
            $table->foreign('inventory_receipt_id')->references('id')->on('inventory_receipts')->onDelete('cascade');
            $table->foreign('product_id')->references('id')->on('products')->onDelete('SET NULL');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('inventory_receipt_items');
        Schema::dropIfExists('inventory_receipts');
    }
}