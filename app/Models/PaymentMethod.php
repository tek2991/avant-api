<?php

namespace App\Models;

use App\Models\Payment;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PaymentMethod extends Model
{
    use HasFactory;

    /**
     * Get the payments.
     */
    public function payments()
    {
        return $this->hasMany(Payment::class);
    }
}
