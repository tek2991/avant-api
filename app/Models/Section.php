<?php

namespace App\Models;

use App\Models\Standard;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Section extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
    ];

    public function standards(){
        return $this->belongsToMany(Standard::class)->withPivot('id')->withTimestamps();
    }
}
