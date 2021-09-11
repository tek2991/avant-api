<?php

namespace App\Http\Controllers\API\v1\SMS;

use App\Models\User;
use App\Jobs\SendSmsJob;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

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

        // SendSmsJob::dispatch($variables, $numbers);

        return response($numbers, 200);
    }
}
