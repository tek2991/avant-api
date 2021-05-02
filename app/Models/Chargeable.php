<?php

namespace App\Models;

use App\Models\Fee;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Chargeable extends Model
{
    use HasFactory;

    public function fee(){
        return $this->belongsTo(Fee::class);
    }
}
