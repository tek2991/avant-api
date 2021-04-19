<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;

class ApiLoginController extends Controller
{
    public function directorLogin(Request $request)
    {
        $this->validate($request, [
            'username' => 'required|alpha_num',
            'password' => 'required|string',
        ]);

        $user = User::where('username', $request->username)->first();

        if(!$user || !Hash::check($request->password, $user->password)) {
            return response([
                'header' => 'Bad login details',
                'message' => 'Enter correct username & password.'
            ], 401);
        }

        $token =  $user->createToken('myapptoken')->plainTextToken; 

        $response = [
            'user' => $user,
            'token' => $token
        ];

        return response($response, 201);
    }
}
