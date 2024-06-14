<?php

namespace App\Http\Controllers\API\v1\Fee;

use App\Models\Fee;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AttachChargeableToFeeController extends Controller
{
    public function store(Request $request){
        $this->validate($request, [
            'fee_id' => 'required|exists:fees,id',
            'chargeable_ids' => 'exists:chargeables,id'
        ]);

        $fee = Fee::find($request->fee_id)->chargeables()->sync($request->chargeable_ids);

        return $fee;
    }
}
