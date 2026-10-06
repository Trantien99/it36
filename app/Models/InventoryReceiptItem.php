<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InventoryReceiptItem extends Model
{
    protected $fillable = [
        'inventory_receipt_id',
        'product_id',
        'product_name',
        'quantity',
        'unit_cost',
        'line_total',
    ];

    public function receipt()
    {
        return $this->belongsTo(InventoryReceipt::class, 'inventory_receipt_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}