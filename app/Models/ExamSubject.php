<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExamSubject extends Model
{
    use HasFactory;

    protected $fillable = [
        'exam_id',
        'subject_id',
        'exam_schedule_id',
        'full_mark',
        'pass_mark',
        'negative_percent',
        'exam_subject_state_id',
    ];

    public function exam(){
        return $this->belongsTo(Exam::class);
    }

    public function subject(){
        return $this->belongsTo(Subject::class);
    }

    public function examSchedule(){
        return $this->belongsTo(ExamSchedule::class);
    }

    public function examSubjectState(){
        return $this->belongsTo(ExamSubjectState::class);
    }

    public function users(){
        return $this->belongsToMany(User::class, 'exam_subject_score', 'exam_subject_id', 'user_id')->withPivot('id', 'marks_secured')->withTimestamps();
    }
}
