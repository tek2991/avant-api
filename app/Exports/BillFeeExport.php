<?php

namespace App\Exports;

use App\Models\BillFee;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;

class BillFeeExport implements FromCollection, WithMapping, WithHeadings
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
        $payment_status = $payment ? $payment->status : 'N/A';

        return [
            $feeInvoice->id,
            $feeInvoice->name,
            $feeInvoice->standard->name,
            $feeInvoice->billFee->bill->session->name,
            ($feeInvoice->amount_in_cent)/100,
            ($feeInvoice->gross_amount_in_cent)/100,
            $payment_status,
            $payment_method,
            $feeInvoice->user->userDetail->name,
            $feeInvoice->user->userDetail->fathers_name,
            $feeInvoice->user->userDetail->phone,
            $feeInvoice->user->userDetail->phone_alternate,
        ];
    }

    public function headings(): array
    {
        return [
            'Invoice #',
            'Fee name',
            'Standard name',
            'Academic session',
            'Net amount',
            'Amount with tax',
            'Payment status',
            'Payment method',
            'Student name',
            'Fathers name',
            'Phone',
            'Phone alternate',
        ];
    }
}
