<?php

namespace App\Models;

use App\Models\Stream;
use App\Models\Subject;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SubjectGroup extends Model
{
    use HasFactory;


    protected $fillable = [
        'name',
        'stream_id'
    ];

    protected $hidden = [
        'created_at',
        'updated_at'
    ];


    public function stream(){
        return $this->belongsTo(Stream::class);
    }

    public function subjects(){
        return $this->hasMany(Subject::class);
    }
}
