<?php

namespace App\Http\Controllers\API\v1\Fee;

use App\Models\Fee;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;


class FeeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Fee::with('chargeables', 'standards')->paginate();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function all()
    {
        return Fee::orderBy('id')->get();
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
            'name' => 'required|max:255|string|unique:fees',
            'chargeable_ids' => 'exists:chargeables,id',
            'standard_ids' => 'exists:standards,id',
        ]);


        $fee = Fee::create([
            'name' => $request->name
        ]);

        $fee->chargeables()->sync($request->chargeable_ids);

        $fee->standards()->sync($request->standard_ids);

        return $fee;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Fee  $fee
     * @return \Illuminate\Http\Response
     */
    public function show(Fee $fee)
    {
        return $fee->load(['chargeables']);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Fee  $fee
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Fee $fee)
    {
        $this->validate($request, [
            'name' => [
                'required', 'max:255', 'string',
                Rule::unique('fees')->ignore($fee)
            ],
            'chargeable_ids' => 'exists:chargeables,id',
            'standard_ids' => 'exists:standards,id',
        ]);

        $fee->update([
            'name' => $request->name,
        ]);

        $fee->chargeables()->sync($request->chargeable_ids);

        $fee->standards()->sync($request->standard_ids);

        return $fee;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Fee  $fee
     * @return \Illuminate\Http\Response
     */
    public function destroy(Fee $fee)
    {
        $fee->delete();
        return response('', 204);
    }
}
