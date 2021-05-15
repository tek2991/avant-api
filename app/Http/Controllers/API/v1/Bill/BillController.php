<?php

namespace App\Http\Controllers\API\v1\Bill;

use App\Models\Fee;
use App\Models\Bill;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Jobs\CreateBillWithInvoiceJob;

class BillController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Bill::with(['billFees', 'billFees.fee'])->paginate();
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
            'session_id' => 'required|exists:sessions,id',
            'bill_from_date' => 'required|date|after_or_equal:today',
            'bill_to_date' => 'required|date|after_or_equal:bill_date_from',
            'fee_ids' => 'required|min:1|exists:fees,id'
        ]);

        $bill = Bill::create($request->only(['name', 'description', 'session_id', 'bill_from_date', 'bill_to_date']));

        $bill->fees()->attach($request->fee_ids);

        $fees = Fee::whereIn('id', $request->fee_ids)->get();

        CreateBillWithInvoiceJob::dispatch($bill, $fees);

        return $bill;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Bill  $bill
     * @return \Illuminate\Http\Response
     */
    public function show(Bill $bill)
    {
        Return $bill->load(['billFees', 'billFees.fee']);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Bill  $bill
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Bill $bill)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Bill  $bill
     * @return \Illuminate\Http\Response
     */
    public function destroy(Bill $bill)
    {
        //
    }
}
