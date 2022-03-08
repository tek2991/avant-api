<?php

namespace Database\Seeders;

use App\Models\ExamAnswer;
use App\Models\ExamQuestion;
use Illuminate\Database\Seeder;
use App\Models\ExamSubjectScore;

class ExamOnlineScoreUpdateSeeder extends Seeder
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
            $exam_question_ids = ExamQuestion::where('exam_subject_id', $score->exam_subject_id)->pluck('id');
            $total_marks_secured = ExamAnswer::where('user_id', $score->user_id)->whereIn('exam_question_id', $exam_question_ids)->sum('marks_secured');
            $score->update([
                'marks_secured' => $total_marks_secured,
                'evaluated_by' => 2,
            ]);
        }
    }
}
