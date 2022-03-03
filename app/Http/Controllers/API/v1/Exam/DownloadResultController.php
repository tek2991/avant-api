<?php

namespace App\Http\Controllers\API\v1\Exam;

use App\Models\Exam;
use App\Models\User;
use App\Models\Subject;
use App\Models\Standard;
use App\Models\ExamSubject;
use Illuminate\Http\Request;
use App\Exports\ClassExamResult;
use App\Models\ExamSubjectScore;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use Laravel\Sanctum\PersonalAccessToken;

class DownloadResultController extends Controller
{
    public function standard(Request $request)
    {
        $this->validate($request, [
            'pat' => 'required|string',
            'exam_id' => 'required|integer|exists:exams,id',
            'standard_id' => 'required|integer|exists:standards,id',
        ]);
        if (strpos($request->pat, '|') === false) {
            return response([
                'header' => 'Forbidden',
                'message' => 'You are not authorised to read this resource!'
            ], 401);
        }

        $patArray = explode("|", $request->pat);
        $model_id = $patArray[0];
        $token = $patArray[1];
        $pas = PersonalAccessToken::findOrFail($model_id);

        if (!hash_equals($pas->token, hash('sha256', $token))) {
            return response([
                'header' => 'Forbidden',
                'message' => 'You are not authorised to read this resource!'
            ], 401);
        }

        $user = $pas->tokenable;

        if ($user->hasRole('student') !== true && $user->hasRole('director') !== true) {
            return response([
                'header' => 'Forbidden',
                'message' => 'Please Logout and Login again'
            ], 401);
        }

        $exam = Exam::find($request->exam_id);
        $standard = Standard::find($request->standard_id);

        $subject_ids = Subject::where('standard_id', $standard->id)->pluck('id')->toArray();
        $exam_subject_ids = ExamSubject::where('exam_id', $exam->id)->whereIn('subject_id', $subject_ids)->pluck('id')->toArray();

        $name = $exam->name . '_class_' . $standard->name . '.xlsx';
        return Excel::download(new ClassExamResult($exam_subject_ids, $request->exam_id), $name);
    }
}
