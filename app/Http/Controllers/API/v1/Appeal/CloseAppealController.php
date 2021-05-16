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

        if ($appeal->appeal_type_id == AppealType::firstWhere('name', 'Leave Request')->id) {
            $period = CarbonPeriod::create($request->appeal_from_date, $request->appeal_to_date);
            $attendanceStateId = $request->appeal_state_id == 3 ? 5 : 6;
            foreach ($period as $date) {
                Attendance::updateOrCreate(
                    [
                        'user_id' => Auth::user()->id,
                        'attendance_date' => $date,
                    ],
                    [
                        'attendance_state_id' => $attendanceStateId,
                    ]
                );
            }
        }

        return $appeal->load('user', 'appealType');
    }
}
