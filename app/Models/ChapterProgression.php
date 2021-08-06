<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChapterProgression extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'session_id',
        'chapter_id',
        'section_id',
        'started_at',
        'completed_at',
        'started_by',
        'completed_by',
        'complete_before',
    ];

    protected $cast = [
        'complete_before_date' => 'datetime',
    ];

    protected $dates = ['complete_before_date'];

    public function chapter(){
        return $this->belongsTo(Chapter::class);
    }

    public function session(){
        return $this->belongsTo(Session::class);
    }

    public function startedBy(){
        return $this->belongsTo(Teacher::class, 'started_by', 'id');
    }

    public function completedBy(){
        return $this->belongsTo(Teacher::class, 'completed_by', 'id');
    }
}
