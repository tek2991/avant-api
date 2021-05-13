<?php

namespace App\Models;

use App\Models\User;
use App\Models\Appeal;
use App\Models\AppealState;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AppealEvent extends Model
{
    use HasFactory;

    protected $fillable = [
        'appeal_id',
        'appeal_state_id',
        'user_id'
    ];

    public function appeal(){
        return $this->belongsTo(Appeal::class);
    }

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function appealState(){
        return $this->belongsTo(AppealState::class);
    }
}
