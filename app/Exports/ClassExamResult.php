<?php

namespace App\Exports;

use App\Models\Exam;
use App\Models\ExamSubjectScore;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class ClassExamResult implements FromCollection, WithMapping, WithHeadings
{
    protected $exam_subject_ids;
    protected $exam;
    function __construct($exam_subject_ids, $exam_id)
    {
        $this->exam_subject_ids = $exam_subject_ids;
        $this->exam = Exam::find($exam_id);
    }
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $query = ExamSubjectScore::whereIn('exam_subject_id', $this->exam_subject_ids)
        // Must have user, which must have student
        ->whereHas('user', function($q){
            $q->whereHas('student');
        });

        $query
            ->join('users', 'users.id', '=', 'exam_subject_scores.user_id')
            ->join('students', 'students.user_id', '=', 'users.id')
            ->orderBy('students.section_standard_id')->orderBy('students.roll_no');

        $exam_subject_scores = $query
            ->with(
                'user:id',
                'user.userDetail:id,user_id,name',
                'user.student:id,user_id,section_standard_id,roll_no',
                'user.student.sectionStandard.section',
                'user.student.sectionStandard.standard',
                'user.profilePicture:id,imageable_id,imageable_type,url',
                'examSubject:id,subject_id,full_mark,pass_mark,negative_percentage',
                'examSubject.subject:id,name',
            )
            ->get();
            // ->groupBy('user_id');

        return $exam_subject_scores;
    }

    public function map($score): array
    {
        return [
            $this->exam->session->name,
            $this->exam->name,
            $this->exam->examType->name,
            $score->examSubject->subject->name,
            $score->examSubjectState->name,
            $score->examSubject->full_mark,
            $score->examSubject->pass_mark,
            $score->examSubject->negative_percentage . ' %',
            $score->user->userDetail->name,
            $score->user->student->sectionStandard->standard->name,
            $score->user->student->sectionStandard->section->name,
            $score->user->student->roll_no,
            $score->marks_secured,
            $score->examSubject->pass_mark <= $score->marks_secured ? 'Pass' : 'Fail',
            $score->user->id,
            optional($score->user->profilePicture)->url ?? null,
        ];
    }

    public function headings(): array
    {
        return [
            'Academic session',
            'Exam Name',
            'Exam Mode',
            'Subject Name',
            'Subject Status',
            'Full Mark',
            'Pass Mark',
            'Negative Percentage',
            'Student Name',
            'Class',
            'Section',
            'Roll No',
            'Marks Secured',
            'Result',
            'user_id',
            'profile_photo_url',
        ];
    }
}
