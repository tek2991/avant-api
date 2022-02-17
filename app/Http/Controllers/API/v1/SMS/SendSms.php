<?php

namespace App\Http\Controllers\API\v1\SMS;

use App\Models\User;
use App\Jobs\SendSmsJob;
use App\Models\SmsTemplate;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Jobs\SendMultipleSmsJob;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\API\v1\Attributes\VariableController;

class SendSms extends Controller
{
    public function holidaySms(Request $request){
        $user = Auth::user();
        if ($user->hasRole('director') !== true) {
            return response([
                'header' => 'Forbidden',
                'message' => 'Please Logout and Login again.'
            ], 401);
        }

        $this->validate($request, [
            'holiday_date' => 'required|date',
            'user_ids' => 'exists:users,id'
        ]);

        $variables = [$request->holiday_date];

        $users = User::whereIn('id', $request->user_ids)->get();
        
        $numbers = [];

        foreach ($users as $user) {
            $numbers[] = $user->userDetail->phone;
        }

        $template = SmsTemplate::where('message_id', '111043')->firstOrFail();
        $route = 'dlt';
        $db_variables = VariableController::keyPairs();
        $url = $db_variables['FAST2SMS_URL'];
        $key = $db_variables['FAST2SMS_KEY'];

        SendSmsJob::dispatch($variables, $numbers, $request->user_ids, $template, $route, $url, $key);

        return response($numbers, 200);
    }

    public function absenteeSms(Request $request){
        $user = Auth::user();
        if ($user->hasRole('director') !== true) {
            return response([
                'header' => 'Forbidden',
                'message' => 'Please Logout and Login again.'
            ], 401);
        }

        $this->validate($request, [
            'attendance_date' => 'required|date',
            'user_ids' => 'exists:users,id'
        ]);

        $users = User::whereIn('id', $request->user_ids)->get();
        $sms_objects = [];

        foreach ($users as $user) {
            $sms_object = [
                "user_id" => $user->id,
                "number" => $user->userDetail->phone,
                "variables" => [$user->userDetail->name, $request->attendance_date]
            ];

            $sms_objects[] = $sms_object;
        }

        $template = SmsTemplate::where('message_id', '111044')->firstOrFail();
        $route = 'dlt';
        $db_variables = VariableController::keyPairs();
        $url = $db_variables['FAST2SMS_URL'];
        $key = $db_variables['FAST2SMS_KEY'];

        SendMultipleSmsJob::dispatch($sms_objects, $template, $route, $url, $key);

        return response($sms_objects, 200);
    }

    public function unpaidDueSms(Request $request){
        $user = Auth::user();
        if ($user->hasRole('director') !== true) {
            return response([
                'header' => 'Forbidden',
                'message' => 'Please Logout and Login again.'
            ], 401);
        }

        $this->validate($request, [
            'due_upto' => 'required|date',
            'pay_before' => 'required|date',
            'user_ids' => 'exists:users,id'
        ]);

        $due_upto = Carbon::create($request->due_upto)->toFormattedDateString();
        $pay_before = Carbon::create($request->pay_before)->toFormattedDateString();

        $users = User::whereIn('id', $request->user_ids)->get();
        $sms_objects = [];

        foreach ($users as $user) {
            $unpaid_due = $user->unpaidDue();
            $sms_object = [
                "user_id" => $user->id,
                "number" => $user->userDetail->phone,
                "variables" => [$due_upto, $unpaid_due/100, $pay_before]
            ];

            $sms_objects[] = $sms_object;
        }

        $template = SmsTemplate::where('message_id', '111041')->firstOrFail();
        $route = 'dlt';
        $db_variables = VariableController::keyPairs();
        $url = $db_variables['FAST2SMS_URL'];
        $key = $db_variables['FAST2SMS_KEY'];

        SendMultipleSmsJob::dispatch($sms_objects, $template, $route, $url, $key);

        return response($sms_objects, 200);
    }
}
