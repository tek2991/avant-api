<?php

namespace App\Http\Controllers\API\v1\Appeal;

use App\Models\Appeal;
use App\Models\AppealState;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class RecommendAppealController extends Controller
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
            'recommending_remark' => 'required|max:255|string',
        ]);

        $appealStateId = AppealState::firstWhere('name', 'Recommended')->id;
        $request['appeal_state_id'] = $appealStateId;

        $appeal->update([
            'recommending_remark' => $request->recommending_remark,
            'appeal_state_id' => $appealStateId
        ]);

        $appeal->appealEvents()->create([
            'appeal_state_id' => $appealStateId,
            'user_id' => Auth::user()->id
        ]);

        return $appeal->load('user', 'appealType');
    }
}
