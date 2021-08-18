<?php

namespace App\Models;

use App\Models\Standard;
use App\Models\SubjectGroup;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Subject extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'subject_group_id',
        'standard_id',
        'is_mandatory'
    ];

    protected $hidden = [
        'created_at',
        'updated_at'
    ];

    public function subjectGroup(){
        return $this->belongsTo(SubjectGroup::class);
    }

    public function standard(){
        return $this->belongsTo(Standard::class);
    }

    public function teachers(){
        return $this->belongsToMany(Teacher::class)->withPivot('id');
    }

    public function students(){
        return $this->belongsToMany(Student::class)->withPivot('id');
    }
}
