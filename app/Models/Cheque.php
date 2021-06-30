<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cheque extends Model
{
    use HasFactory;

    /**
     * Get all of the payments for the cheque.
     */
    public function payments()
    {
        return $this->morphToMany(Payment::class, 'paymentable');
    }
}
