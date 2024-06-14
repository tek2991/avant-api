<?php

namespace App\Http\Controllers\API\v1\Student;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UnallocatedStudentController extends Controller
{
    public function index(){
        return User::role('student')->doesntHave('student')->select(['id'])->with('userDetail:id,user_id,name')->get();
    }
}
