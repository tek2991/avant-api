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
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ExamUser  $examUser
     * @return \Illuminate\Http\Response
     */
    public function show(ExamUser $examUser)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ExamUser  $examUser
     * @return \Illuminate\Http\Response
     */
    public function destroy(ExamUser $examUser)
    {
        //
    }
}
