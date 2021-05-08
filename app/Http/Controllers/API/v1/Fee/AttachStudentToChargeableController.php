<?php

namespace App\Http\Controllers\API\v1\Fee;

use App\Http\Controllers\Controller;
use App\Models\Chargeable;
use Illuminate\Http\Request;

class AttachStudentToChargeableController extends Controller
{
    public function store(Request $request){
        $this->validate($request, [
            'chargeable_id' => 'required|exists:chargeables,id',
            'student_ids' => 'exists:students,id'
        ]);
        $chargeable = Chargeable::find($request->chargeable_id)->students()->sync($request->student_ids);
        return $chargeable;
    }
}
