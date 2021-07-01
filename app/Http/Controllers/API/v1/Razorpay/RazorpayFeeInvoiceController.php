<?php

namespace App\Http\Controllers\API\v1\Razorpay;

use Exception;
use App\Models\Payment;
use App\Models\FeeInvoice;
use Illuminate\Http\Request;
use App\Models\PaymentMethod;
use Razorpay\Api\Api as Razorpay;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Database\Seeders\PaymentMethodSeeder;
use App\Models\Razorpay as ModelsRazorpay;
use Illuminate\Support\Carbon;
use PhpParser\Node\Stmt\TryCatch;

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
            if ($feeInvoice->payment->status === 'credited') {
                return response([
                    'header' => 'Payment captured',
                    'message' => 'Payment already captured. Please contact admin.'
                ], 401);
            }

            if ($feeInvoice->payment->status === 'paid') {
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

        $attributes  = array('razorpay_signature'  => $request->signature,  'razorpay_payment_id'  => $request->payment_id,  'razorpay_order_id' => $order_id);

        try {
            $razorpay->utility->verifyPaymentSignature($attributes);

            Payment::where('fee_invoice_id', $feeInvoice->id)->first()->update([
                'status' => 'captured',
            ]);
        } catch (Exception $ex) {
            Payment::where('fee_invoice_id', $feeInvoice->id)->first()->update([
                'status' => 'failed',
            ]);

            return response([
                'header' => 'Verification failed',
                'message' => $ex->getMessage(),
            ], 401);
        }

        return response('OK', 200);
    }

    public function webhook(Request $request)
    {
        $webhookBody = $request->getContent();
        $webhookSignature = $request->header('X-Razorpay-Signature');
        $webhookSecret = env('RAZORPAY_WEBHOOK_SECRET');

        $razorpay = new Razorpay(env('RAZORPAY_KEY_ID'), env('RAZORPAY_KEY_SECRET'));

        $now = Carbon::now()->format('u');
        $ip = $request->ip();

        try {
            $razorpay->utility->verifyWebhookSignature($webhookBody, $webhookSignature, $webhookSecret);
        } catch (Exception $ex) {
            $content = $ip . ' _|_ ' . $ex->getMessage() . ' _|_ ' . $webhookBody;
            Storage::put('error_signature_' . $ip . '_' . $now . '_.txt', $content);

            return response('OK', 200);
        }

        $event = null;
        $payload = null;
        $order_id = null;
        $payment_id = null;
        $fee_invoice_id = null;

        try {
            $event = $request->event;
            $payload = $request->payload;
            $order_id = $payload['payment']['entity']['order_id'];
            $payment_id = $payload['payment']['entity']['id'];
            $fee_invoice_id = ModelsRazorpay::where('order_id', $order_id)->first()->payments()->first()->fee_invoice_id;
        } catch (Exception $ex) {
            $content = $ex->getMessage();
            Storage::put('error_log_' . $ip . '_' . $now . '_.txt', $content);
        }



        if ($event === 'payment.authorized') {
            Storage::put('payment.authorized' . $ip . '_' . $now . '_.txt', $payment_id . ' _|_ ' . $fee_invoice_id . ' _|_ ' . $webhookBody);
        }

        if ($event === 'payment.failed') {
            Storage::put('payment.failed' . $ip . '_' . $now . '_.txt', $payment_id . ' _|_ ' . $fee_invoice_id . ' _|_ ' . $webhookBody);
        }

        if ($event === 'order.paid') {
            Storage::put('order.paid' . $ip . '_' . $now . '_.txt', $payment_id . ' _|_ ' . $fee_invoice_id . ' _|_ ' . $webhookBody);
        }

        return response('OK', 200);
    }
}
