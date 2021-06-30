<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cash extends Model
{
    use HasFactory;

    /**
     * Get all of the payments for the cash entry.
     */
    public function payments()
    {
        return $this->morphToMany(Payment::class, 'paymentable');
    }
}
