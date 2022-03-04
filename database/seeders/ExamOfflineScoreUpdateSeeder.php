<?php

namespace Database\Seeders;

use App\Models\ExamAnswer;
use App\Models\ExamQuestion;
use Illuminate\Database\Seeder;
use App\Models\ExamSubjectScore;

class ExamOfflineScoreUpdateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $exam_subject_scores = ExamSubjectScore::where('exam_subject_state_id', 3)->get();
        foreach($exam_subject_scores as $score){
            $exam_subject = $score->examSubject;
            $full_mark = $exam_subject->full_mark;
            $pass_mark = $exam_subject->pass_mark;

            $random_score = rand(rand(0, $pass_mark + 10), $full_mark);
            $score->update([
                'marks_secured' => $random_score,
                'evaluated_by' => 2,
            ]);
        }
    }
}
