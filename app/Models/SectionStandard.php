<?php

namespace App\Models;

use App\Models\Section;
use App\Models\Student;
use App\Models\Teacher;
use Illuminate\Database\Eloquent\Relations\Pivot;

class SectionStandard extends Pivot
{
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
}
