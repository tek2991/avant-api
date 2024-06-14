<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExamAnswerState extends Model
{
    protected $fillable = [
        'name'
    ];

    public function examAnswers(){
        return $this->hasMany(ExamAnswer::class);
    }
}
