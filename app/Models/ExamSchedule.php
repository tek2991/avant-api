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
        'started_at',
        'ended_at',
    ];

    protected $cast = [
        'start' => 'datetime',
        'end' => 'datetime',
    ];

    protected $dates = ['start', 'end'];

    public function exam(){
        return $this->belongsTo(Exam::class);
    }

    public function examSubjects(){
        return $this->hasMany(ExamSubject::class);
    }
}