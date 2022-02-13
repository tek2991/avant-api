<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExamUserState extends Model
{
    use HasFactory;

    protected $fillable = [
        'name'
    ];

    public function examUsers()
    {
        return $this->hasMany(ExamUser::class);
    }
}
