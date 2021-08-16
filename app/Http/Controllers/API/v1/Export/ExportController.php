<?php

namespace App\Http\Controllers\API\v1\Export;

use App\Exports\UsersExport;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ExportController extends Controller
{
    public function user(){
        return Excel::download(new UsersExport, 'users.xlsx');
    }
}
