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

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function chargeables(){
        return $this->hasMany(Chargeable::class);
    }

    public function standards()
    {
       return $this->belongsToMany(Standard::class)->withPivot('id')->withTimestamps();
    }
}
