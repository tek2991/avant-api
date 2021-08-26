<?php

namespace App\Http\Controllers\API\v1\Chart;

use App\Models\User;
use App\Models\FeeInvoice;
use Illuminate\Support\Carbon;
use App\Http\Controllers\Controller;

class DirectorChartController extends Controller
{
    public function allInvoiceStat()
    {
        $invoices_with_payment = cache()->remember('director_dashboard_invoices_with_payment', 60 * 60 * 24, function () {
            return FeeInvoice::has('payment')->with('payment')->get();
        });

        $amount_not_paid = cache()->remember('director_dashboard_amount_not_paid', 60 * 60 * 24, function () {
            return FeeInvoice::doesntHave('payment')->get()->sum('gross_amount_in_cent');
        });

        return response(compact('invoices_with_payment', 'amount_not_paid'));
    }

    public function allAttendanceRecord()
    {
        $today = Carbon::now()->startOfDay();

        $present_today = cache()->remember('director_dashboard_present_today', 60 * 60 * 24, function () use ($today) {
            return User::has('student')->whereHas(
                'attendances',
                function ($query) use ($today) {
                    $query->where('attendance_date', $today);
                    $query->where('attendance_state_id', 2);
                }
            )->get()->count('id');
        });

        $absent_today = cache()->remember('director_dashboard_absent_today', 60 * 60 * 24, function () use ($today) {
            return User::has('student')->whereHas(
                'attendances',
                function ($query) use ($today) {
                    $query->where('attendance_date', $today);
                    $query->where('attendance_state_id', '<>', 2);
                }
            )->get()->count('id');
        });

        $total_students = cache()->remember('director_dashboard_total_students', 60 * 60 * 24, function () {
            return User::has('student')->count('id');
        });

        $today = [
            "present" => $present_today,
            "absent" => $absent_today,
        ];

        return response(compact('total_students', 'today'));
    }
}
