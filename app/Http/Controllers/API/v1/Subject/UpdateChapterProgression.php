<?php

namespace App\Http\Controllers\API\v1\Subject;

use App\Models\Chapter;
use App\Models\Session;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class UpdateChapterProgression extends Controller
{
    public function update(Chapter $chapter, Request $request)
    {
        $user = Auth::user();
        if ($user->hasRole('director') !== true && $user->hasRole('teacher') !== true) {
            return response([
                'header' => 'Forbidden',
                'message' => 'Please Logout and Login again.'
            ], 401);
        }

        $canProceed = false;

        $user->hasRole('director') === true ? $canProceed = true : false;

        if ($user->hasRole('teacher') === true) {
            $canProceed = $chapter->subject->teachers()->get()->contains('user_id', $user->id);
        }

        if ($canProceed == false) {
            return response([
                'header' => 'Forbidden',
                'message' => 'Please Logout and Login again.'
            ], 401);
        }

        $this->validate($request, [
            'status' => [
                'required',
                Rule::in(['start', 'complete'])
            ]
        ]);

        $session = Session::where('is_active', true)->firstOrFail();
        $chapter_id = $chapter->id;
        

        if($request->status == 'start'){

        }

        if($request->status == 'complete'){

        }
    }
}
