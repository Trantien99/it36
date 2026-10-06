<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InventoryReceipt extends Model
{
    protected $fillable = [
        'receipt_number',
        'supplier_name',
        'reference_number',
        'notes',
        'total_quantity',
        'received_by',
    ];

    public function items()
    {
        return $this->hasMany(InventoryReceiptItem::class);
    }

    public function receivedBy()
    {
        return $this->belongsTo(\App\User::class, 'received_by');
    }
}