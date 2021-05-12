<?php

namespace App\Http\Controllers\API\v1\Appeal;

use App\Models\Appeal;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\AppealState;
use Illuminate\Support\Facades\Auth;

class MyAppealController extends Controller
{
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
            'body' => 'required|text',
            'appeal_from_date' =>'required|date',
            'appeal_to_date' => 'fter_or_equal:appeal_date_from',
            'appeal_type_id' =>'required|exists:appeal_types,id',
        ]);

        $appealStateId = AppealState::firstWhere('name', 'Created')->id;

        $appeal = Auth::user()->appeals->create([
            $request->only(['title', 'body', 'appeal_date_from', 'appeal_date_to', 'appeal_type_id']),
            'appeal_state_id', $appealStateId
        ]);

        return $appeal;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Appeal  $appeal
     * @return \Illuminate\Http\Response
     */
    public function show(Appeal $appeal)
    {
        //
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
        //
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
