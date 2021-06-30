<?php

namespace App\Models;

use App\Models\FeeInvoice;
use App\Models\PaymentMethod;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'payment_method_id',
        'fee_invoice_id',
        'status',
    ];

    /**
     * Get all of the razorpays that are assigned this payment.
     */
    public function razorpays()
    {
        return $this->morphedByMany(Razorpay::class, 'paymentable');
    }

    /**
     * Get all of the cheques that are assigned this payment.
     */
    public function cheques()
    {
        return $this->morphedByMany(Cheque::class, 'paymentable');
    }

    /**
     * Get all of the cashs that are assigned this payment.
     */
    public function cashs()
    {
        return $this->morphedByMany(Cash::class, 'paymentable');
    }

    /**
     * Get the payment method.
     */
    public function paymentMethod()
    {
        return $this->belongsTo(PaymentMethod::class);
    }

    /**
     * Get the fee invoice.
     */
    public function feeInvoice()
    {
        return $this->belongsTo(FeeInvoice::class);
    }
}
