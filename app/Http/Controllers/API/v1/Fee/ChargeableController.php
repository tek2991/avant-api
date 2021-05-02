<?php

namespace App\Http\Controllers\API\v1\Fee;

use App\Models\Fee;
use App\Models\Chargeable;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;

class ChargeableController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Chargeable::paginate();
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
            'name' => 'required|max:255|string',
            'description' => 'required|max:255|string',
            'amount' => 'required|integer|min:1|max:9999999999',
            'tax_rate' => [
                'required', 'integer',
                Rule::in([5, 12, 18, 28])
            ]
        ]);
        
        if(!Fee::find($request->fee_id)){
            return response([
                'header' => 'Invalid Fee',
                'message' => 'The Fee id was not found in the database.'
            ], 400);
        }

        $amount_in_cent = ($request->amount)*100;
        $gross_amount_in_cent = (($request->amount)*100)*((($request->tax_rate)/100)+1);

        return Fee::find($request->fee_id)->chargeables()->create([
            'name' => $request->name,
            'description' => $request->description,
            'amount_in_cent' => $amount_in_cent,
            'tax_rate' => $request->tax_rate,
            'gross_amount_in_cent' => $gross_amount_in_cent
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Chargeable  $chargeable
     * @return \Illuminate\Http\Response
     */
    public function show(Chargeable $chargeable)
    {
        return $chargeable->load(['fee']);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Chargeable  $chargeable
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Chargeable $chargeable)
    {
        $this->validate($request, [
            'name' => 'required|max:255|string',
            'description' => 'required|max:255|string',
            'amount' => 'required|integer|min:1|max:9999999999',
            'tax_rate' => [
                'required', 'integer',
                Rule::in([5, 12, 18, 28])
            ]
        ]);

        $amount_in_cent = ($request->amount)*100;
        $gross_amount_in_cent = (($request->amount)*100)*((($request->tax_rate)/100)+1);

        $chargeable->update([
            'name' => $request->name,
            'description' => $request->description,
            'amount_in_cent' => $amount_in_cent,
            'tax_rate' => $request->tax_rate,
            'gross_amount_in_cent' => $gross_amount_in_cent
        ]);

        return $chargeable;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Chargeable  $chargeable
     * @return \Illuminate\Http\Response
     */
    public function destroy(Chargeable $chargeable)
    {
        $chargeable->delete();
        return response('', 204);
    }
}
