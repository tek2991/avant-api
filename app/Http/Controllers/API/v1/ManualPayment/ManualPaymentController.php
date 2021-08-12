<?php

namespace App\Http\Controllers\API\v1\ManualPayment;

use App\Models\Payment;
use App\Models\Receipt;
use App\Models\FeeInvoice;
use Illuminate\Http\Request;
use App\Models\ManualPayment;
use App\Models\PaymentMethod;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class ManualPaymentController extends Controller
{
    /**
     * Display the specified resource.
     *
     */
    public function show(FeeInvoice $feeInvoice)
    {
        if (Auth::user()->hasRole('director') !== true) {
            return response([
                'header' => 'Forbidden',
                'message' => 'Please Logout and Login again.'
            ], 401);
        }

        if ($feeInvoice->payment()->count() > 0) {
            if ($feeInvoice->payment->status === 'verified') {
                return response([
                    'header' => 'Payment error',
                    'message' => 'Payment verified. Please contact admin.'
                ], 401);
            }

            if ($feeInvoice->payment->status === 'authorised') {
                return response([
                    'header' => 'Payment error',
                    'message' => 'Payment authorired. Please contact admin.'
                ], 401);
            }

            if ($feeInvoice->payment->status === 'captured') {
                return response([
                    'header' => 'Payment error',
                    'message' => 'Payment credited. Please contact admin.'
                ], 401);
            }
        }

        $manualPayment = null;
        $razorPay = null;

        $payment_method_id = PaymentMethod::where('name', 'Manual')->firstOrFail()->id;

        $payment = Payment::updateOrCreate(
            ['fee_invoice_id' => $feeInvoice->id],
            ['payment_method_id' => $payment_method_id]
        );

        if ($feeInvoice->payment()->count() > 0){
            if ($feeInvoice->payment->razorpays()->count() > 0) {
                $razorPay = $feeInvoice->payment->razorpays->first();
            }
            if ($feeInvoice->payment->manualPayments()->count() > 0){
                $manualPayment = $feeInvoice->payment->manualPayments->first();
                return response(compact('feeInvoice', 'manualPayment', 'razorPay'));
            }
        }

        $manualPayment = ManualPayment::create([
            'remarks' => "Opted for manual payment method"
        ]);

        $manualPayment->payments()->attach($payment->id);

        return response(compact('feeInvoice', 'manualPayment', 'razorPay'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ManualPayment  $manualPayment
     * @return \Illuminate\Http\Response
     */

    public function update(ManualPayment $manualPayment, Request $request)
    {
        if (Auth::user()->hasRole('director') !== true) {
            return response([
                'header' => 'Forbidden',
                'message' => 'Please Logout and Login again.'
            ], 401);
        }


        $this->validate($request, [
            'amount' => 'required|integer|min:1|max:9999999999',
            'instrument_id' => 'required|exists:instruments,id',
            'transaction_no' => ['nullable', Rule::requiredIf($request->instrument_id != '1')],
            'transaction_date' => ['nullable', Rule::requiredIf($request->instrument_id != '1'), 'date'],
            'bank_id' => ['nullable', Rule::requiredIf($request->instrument_id != '1'), 'exists:banks,id'],
            'remarks' => ['nullable', Rule::requiredIf($request->instrument_id == '1')],
        ]);

        $amount_in_cent = ($request->amount) * 100;

        $manualPayment->update([
            'amount_in_cent' => $amount_in_cent,
            'instrument_id' => $request->instrument_id,
            'transaction_no' => $request->transaction_no,
            'transaction_date' => $request->transaction_date,
            'bank_id' => $request->bank_id,
            'remarks' => $request->remarks,
        ]);

        $payment = $manualPayment->payments->first();

        $payment->update([
            'status' => 'captured'
        ]);

        Receipt::create([
            'fee_invoice_id' => $payment->fee_invoice_id,
        ]);

        return $manualPayment;
    }
}
