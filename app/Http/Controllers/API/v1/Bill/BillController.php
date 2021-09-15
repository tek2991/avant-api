<?php

namespace App\Http\Controllers\API\v1\Bill;

use App\Models\Fee;
use App\Models\Bill;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Jobs\CreateBillWithInvoiceJob;
use App\Models\TransactionLock;

class BillController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Bill::with(['session'])->paginate();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function all()
    {
        return Bill::orderBy('id')->get();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $billing_locked = TransactionLock::where("name", "Billing")->firstOrFail()->locked;

        if($billing_locked){
            return response([
                'header' => 'Transaction Locked',
                'message' => 'Another process in queue, please try later!'
            ], 401);
        }

        $this->validate($request, [
            'name' => 'required|max:255|string',
            'description' => 'required|max:255|string',
            'session_id' => 'required|exists:sessions,id',
            'bill_from_date' => 'required|date|after_or_equal:today',
            'bill_to_date' => 'required|date|after_or_equal:bill_date_from',
            'bill_due_date' => 'required|date|after_or_equal:bill_date_from',
            'fee_ids' => 'required|min:1|exists:fees,id'
        ]);

        TransactionLock::where("name", "Billing")->firstOrFail()->update([
            'locked' => true
        ]);

        $bill = Bill::create($request->only(['name', 'description', 'session_id', 'bill_from_date', 'bill_to_date', 'bill_due_date']));

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
        $bill =  $bill->load(['session']);

        $billFees = $bill->billFees()->with('fee')->paginate();

        return response(compact('bill', 'billFees'));
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
