<?php

namespace App\Http\Controllers\API\v1\Exam;

use App\Models\Exam;
use App\Models\ExamUser;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class ExamUserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Exam $exam, Request $request)
    {
        $user = Auth::user();
        if ($user->hasRole('director') !== true && $user->hasRole('teacher') !== true && $request->filled('user_id') !== true) {
            return response([
                'header' => 'Forbidden',
                'message' => 'Please Logout and Login again.'
            ], 401);
        }

        $this->validate($request, [
            'standard_id' => 'bail|nullable|integer|exists:standards,id',
            'section_id' => 'bail|nullable|integer|exists:sections,id',
            'user_id' => 'bail|nullable|integer|exists:users,id',
        ]);

        $query = ExamUser::where('exam_id', $exam->id);
        $query = $query->whereHas('user', function ($query) {
            if (request()->filled('user_id')) {
                $query->where('id', request()->user_id);
            }
            $query->whereHas('student', function ($query) {
                $query->whereHas('sectionStandard', function ($query) {
                    $query->whereHas('standard', function ($query) {
                        if (request()->filled('standard_id')) {
                            $query->where('id', request()->standard_id);
                        }
                    });
                    $query->whereHas('section', function ($query) {
                        if (request()->filled('section_id')) {
                            $query->where('id', request()->section_id);
                        }
                    });
                });
            });
        })
            ->select('exam_user.*')
            ->join('users', 'users.id', '=', 'exam_user.user_id')
            ->join('students', 'students.user_id', '=', 'users.id')
            ->orderBy('students.section_standard_id')->orderBy('students.roll_no');
        $exam_users = $query->with(
            'examUserState:id,name',
            'user:id',
            'user.userDetail:id,user_id,name',
            'user.student:id,user_id,section_standard_id,roll_no',
            'user.student.sectionStandard.section',
            'user.student.sectionStandard.standard'
        )->paginate();
        return $exam_users;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ExamUser  $examUser
     * @return \Illuminate\Http\Response
     */
    public function update(ExamUser $examUser, Request $request)
    {
        $user = Auth::user();
        if ($user->hasRole('director') !== true && $user->hasRole('teacher') !== true) {
            return response([
                'header' => 'Forbidden',
                'message' => 'Please Logout and Login again.'
            ], 401);
        }
        $this->validate($request, [
            'exam_user_state_id' => 'bail|required|integer|exists:exam_user_states,id',
        ]);
        $examUser->update([
            'exam_user_state_id' => $request->exam_user_state_id,
        ]);
        return response([
            'header' => 'Success',
            'message' => 'Exam User State Updated Successfully.',
            'data' => $examUser,
            'request' => $request->all(),
        ], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ExamUser  $examUser
     * @return \Illuminate\Http\Response
     */

    public function show(ExamUser $examUser)
    {
        $user = Auth::user();
        if ($user->hasRole('director') !== true) {
            return response([
                'header' => 'Forbidden',
                'message' => 'Please Logout and Login again.'
            ], 401);
        }
        return $examUser->load('examUserState:id,name');
    }
}
