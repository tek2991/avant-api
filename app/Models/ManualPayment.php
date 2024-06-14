<?php

namespace App\Models;

use App\Models\Bank;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ManualPayment extends Model
{
    use HasFactory;
    protected $fillable =[
        'amount_in_cent',
        'instrument_id',
        'transaction_no',
        'transaction_date',
        'bank_id',
        'remarks'
    ];

    protected $cast = [
        'transaction_date' => 'datetime',
    ];

    protected $dates = ['transaction_date'];

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
    /**
     * Get the payment bank.
     */
    public function bank()
    {
        return $this->belongsTo(Bank::class);
    }
}
