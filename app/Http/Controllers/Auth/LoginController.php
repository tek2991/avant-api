<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function directorLogin(Request $request)
    {
        $this->validate($request, [
            'username' => 'required|alpha_num',
            'password' => 'required|string',
        ]);

        // $user = User::where('username', $request['username'])->first();

        if (!Auth::attempt($request->only('username', 'password'), $request->remember)) {
            return response([
                'message' => 'Bad login details'
            ], 401);
        }

        $user = Auth::user();
        
        $token = $user->createToken('myapptoken')->plainTextToken;

        $response = [
            'user' => $user,
            'token' => $token
        ];


        return response($response, 201);
    }
}
