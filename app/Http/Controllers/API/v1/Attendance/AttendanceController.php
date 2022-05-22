<?php

namespace App\Http\Controllers\API\v1\Attendance;


use App\Models\User;
use App\Models\Session;
use App\Models\Student;
use App\Models\Attendance;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Models\AttendanceState;
use App\Models\SectionStandard;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class AttendanceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //    
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        if ($user->hasRole('director') !== true && $user->hasRole('teacher') !== true) {
            return response([
                'header' => 'Forbidden',
                'message' => 'Please Logout and Login again.'
            ], 401);
        }

        $this->validate($request, [
            'user_id' => 'required|min:1|exists:users,id',
            'attendance_date' => 'required|date',
            'attendance_state_id' => 'required|min:1|exists:attendance_states,id',
        ]);

        $student = User::find($request->user_id)->student()->exists() ? User::find($request->user_id)->student : false;

        if ($student == false) {
            return response([
                'header' => 'Error',
                'message' => 'Student not found.'
            ], 401);
        }

        $sectionStandard = $student->sectionStandard;

        $canProceed = false;

        
        if ($user->hasRole('teacher') === true) {
            $user->teacher->id === $sectionStandard->teacher_id ? $canProceed = true : false;
        }
        
        $user->hasRole('director') === true ? $canProceed = true : false;

        if ($canProceed == false) {
            return response([
                'header' => 'Forbidden',
                'message' => 'Please Logout and Login again.'
            ], 401);
        }

        $session = Session::where('is_active', true)->firstOrFail();

        return Attendance::Create([
            'user_id' => $request->user_id,
            'attendance_date' => $request->attendance_date,
            'attendance_state_id' => $request->attendance_state_id,
            'section_standard_id' => $sectionStandard->id,
            'session_id' => $session->id,
            'created_by' => $user->id,
            'updated_by' => $user->id,
        ]);

        // return Attendance::updateOrCreate([
        //     'user_id' => $request->user_id,
        //     'attendance_date' => $request->attendance_date,
        // ], [
        //     'section_standard_id' => $sectionStandard->id,
        //     'session_id' => $session->id,
        //     'attendance_state_id' => $request->attendance_state_id,
        //     'created_by' => $user->id,
        //     'updated_by' => $user->id,
        // ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Attendance  $attendance
     * @return \Illuminate\Http\Response
     */
    public function show(SectionStandard $sectionStandard, Request $request)
    {
        $user = Auth::user();
        if ($user->hasRole('director') !== true && $user->hasRole('teacher') !== true) {
            return response([
                'header' => 'Forbidden',
                'message' => 'Please Logout and Login again.'
            ], 401);
        }

        $this->validate($request, [
            // 'section_standard_id' => 'required|min:1|exists:section_standard,id',
            'attendance_date' => 'required|date',
        ]);

        // $sectionStandard = SectionStandard::where('id', $request->section_standard_id)->firstOrFail();

        $canProceed = false;

        
        if ($user->hasRole('teacher') === true) {
            $user->teacher->id === $sectionStandard->teacher_id ? $canProceed = true : false;
        }

        $user->hasRole('director') === true ? $canProceed = true : false;

        if ($canProceed == false) {
            return response([
                'header' => 'Forbidden',
                'message' => 'Please Logout and Login again.'
            ], 401);
        }

        $students = $sectionStandard->students()->with([
            'user:id', 'user.userDetail:id,user_id,name',
            'user.attendances' => function ($query) use ($request) {
                $query->where('attendance_date', 'like', '%' . $request->attendance_date . '%');
            }
        ])->select(['id', 'user_id', 'section_standard_id', 'roll_no'])->orderBy('roll_no')->paginate();

        $sectionStandard = $sectionStandard->with(['section', 'standard', 'teacher:id,user_id', 'teacher.user:id', 'teacher.user.userDetail:id,user_id,name'])->find($sectionStandard->id);

        return response(compact('sectionStandard', 'students'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Attendance  $attendance
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Attendance $attendance)
    {
        $user = Auth::user();
        if ($user->hasRole('director') !== true && $user->hasRole('teacher') !== true) {
            return response([
                'header' => 'Forbidden',
                'message' => 'Please Logout and Login again.'
            ], 401);
        }

        $this->validate($request, [
            'user_id' => 'required|min:1|exists:users,id',
            'attendance_date' => 'required|date',
            'attendance_state_id' => 'required|min:1|exists:attendance_states,id',
        ]);

        $student = User::find($request->user_id)->student()->exists() ? User::find($request->user_id)->student : false;

        if ($student == false) {
            return response([
                'header' => 'Error',
                'message' => 'Student not found.'
            ], 401);
        }

        $sectionStandard = $student->sectionStandard;

        $canProceed = false;

        
        if ($user->hasRole('teacher') === true) {
            $user->teacher->id === $sectionStandard->teacher_id ? $canProceed = true : false;
        }
        
        $user->hasRole('director') === true ? $canProceed = true : false;

        if ($canProceed == false) {
            return response([
                'header' => 'Forbidden',
                'message' => 'Please Logout and Login again.'
            ], 401);
        }

        $session = Session::where('is_active', true)->firstOrFail();

        $attendance->updateOrCreate(
            [
                'user_id' => $request->user_id,
                'attendance_date' => $request->attendance_date,
            ],
            [
                'attendance_state_id' => $request->attendance_state_id,
                'section_standard_id' => $sectionStandard->id,
                'session_id' => $session->id,
                'updated_by' => $user->id,
            ]
        );

        return $attendance;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Attendance  $attendance
     * @return \Illuminate\Http\Response
     */
    public function destroy(Attendance $attendance)
    {
        //
    }
}
