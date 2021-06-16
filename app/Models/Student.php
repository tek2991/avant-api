<?php

namespace App\Models;

use App\Models\User;
use App\Models\Chargeable;
use App\Models\SectionStandard;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Student extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'section_standard_id',
        'roll_no',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    public function sectionStandard(){
        return $this->belongsTo(SectionStandard::class);
    }

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function chargeables(){
        return $this->belongsToMany(Chargeable::class)->withPivot('id')->withTimestamps();
    }
}
