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
        if ($user->hasRole('director') !== true) {
            return response([
                'header' => 'Forbidden',
                'message' => 'Please Logout and Login again.'
            ], 401);
        }

        $users = $examSubject->users()
            ->join('students', 'students.user_id', '=', 'users.id')
            ->orderBy('students.section_standard_id')->orderBy('students.roll_no')
            ->with('userDetail', 'student', 'student.sectionStandard', 'student.sectionStandard.standard', 'student.sectionStandard.section')->get();
        return $users;
    }
}
