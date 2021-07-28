<?php

namespace App\Http\Controllers\API\v1\Subject;

use Exception;
use App\Models\Subject;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SubjectController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Subject::select(['id', 'name', 'subject_group_id'])->with('subjectGroup:id,name')->paginate();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function all()
    {
        return Subject::orderBy('id')->get();
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
            'name' => 'required|max:255',
            'subject_group_id' => 'required|exists:subject_groups,id',
            'standard_id' => 'required|exists:standards,id',
            'is_mandatory' => 'required|boolean',
        ]);

        return Subject::create([
            'name' => $request->name,
            'subject_group_id' => $request->subject_group_id,
            'standard_id' => $request->standard_id,
            'is_mandatory' => $request->is_mandatory,
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Subject  $subject
     * @return \Illuminate\Http\Response
     */
    public function show(Subject $subject)
    {
        return $subject->with('subjectGroup')->get();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Subject  $subject
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Subject $subject)
    {
        $this->validate($request, [
            'name' => 'required|max:255',
            'subject_group_id' => 'required|exists:subject_groups,id',
            'standard_id' => 'required|exists:standards,id',
            'is_mandatory' => 'required|boolean',
        ]);

        $subject->update([
            'name' => $request->name,
            'subject_group_id' => $request->subject_group_id,
            'standard_id' => $request->standard_id,
            'is_mandatory' => $request->is_mandatory,
        ]);

        return $subject;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Subject  $subject
     * @return \Illuminate\Http\Response
     */
    public function destroy(Subject $subject)
    {
        try {
            $subject->delete();
        } catch (Exception $ex) {
            return response([
                'header' => 'Dependency error',
                'message' => 'Other resources depend on this record.'
            ], 418);
        }

        return response('', 204);
    }
}
