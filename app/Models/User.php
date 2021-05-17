<?php

namespace App\Models;

use App\Models\Appeal;
use App\Models\Gender;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\Attendence;
use App\Models\FeeInvoice;
use App\Models\UserDetail;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'username',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function feeInvoices(){
        return $this->hasMany(FeeInvoice::class);
    }

    public function appeals(){
        return $this->hasMany(Appeal::class);
    }

    public function attendences(){
        return $this->hasMany(Attendence::class);
    }

    public function userDetail(){
        return $this->hasOne(UserDetail::class);
    }

    public function student(){
        return $this->hasOne(Student::class);
    }

    public function teacher(){
        return $this->hasOne(Teacher::class);
    }
}
