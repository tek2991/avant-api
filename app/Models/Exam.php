<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Exam extends Model
{
    use HasFactory;

    protected $fillable = [
        'session_id',
        'name',
        'description',
        'exam_type_id',
    ];

    public function session(){
        return $this->belongsTo(Session::class);
    }

    public function examType(){
        return $this->belongsTo(ExamType::class);
    }
}
