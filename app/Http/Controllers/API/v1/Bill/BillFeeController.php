<?php

namespace App\Http\Controllers\API\v1\Bill;

use App\Http\Controllers\Controller;
use App\Models\BillFee;
use Illuminate\Http\Request;

class BillFeeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\BillFee  $billFee
     * @return \Illuminate\Http\Response
     */
    public function show(BillFee $billFee)
    {
        $feeInvoices = $billFee->feeInvoices()->paginate();

        return response(compact('billFee', 'feeInvoices'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\BillFee  $billFee
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, BillFee $billFee)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\BillFee  $billFee
     * @return \Illuminate\Http\Response
     */
    public function destroy(BillFee $billFee)
    {
        //
    }
}
