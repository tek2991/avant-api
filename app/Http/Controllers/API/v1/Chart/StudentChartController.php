<?php

namespace App\Http\Controllers\API\v1\Chart;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Session;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class StudentChartController extends Controller
{

    public function attendanceForSession(User $user)
    {
        $session = Session::where('is_active', true)->firstOrFail();

        $key = 'student_dashboard_attendances_' . $user->id;

        $attendances = cache()->remember($key, 60 * 60 * 24, function () use ($user, $session) {
            return $user->attendances()->select('attendance_date', 'attendance_state_id')->where('session_id', $session->id)->get()->groupBy(function ($val) {
                return Carbon::parse($val->attendance_date)->format('M Y');
            });
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
        $key = 'student_dashboard_allChaptersInProgress_' . $user->id;

        $chapters = cache()->remember($key, 60 * 60 * 24, function () use ($user) {
            return $user->student->chapters()->whereHas(
                'chapterProgressions',
                function ($query) use ($user) {
                    $query->where('section_id', $user->student->sectionStandard->section->id);
                }
            )->with([
                'chapterProgressions' => function ($query) use ($user) {
                    $query->where('section_id', $user->student->sectionStandard->section->id);
                },
                'subject'
            ])->get();
        });

        return $chapters;
    }
}
