<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExamQuestionOption extends Model
{
    use HasFactory;

    protected $fillable = [
        'exam_question_id',
        'description',
        'is_correct',
    ];

    public function examQuestion(){
        return $this->belongsTo(ExamQuestion::class);
    }
}
