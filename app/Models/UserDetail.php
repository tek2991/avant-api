<?php

namespace App\Models;

use App\Models\User;
use App\Models\Gender;
use App\Models\Language;
use App\Models\BloodGroup;
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
        'language_id',
        'religion_id',
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

        /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'created_at',
        'updated_at',
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

    public function language(){
        return $this->hasOne(Language::class);
    }

    public function religion(){
        return $this->hasOne(Religion::class);
    }
}
