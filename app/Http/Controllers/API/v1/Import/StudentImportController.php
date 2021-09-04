<?php

namespace App\Http\Controllers\API\v1\Import;

use Illuminate\Http\Request;
use App\Imports\StudentImport;
use App\Http\Controllers\Controller;

class StudentImportController extends Controller
{
    public function index(){
        return view('importStudent');
    }

    public function store(Request $request)
    {
        set_time_limit(600);
        $file = $request->file('file')->store('import');

        $import = new StudentImport;
        $import->import($file);

        return back()->withStatus('excel file imported');
    }
}
