<?php

namespace App\Http\Controllers\API\v1\Bill;

use App\Models\User;
use App\Models\Receipt;
use App\Models\FeeInvoice;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade as PDF;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class FeeInvoiceController extends Controller
{

    public function __construct()
    {
        $this->authorizeResource(FeeInvoice::class);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $this->validate($request, [
            'session_id' => 'exists:sessions,id',
            'bill_id' => 'exists:bills,id',
            'fee_id' => 'exists:fees,id',
            'standard_id' => 'exists:standards,id',
            'invoice_id' => 'max:255',
        ]);

        return FeeInvoice::
        whereHas('billFee', function ($query) use ($request) {
            $query->whereHas('bill', function ($query) use ($request) {
                $query->whereHas('session', function ($query) use ($request) {
                    $query->where('id', 'like', '%' . $request->session_id . '%');
                });
                $query->where('id', 'like', '%' . $request->bill_id . '%');
            });
            $query->whereHas('fee', function ($query) use ($request) {
                $query->where('id', 'like', '%' . $request->fee_id . '%');
            });
        })
        ->where('standard_id', 'like', '%' . $request->standard_id . '%')
        ->where('id', 'like', '%' . $request->invoice_id . '%')
        ->with('user:id', 'user.userDetail:id,user_id,name', 'user.student:id,user_id,section_standard_id', 'user.student.sectionStandard.section', 'user.student.sectionStandard.standard')->paginate();
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
     * @param  \App\Models\FeeInvoice  $feeInvoice
     * @return \Illuminate\Http\Response
     */
    public function show(FeeInvoice $feeInvoice)
    {
        return $feeInvoice->load('billFee:id,bill_id,fee_id', 'billFee.bill', 'billFee.bill.session', 'feeInvoiceItems', 'payment', 'standard', 'user:id,email', 'user.userDetail:id,user_id,name');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\FeeInvoice  $feeInvoice
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, FeeInvoice $feeInvoice)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\FeeInvoice  $feeInvoice
     * @return \Illuminate\Http\Response
     */
    public function destroy(FeeInvoice $feeInvoice)
    {
        //
    }

    /**
     * Print the Fee Invoice.
     *
     * @param  \App\Models\FeeInvoice  $feeInvoice
     * @return \Illuminate\Http\Response
     */
    public function print(FeeInvoice $feeInvoice)
    {
        // if (Auth::user()->id !== $feeInvoice->user_id && Auth::user()->hasRole('director') !== true) {
        //     return response([
        //         'header' => 'Forbidden',
        //         'message' => 'Please Logout and Login again.'
        //     ], 401);
        // }

        $data = $feeInvoice->load('billFee:id,bill_id,fee_id', 'billFee.bill', 'billFee.bill.session', 'feeInvoiceItems', 'standard', 'user:id,email', 'user.userDetail:id,user_id,name');

        $pdf = PDF::loadView('documents.fee-invoice', ['data' => $data]);
        return $pdf->download('fee_invoice_' . $feeInvoice->id . '.pdf');
    }

    /**
     * Print the Fee Invoice.
     *
     * @param  \App\Models\FeeInvoice  $feeInvoice
     * @return \Illuminate\Http\Response
     */
    public function printReceipt(FeeInvoice $feeInvoice)
    {
        // if (Auth::user()->id !== $feeInvoice->user_id && Auth::user()->hasRole('director') !== true) {
        //     return response([
        //         'header' => 'Forbidden',
        //         'message' => 'Please Logout and Login again.'
        //     ], 401);
        // }

        $data = $feeInvoice->load('billFee:id,bill_id,fee_id', 'billFee.bill', 'billFee.bill.session', 'feeInvoiceItems', 'standard', 'payment', 'user:id,email', 'user.userDetail:id,user_id,name');

        $receipt = Receipt::where('fee_invoice_id', $feeInvoice->id)->first();

        $pdf = PDF::loadView('documents.fee-invoice-receipt', ['data' => $data, 'receipt' =>$receipt]);

        return $pdf->download('fee_invoice_receipt' . $feeInvoice->id . '.pdf');
    }
}
