<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class HomeworkStudent extends Pivot
{
    public function homework(){
        return $this->belongsTo(Homework::class);
    }
    public function student(){
        return $this->belongsTo(Student::class);
    }
}
