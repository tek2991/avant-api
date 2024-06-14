<?php

namespace App\Models;

use App\Models\Fee;
use App\Models\Section;
use App\Models\Student;
use App\Models\SectionStandard;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Standard extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'hierachy'
    ];

    protected $hidden = [
        'created_at',
        'updated_at'
    ];

    public function sections(){
        return $this->belongsToMany(Section::class)->withPivot('id');
    }

    public function fees(){
        return $this->belongsToMany(Fee::class)->withPivot('id')->withTimestamps();
    }

    public function students(){
        return $this->hasManyThrough(Student::class, SectionStandard::class, 'standard_id', 'section_standard_id');
    }

    public function subjects(){
        return $this->hasMany(Subject::class);
    }
}
