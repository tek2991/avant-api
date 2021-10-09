<?php

namespace App\Http\Controllers\API\v1\Student;

use Auth;
use App\Models\User;
use App\Models\Student;
use Illuminate\Http\Request;
use App\Models\SectionStandard;
use App\Http\Controllers\Controller;

class StudentTrashedController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $this->validate($request, [
            'search_string' => 'max:255',
        ]);

        return User::role('student')->whereHas('userDetail', function ($query) use ($request) {
            $query->where('name', 'ILIKE', '%' . $request->search_string . '%');
        })->whereHas('studentTrashed')->with([
            'studentTrashed',
            'userDetail',
            'roles:id,name',
            'studentTrashed.sectionStandard.section',
            'studentTrashed.sectionStandard.standard',
        ])->paginate();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (Auth::user()->hasRole('director') !== true) {
            return response([
                'header' => 'Forbidden',
                'message' => 'Please Logout and Login again.'
            ], 401);
        }
        $this->validate($request, [
            'student_ids' => 'exists:students,id'
        ]);
        Student::destroy($request->student_ids);
        return response('OK', 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Student  $student
     * @return \Illuminate\Http\Response
     */
    public function show(Student $student)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Student  $student
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        if (Auth::user()->hasRole('director') !== true) {
            return response([
                'header' => 'Forbidden',
                'message' => 'Please Logout and Login again.'
            ], 401);
        }

        $this->validate($request, [
            'section_id' => 'required|min:1|exists:sections,id',
            'standard_id' => 'required|min:1|exists:standards,id',
            'roll_no' => 'required|max:255',
        ]);

        
        $user->studentTrashed->restore();

        $student = $user->student;
        
        $section_standard_id = SectionStandard::where('section_id', $request->section_id)->where('standard_id', $request->standard_id)->firstOrFail()->id;
        $student->update([
            'section_standard_id' => $section_standard_id,
            'roll_no' => $request->roll_no,
        ]);

        return $student;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Student  $student
     * @return \Illuminate\Http\Response
     */
    public function destroy(Student $student)
    {
        //
    }
}
