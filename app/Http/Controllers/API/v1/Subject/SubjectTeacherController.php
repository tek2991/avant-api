<?php

namespace App\Http\Controllers\API\v1\Subject;

use Exception;
use App\Models\Subject;
use App\Models\Teacher;
use Illuminate\Http\Request;
use App\Models\SubjectTeacher;
use App\Http\Controllers\Controller;

class SubjectTeacherController extends Controller
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

        return SubjectTeacher::where('subject_id', $request->subject_id)->with(['teacher.user:id','teacher.user.userDetail:user_id,name'])->paginate();
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
            'teacher_id' => 'required|min:1|exists:teachers,id',
        ]);

        $teacher = [$request->teacher_id];

        return Subject::find($request->subject_id)->teachers()->syncWithoutDetaching($teacher);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\SubjectTeacher  $subjectTeacher
     * @return \Illuminate\Http\Response
     */
    public function show(SubjectTeacher $subjectTeacher)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\SubjectTeacher  $subjectTeacher
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, SubjectTeacher $subjectTeacher)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\SubjectTeacher  $subjectTeacher
     * @return \Illuminate\Http\Response
     */
    public function destroy(SubjectTeacher $subjectTeacher)
    {
        try {
            $subjectTeacher->delete();
        } catch (Exception $ex) {
            return response([
                'header' => 'Dependency error',
                'message' => 'Other resources depend on this record.'
            ], 418);
        }

        return response('', 204);
    }
}
