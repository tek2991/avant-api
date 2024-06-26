<?php

namespace App\Models;

use App\Models\ChapterProgression;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Chapter extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'name',
        'subject_id',
        'description',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    public function subject(){
        return $this->belongsTo(Subject::class);
    }

    public function chapterProgressions(){
        return $this->hasMany(ChapterProgression::class);
    }
}
