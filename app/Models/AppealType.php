<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AppealType extends Model
{
    public $timestamps = false;
    protected $fillable = [
        'name'
    ];
}
