<?php

namespace App\Models;

use App\Models\Subject;
use App\Models\Teacher;
use Illuminate\Database\Eloquent\Relations\Pivot;

class SubjectTeacher extends Pivot
{


    protected $fillable = [
        'subject_id',
        'teacher_id'
    ];

    protected $hidden = [
        'created_at',
        'updated_at'
    ];

    public function subject(){
        return $this->belongsTo(Subject::class);
    }

    public function teacher(){
        return $this->belongsTo(Teacher::class);
    }
}
