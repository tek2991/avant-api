<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class ExamSectionStandard extends Pivot
{
    protected $table = 'exam_section_standards';
    protected $fillable = ['exam_id', 'section_standard_id'];

    public function exam()
    {
        return $this->belongsTo(Exam::class);
    }

    public function sectionStandard()
    {
        return $this->belongsTo(SectionStandard::class);
    }
}
