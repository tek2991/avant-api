<?php

namespace App\Http\Controllers\TechDemos;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TinyMceController extends Controller
{
    public function index()
    {
        return view('tech-demos.tiny-mce');
    }
}
