<?php

namespace App\Http\Controllers\API\v1\Student;

use Exception;
use App\Models\Student;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
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
        $this->validate($request, [
            'user_id' => 'required|min:1|exists:users,id',
            'section_standard_id' => 'required|min:1|exists:section_standard,id',
            'roll_no' => 'required|max:255',
        ]);

        $studentExist = Student::where('user_id', $request->user_id)->first();

        if($studentExist){
            return response([
                'header' => 'Already exists',
                'message' => 'Student is already allocated.'
            ], 418);
        }

        return Student::create($request->only(['user_id', 'section_standard_id', 'roll_no']));
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
        $this->validate($request, [
            'roll_no' => 'required|max:255',
        ]);

        $student->update([
            'roll_no' => $request->roll_no
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
        try {
            $student->delete();
        } catch (Exception $ex) {
            return response([
                'header' => 'Dependency error',
                'message' => 'Other resources depend on this record.'
            ], 418);
        }

        return response('', 204);
    }
}
