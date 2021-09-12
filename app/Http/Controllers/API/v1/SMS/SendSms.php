<?php

namespace App\Http\Controllers\API\v1\SMS;

use App\Models\User;
use App\Jobs\SendSmsJob;
use App\Models\SmsTemplate;
use Illuminate\Http\Request;
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
}
