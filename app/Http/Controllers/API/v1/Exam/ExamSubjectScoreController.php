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
        if ($user->hasRole('director') !== true) {
            return response([
                'header' => 'Forbidden',
                'message' => 'Please Logout and Login again.'
            ], 401);
        }

        $this->validate($request, [
            'marks_secured' => 'required|integer',
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
