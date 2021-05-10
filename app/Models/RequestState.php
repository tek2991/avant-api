<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RequestState extends Model
{
    public $timestamps = false;
    protected $fillable = [
        'name'
    ];
}
