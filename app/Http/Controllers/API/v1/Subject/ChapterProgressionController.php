<?php

namespace App\Http\Controllers\API\v1\Subject;

use App\Models\Chapter;
use App\Models\Session;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Models\ChapterProgression;
use App\Http\Controllers\Controller;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class ChapterProgressionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $this->validate($request, [
            'chapter_id' => 'required|exists:chapters,id',
        ]);

        $chapter = Chapter::findOrFail($request->chapter_id);

        $session_id =  Session::where('is_active', true)->firstOrFail()->id;

        return $chapter->subject->standard->sections()
            ->with([
                'chapterProgressions.startedBy.user.userDetail:id,user_id,name', 'chapterProgressions.completedBy.user.userDetail:id,user_id,name',
                'chapterProgressions' => function ($query) use ($chapter, $session_id) {
                    $query->where('session_id', $session_id)->where('chapter_id', $chapter->id);
                },
            ])
            ->paginate();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        if ($user->hasRole('director') !== true && $user->hasRole('teacher') !== true) {
            return response([
                'header' => 'Forbidden',
                'message' => 'Please Logout and Login again.'
            ], 401);
        }

        $this->validate($request, [
            'chapter_id' => 'required|exists:chapters,id',
            'complete_before' => 'required|date',
        ]);

        $chapter = Chapter::findOrFail($request->chapter_id);
        $session_id = Session::where('is_active', true)->firstOrFail()->id;

        $canProceed = false;

        
        if ($user->hasRole('teacher') === true) {
            $canProceed = $chapter->subject->teachers()->get()->contains('user_id', $user->id);
        }

        $user->hasRole('director') === true ? $canProceed = true : false;
        
        if ($canProceed == false) {
            return response([
                'header' => 'Forbidden',
                'message' => 'Please Logout and Login again.'
            ], 401);
        }

        $progression = ChapterProgression::where('chapter_id', $chapter->id)->where('session_id', $session_id)->first();

        if ($progression) {
            ChapterProgression::where('chapter_id', $chapter->id)->where('session_id', $session_id)->update([
                'complete_before' => $request->complete_before
            ]);
        }

        if (!$progression) {
            $sections = $chapter->subject->standard->sections()->get()->modelKeys();

            foreach ($sections as $section_id) {
                ChapterProgression::create([
                    'session_id' => $session_id,
                    'chapter_id' => $chapter->id,
                    'section_id' => $section_id,
                    'complete_before' => $request->complete_before
                ]);
            }
        }


        return $chapter->whereHas(
            'chapterProgressions', function ($query) use ($chapter, $session_id) {
                $query->where('session_id', $session_id)->where('chapter_id', $chapter->id);
            }
        )->with(['chapterProgressions'])->get();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ChapterProgression  $chapterProgression
     * @return \Illuminate\Http\Response
     */
    public function show(ChapterProgression $chapterProgression)
    {
        return $chapterProgression;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ChapterProgression  $chapterProgression
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ChapterProgression $chapterProgression)
    {
        $user = Auth::user();
        if ($user->hasRole('director') !== true && $user->hasRole('teacher') !== true) {
            return response([
                'header' => 'Forbidden',
                'message' => 'Please Logout and Login again.'
            ], 401);
        }

        $this->validate($request, [
            'status' => [
                'required',
                Rule::in(['start', 'complete'])
            ],
        ]);

        $chapter = $chapterProgression->chapter;

        $canProceed = false;

        if ($user->hasRole('teacher') === true) {
            $canProceed = $chapter->subject->teachers()->get()->contains('user_id', $user->id);
        }

        $user->hasRole('director') === true ? $canProceed = true : false;

        if ($canProceed == false) {
            return response([
                'header' => 'Forbidden',
                'message' => 'Please Logout and Login again.'
            ], 401);
        }

        $status = $request->status;

        $started = $chapterProgression->started_by ? true : false;
        $completed = $chapterProgression->completed_by ? true : false;

        if($status == 'start' && !$started){
            $chapterProgression->update([
                'started_by' => $user->teacher->id,
                'started_at' => Carbon::now(),
            ]);
        }

        if($status == 'complete' && $started && !$completed){
            $chapterProgression->update([
                'completed_by' => $user->teacher->id,
                'completed_at' => Carbon::now(),
            ]);
        }

        return $chapterProgression;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ChapterProgression  $chapterProgression
     * @return \Illuminate\Http\Response
     */
    public function destroy(ChapterProgression $chapterProgression)
    {
        //
    }
}
