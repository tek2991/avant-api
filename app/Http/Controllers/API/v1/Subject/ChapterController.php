<?php

namespace App\Http\Controllers\API\v1\Subject;

use Exception;
use App\Models\Chapter;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ChapterController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $this->validate($request, [
            'subject_id' => 'required|exists:subjects,id',
        ]);

        return Chapter::where('subject_id', $request->subject_id)->with('subject:id,name,standard_id', 'subject.standard')->paginate();
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
            'description' => 'required|max:255',
            'subject_id' => 'required|exists:subjects,id',
        ]);

        return Chapter::create([
            'name' => $request->name,
            'description' => $request->description,
            'subject_id' => $request->subject_id,
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Chapter  $chapter
     * @return \Illuminate\Http\Response
     */
    public function show(Chapter $chapter)
    {
        return $chapter->load([
            'subject.standard'
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Chapter  $chapter
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Chapter $chapter)
    {
        $this->validate($request, [
            'name' => 'required|max:255',
            'description' => 'required|max:255',
        ]);

        $chapter->update([
            'name' => $request->name,
            'description' => $request->description,
        ]);
        return $chapter;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Chapter  $chapter
     * @return \Illuminate\Http\Response
     */
    public function destroy(Chapter $chapter)
    {
        try {
            $chapter->delete();
        } catch (Exception $ex) {
            return response([
                'header' => 'Dependency error',
                'message' => 'Other resources depend on this record.'
            ], 418);
        }

        return response('', 204);
    }
}
