<?php

namespace App\Http\Controllers\API\v1\Student;

use App\Models\User;
use App\Models\Student;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;

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
    public function update(Request $request, Student $student)
    {
        //
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
