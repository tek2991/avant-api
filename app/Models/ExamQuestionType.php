<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExamQuestionType extends Model
{
    protected $fillable = [
        'name'
    ];

    public function examQuestions(){
        return $this->hasMany(ExamQuestion::class);
    }
}
