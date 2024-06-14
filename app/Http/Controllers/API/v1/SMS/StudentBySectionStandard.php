<?php

namespace App\Http\Controllers\API\v1\SMS;

use Illuminate\Http\Request;
use App\Models\SectionStandard;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class StudentBySectionStandard extends Controller
{
    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Attendance  $attendance
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        if ($user->hasRole('director') !== true) {
            return response([
                'header' => 'Forbidden',
                'message' => 'Please Logout and Login again.'
            ], 401);
        }

        $classrooms = SectionStandard::with([
            'students.user.userDetail:id,user_id,name', 'standard', 'section'
        ])->get();

        return $classrooms;
    }
}
