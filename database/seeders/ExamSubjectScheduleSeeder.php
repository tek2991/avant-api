<?php

namespace Database\Seeders;

use App\Models\Exam;
use App\Models\ExamSubject;
use Illuminate\Database\Seeder;

class ExamSubjectScheduleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $exams = Exam::get();

        foreach($exams as $exam){
            $exam_subjects = ExamSubject::where('exam_id', $exam->id)->get();
            $exam_schedules = $exam->examSchedules()->get();

            foreach($exam_subjects as $exam_subject){
                $exam_subject->update([
                    'exam_schedule_id' => $exam_schedules->random()->id,
                ]);
            }
        }
    }
}
