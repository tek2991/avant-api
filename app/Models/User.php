<?php

namespace App\Models;

use App\Models\Appeal;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\Attendance;
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
        'remember_token',
        'created_at',
        'updated_at',
        'remember_token',
        'email_verified_at'
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

    public function attendances(){
        return $this->hasMany(Attendance::class);
    }

    public function userDetail(){
        return $this->hasOne(UserDetail::class);
    }

    public function student(){
        return $this->hasOne(Student::class);
    }

    public function studentWithTrashed(){
        return $this->hasOne(Student::class)->withTrashed();
    }

    public function studentTrashed(){
        return $this->hasOne(Student::class)->onlyTrashed();
    }

    public function teacher(){
        return $this->hasOne(Teacher::class);
    }

    public function smsRecords(){
        return $this->hasMany(SmsRecord::class);
    }

    public function examAnswers(){
        return $this->hasMany(ExamAnswer::class);
    }

    public function examSubjects(){
        return $this->belongsToMany(ExamSubject::class, 'exam_subject_scores', 'user_id', 'exam_subject_id')->withPivot('id', 'marks_secured')->withTimestamps();
    }

    public function notifications()
    {
        return $this->belongsToMany(Notification::class)->withPivot('id')->withTimestamps();
    }

    public function profilePicture(){
        return $this->morphOne(Image::class, 'imageable');
    }

    public function tinymces(){
        return $this->hasMany(Tinymce::class);
    }
}
