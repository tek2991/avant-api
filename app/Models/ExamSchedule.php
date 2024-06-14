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
        'closed_at',
    ];

    protected $cast = [
        'start' => 'datetime',
        'end' => 'datetime',
        'started_at' => 'datetime',
        'ended_at' => 'datetime',
        'closed_at' => 'datetime',
    ];

    protected $dates = ['start', 'end', 'started_at', 'ended_at', 'closed_at'];

    public function exam(){
        return $this->belongsTo(Exam::class);
    }

    public function examSubjects(){
        return $this->hasMany(ExamSubject::class);
    }
}