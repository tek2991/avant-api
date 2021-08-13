<?php

namespace App\Models;

use App\Models\Student;
use App\Models\Chargeable;
use Illuminate\Database\Eloquent\Relations\Pivot;

class ChargeableStudent extends Pivot
{
    protected $fillable = [
        'chargeable_id',
        'student_id'
    ];

    protected $hidden = [
        'created_at',
        'updated_at'
    ];

    public function chargeable(){
        return $this->belongsTo(Chargeable::class);
    }

    public function student(){
        return $this->belongsTo(Student::class);
    }
}
