<?php

namespace App\Models;

use App\Models\Fee;
use App\Models\Bill;
use App\Models\FeeInvoice;
use Illuminate\Database\Eloquent\Relations\Pivot;

class BillFee extends Pivot
{
    protected $fillable = [
        'name',
        'is_active',
        'amount_in_cent',
        'tax_in_cent',
        'gross_amount_in_cent'
    ];

    public function fee(){
        return $this->belongsTo(Fee::class);
    }
    public function bill(){
        return $this->belongsTo(Bill::class);
    }
    public function feeInvoices(){
        return $this->hasMany(FeeInvoice::class,'bill_fee_id');
    }
}
