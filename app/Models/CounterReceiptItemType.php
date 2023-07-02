<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CounterReceiptItemType extends Model
{
    protected $fillable = [
        'name',
    ];

    public function counterReceiptItems()
    {
        return $this->hasMany(CounterReceiptItem::class);
    }
}
