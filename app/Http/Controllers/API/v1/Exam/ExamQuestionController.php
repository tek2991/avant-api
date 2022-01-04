<?php

namespace App\Http\Controllers\API\v1\Exam;

use App\Models\ExamSubject;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\ExamQuestion;
use Exception;

class ExamQuestionController extends Controller
{
    public function index(ExamSubject $examSubject)
    {
        $examQuestions = ExamQuestion::where('exam_subject_id', $examSubject->id)->with('examQuestionOptions', 'examQuestionType')->paginate();
        return $examQuestions;
    }

    public function store(Request $request)
    {
        $user = auth()->user();

        $this->validate($request, [
            'exam_subject_id' => 'required|exists:exam_subject,id',
            'chapter_id' => 'nullable|exists:chapters,id',
            'exam_question_type_id' => 'required|exists:exam_question_types,id',
            'question' => 'required',
            'mark' => 'required|numeric',
            'max_time_in_seconds' => 'nullable|numeric',
            'option_1' => 'requiredIf:exam_question_type_id,1',
            'option_2' => 'requiredIf:exam_question_type_id,1',
            'option_3' => 'requiredIf:exam_question_type_id,1',
            'option_4' => 'requiredIf:exam_question_type_id,1',
            'answer' => 'requiredIf:exam_question_type_id,1',
        ]);

        $full_marks = ExamSubject::find($request->exam_subject_id)->full_marks;
        $total_marks = ExamQuestion::where('exam_subject_id', $request->exam_subject_id)->sum('marks');
        $remaining_marks = $full_marks - $total_marks;

        if ($request->mark > $remaining_marks) {
            return response()->json([
                'message' => 'You can not add more than ' . $remaining_marks . ' marks.'
            ], 422);
        }

        try {
            $examQuestion = ExamQuestion::create([
                'exam_subject_id' => $request->exam_subject_id,
                'chapter_id' => $request->chapter_id,
                'exam_question_type_id' => $request->exam_question_type_id,
                'description' => $request->question,
                'marks' => $request->mark,
                'max_time_in_seconds' => $request->max_time_in_seconds ? $request->max_time_in_seconds : 0,
                'created_by' => $user->id,
            ]);

            if ($request->exam_question_type_id == 1) {
                $examQuestion->examQuestionOptions()->createMany([
                    [
                        'description' => $request->option_1,
                        'is_correct' => $request->answer == 1 ? true : false,
                    ],
                    [
                        'description' => $request->option_2,
                        'is_correct' => $request->answer == 2 ? true : false,
                    ],
                    [
                        'description' => $request->option_3,
                        'is_correct' => $request->answer == 3 ? true : false,
                    ],
                    [
                        'description' => $request->option_4,
                        'is_correct' => $request->answer == 4 ? true : false,
                    ],
                ]);
            }
            return response([
                'message' => 'Exam Question Created Successfully',
                'data' => $examQuestion,
            ], 200);
        } catch (Exception $ex) {
            return response([
                'message' => 'Something went wrong.',
                'errors' => $ex->getMessage()
            ], 500);
        }
    }

    public function update(ExamQuestion $examQuestion, Request $request){
        $user = auth()->user();

        $this->validate($request, [
            'chapter_id' => 'nullable|exists:chapters,id',
            'exam_question_type_id' => 'required|exists:exam_question_types,id',
            'question' => 'required',
            'mark' => 'required|numeric',
            'max_time_in_seconds' => 'nullable|numeric',
            'option_1' => 'requiredIf:exam_question_type_id,1',
            'option_2' => 'requiredIf:exam_question_type_id,1',
            'option_3' => 'requiredIf:exam_question_type_id,1',
            'option_4' => 'requiredIf:exam_question_type_id,1',
            'answer' => 'requiredIf:exam_question_type_id,1',
        ]);

        $full_marks = ExamSubject::find($examQuestion->exam_subject_id)->full_marks;
        $total_marks = ExamQuestion::where('exam_subject_id', $examQuestion->exam_subject_id)->whereNotIn('id', [$examQuestion->id])->sum('marks');
        $remaining_marks = $full_marks - $total_marks;

        if ($request->mark > $remaining_marks) {
            return response()->json([
                'message' => 'You can not add more than ' . $remaining_marks . ' marks.'
            ], 422);
        }

        try {
            $examQuestion->update([
                'chapter_id' => $request->chapter_id,
                'exam_question_type_id' => $request->exam_question_type_id,
                'description' => $request->question,
                'marks' => $request->mark,
                'max_time_in_seconds' => $request->max_time_in_seconds ? $request->max_time_in_seconds : 0,
                'created_by' => $user->id,
            ]);

            if ($request->exam_question_type_id == 1) {
                $examQuestion->examQuestionOptions()->delete();
                $examQuestion->examQuestionOptions()->createMany([
                    [
                        'description' => $request->option_1,
                        'is_correct' => $request->answer == 1 ? true : false,
                    ],
                    [
                        'description' => $request->option_2,
                        'is_correct' => $request->answer == 2 ? true : false,
                    ],
                    [
                        'description' => $request->option_3,
                        'is_correct' => $request->answer == 3 ? true : false,
                    ],
                    [
                        'description' => $request->option_4,
                        'is_correct' => $request->answer == 4 ? true : false,
                    ],
                ]);
            }
            return response([
                'message' => 'Exam Question Updated Successfully',
                'data' => $examQuestion->load('examQuestionOptions', 'examQuestionType'),
            ], 200);
        } catch (Exception $ex) {
            return response([
                'message' => 'Something went wrong.',
                'errors' => $ex->getMessage()
            ], 500);
        }
    }

    public function destroy(ExamQuestion $examQuestion){
        try {
            $examQuestion->delete();
            return response([
                'message' => 'Exam Question Deleted Successfully',
            ], 200);
        } catch (Exception $ex) {
            return response([
                'message' => 'Something went wrong.',
                'errors' => $ex->getMessage()
            ], 500);
        }
    }
}
