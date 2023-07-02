<?php

namespace App\Http\Controllers\Accountant;

use App\Http\Controllers\Controller;
use App\Models\CounterReceipt;
use App\Models\Variable;
use Illuminate\Http\Request;

class CounterReceiptController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize(CounterReceipt::class, 'viewAny');
        return view('accountant.counter-receipts.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize(CounterReceipt::class, 'create');
        return view('accountant.counter-receipts.create');
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
     * @param  \App\Models\CounterReceipt  $counterReceipt
     * @return \Illuminate\Http\Response
     */
    public function show(CounterReceipt $counterReceipt)
    {
        $this->authorize($counterReceipt, 'view');
        $counterReceipt->load('counterReceiptItems.counterReceiptItemType', 'student.user.userDetail', 'standard');
        $variables = [
            'ADDRESS_LINE_1' => Variable::where('key', 'ADDRESS_LINE_1')->first()->value,
            'ADDRESS_LINE_2' => Variable::where('key', 'ADDRESS_LINE_2')->first()->value,
            'ADDRESS_LINE_3' => Variable::where('key', 'ADDRESS_LINE_3')->first()->value,
            'SCHOOL_REG_ID' => Variable::where('key', 'SCHOOL_REG_ID')->first()->value,
        ];
        return view('accountant.counter-receipts.show', compact('counterReceipt', 'variables'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\CounterReceipt  $counterReceipt
     * @return \Illuminate\Http\Response
     */
    public function edit(CounterReceipt $counterReceipt)
    {
        $this->authorize($counterReceipt, 'update');
        return view('accountant.counter-receipts.edit', compact('counterReceipt'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\CounterReceipt  $counterReceipt
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, CounterReceipt $counterReceipt)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\CounterReceipt  $counterReceipt
     * @return \Illuminate\Http\Response
     */
    public function destroy(CounterReceipt $counterReceipt)
    {
        //
    }
}
