<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExamSubjectScore extends Model
{
    use HasFactory;

    protected $fillable = [
        'exam_subject_id',
        'user_id',
        'marks_secured'
    ];

    public function examSubject(){
        return $this->belongsTo(ExamSubject::class);
    }

    public function user(){
        return $this->belongsTo(User::class);
    }
}
