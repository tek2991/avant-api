<?php

namespace App\Http\Controllers\API\v1\Attendance;


use App\Models\User;
use App\Models\Session;
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
        $user = Auth::user();
        if ($user->hasRole('director') !== true && $user->hasRole('teacher') !== true) {
            return response([
                'header' => 'Forbidden',
                'message' => 'Please Logout and Login again.'
            ], 401);
        }

        $this->validate($request, [
            'section_id' => 'required|min:1|exists:sections,id',
            'standard_id' => 'required|min:1|exists:standards,id',
            'attendance_date' => 'required|date',
        ]);

        $sectionStandard = SectionStandard::where('section_id', $request->section_id)->where('standard_id', $request->standard_id)->firstOrFail();

        $canProceed = false;

        $user->hasRole('director') === true ? $canProceed = true : false;

        if ($user->hasRole('teacher') === true) {
            $user->teacher->id === $sectionStandard->teacher_id ? $canProceed = true : false;
        }

        if ($canProceed == false) {
            return response([
                'header' => 'Forbidden',
                'message' => 'Please Logout and Login again.'
            ], 401);
        }

        // $attendances = $sectionStandard->whereHas('students', function ($query) use ($request) {
        //     $query->whereHas('user', function ($query) use ($request) {
        //         $query->whereHas('attendances', function ($query) use ($request) {
        //             $query->where('attendance_date', 'like', '%' . $request->attendance_date . '%');
        //         });
        //     });
        // })->with(['students:id,user_id,section_standard_id,roll_no', 'students.user:id', 'students.user.attendances'])->get();

        $attendances = $sectionStandard->load([
            'students:id,user_id,section_standard_id,roll_no', 'students.user:id', 'students.user.userDetail',
            'students.user.attendances' => function ($query) use ($request){
                $query->where('attendance_date', 'like', '%' . $request->attendance_date . '%');
            }
        ]);

        // $attendances = 'ok';

        return $attendances;
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
            'section_id' => 'required|min:1|exists:sections,id',
            'standard_id' => 'required|min:1|exists:standards,id',
            'attendance_date' => 'required|date',
        ]);

        $sectionStandard = SectionStandard::where('section_id', $request->section_id)->where('standard_id', $request->standard_id)->firstOrFail();

        $canProceed = false;

        $user->hasRole('director') === true ? $canProceed = true : false;

        if($user->hasRole('teacher') === true){
            $user->teacher->id === $sectionStandard->teacher_id ? $canProceed = true : false;
        }

        if($canProceed == false){
            return response([
                'header' => 'Forbidden',
                'message' => 'Please Logout and Login again.'
            ], 401);
        }

        $students = $sectionStandard->students;
        $attendanceStateId = AttendanceState::where('name', 'Not taken')->firstOrFail()->id;
        $sessionId = Session::where('is_active', true)->firstOrFail()->id;
        $data = [];
        $now = Carbon::now('utc')->toDateTimeString();

        foreach($students as $student){
            $data[] = [
                'user_id' => $student->user_id,
                'attendance_state_id' => $attendanceStateId,
                'attendance_date' => $request->attendance_date,
                'section_standard_id' => $sectionStandard->id,
                'session_id' => $sessionId,
                'created_by' => $user->id,
                'updated_by' => $user->id,
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        $chunks = array_chunk($data, 1000);

        foreach($chunks as $chunk){
            Attendance::insert($chunk);
        }

        return response('OK', 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Attendance  $attendance
     * @return \Illuminate\Http\Response
     */
    public function show(Attendance $attendance)
    {
        //
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
            // 'attendance_date' => 'required|date',
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

        $user->hasRole('director') === true ? $canProceed = true : false;

        if ($user->hasRole('teacher') === true) {
            $user->teacher->id === $sectionStandard->teacher_id ? $canProceed = true : false;
        }

        if ($canProceed == false) {
            return response([
                'header' => 'Forbidden',
                'message' => 'Please Logout and Login again.'
            ], 401);
        }



        $attendance->updateOrCreate(
            [
                'user_id' => $request->user_id,
                'attendance_date' => $request->attendance_date,
            ],
            [
                'attendeace_state_id' => $request->attendance_state_id,
                'section_standard_id' => $sectionStandard->id,
                'session_id' => $request->session_id,
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
