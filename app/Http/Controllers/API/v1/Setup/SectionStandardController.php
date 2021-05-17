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
        return SectionStandard::with(['section:id,name', 'standard:id,name', 'teacher.user.userDetail'])->withCount('students')->join('standards', 'section_standard.standard_id', 'standards.id')->orderby('standards.hierachy','asc')->paginate();
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
        $students = $sectionStandard->students()->with('user.userDetail')->paginate();
        $sectionStandard = $sectionStandard->with(['section:id,name', 'standard:id,name', 'teacher.user.userDetail'])->first();
        return response(compact('sectionStandard', 'students'));
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
