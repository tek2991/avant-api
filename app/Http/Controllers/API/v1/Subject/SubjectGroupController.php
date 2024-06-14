<?php

namespace App\Http\Controllers\API\v1\Subject;

use Exception;
use App\Models\SubjectGroup;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SubjectGroupController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return SubjectGroup::select(['id', 'name', 'stream_id'])->with('stream:id,name')->paginate();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function all()
    {
        return SubjectGroup::orderBy('id')->get();
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
            'stream_id' => 'required|exists:streams,id'
        ]);

        return SubjectGroup::create([
            'name' => $request->name,
            'stream_id' => $request->stream_id,
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\SubjectGroup  $subjectGroup
     * @return \Illuminate\Http\Response
     */
    public function show(SubjectGroup $subjectGroup)
    {
        return $subjectGroup->with('stream', 'subjects')->get();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\SubjectGroup  $subjectGroup
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, SubjectGroup $subjectGroup)
    {
        $this->validate($request, [
            'name' => 'required|max:255',
            'stream_id' => 'required|exists:streams,id'
        ]);

        $subjectGroup->update([
            'name' => $request->name,
            'stream_id' => $request->stream_id,
        ]);

        return $subjectGroup;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\SubjectGroup  $subjectGroup
     * @return \Illuminate\Http\Response
     */
    public function destroy(SubjectGroup $subjectGroup)
    {
        try {
            $subjectGroup->delete();
        } catch (Exception $ex) {
            return response([
                'header' => 'Dependency error',
                'message' => 'Other resources depend on this record.'
            ], 418);
        }

        return response('', 204);
    }
}
