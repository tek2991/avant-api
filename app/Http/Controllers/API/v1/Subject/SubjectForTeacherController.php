<?php

namespace App\Http\Controllers\API\v1\Subject;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class SubjectForTeacherController extends Controller
{
    public function index(User $user, Request $request){
        $user = Auth::user();

        if ($user->hasRole('teacher') !== true) {
            return response([
                'header' => 'Forbidden',
                'message' => 'Please Logout and Login again.'
            ], 401);
        }

        $this->validate($request, [
            'standard_id' => 'nullable|exists:standards,id',
            'subject_group_id' => 'nullable|exists:subject_groups,id',
        ]);

        $standard_id = empty($request->standard_id) ? '%%' : $request->standard_id;
        $subject_group_id = empty($request->subject_group_id) ? '%%' : $request->subject_group_id;
        
        return $user->teacher->subjects->select(['subjects.id', 'subjects.name', 'subjects.subject_group_id', 'subjects.standard_id', 'subjects.is_mandatory'])
        ->where('standard_id', 'like', $standard_id)
        ->where('subject_group_id', 'like', $subject_group_id)
        ->with('subjectGroup:id,name', 'standard')
        ->join('standards', 'subjects.standard_id', 'standards.id')
        ->orderBy('standards.hierachy', 'asc')
        ->orderBy('subject_group_id', 'asc')
        ->paginate();
    }
}
