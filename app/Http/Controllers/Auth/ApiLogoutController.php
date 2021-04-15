<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

class ApiLogoutController extends Controller
{
    public function logout(Request $request){
        Auth::user()->tokens()->delete();
        return [
            'message' => 'Logged out'
        ];
    }
}
