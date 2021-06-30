<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Razorpay extends Model
{
    use HasFactory;


    protected $fillable = [
        'order_id',
        'payment_id',
        'signature',
        'attempts',
        'amount_in_cent',
        'amount_paid_in_cent',
        'amount_due_in_cent',
        'currency',
        'order_status',
        'payment_status',
    ];

    /**
     * Get all of the payments for the razorpay entry.
     */
    public function payments()
    {
        return $this->morphToMany(Payment::class, 'paymentable')->withTimestamps();
    }
}
