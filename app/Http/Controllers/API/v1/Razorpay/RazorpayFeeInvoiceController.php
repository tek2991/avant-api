<?php

namespace App\Http\Controllers\API\v1\Razorpay;

use App\Models\FeeInvoice;
use Illuminate\Http\Request;
use Razorpay\Api\Api as Razorpay;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class RazorpayFeeInvoiceController extends Controller
{
    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show(FeeInvoice $feeInvoice)
    {
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

        
        $razorpay = new Razorpay(env('RAZORPAY_KEY_ID'), env('RAZORPAY_KEY_SECRET'));
        
        $razorpayOrder = $razorpay->order->create($orderData);
        
        $razorpayData = [
            'order_id' => $razorpayOrder['id'],
            'currency' => $razorpayOrder['currency'],
            'amount' => $razorpayOrder['amount'],
            
            'key' => env('RAZORPAY_KEY_ID'),
            'name' => $feeInvoice->name,
            'description' => $feeInvoice->name . ' for ' . $feeInvoice->user->userDetail->name . ' of Class: ' . $feeInvoice->standard->name . ' of Session: ' . $feeInvoice->billFee->bill->session->name,
            'image' => env('LOGO'),
        ];

        
        return response(compact('user', 'razorpayData'));
    }
}
