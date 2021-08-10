<?php

namespace App\Http\Controllers\API\v1\ManualPayment;

use App\Models\FeeInvoice;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class ManualPaymentController extends Controller
{
    public function show(FeeInvoice $feeInvoice)
    {
        if (Auth::user()->id !== $feeInvoice->user_id && Auth::user()->hasRole('director') !== true) {
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

        $manualPayment;

        if ($feeInvoice->payment()->count() > 0){
            if ($feeInvoice->payment->manualPayments()->count() > 0){
                $manualPayment = $feeInvoice->payment->manualPayments->first();
            }
        }

        return response(compact('feeInvoice', 'manualPayment'));
    }

    public function store(FeeInvoice $feeInvoice)
    {
        if (Auth::user()->id !== $feeInvoice->user_id && Auth::user()->hasRole('director') !== true) {
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

        
    }
}
