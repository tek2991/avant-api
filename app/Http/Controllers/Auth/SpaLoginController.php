<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class SpaLoginController extends Controller
{
    public function directorLogin(Request $request)
    {
        $this->validate($request, [
            'username' => 'required|alpha_num',
            'password' => 'required|string',
        ]);

        if (!Auth::attempt($request->only('username', 'password'), $request->remember)) {
            return response([
                'message' => 'Bad login details'
            ], 401);
        }

        $user = Auth::user();

        return response($user, 201);
    }
}
