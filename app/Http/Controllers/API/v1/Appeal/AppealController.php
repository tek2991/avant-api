<?php

namespace App\Http\Controllers\API\v1\Appeal;

use App\Models\Appeal;
use App\Models\AppealState;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
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
    public function index()
    {
        $user = Auth::user();
        return $user->appeals()->paginate();
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
            'title' => 'required|max:255|string',
            'body' => 'required|string',
            'appeal_from_date' => 'required|date',
            'appeal_to_date' => 'after_or_equal:appeal_date_from',
            'appeal_type_id' => 'required|exists:appeal_types,id',
        ]);

        $appealStateId = AppealState::firstWhere('name', 'Created')->id;
        $request['appeal_state_id'] = $appealStateId;
        $appeal = Auth::user()->appeals()->create(
            $request->only(['title', 'body', 'appeal_from_date', 'appeal_to_date', 'appeal_type_id', 'appeal_state_id'])
        );
        $appeal->appealEvents()->create([
            'appeal_state_id' => $appealStateId,
            'user_id' => Auth::user()->id
        ]);

        return $appeal->load('appealEvents', 'appealState');
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
