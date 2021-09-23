<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExamSubjectState extends Model
{
    protected $fillable = [
        'name'
    ];

    public function examSubject(){
        return $this->hasMany(ExamSubject::class);
    }
}
