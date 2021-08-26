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
        $invoices_with_payment = FeeInvoice::has('payment')->with('payment')->get();
        $amount_not_paid = FeeInvoice::doesntHave('payment')->get()->sum('gross_amount_in_cent');

        return response(compact('invoices_with_payment', 'amount_not_paid'));
    }

    public function allAttendanceRecord()
    {
        $today = Carbon::now()->startOfDay();

        $present_today = User::has('student')->whereHas(
            'attendances',
            function($query) use($today){
                $query->where('attendance_date', $today);
                $query->where('attendance_state_id', 2);
            }
        )->get()->count('id');

        $absent_today = User::has('student')->whereHas(
            'attendances',
            function($query) use($today){
                $query->where('attendance_date', $today);
                $query->where('attendance_state_id', '<>', 2);
            }
        )->get()->count('id');

        $total_students = User::has('student')->count('id');

        $today = [
            "present" => $present_today,
            "absent" => $absent_today,
        ];

        return response(compact('total_students', 'today'));
    }
}
