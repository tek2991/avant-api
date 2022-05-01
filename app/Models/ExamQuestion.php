<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExamQuestion extends Model
{
    use HasFactory;

    protected $fillable = [
        'exam_subject_id',
        'chapter_id',
        'exam_question_type_id',
        'description',
        'marks',
        'max_time_in_seconds',
        'created_by'
    ];

    public function examSubject(){
        return $this->belongsTo(ExamSubject::class);
    }

    public function examQuestionType(){
        return $this->belongsTo(ExamQuestionType::class, 'exam_question_type_id', 'id');
    }

    public function creator(){
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

    public function examQuestionOptions(){
        return $this->hasMany(ExamQuestionOption::class);
    }

    public function examAnswers(){
        return $this->hasMany(ExamAnswer::class);
    }
}
