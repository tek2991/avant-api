<?php

namespace App\Http\Controllers\API\v1\Attributes;

use App\Models\AppealType;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AppealTypeController extends Controller
{
    public function index()
    {
        return AppealType::all();
    }
}
