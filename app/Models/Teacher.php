<?php

namespace App\Models;

use App\Models\SectionStandard;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Teacher extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    public function sectionStandard()
    {   
        return $this->hasMany(SectionStandard::class);
    }

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function classStudents(){
        return $this->hasManyThrough(Student::class, SectionStandard::class, 'teacher_id', 'section_standard_id');
    }
}
