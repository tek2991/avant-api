<?php

namespace App\Models;

use App\Models\FeeInvoice;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class FeeInvoiceItem extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'description',
        'bill_id',
        'chargeable_id',
        'amount_in_cent',
        'tax_rate',
        'gross_amount_in_cent'
    ];

    public function chargeables(){
        return $this->belongsTo(Chargeable::class);
    }

    public function feeInvoices(){
        return $this->belongsToMany(FeeInvoice::class, 'fee_invoice_fee_invoice_item', 'fee_invoice_item_id', 'fee_invoice_id')->withPivot('id')->withTimestamps();
    }
}
