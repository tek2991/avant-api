<?php

namespace App\Http\Controllers\API\v1\Razorpay;

use App\Models\Payment;
use App\Models\FeeInvoice;
use Illuminate\Http\Request;
use App\Models\PaymentMethod;
use Razorpay\Api\Api as Razorpay;
use App\Http\Controllers\Controller;
use App\Models\Razorpay as ModelsRazorpay;
use Illuminate\Support\Facades\Auth;
use Database\Seeders\PaymentMethodSeeder;

class RazorpayFeeInvoiceController extends Controller
{
    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show(FeeInvoice $feeInvoice)
    {

        if (Auth::user()->id !== $feeInvoice->user_id && Auth::user()->hasRole('director') !== true) {
            return response([
                'header' => 'Forbidden',
                'message' => 'Please Logout and Login again.'
            ], 401);
        }

        if ($feeInvoice->payment()->count() > 0) {
            if ($feeInvoice->payment->status === 'captured') {
                return response([
                    'header' => 'Payment captured',
                    'message' => 'Payment already captured. Please contact admin.'
                ], 401);
            }

            if ($feeInvoice->payment->status === 'authorized') {
                return response([
                    'header' => 'Payment authorized',
                    'message' => 'Payment already under process. Please try again later.'
                ], 401);
            }
        }

        $user = Auth::user()->load('userDetail');
        $amount = $feeInvoice->gross_amount_in_cent;
        $currency = env('RAZORPAY_CURRENCY', 'INR');
        $receipt = $feeInvoice->id;

        $orderData = [
            'receipt'         => $receipt,
            'amount'          => $amount,
            'currency'        => $currency,
            'notes' => [
                'creator_username' => $user->username,
            ]
        ];

        $razorpayData = [
            // 'order_id' => $razorpayOrder['id'],
            'currency' => $currency,
            'amount' => $amount,

            'key' => env('RAZORPAY_KEY_ID'),
            'name' => $feeInvoice->name,
            'description' => $feeInvoice->name . ' for ' . $feeInvoice->user->userDetail->name . ' of Class: ' . $feeInvoice->standard->name . ' of Session: ' . $feeInvoice->billFee->bill->session->name,
            'image' => env('LOGO'),
        ];

        $payment_method_id = PaymentMethod::where('name', 'Razorpay')->first()->id;

        $payment = Payment::updateOrCreate(
            ['fee_invoice_id' => $feeInvoice->id],
            ['payment_method_id' => $payment_method_id]
        );

        if ($feeInvoice->payment()->count() > 0) {
            if ($feeInvoice->payment->razorpays()->count() > 0) {
                $razorpayData['order_id'] = $feeInvoice->payment->razorpays->first()->order_id;
                return response(compact('user', 'razorpayData'));
            }
        }

        $razorpay = new Razorpay(env('RAZORPAY_KEY_ID'), env('RAZORPAY_KEY_SECRET'));
        $razorpayOrder = $razorpay->order->create($orderData);
        $razorpayData['order_id'] = $razorpayOrder['id'];

        $modelsRazorpay = ModelsRazorpay::create([
            'order_id' => $razorpayOrder['id'],
            'attempts' => $razorpayOrder['attempts'],
            'amount_in_cent' => $razorpayOrder['amount'],
            'amount_paid_in_cent' => $razorpayOrder['amount_paid'],
            'amount_due_in_cent' => $razorpayOrder['amount_due'],
            'currency' => $currency,
            'order_status' => $razorpayOrder['status'],
        ]);

        $modelsRazorpay->payments()->attach($payment->id);

        return response(compact('user', 'razorpayData'));
    }

    public function verifyPayment(Request $request, FeeInvoice $feeInvoice)
    {

        if (Auth::user()->id !== $feeInvoice->user_id && Auth::user()->hasRole('director') !== true) {
            return response([
                'header' => 'Forbidden',
                'message' => 'Please Logout and Login again.'
            ], 401);
        }

        if ($feeInvoice->payment()->count() > 0) {
            if ($feeInvoice->payment->status === 'captured') {
                return response([
                    'header' => 'Payment captured',
                    'message' => 'Payment already captured. Please contact admin.'
                ], 401);
            }

            if ($feeInvoice->payment->status === 'authorized') {
                return response([
                    'header' => 'Payment authorized',
                    'message' => 'Payment already under process. Please try again later.'
                ], 401);
            }
        }

        if ($feeInvoice->payment()->count() < 1) {
            return response([
                'header' => 'No Payment',
                'message' => 'No payment found for invoice',
            ], 401);
        }

        if ($feeInvoice->payment->razorpays()->count() < 1) {
            return response([
                'header' => 'No Razorpay Payment',
                'message' => 'No razorpay payment found for verification'
            ], 401);
        }

        $razorpay = new Razorpay(env('RAZORPAY_KEY_ID'), env('RAZORPAY_KEY_SECRET'));

        $order_id = $feeInvoice->payment->razorpays->first()->order_id;

        $attributes  = array('razorpay_signature'  => $request->razorpay_signature,  'razorpay_payment_id'  => $request->razorpay_payment_id,  'razorpay_order_id' => $order_id);

        $order  = $razorpay->utility->verifyPaymentSignature($attributes);

        
    }
}
