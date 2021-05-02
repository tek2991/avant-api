<?php

namespace App\Models;

use App\Models\Standard;
use App\Models\Chargeable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Fee extends Model
{
    use HasFactory;

    public function chargeables(){
        return $this->hasMany(Chargeable::class);
    }

    public function standards()
    {
       return $this->belongsToMany(Standard::class)->withPivot('id')->withTimestamps();
    }
}
