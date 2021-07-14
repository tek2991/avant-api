<?php

namespace App\Models;

use App\Models\User;
use App\Models\Session;
use App\Models\AttendanceState;
use App\Models\SectionStandard;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Attendance extends Model
{
    use HasFactory;

    protected $fillable =[
        'user_id',
        'attendance_state_id',
        'attendance_date',
        'section_standard_id',
        'session_id',
        'created_by',
        'updated_by',
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

    public function sectionStandard(){
        return $this->belongsTo(SectionStandard::class);
    }

    public function session(){
        return $this->belongsTo(Session::class);
    }

    public function creator(){
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

    public function updator(){
        return $this->belongsTo(User::class, 'updated_by', 'id');
    }
}
