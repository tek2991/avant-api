<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Caste extends Model
{
    protected $fillable = [
        'name',
    ];

    public function userDetails(){
        return $this->hasMany(UserDetail::class);
    }
}
