<?php

namespace App\Http\Controllers\API\v1\Chart;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Session;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class TeacherChartController extends Controller
{

    public function attendanceForAssignedClasses(User $user)
    {
        $key = 'teacher_dashboard_attendances_' . $user->id;
        $today = Carbon::now()->startOfDay();

        $attendances = cache()->remember($key, 1, function () use ($user, $today) {

            $present_today = $user->teacher->classStudents()->whereHas(
                'user',
                function ($query) use ($today) {
                    $query->whereHas(
                        'attendances',
                        function ($query) use ($today) {
                            $query->where('attendance_date', $today);
                            $query->where('attendance_state_id', 2);
                        }
                    );
                }
            )->get()->count('id');

            $absent_today = $user->teacher->classStudents()->whereHas(
                'user',
                function ($query) use ($today) {
                    $query->whereHas(
                        'attendances',
                        function ($query) use ($today) {
                            $query->where('attendance_date', $today);
                            $query->where('attendance_state_id', '<>', 2);
                        }
                    );
                }
            )->get()->count('id');

            $total_students = $user->teacher->classStudents()->get()->count('id');


            $today = [
                "present" => $present_today,
                "absent" => $absent_today,
            ];

            return compact('total_students', 'today');
        });

        return $attendances;
    }
}
