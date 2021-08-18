<?php

namespace App\Http\Controllers\API\v1\Setup;

use Exception;
use Illuminate\Http\Request;
use App\Models\SectionStandard;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class SectionStandardController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::user();
        if ($user->hasRole('director') !== true && $user->hasRole('teacher') !== true) {
            return response([
                'header' => 'Forbidden',
                'message' => 'Please Logout and Login again.'
            ], 401);
        }

        $teacher_id = Auth::user()->teacher->id;;

        if (Auth::user()->hasRole('director') === true) {
            $teacher_id = '%%';
        }
        
        return SectionStandard::where('teacher_id', 'like', $teacher_id)->with(['section', 'standard', 'teacher:id,user_id', 'teacher.user:id', 'teacher.user.userDetail:id,user_id,name'])->withCount('students')->join('standards', 'section_standard.standard_id', 'standards.id')->join('sections', 'section_standard.section_id', 'sections.id')->orderby('standards.hierachy', 'asc')->orderby('sections.id', 'asc')->paginate();
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
            'section_id' => 'required|min:1|exists:sections,id',
            'standard_id' => 'required|min:1|exists:standards,id',
            'teacher_id' => 'required|min:1|exists:teachers,id',
        ]);

        $sectionStandard = SectionStandard::where('section_id', $request->section_id)->where('standard_id', $request->standard_id)->first();

        if ($sectionStandard) {
            return response([
                'header' => 'Class exists',
                'message' => 'Class already exist!'
            ], 400);
        }

        return SectionStandard::create($request->only(['section_id', 'standard_id', 'teacher_id']));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function all()
    {
        return SectionStandard::with(['section', 'standard'])->join('standards', 'section_standard.standard_id', 'standards.id')->join('sections', 'section_standard.section_id', 'sections.id')->orderby('standards.hierachy', 'asc')->orderby('sections.id', 'asc')->get();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\SectionStandard  $sectionStandard
     * @return \Illuminate\Http\Response
     */
    public function show(SectionStandard $sectionStandard)
    {
        $students = $sectionStandard->students()->with('user:id', 'user.userDetail:id,user_id,name')->select(['id', 'user_id', 'section_standard_id', 'roll_no'])->orderBy('roll_no')->paginate();

        $sectionStandard = $sectionStandard->with(['section', 'standard', 'teacher:id,user_id', 'teacher.user:id', 'teacher.user.userDetail:id,user_id,name'])->find($sectionStandard->id);

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
        $this->validate($request, [
            'teacher_id' => 'required|min:1|exists:teachers,id',
        ]);

        $sectionStandard->update([
            'teacher_id' => $request->teacher_id
        ]);

        return $sectionStandard;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\SectionStandard  $sectionStandard
     * @return \Illuminate\Http\Response
     */
    public function destroy(SectionStandard $sectionStandard)
    {
        try {
            $sectionStandard->delete();
        } catch (Exception $ex) {
            return response([
                'header' => 'Dependency error',
                'message' => 'Other resources depend on this record.'
            ], 418);
        }
        return response('', 204);
    }
}
