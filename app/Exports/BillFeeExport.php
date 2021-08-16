<?php

namespace App\Exports;

use App\Models\BillFee;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithMapping;

class BillFeeExport implements FromCollection, WithMapping
{
    protected $billFee;
    function __construct($billFee) {
            $this->billFee = $billFee;
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $feeInvoices = BillFee::where('id', $this->billFee)->first()->feeInvoices()->get();
        return $feeInvoices;
    }

        /**
    * @var FeeInvoice $feeInvoice
    */
    public function map($feeInvoice): array
    {
        $payment = $feeInvoice->payment()->exists() ? $feeInvoice->payment : null;
        $payment_method = $payment ? $payment->paymentMethod->name : 'Not Paid'; 
        $payment_status = $payment ? $payment->status.'('.$payment->status_source.')' : 'Not Paid'; 
        return [
            $feeInvoice->id,
            $feeInvoice->name,
            $feeInvoice->amount_in_cent,
            $feeInvoice->gross_amount_in_cent,
            $payment_status,
            $payment_method,
            $feeInvoice->user->userDetail->name,
            $feeInvoice->standard->name,
        ];
    }
}
