<?php

namespace App\Models;

use App\Models\User;
use App\Models\BillFee;
use App\Models\Payment;
use App\Models\Standard;
use App\Models\FeeInvoiceItem;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class FeeInvoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'user_id',
        'bill_fee_id',
        'standard_id',
        'amount_in_cent',
        'tax_in_cent',
        'gross_amount_in_cent'
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function standard(){
        return $this->belongsTo(Standard::class);
    }

    public function billFee(){
        return $this->belongsTo(BillFee::class, 'bill_fee_id');
    }

    public function feeInvoiceItems(){
        return $this->belongsToMany(FeeInvoiceItem::class, 'fee_invoice_fee_invoice_item', 'fee_invoice_id', 'fee_invoice_item_id')->withPivot('id')->withTimestamps();
    }

    public function payment(){
        return $this->hasOne(Payment::class);
    }
}
