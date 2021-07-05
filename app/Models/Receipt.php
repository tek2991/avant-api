<?php

namespace App\Models;

use App\Models\FeeInvoice;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Receipt extends Model
{
    use HasFactory;

    protected $fillable = [
        'fee_invoice_id',
    ];

    public function feeInvoice(){
        return $this->belongsTo(FeeInvoice::class);
    }
}
