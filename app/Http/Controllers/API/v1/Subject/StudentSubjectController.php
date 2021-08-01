<?php

namespace App\Http\Controllers\API\v1\Subject;

use Exception;
use App\Models\Subject;
use Illuminate\Http\Request;
use App\Models\StudentSubject;
use App\Http\Controllers\Controller;

class StudentSubjectController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $this->validate($request, [
            'subject_id' => 'required|min:1|exists:subjects,id',
        ]);

        return Subject::find($request->subject_id)->standard->students()->with([
            'subjects' => function ($query) use ($request) {
                $query->select('subject_id')->where('subject_id', $request->subject_id);
            }, 
            'user:id','user.userDetail:user_id,name', 'sectionStandard:id,section_id', 'sectionStandard.section:id,name'
        ])->orderBy('section_standard_id')->orderBy('roll_no')->paginate();
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
            'subject_id' => 'required|min:1|exists:subjects,id',
            'student_id' => 'required|min:1|exists:students,id',
        ]);

        $student = [$request->student_id];

        return Subject::find($request->subject_id)->students()->syncWithoutDetaching($student);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\StudentSubject  $studentSubject
     * @return \Illuminate\Http\Response
     */
    public function show(StudentSubject $studentSubject)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\StudentSubject  $studentSubject
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, StudentSubject $studentSubject)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     * 
     * @param  \App\Models\StudentSubject  $studentSubject
     * @return \Illuminate\Http\Response
     */
    public function destroy(StudentSubject $studentSubject)
    {
        try {
            $studentSubject->delete();
        } catch (Exception $ex) {
            return response([
                'header' => 'Dependency error',
                'message' => 'Other resources depend on this record.'
            ], 418);
        }

        return response('', 204);
    }
}
