<?php

namespace App\Models;

use App\Models\Student;
use App\Models\Subject;
use Illuminate\Database\Eloquent\Relations\Pivot;

class StudentSubject extends Pivot
{
    protected $fillable = [
        'subject_id',
        'student_id'
    ];

    protected $hidden = [
        'created_at',
        'updated_at'
    ];

    public function subject(){
        return $this->belongsTo(Subject::class);
    }

    public function student(){
        return $this->belongsTo(Student::class);
    }
}
