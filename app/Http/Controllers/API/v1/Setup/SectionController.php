<?php

namespace App\Http\Controllers\API\v1\Setup;

use App\Models\Section;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use Exception;

class SectionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Section::paginate();
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
            'name' => 'required|max:255|unique:sections',
        ]);

        return Section::create([
            'name' => $request->name,
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Section  $section
     * @return \Illuminate\Http\Response
     */
    public function show(Section $section)
    {
        return $section;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Section  $section
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Section $section)
    {
        $this->validate($request, [
            'name' => [
                'required', 'max:255',
                Rule::unique('sections')->ignore($section)
            ],
        ]);

        $section->update([
            'name' => $request->name,
        ]);

        return $section;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Section  $section
     * @return \Illuminate\Http\Response
     */
    public function destroy(Section $section)
    {
        try {
            $section->delete();
        } catch (Exception $ex) {
            return response([
                'header' => 'Dependency error',
                'message' => 'Other resources depend on this record.'
            ], 418);
        }
        return response('', 204);
    }
}
