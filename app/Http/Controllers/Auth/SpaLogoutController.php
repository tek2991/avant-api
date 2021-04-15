<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class SpaLogoutController extends Controller
{
    public function logout(){
        Auth::logout();
        return [
            'message' => 'Logged out'
        ];
    }
}
