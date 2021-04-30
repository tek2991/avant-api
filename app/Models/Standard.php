<?php

namespace App\Models;

use App\Models\Section;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Standard extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'hierachy'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function sections(){
        return $this->belongsToMany(Section::class)->withPivot('id')->withTimestamps();
    }
}
