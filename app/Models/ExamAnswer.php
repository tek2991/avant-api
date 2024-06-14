<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ExamAnswer extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'exam_question_id',
        'user_id',
        'description',
        'exam_answer_state_id',
        'exam_question_option_id',
        'marks_secured',
        'evaluated_by',
    ];

    public function examQuestion(){
        return $this->belongsTo(ExamQuestion::class);
    }

    public function examAnswerState(){
        return $this->belongsTo(ExamAnswerState::class);
    }

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function evaluator(){
        return $this->belongsTo(User::class, 'evaluated_by', 'id');
    }
}
