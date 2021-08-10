<?php

namespace App\Models;

use App\Models\ManualPayment;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Instrument extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'name',
    ];

    public function manualPayments(){
        return $this->hasMany(ManualPayment::class);
    }
}
