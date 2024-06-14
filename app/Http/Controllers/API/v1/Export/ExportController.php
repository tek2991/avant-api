<?php

namespace App\Http\Controllers\API\v1\Export;

use App\Models\BillFee;
use App\Exports\UsersExport;
use Illuminate\Http\Request;
use App\Exports\BillFeeExport;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Carbon;
use Laravel\Sanctum\PersonalAccessToken;

class ExportController extends Controller
{
    public function billFee(Request $request, BillFee $billFee){
        $this->validate($request, [
            'pat' => 'required|max:255',
        ]);

        if (strpos($request->pat, '|') === false) {
            return response([
                'header' => 'Forbidden',
                'message' => 'Please Logout and Login again.'
            ], 401);
        }

        $patArray = explode("|", $request->pat);
        $model_id = $patArray[0];
        $token = $patArray[1];
        $pas = PersonalAccessToken::findOrFail($model_id);

        if(!hash_equals($pas->token, hash('sha256', $token))){
            return response([
                'header' => 'Forbidden',
                'message' => 'Please Logout and Login again.'
            ], 401);
        }

        $user = $pas->tokenable;

        if ($user->hasRole('director') !== true) {
            return response([
                'header' => 'Forbidden',
                'message' => 'Please Logout and Login again.'
            ], 401);
        }

        $id = $billFee->id;
        $timeStamp = Carbon::now()->format('u');
        $name = $billFee->fee->name.'_'.$timeStamp.'.xlsx';
        return Excel::download(new BillFeeExport($id), $name);
    }
}
