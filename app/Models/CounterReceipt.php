<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CounterReceipt extends Model
{
    protected $fillable = [
        'student_id',
        'standard_id',
        'remarks',

        'payment_mode',
        'cheque_number',
        'cheque_date',
        'bank_name',

        'completed',
        'created_by',
    ];

    protected $casts = [
        'completed' => 'boolean',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function standard()
    {
        return $this->belongsTo(Standard::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function counterReceiptItems()
    {
        return $this->hasMany(CounterReceiptItem::class);
    }

    public function totalAmountInCents()
    {
        return $this->counterReceiptItems->sum('amount_in_cents');
    }

    public function totalAmount()
    {
        return $this->totalAmountInCents() / 100;
    }
}
