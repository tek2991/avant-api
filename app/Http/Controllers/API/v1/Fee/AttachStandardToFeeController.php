<?php

namespace App\Http\Controllers\API\v1\Fee;

use App\Models\Fee;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AttachStandardToFeeController extends Controller
{
    public function store(Request $request){
        $this->validate($request, [
            'fee_id' => 'required|exists:fees,id',
            'standard_ids' => 'exists:standards,id'
        ]);

        $fee = Fee::find($request->fee_id)->standards()->sync($request->standard_ids);

        return $fee;
    }
}
