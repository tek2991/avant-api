<?php

namespace App\Http\Controllers\API\v1\Import;

use Illuminate\Http\Request;
use App\Imports\StudentImport;
use App\Http\Controllers\Controller;

class StudentImportController extends Controller
{
    public function store(Request $request){
        $file = $request->file('file')->store('import');
        Excel::import(new StudentImport, $file);
        return back()->withStatus('excel file imported');
    }
}
