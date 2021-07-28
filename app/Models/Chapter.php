<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chapter extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'subject_id',
        'decription',
    ];

    protected $hidden = [
        'created_at',
        'updated_at'
    ];

    public function subject(){
        return $this->belongsTo(Subject::class);
    }
}
