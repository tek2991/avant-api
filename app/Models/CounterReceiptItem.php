<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CounterReceiptItem extends Model
{
    protected $fillable = [
        'counter_receipt_id',
        'counter_receipt_item_type_id',
        'amount_in_cents',
        'remarks',
    ];

    public function counterReceipt()
    {
        return $this->belongsTo(CounterReceipt::class);
    }

    public function counterReceiptItemType()
    {
        return $this->belongsTo(CounterReceiptItemType::class);
    }
}
