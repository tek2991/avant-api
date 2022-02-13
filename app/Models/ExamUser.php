<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExamUser extends Model
{
    use HasFactory;

    protected $fillable = [
        'exam_id',
        'user_id',
        'exam_user_state_id'
    ];

    public function exam()
    {
        return $this->belongsTo(Exam::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function examUserState()
    {
        return $this->belongsTo(ExamUserState::class);
    }
}
