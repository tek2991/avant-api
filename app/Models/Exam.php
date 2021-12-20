<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Exam extends Model
{
    use HasFactory;

    protected $fillable = [
        'session_id',
        'name',
        'description',
        'exam_type_id',
        'exam_start_date',
        'exam_end_date',
        'created_by',
    ];

    protected $cast = [
        'exam_start_date' => 'datetime',
        'exam_end_date' => 'datetime',
    ];

    protected $dates = ['exam_start_date', 'exam_end_date'];

    public function session()
    {
        return $this->belongsTo(Session::class);
    }

    public function examType()
    {
        return $this->belongsTo(ExamType::class);
    }

    public function examSchedules()
    {
        return $this->hasMany(ExamSchedule::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

    public function sectionStandards()
    {
        return $this->belongsToMany(SectionStandard::class, 'exam_section_standard', 'exam_id', 'section_standard_id')->withPivot('id')->withTimestamps();
    }

    public function subjects()
    {
        return $this->belongsToMany(Subject::class, 'exam_subject', 'exam_id', 'subject_id')->withPivot([
            'id',
            'exam_schedule_id',
            'full_mark',
            'pass_mark',
            'negative_percentage',
            'exam_subject_state_id',
            'auto_start',
        ])->withTimestamps();
    }
}
