<?php

namespace App\Http\Controllers\API\v1\Fee;

use Exception;
use Illuminate\Http\Request;
use App\Models\ChargeableStudent;
use App\Http\Controllers\Controller;
use App\Models\Chargeable;

class ChargeableStudentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $this->validate($request, [
            'chargeable_id' => 'required|min:1|exists:chargeables,id',
        ]);

        return Chargeable::find($request->chargeable_id)->students()->with([
            'user:id','user.userDetail:user_id,name', 'sectionStandard:id,section_id', 'sectionStandard.section:id,name'
        ])->orderBy('section_standard_id')->orderBy('roll_no')->paginate();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'chargeable_id' => 'required|min:1|exists:chargeables,id',
            'student_id' => 'required|min:1|exists:students,id',
        ]);

        $student = [$request->student_id];

        return Chargeable::find($request->chargeable_id)->students()->syncWithoutDetaching($student);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ChargeableStudent  $chargeableStudent
     * @return \Illuminate\Http\Response
     */
    public function show(ChargeableStudent $chargeableStudent)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ChargeableStudent  $chargeableStudent
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ChargeableStudent $chargeableStudent)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     * 
     * @param  \App\Models\ChargeableStudent  $chargeableStudent
     * @return \Illuminate\Http\Response
     */
    public function destroy(ChargeableStudent $chargeableStudent)
    {
        try {
            $chargeableStudent->delete();
        } catch (Exception $ex) {
            return response([
                'header' => 'Dependency error',
                'message' => 'Other resources depend on this record.'
            ], 418);
        }

        return response('', 204);
    }
}
