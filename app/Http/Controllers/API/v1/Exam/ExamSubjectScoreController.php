<?php

namespace App\Http\Controllers\API\v1\Exam;

use Illuminate\Http\Request;
use App\Models\ExamSubjectScore;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class ExamSubjectScoreController extends Controller
{
    public function update(ExamSubjectScore $examSubjectScore, Request $request)
    {
        $user = Auth::user();
        if ($user->hasRole('director') !== true && $user->hasRole('teacher') !== true) {
            return response([
                'header' => 'Forbidden',
                'message' => 'Please Logout and Login again.'
            ], 401);
        }

        if($user->hasRole('teacher') === true) {
            $subject_ids = $user->teacher->subjects()->pluck('subject_id')->toArray();
            $subject_id = $examSubjectScore->examSubject->subject_id;
            if(!in_array($subject_id, $subject_ids)) {
                return response([
                    'header' => 'Forbidden',
                    'message' => 'You are not authorized to access this resource.'
                ], 403);
            }
        }

        $this->validate($request, [
            'marks_secured' => 'required|numeric',
        ]);

        
        $full_mark = $examSubjectScore->examSubject->full_mark;
        $marks_secured = $request->marks_secured;

        if ($marks_secured > $full_mark) {
            return response([
                'header' => 'Forbidden',
                'message' => 'Marks secured cannot be greater than full marks.',
            ], 403);
        }
        
        $examSubjectScore->update([
            'marks_secured' => $marks_secured,
            'evaluated_by' => $user->id,
        ]);

        return response([
            'header' => 'Success',
            'message' => 'Marks secured updated successfully.',
        ], 200);
    }
}
