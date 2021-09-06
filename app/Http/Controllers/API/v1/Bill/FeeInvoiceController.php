<?php

namespace App\Http\Controllers\API\v1\Bill;

use App\Models\Fee;
use App\Models\User;
use App\Models\Receipt;
use App\Models\FeeInvoice;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade as PDF;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Laravel\Sanctum\PersonalAccessToken;
use App\Http\Controllers\API\v1\Attributes\VariableController;

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
            'session_id' => 'nullable|exists:sessions,id',
            'bill_id' => 'nullable|exists:bills,id',
            'fee_id' => 'nullable|exists:fees,id',
            'standard_id' => 'nullable|exists:standards,id',
            'invoice_id' => 'nullable|max:255',
            'segment' => 'nullable|max:255',
        ]);

        $user_id = '%%';

        if (Auth::user()->hasRole('director') !== true) {
            $user_id = Auth::user()->id;
        }

        $segment = $request->segment;
        $payment_status = '';

        switch ($segment) {
            case "paid":
                $payment_status = ['verified', 'authorised', 'captured'];
                break;
            case "pending":
                $payment_status = ['created', 'failed'];
                break;
            case "due":
                $payment_status = ['created'];
                break;
        }

        $invoices = null;

        if ($segment === 'paid' || $segment === 'pending') {
            $invoices = FeeInvoice::whereHas('payment', function ($query) use ($payment_status) {
                    $query->whereIn('status', $payment_status);
                });
        }

        if ($segment === 'due') {
            // $invoices = FeeInvoice::whereHas('payment', function ($query) use ($payment_status) {
            //         $query->whereIn('status', $payment_status);
            //     })
            //     ->orDoesntHave('payment');
            $invoices = FeeInvoice::doesntHave('payment');
        }

        if ($segment === 'all') {
            $invoices = FeeInvoice::where('id', 'like', "%%");
        }

        return $invoices->whereHas('billFee', function ($query) use ($request) {
            $query->whereHas('bill', function ($query) use ($request) {
                $query->whereHas('session', function ($query) use ($request) {
                    $query->when(!empty($request->session_id), function($query) use($request){$query->where('id', $request->session_id);});
                });
                $query->when(!empty($request->bill_id), function($query) use($request){$query->where('id', $request->bill_id);});
            });
            $query->whereHas('fee', function ($query) use ($request) {
                $query->when(!empty($request->fee_id), function($query) use($request){$query->where('id', $request->fee_id);});
            });
        })
            ->when(!empty($request->standard_id), function($query) use($request){$query->where('standard_id', $request->standard_id);})
            ->where('id', 'like', '%' . $request->invoice_id . '%')
            ->where('user_id', 'like', $user_id)
            ->with(['user:id', 'user.userDetail:id,user_id,name', 'user.student:id,user_id,section_standard_id', 'user.student.sectionStandard.section', 'user.student.sectionStandard.standard', 'payment'])->paginate();
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
        if (Auth::user()->hasRole('director') !== true && $feeInvoice->user->id != Auth::user()->id) {
            return response([
                'header' => 'Forbidden',
                'message' => 'You are not authorised to read this resource!'
            ], 401);
        }

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
    public function print(Request $request, FeeInvoice $feeInvoice)
    {

        if (strpos($request->pat, '|') === false) {
            return response([
                'header' => 'Forbidden',
                'message' => 'You are not authorised to read this resource!'
            ], 401);
        }

        $patArray = explode("|", $request->pat);
        $model_id = $patArray[0];
        $token = $patArray[1];
        $pas = PersonalAccessToken::findOrFail($model_id);

        if(!hash_equals($pas->token, hash('sha256', $token))){
            return response([
                'header' => 'Forbidden',
                'message' => 'You are not authorised to read this resource!'
            ], 401);
        }

        $user = $pas->tokenable;

        if ($user->hasRole('director') !== true && $feeInvoice->user->id != $user->id) {
            return response([
                'header' => 'Forbidden',
                'message' => 'You are not authorised to read this resource!'
            ], 401);
        }

        $data = $feeInvoice->load('billFee:id,bill_id,fee_id', 'billFee.bill', 'billFee.bill.session', 'feeInvoiceItems', 'standard', 'user:id,email', 'user.userDetail:id,user_id,name');

        $variables = VariableController::getAll();

        $pdf = PDF::loadView('documents.fee-invoice', ['data' => $data, 'variables' => $variables]);
        return $pdf->download('fee_invoice_' . $feeInvoice->id . '.pdf');
    }

    /**
     * Print the Fee Invoice.
     *
     * @param  \App\Models\FeeInvoice  $feeInvoice
     * @return \Illuminate\Http\Response
     */
    public function printReceipt(Request $request, FeeInvoice $feeInvoice)
    {
        if (strpos($request->pat, '|') === false) {
            return response([
                'header' => 'Forbidden',
                'message' => 'You are not authorised to read this resource!'
            ], 401);
        }

        $patArray = explode("|", $request->pat);
        $model_id = $patArray[0];
        $token = $patArray[1];
        $pas = PersonalAccessToken::findOrFail($model_id);

        if(!hash_equals($pas->token, hash('sha256', $token))){
            return response([
                'header' => 'Forbidden',
                'message' => 'You are not authorised to read this resource!'
            ], 401);
        }

        $user = $pas->tokenable;

        if ($user->hasRole('director') !== true && $feeInvoice->user->id != $user->id) {
            return response([
                'header' => 'Forbidden',
                'message' => 'You are not authorised to read this resource!'
            ], 401);
        }

        $data = $feeInvoice->load('billFee:id,bill_id,fee_id', 'billFee.bill', 'billFee.bill.session', 'feeInvoiceItems', 'standard', 'payment', 'user:id,email', 'user.userDetail:id,user_id,name');

        $receipt = Receipt::where('fee_invoice_id', $feeInvoice->id)->firstOrFail();

        $pdf = PDF::loadView('documents.fee-invoice-receipt', ['data' => $data, 'receipt' => $receipt]);

        return $pdf->download('fee_invoice_receipt' . $feeInvoice->id . '.pdf');
    }
}
