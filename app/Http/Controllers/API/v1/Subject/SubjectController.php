<?php

namespace App\Http\Controllers\API\v1\Subject;

use Exception;
use App\Models\Subject;
use App\Models\Standard;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SubjectController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $this->validate($request, [
            'standard_id' => 'nullable|exists:standards,id',
            'subject_group_id' => 'nullable|exists:subject_groups,id',
        ]);

        $standard_id = $request->input('standard_id', '%%');
        $subject_group_id = $request->input('subject_group_id', '%%');

        return Subject::select(['subjects.id', 'subjects.name', 'subjects.subject_group_id', 'subjects.standard_id', 'subjects.is_mandatory'])
        ->where('standard_id', 'like', $standard_id)
        ->where('subject_group_id', 'like', $subject_group_id)
        ->with('subjectGroup:id,name', 'standard')
        ->join('standards', 'subjects.standard_id', 'standards.id')
        ->orderBy('standards.hierachy', 'asc')
        ->paginate();
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

        $subject = Subject::create([
            'name' => $request->name,
            'subject_group_id' => $request->subject_group_id,
            'standard_id' => $request->standard_id,
            'is_mandatory' => $request->is_mandatory,
        ]);

        if ($request->boolean('is_mandatory')) {
            $students = Standard::find($request->standard_id)->students()->get()->modelKeys();
            $subject->students()->sync($students);
        }

        return $subject;
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

        $students = [];

        if ($request->boolean('is_mandatory')) {
            $students = Standard::find($request->standard_id)->students()->get()->modelKeys();
        }

        $subject->students()->sync($students);

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
