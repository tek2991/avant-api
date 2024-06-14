<?php

namespace Database\Seeders;

use App\Models\ExamType;
use App\Models\ExamAnswerState;
use Illuminate\Database\Seeder;
use App\Models\ExamQuestionType;
use App\Models\ExamSubjectState;

class ExamAttributesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {   
        $examTypes = ['Online', 'Offline'];

        foreach($examTypes as $examType){
            ExamType::create([
                'name' => $examType,
            ]);
        }

        $examSubjectStates = ['Created', 'Active', 'Evaluating', 'Locked'];

        foreach($examSubjectStates as $examSubjectState){
            ExamSubjectState::create([
                'name' => $examSubjectState,
            ]);
        }

        $examQuestionTypes = ['Objective', 'Descriptive'];

        foreach($examQuestionTypes as $examQuestionType){
            ExamQuestionType::create([
                'name' => $examQuestionType,
            ]);
        }

        $examAnswerStates = ['Created', 'Answered', 'Skiped'];

        foreach($examAnswerStates as $examAnswerState){
            ExamAnswerState::create([
                'name' => $examAnswerState,
            ]);
        }
    }
}
