<?php

namespace App\Models;

use App\Models\Fee;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Chargeable extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'amount_in_cent',
        'tax_rate',
        'gross_amount_in_cent'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function fee(){
        return $this->belongsTo(Fee::class);
    }
}
