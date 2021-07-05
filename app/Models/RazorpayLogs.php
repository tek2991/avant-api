<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RazorpayLogs extends Model
{
    use HasFactory;


    protected $fillable = [
        'fee_invoice_id',
        'order_id',
        'event',
        'payload',
    ];
}
