<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class ExamSubject extends Pivot
{
    protected $table = 'exam_subject';

    protected $fillable = [
        'exam_id',
        'subject_id',
        'exam_schedule_id',
        'full_mark',
        'pass_mark',
        'negative_percentage',
        'exam_subject_state_id',
        'auto_start',
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
        return $this->belongsToMany(User::class, 'exam_subject_scores', 'exam_subject_id', 'user_id')->withPivot('id', 'marks_secured', 'evaluated_by')->withTimestamps();
    }

    public function examQuestions(){
        return $this->hasMany(ExamQuestion::class, 'exam_subject_id');
    }

    public function examSubjectScores(){
        return $this->hasMany(ExamSubjectScore::class, 'exam_subject_id');
    }
}
