<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Homework extends Model
{
    use HasFactory;

    protected $table = 'homeworks';
    
    protected $fillable = [
        'session_id',
        'section_standard_id',
        'subject_id',
        'chapter_id',
        'descrioption',
        'created_by',
        'homework_from_date',
        'homework_to_date',
    ];

    protected $cast = [
        'homework_from_date' => 'datetime',
        'homework_to_date' => 'datetime',
    ];

    protected $dates = ['homework_from_date', 'homework_to_date'];

    public function session(){
        return $this->belongsTo(Session::class);
    }

    public function sectionStandard(){
        return $this->belongsTo(SectionStandard::class);
    }

    public function subject(){
        return $this->belongsTo(Subject::class);
    }

    public function chapter(){
        return $this->belongsTo(Chapter::class);
    }

    public function creator(){
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

    public function students(){
        return $this->belongsToMany(Students::class)->withPivot('id');
    }
}
