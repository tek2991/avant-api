<?php

namespace App\Models;

use App\Models\User;
use App\Models\AttendenceState;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Attendence extends Model
{
    use HasFactory;

    protected $fillable =[
        'user_id',
        'attendence_state_id',
        'attendence_date'
    ];

    protected $cast = [
        'attendence_date' => 'datetime',
    ];

    protected $dates = ['attendence_date'];

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function attendenceState(){
        return $this->belongsTo(AttendenceState::class);
    }
}
