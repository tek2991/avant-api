<?php

namespace App\Http\Controllers\API\v1\Appeal;

use App\Models\Appeal;
use App\Models\AppealState;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class CloseAppealController extends Controller
{
        /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Appeal $appeal)
    {
        $this->validate($request, [
            'closing_remark' => 'required|max:255|string',
            'appeal_state_id' => 'required|exists:appeal_states,id'
        ]);

        $appeal->update($request->only(['closing_remark', 'appeal_state_id']));

        $appeal->appealEvents()->create([
            'appeal_state_id' => $request->appeal_state_id,
            'user_id' => Auth::user()->id
        ]);

        return $appeal->load('user', 'appealType');
    }
}
