<?php

namespace App\Models;

use App\Models\Section;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\Attendance;
use Illuminate\Database\Eloquent\Relations\Pivot;

class SectionStandard extends Pivot
{
    protected $hidden = [
        'created_at',
        'updated_at'
    ];

     public function teacher(){
         return $this->belongsTo(Teacher::class);
     }

     public function students(){
         return $this->hasMany(Student::class, 'section_standard_id');
     }

     public function section(){
         return $this->belongsTo(Section::class);
     }
     public function standard(){
         return $this->belongsTo(Standard::class);
     }
     public function attendance(){
         return $this->hasMany(Attendance::class);
     }
}
