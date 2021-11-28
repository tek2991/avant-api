<?php

namespace App\Http\Controllers\API\v1\Appeal;

use App\Models\Appeal;
use Carbon\CarbonPeriod;
use App\Models\AppealType;
use App\Models\Attendance;
use App\Models\AppealState;
use Illuminate\Http\Request;
use App\Models\AttendanceState;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class ResolveAppealController extends Controller
{
        /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Appeal $appeal)
    {
        $user = Auth::user();
        $this->validate($request, [
            'appeal_state' => 'required|in:Approved,Rejected'
        ]);

        $appeal_state_id = AppealState::where('name', $request->appeal_state)->first()->id;

        $appeal->update([
            'appeal_state_id' => $appeal_state_id,
            'closing_remark' => $request->appeal_state . ' by ' . $user->userDetail->name,
        ]);

    }
}
