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
    public function index(Exam $exam)
    {
        $user = Auth::user();
        if ($user->hasRole('director') !== true && $user->hasRole('teacher') !== true) {
            return response([
                'header' => 'Forbidden',
                'message' => 'Please Logout and Login again.'
            ], 401);
        }

        $this->validate(request(), [
            'standard_id' => 'bail|nullable|integer|exists:standards,id',
            'section_id' => 'bail|nullable|integer|exists:sections,id',
        ]);

        $exam_users = ExamUser::where('exam_id', $exam->id)
        ->whereHas('user', function ($query) {
            $query->whereHas('student', function ($query) {
                $query->whereHas('sectionStandard', function ($query) {
                    $query->whereHas('standard', function ($query) {
                        if(request()->filled('standard_id')) {
                            $query->where('id', request()->standard_id);
                        }
                        $query->orderBy('id');
                    });

                    $query->whereHas('section', function ($query) {
                        if(request()->filled('section_id')) {
                            $query->where('id', request()->section_id);
                        }
                        $query->orderBy('id');
                    });

                    $query->orderBy('roll_no');
                });
            });
        })
        ->with('user.userDetail', 'user.student.sectionStandard.section', 'user.student.sectionStandard.standard')->paginate();

        return $exam_users;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ExamUser  $examUser
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ExamUser $examUser)
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

        return response ([
            'header' => 'Success',
            'message' => 'Exam User State Updated Successfully.'
        ], 200);
    }
}
