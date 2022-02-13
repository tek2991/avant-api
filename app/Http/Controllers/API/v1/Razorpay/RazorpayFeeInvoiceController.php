<?php

namespace App\Http\Controllers\API\v1\Razorpay;

use Exception;
use App\Models\Payment;
use App\Models\Receipt;
use App\Models\FeeInvoice;
use App\Models\RazorpayLogs;
use Illuminate\Http\Request;
use App\Models\PaymentMethod;
use Illuminate\Support\Carbon;
use App\Models\TransactionLock;
use Razorpay\Api\Api as Razorpay;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\Razorpay as ModelsRazorpay;
use App\Http\Controllers\API\v1\Attributes\VariableController;
use App\Jobs\UpdateExamUsersJob;

class RazorpayFeeInvoiceController extends Controller
{
    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show(FeeInvoice $feeInvoice)
    {
        $razorpay_locked = TransactionLock::where("name", "Razorpay")->firstOrFail()->locked;

        if($razorpay_locked){
            return response([
                'header' => 'Transaction Locked',
                'message' => 'Please try later, or contact admin!'
            ], 401);
        }
        
        $variables = VariableController::keyPairs();

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

        $user = Auth::user()->load('userDetail');
        $amount = $feeInvoice->gross_amount_in_cent;
        $currency = $variables['RAZORPAY_CURRENCY'];
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

            'key' => $variables['RAZORPAY_KEY_ID'],
            'name' => $feeInvoice->name,
            'description' => $feeInvoice->name . ' for ' . $feeInvoice->user->userDetail->name . ' of Class: ' . $feeInvoice->standard->name . ' of Session: ' . $feeInvoice->billFee->bill->session->name,
            'image' => $variables['LOGO'],
        ];

        $payment_method_id = PaymentMethod::where('name', 'Razorpay')->firstOrFail()->id;

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

        $razorpay = new Razorpay($variables['RAZORPAY_KEY_ID'], $variables['RAZORPAY_KEY_SECRET']);
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
        $variables = VariableController::keyPairs();

        if (Auth::user()->id !== $feeInvoice->user_id && Auth::user()->hasRole('director') !== true) {
            return response([
                'header' => 'Forbidden',
                'message' => 'Please Logout and Login again.'
            ], 401);
        }

        if ($feeInvoice->payment()->count() > 0) {
            if ($feeInvoice->payment->status === 'authorised') {
                // return response([
                //     'header' => 'Payment error',
                //     'message' => 'Payment already authorires. Please contact admin.'
                // ], 401);

                return response('OK', 200);
            }

            if ($feeInvoice->payment->status === 'captured') {
                // return response([
                //     'header' => 'Payment error',
                //     'message' => 'Payment credited. Please contact admin.'
                // ], 401);

                return response('OK', 200);
            }
        }

        if ($feeInvoice->payment()->count() < 1) {
            return response([
                'header' => 'Payment error',
                'message' => 'No payment found for invoice',
            ], 401);
        }

        if ($feeInvoice->payment->razorpays()->count() < 1) {
            return response([
                'header' => 'Razorpay error',
                'message' => 'No razorpay payment found for verification'
            ], 401);
        }

        $razorpay = new Razorpay($variables['RAZORPAY_KEY_ID'], $variables['RAZORPAY_KEY_SECRET']);

        $order_id = $feeInvoice->payment->razorpays->first()->order_id;

        $attributes  = array('razorpay_signature'  => $request->signature,  'razorpay_payment_id'  => $request->payment_id,  'razorpay_order_id' => $order_id);

        try {
            $razorpay->utility->verifyPaymentSignature($attributes);

            Payment::where('fee_invoice_id', $feeInvoice->id)->first()->update([
                'status' => 'verified',
            ]);
        } catch (Exception $ex) {
            return response([
                'header' => 'Verification failed',
                'message' => $ex->getMessage(),
            ], 401);
        }

        return response('OK', 200);
    }

    public function webhook(Request $request)
    {
        $variables = VariableController::keyPairs();

        $webhookBody = $request->getContent();
        $webhookSignature = $request->header('X-Razorpay-Signature');
        $webhookSecret = $variables['RAZORPAY_WEBHOOK_SECRET'];

        $razorpay = new Razorpay($variables['RAZORPAY_KEY_ID'], $variables['RAZORPAY_KEY_SECRET']);

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
        $models_razorpay = null;
        $fee_invoice_id = null;
        $payment = null;
        $payment_status = null;
        $order_status = null;
        $razorpay_payment_status = null;

        try {
            $event = $request->event;
            $payload = $request->payload;
            $order_id = $payload['payment']['entity']['order_id'];
            $payment_id = $payload['payment']['entity']['id'];

            $models_razorpay = ModelsRazorpay::where('order_id', $order_id)->first();
            $fee_invoice_id = $models_razorpay->payments()->first()->fee_invoice_id;
            $payment = Payment::where('fee_invoice_id', $fee_invoice_id)->first();
            $payment_status = $payment->status;
            $order_status = $payment->razorpays->first()->order_status;
            $razorpay_payment_status = $payment->razorpays->first()->payment_status;
        } catch (Exception $ex) {
            $content = $ex->getMessage();
            Storage::put('error_log_' . $ip . '_' . $now . '_.txt', $content);
        }

        if ($event === 'payment.authorized') {
            if ($payment_status === 'created' || $payment_status ===  'failed' || $payment_status ===  'verified') {
                $payment->update([
                    'status' => 'authorised',
                    'status_source' => 'webhook',
                ]);
            }

            if ($order_status === 'created' || $order_status ===  'failed') {
                $models_razorpay->update([
                    'order_status' => 'attempted',
                ]);
            }

            if ($razorpay_payment_status === 'created' || $razorpay_payment_status ===  'failed' || empty($razorpay_payment_status)) {
                $models_razorpay->update([
                    'payment_status' => 'authorised',
                    'payment_id' => $payment_id,
                ]);
            }
        }

        if ($event === 'payment.failed') {
            if ($payment_status === 'created' || $payment_status ===  'verified') {
                $payment->update([
                    'status' => 'failed',
                    'status_source' => 'webhook',
                ]);
            }

            if ($order_status === 'created') {
                $models_razorpay->update([
                    'order_status' => 'attempted',
                ]);
            }

            if ($razorpay_payment_status === 'created' || empty($razorpay_payment_status)) {
                $models_razorpay->update([
                    'payment_status' => 'failed',
                    'payment_id' => $payment_id,
                ]);
            }
        }

        if ($event === 'order.paid') {
            $payment->update([
                'status' => 'captured',
                'status_source' => 'webhook',
            ]);

            $models_razorpay->update([
                'order_status' => 'paid',
            ]);

            $models_razorpay->update([
                'payment_status' => 'captured',
                'payment_id' => $payment_id,
            ]);

            Receipt::create([
                'fee_invoice_id' => $fee_invoice_id,
            ]);
        }

        RazorpayLogs::create([
            'fee_invoice_id' => $fee_invoice_id,
            'order_id' => $order_id,
            'event' => $event,
            'payload' => $webhookBody,
        ]);

        $user = $payment->feeInvoice->user;
        UpdateExamUsersJob::dispatch($user);

        return response('OK', 200);
    }
}
