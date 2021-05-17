<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BloodGroup extends Model
{
    protected $fillable = [
        'name',
    ];

    public function users(){
        return $this->hasOneThrough(User::class, UserDetail::class);
    }
}
