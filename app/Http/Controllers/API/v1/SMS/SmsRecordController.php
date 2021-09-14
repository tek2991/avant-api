<?php

namespace App\Http\Controllers\API\v1\SMS;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Exports\SmsRecordExport;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use Laravel\Sanctum\PersonalAccessToken;

class SmsRecordController extends Controller
{
    public function index(Request $request)
    {
        $this->validate($request, [
            'pat' => 'required|max:255',
            'from_date' => 'required|date',
            'to_date' => 'required|date',
        ]);

        $to_date = Carbon::create($request->to_date)->addDay()->format('Y-m-d');
        
        if (strpos($request->pat, '|') === false) {
            return response([
                'header' => 'Forbidden',
                'message' => 'Please Logout and Login again.'
            ], 401);
        }

        $patArray = explode("|", $request->pat);
        $model_id = $patArray[0];
        $token = $patArray[1];
        $pas = PersonalAccessToken::findOrFail($model_id);

        if (!hash_equals($pas->token, hash('sha256', $token))) {
            return response([
                'header' => 'Forbidden',
                'message' => 'Please Logout and Login again.'
            ], 401);
        }

        $user = $pas->tokenable;

        if ($user->hasRole('director') !== true) {
            return response([
                'header' => 'Forbidden',
                'message' => 'Please Logout and Login again.'
            ], 401);
        }

        $timeStamp = Carbon::now()->format('u');
        $name = 'sms_report_'.$timeStamp.'.xlsx';

        return Excel::download(new SmsRecordExport($request->from_date, $to_date), $name);
    }
}
