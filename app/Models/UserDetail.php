<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class UserDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'phone',
        'phone_alternate',
        'dob',
        'gender_id',
        'blood_group_id',
        'fathers_name',
        'mothers_name',
        'address',
        'pincode',
        'pan_no',
        'aadhar_no',
        'dl_no',
        'voter_id',
        'passport_no',
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function bloodGroup(){
        return $this->hasOne(BloodGroup::class);
    }

    public function gender(){
        return $this->hasOne(Gender::class);
    }
}
