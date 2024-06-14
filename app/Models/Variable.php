<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Variable extends Model
{
    protected $fillable = [
        'key',
        'value',
    ];

    protected $hidden = [
        'created_at',
        'updated_at'
    ];
}
