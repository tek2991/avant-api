<?php

namespace App\Http\Controllers\API\v1\Setup;

use Illuminate\Http\Request;
use App\Models\SectionStandard;
use App\Http\Controllers\Controller;

class SectionStandardController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return SectionStandard::with(['section:id,name', 'standard:id,name', 'teacher.user'])->paginate();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\SectionStandard  $sectionStandard
     * @return \Illuminate\Http\Response
     */
    public function show(SectionStandard $sectionStandard)
    {
        // return $sectionStandard;
        return $sectionStandard->load(['section:id,name', 'standard:id,name', 'students.user', 'teacher.user']);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\SectionStandard  $sectionStandard
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, SectionStandard $sectionStandard)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\SectionStandard  $sectionStandard
     * @return \Illuminate\Http\Response
     */
    public function destroy(SectionStandard $sectionStandard)
    {
        $sectionStandard->delete();
        return response('', 204);
    }
}
