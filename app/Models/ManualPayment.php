<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ManualPayment extends Model
{
    use HasFactory;

    /**
     * Get all of the payments for manual payment.
     */
    public function payments()
    {
        return $this->morphToMany(Payment::class, 'paymentable');
    }
    /**
     * Get the payment instrument type.
     */
    public function instrument()
    {
        return $this->belongsTo(Instrument::class);
    }
}
