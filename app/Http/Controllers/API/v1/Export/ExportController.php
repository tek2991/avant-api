<?php

namespace App\Http\Controllers\API\v1\Export;

use App\Models\BillFee;
use App\Exports\UsersExport;
use Illuminate\Http\Request;
use App\Exports\BillFeeExport;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;

class ExportController extends Controller
{
    public function user(){
        return Excel::download(new UsersExport, 'users.xlsx');
    }

    public function billFee(BillFee $billFee){
        $id = $billFee->id;
        return Excel::download(new BillFeeExport($id), 'billFee.xlsx');
    }
}
