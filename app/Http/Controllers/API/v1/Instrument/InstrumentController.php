<?php

namespace App\Http\Controllers\API\v1\Instrument;

use App\Models\Instrument;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use Exception;

class InstrumentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // return Instrument::orderBy('id')->paginate();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function all()
    {
        return Instrument::orderBy('id')->get();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // $this->validate($request, [
        //     'name' => 'required|max:255|unique:Instruments',
        // ]);

        // return Instrument::create([
        //     'name' => $request->name,
        // ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Instrument  $Instrument
     * @return \Illuminate\Http\Response
     */
    public function show(Instrument $Instrument)
    {
        // return $Instrument;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Instrument  $Instrument
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Instrument $Instrument)
    {
        // $this->validate($request, [
        //     'name' => [
        //         'required', 'max:255',
        //         Rule::unique('Instruments')->ignore($Instrument)
        //     ],
        // ]);

        // $Instrument->update([
        //     'name' => $request->name,
        // ]);

        // return $Instrument;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Instrument  $Instrument
     * @return \Illuminate\Http\Response
     */
    public function destroy(Instrument $Instrument)
    {
        // try {
        //     $Instrument->delete();
        // } catch (Exception $ex) {
        //     return response([
        //         'header' => 'Dependency error',
        //         'message' => 'Other resources depend on this record.'
        //     ], 418);
        // }
        // return response('', 204);
    }
}
