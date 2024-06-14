<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;

class SpaRegisterController extends Controller
{
    public function register(Request $request)
    {
        // $this->validate($request, [
        //     'name' => 'required|max:255',
        //     'username' => 'required|alpha_num|unique:users|max:255',
        //     'email' => 'required|email|unique:users|max:255',
        //     'password' => 'required|confirmed',
        // ]);

        // $user = User::create([
        //     'name' => $request->name,
        //     'username' => $request->username,
        //     'email' => $request->email,
        //     'password' => Hash::make($request->password),
        // ]);

        // $response = [
        //     'user' => $user,
        // ];

        // return response($response, 201);

        return response("Registration is diabled!", 423);
    }
}
