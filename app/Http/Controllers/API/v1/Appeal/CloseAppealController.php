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
            Attendance::where('user_id', Auth::user()->id)->whereBetween('attendance_date', [$appeal->appeal_from_date, $appeal->appeal_to_date])->delete();
            $period = CarbonPeriod::create($request->appeal_from_date, $request->appeal_to_date);
            foreach ($period as $date) {
                Attendance::firstOrCreate(
                    [
                        'user_id' => Auth::user()->id,
                        'attendance_date' => $date,
                        'attendance_state_id' => AttendanceState::firstWhere('name', 'Leave applied')->id,
                    ]
                );
            }
        }

        return $appeal->load('user', 'appealType');
    }
}
