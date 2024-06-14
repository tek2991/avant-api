<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransactionLock extends Model
{
    protected $fillable = [
        'name',
        'locked',
    ];

    protected $hidden = [
        'created_at',
        'updated_at'
    ];
}
