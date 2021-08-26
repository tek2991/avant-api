<?php

namespace App\Http\Controllers\API\v1\Chart;

use App\Models\FeeInvoice;
use App\Http\Controllers\Controller;

class DirectorChartController extends Controller
{
    public function allInvoiceStat()
    {
        $invoices_with_payment = FeeInvoice::has('payment')->with('payment')->get();
        $amount_not_paid = FeeInvoice::doesntHave('payment')->get()->sum('gross_amount_in_cent');

        return response(compact('invoices_with_payment', 'amount_not_paid'));
    }
}
