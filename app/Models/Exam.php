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
        'created_by',
    ];

    public function session(){
        return $this->belongsTo(Session::class);
    }

    public function examType(){
        return $this->belongsTo(ExamType::class);
    }

    public function examDateTimes(){
        return $this->hasMany(ExamDateTime::class);
    }

    public function creator(){
        return $this->belongsTo(User::class, 'created_by', 'id');
    }
}
