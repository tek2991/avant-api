<?php

namespace App\Models;

use App\Models\Fee;
use App\Models\BillFee;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Bill extends Model
{
    use HasFactory;

    protected $fillable =[
        'name',
        'description',
        'session_id',
        'bill_from_date',
        'bill_to_date',
        'amount_in_cent',
        'tax_in_cent',
        'gross_amount_in_cent'

    ];

    protected $cast = [
        'bill_from_date' => 'datetime',
        'bill_to_date' => 'datetime',
    ];

    protected $dates = ['bill_from_date', 'bill_to_date'];

    public function fees(){
        return $this->belongsToMany(Fee::class)->withPivot('id')->withTimestamps();
    }

    public function billFees(){
        return $this->hasMany(BillFee::class);
    }

    public function feeInvoices(){
        return $this->hasMany(FeeInvoice::class);
    }
}
