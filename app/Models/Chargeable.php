<?php

namespace App\Models;

use App\Models\Fee;
use App\Models\Student;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Chargeable extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'is_mandatory',
        'amount_in_cent',
        'tax_rate',
        'gross_amount_in_cent'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function fees(){
        return $this->belongsToMany(Fee::class)->withPivot('id')->withTimestamps();
    }

    public function students(){
        return $this->belongsToMany(Student::class)->withPivot('id')->withTimestamps();
    }
}
