<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExamSchedule extends Model
{
    protected $fillable = [
        'exam_id',
        'date',
        'start',
        'end',
    ];

    public function exam(){
        return $this->belongsTo(Exam::class);
    }
}