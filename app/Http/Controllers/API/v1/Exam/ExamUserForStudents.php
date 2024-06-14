<?php

namespace App\Http\Controllers\API\v1\Exam;

use App\Models\ExamUser;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class ExamUserForStudents extends Controller
{
    public function all(Request $request){
        $user = Auth::user();
        $this->validate($request, [
            'exam_id' => 'bail|nullable|integer|exists:exams,id',
        ]);

        $query = ExamUser::where('user_id', $user->id);

        if($request->filled('exam_id')){
            $query->where('exam_id', $request->exam_id);
        }

        $exam_users = $query->with('examUserState')->get();

        return $exam_users;
    }
}
