<?php

namespace App\Models;

use App\Models\Standard;
use App\Models\Chargeable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Fee extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
    ];

    public function chargeables(){
        return $this->belongsToMany(Chargeable::class)->withPivot('id')->withTimestamps();
    }

    public function standards()
    {
       return $this->belongsToMany(Standard::class)->withPivot('id')->withTimestamps();
    }

    public function bills(){
        return $this->belongsToMany(Bill::class)->withPivot('id')->withTimestamps();
    }
}
