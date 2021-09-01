<?php

namespace App\Models;

use App\Models\UserDetail;
use Illuminate\Database\Eloquent\Model;

class Religion extends Model
{
    protected $fillable = [
        'name',
    ];

    public function userDetails(){
        return $this->hasMany(UserDetail::class);
    }
}
