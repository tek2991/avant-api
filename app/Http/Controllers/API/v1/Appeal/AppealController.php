<?php

namespace App\Http\Controllers\API\v1\Appeal;

use App\Models\Appeal;
use App\Models\AppealState;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\AppealType;
use Illuminate\Support\Facades\Auth;

class AppealController extends Controller
{

    public function __construct()
    {
        $this->authorizeResource(Appeal::class);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $this->validate($request, [
            'session_id' => 'nullable|exists:sessions,id',
            'appeal_type_id' => 'nullable|exists:appeal_types,id',
            'segment' => 'nullable|max:255',
        ]);

        $segment = $request->segment;
        $appeal_state = '';

        switch ($segment) {
            case "approved":
                $appeal_state = ['Approved'];
                break;
            case "pending":
                $appeal_state = ['Created'];
                break;
            case "rejected":
                $appeal_state = ['Rejected'];
                break;
            case "all":
                $appeal_state = ['Approved', 'Created', 'Rejected'];
                break;
        }

        $appeal_state_ids = AppealState::whereIn('name', $appeal_state)->pluck('id')->toArray();
        $appeal_type_ids = $request->appeal_type_id ? [$request->appeal_type_id] : AppealType::get()->pluck('id')->toArray();
        $user = Auth::user();
        $appeals = null;

        if ($user->hasRole('director')) {
            $appeals = Appeal::whereIn('appeal_state_id', $appeal_state_ids)
                ->whereIn('appeal_type_id', $appeal_type_ids)
                ->orderBy('created_at', 'desc')
                ->with('user.userDetail', 'user.student', 'user.teacher')
                ->paginate();
        } else if ($user->hasRole('teacher')) {
            $appeals = $user->appeals()->whereIn('appeal_state_id', $appeal_state_ids)
                ->whereIn('appeal_type_id', $appeal_type_ids)
                ->orderBy('created_at', 'desc')
                ->with('user.userDetail', 'user.teacher')
                ->paginate();
        } else {
            $appeals = $user->appeals()->whereIn('appeal_state_id', $appeal_state_ids)
                ->whereIn('appeal_type_id', $appeal_type_ids)
                ->orderBy('created_at', 'desc')
                ->with('user.userDetail', 'user.studentWithTrashed')
                ->paginate();
        }

        return $appeals;
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
            'name' => 'required|max:255|string',
            'description' => 'required|string',
            'appeal_from_date' => 'required|date',
            'appeal_to_date' => 'after_or_equal:appeal_date_from',
            'appeal_type_id' => 'required|exists:appeal_types,id',
        ]);

        $appealStateId = AppealState::firstWhere('name', 'Created')->id;
        $request['appeal_state_id'] = $appealStateId;
        $appeal = Auth::user()->appeals()->create(
            $request->only(['name', 'description', 'appeal_from_date', 'appeal_to_date', 'appeal_type_id', 'appeal_state_id'])
        );
        $appeal->appealEvents()->create([
            'appeal_state_id' => $appealStateId,
            'user_id' => Auth::user()->id
        ]);

        return response([
            'header' => 'Success',
            'message' => 'Appeal created successfully.'
        ], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Appeal  $appeal
     * @return \Illuminate\Http\Response
     */
    public function show(Appeal $appeal)
    {
        return $appeal->load('appealEvents', 'appealState', 'appealEvents');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Appeal  $appeal
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Appeal $appeal)
    {
        $this->validate($request, [
            'title' => 'required|max:255|string',
            'body' => 'required|string'
        ]);

        $appeal->update(
            $request->only(['title', 'body'])
        );

        return $appeal->load('appealEvents', 'appealState');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Appeal  $appeal
     * @return \Illuminate\Http\Response
     */
    public function destroy(Appeal $appeal)
    {
        // 
    }
}
