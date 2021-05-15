<?php

namespace App\Models;

use App\Models\User;
use App\Models\AttendanceState;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Attendance extends Model
{
    use HasFactory;

    protected $fillable =[
        'user_id',
        'attendance_state_id',
        'attendance_date'
    ];

    protected $cast = [
        'attendance_date' => 'datetime',
    ];

    protected $dates = ['attendance_date'];

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function attendanceState(){
        return $this->belongsTo(AttendanceState::class);
    }
}
