<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RequestType extends Model
{
    public $timestamps = false;
    protected $fillable = [
        'name'
    ];
}
