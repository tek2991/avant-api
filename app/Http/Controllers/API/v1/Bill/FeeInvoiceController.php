<?php

namespace App\Http\Controllers\API\v1\Bill;

use Auth;
use App\Models\User;
use App\Models\FeeInvoice;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class FeeInvoiceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::user();
        // $user = User::find(65);
        return $user->FeeInvoices()->paginate();
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
        $isOwner = $feeInvoice->user_id === Auth::user()->id ? true : false;
        if(!Auth::user()->hasPermissionTo('section CRUD') && !$isOwner){
            return response([
                'header' => 'Not Authoried',
                'message' => 'You cannot view Invoices.'
            ], 401);
        }

        return $feeInvoice->load('feeInvoiceItems', 'user', 'standard');
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
}
