<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExamDateTime extends Model
{
    protected $fillable = [
        'exam_id',
        'date',
        'from',
        'to',
    ];

    public function exam(){
        return $this->belongsTo(Exam::class);
    }
}
