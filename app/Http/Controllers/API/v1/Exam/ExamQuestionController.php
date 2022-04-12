<?php

namespace App\Http\Controllers\API\v1\Exam;

use Exception;
use App\Models\ExamSubject;
use App\Models\ExamQuestion;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ExamQuestionController extends Controller
{
    public function index(ExamSubject $examSubject)
    {
        $examQuestions = ExamQuestion::where('exam_subject_id', $examSubject->id)->with('examQuestionOptions', 'examQuestionType')->orderBy('id')->paginate();
        return $examQuestions;
    }

    public function show(ExamQuestion $examQuestion)
    {
        $user = Auth::user();

        if ($user->hasRole('student') !== true) {
            return response([
                'header' => 'Forbidden',
                'message' => 'Please Logout and Login again.'
            ], 401);
        }

        $question = $examQuestion->load('examQuestionOptions', 'examQuestionType');

        return $question;
    }

    /**
     * Display a listing of all the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function all(ExamSubject $examSubject)
    {
        $user = Auth::user();
        if ($user->hasRole('director') !== true) {
            return response([
                'header' => 'Forbidden',
                'message' => 'Please Logout and Login again.'
            ], 401);
        }
        $exam_questions = ExamQuestion::where('exam_subject_id', $examSubject->id)->with('examQuestionOptions', 'examQuestionType')->orderBy('id')->get();
        return $exam_questions;
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        if ($user->hasRole('director') !== true && $user->hasRole('teacher') !== true) {
            return response([
                'header' => 'Forbidden',
                'message' => 'Please Logout and Login again.'
            ], 401);
        }

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
            'images' => 'nullable|array',
            'images.*' => 'nullable|string',
        ]);

        if ($user->hasRole('teacher') == true && $user->hasRole('director') !== true) {
            $subject_id = ExamSubject::find($request->exam_subject_id)->subject_id;
            $subject_ids = $user->teacher->subjects()->pluck('subject_id');
            if (!in_array($subject_id, $subject_ids->toArray())) {
                return response([
                    'header' => 'Forbidden',
                    'message' => 'You are not authorized to perform this action.'
                ], 403);
            }
        }

        $full_mark = ExamSubject::find($request->exam_subject_id)->full_mark;
        $total_marks = ExamQuestion::where('exam_subject_id', $request->exam_subject_id)->sum('marks');
        $remaining_marks = $full_mark - $total_marks;

        if ($request->mark > $remaining_marks) {
            return response()->json([
                'message' => 'You can not add more than ' . $remaining_marks . ' marks.'
            ], 422);
        }

        try {
            // Move images to storage
            foreach ($request->images as $image_file_name) {
                Storage::move('public/tiny_mce_uploaded_imgs/' . $image_file_name, 'public/exam_question_images/' .  $image_file_name);
            }

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

    public function update(ExamQuestion $examQuestion, Request $request)
    {
        $user = Auth::user();
        if ($user->hasRole('director') !== true && $user->hasRole('teacher') !== true) {
            return response([
                'header' => 'Forbidden',
                'message' => 'Please Logout and Login again.'
            ], 401);
        }

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

        if ($user->hasRole('director') != true && $user->hasRole('teacher') == true) {
            $subject_id = ExamSubject::find($request->exam_subject_id)->subject_id;
            $subject_ids = $user->teacher->subjects()->pluck('subject_id');
            if (!in_array($subject_id, $subject_ids->toArray())) {
                return response([
                    'header' => 'Forbidden',
                    'message' => 'You are not authorized to perform this action.'
                ], 403);
            }
        }

        $full_mark = ExamSubject::find($examQuestion->exam_subject_id)->full_mark;
        $total_marks = ExamQuestion::where('exam_subject_id', $examQuestion->exam_subject_id)->whereNotIn('id', [$examQuestion->id])->sum('marks');
        $remaining_marks = $full_mark - $total_marks;

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

    public function destroy(ExamQuestion $examQuestion)
    {
        $user = Auth::user();
        if ($user->hasRole('director') !== true && $user->hasRole('teacher') !== true) {
            return response([
                'header' => 'Forbidden',
                'message' => 'Please Logout and Login again.'
            ], 401);
        }

        if ($user->hasRole('teacher') == true) {
            $exam_subject_id = $examQuestion->examSubject->id;
            $subject_id = ExamSubject::find($exam_subject_id)->subject_id;
            $subject_ids = $user->teacher->subjects()->pluck('subject_id');
            if (!in_array($subject_id, $subject_ids->toArray())) {
                return response([
                    'header' => 'Forbidden',
                    'message' => 'You are not authorized to perform this action.'
                ], 403);
            }
        }

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
