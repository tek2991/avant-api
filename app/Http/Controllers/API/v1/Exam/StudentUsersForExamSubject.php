<?php

namespace App\Http\Controllers\API\v1\Exam;

use App\Models\ExamSubject;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class StudentUsersForExamSubject extends Controller
{
    public function all(ExamSubject $examSubject)
    {
        $user = Auth::user();
        if ($user->hasRole('director') !== true && $user->hasRole('teacher') !== true) {
            return response([
                'header' => 'Forbidden',
                'message' => 'Please Logout and Login again.'
            ], 401);
        }

        if($user->hasRole('teacher') === true && $user->hasRole('director') !== true) {
            $subject_ids = $user->teacher->subjects()->pluck('subject_id')->toArray();
            $subject_id = $examSubject->subject_id;
            if(!in_array($subject_id, $subject_ids)) {
                return response([
                    'header' => 'Forbidden',
                    'message' => 'You are not authorized to access this resource.'
                ], 403);
            }
        }
        

        $users = $examSubject->users()
            ->join('students', 'students.user_id', '=', 'users.id')
            ->orderBy('students.section_standard_id')->orderBy('students.roll_no')
            ->with('userDetail', 'student', 'student.sectionStandard', 'student.sectionStandard.standard', 'student.sectionStandard.section')
            ->get();
        return $users;
    }
}
