<?php

namespace App\Http\Controllers\API\v1\Student;

use App\Models\Student;
use App\Models\Standard;
use Illuminate\Http\Request;
use App\Models\SectionStandard;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class TransferStudentController extends Controller
{
    public function studentBySectionStandard(Request $request)
    {
        if (Auth::user()->hasRole('director') !== true) {
            return response([
                'header' => 'Forbidden',
                'message' => 'Please Logout and Login again.'
            ], 401);
        }

        $this->validate($request, [
            'section_id' => 'required|min:1|exists:sections,id',
            'standard_id' => 'required|min:1|exists:standards,id',
        ]);

        $section_standard = SectionStandard::where('section_id', $request->section_id)->where('standard_id', $request->standard_id)->firstOrFail();

        return $section_standard->students()->with([
            'user:id', 'user.userDetail:user_id,name,phone', 'sectionStandard.standard:id,name', 'sectionStandard.section:id,name'
        ])->orderBy('section_standard_id')->orderBy('roll_no')->get();
    }

    public function transferStudent(Request $request)
    {
        if (Auth::user()->hasRole('director') !== true) {
            return response([
                'header' => 'Forbidden',
                'message' => 'Please Logout and Login again.'
            ], 401);
        }

        $this->validate($request, [
            'section_id' => 'required|min:1|exists:sections,id',
            'standard_id' => 'required|min:1|exists:standards,id',
            'student_ids' => 'exists:students,id'
        ]);

        $section_standard_id = SectionStandard::where('section_id', $request->section_id)->where('standard_id', $request->standard_id)->firstOrFail()->id;
        $students = Student::whereIn('id', $request->student_ids);

        $subjects = Standard::find($request->standard_id)->subjects()->get()->modelKeys();

        $students->update([
            'section_standard_id' => $section_standard_id,
        ]);

        foreach($students as $student){
            $student->subjects()->sync($subjects);
        }

        return response('OK', 200);
    }
}
