<?php

namespace App\Http\Controllers\API\v1\Chart;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Session;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ChartController extends Controller
{

    public function attendanceForSession(User $user)
    {
        $session = Session::where('is_active', true)->firstOrFail();

        $attendances = $user->attendances()->select('attendance_date', 'attendance_state_id')->where('session_id', $session->id)->get()->groupBy(function ($val) {
            return Carbon::parse($val->attendance_date)->format('M Y');
        });

        return $attendances;
    }

    public function allUserInvoices(User $user)
    {
        $invoices = $user->feeInvoices()->with('payment')->get();

        return $invoices;
    }

    public function allChaptersInProgress(User $user)
    {

        $chapters = $user->student->chapters()->whereHas(
            'chapterProgressions',
            function($query) use ($user) {
                $query->where('section_id', $user->student->sectionStandard->section->id);
            }
        )->with([
            'chapterProgressions' => function($query) use ($user) {
                $query->where('section_id', $user->student->sectionStandard->section->id);
            }
        ])->get();

        return $chapters;
    }
}
