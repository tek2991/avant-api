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
        return [
            $feeInvoice->id,
            $feeInvoice->name,
            // $feeInvoice->user->userDetail->name,
            $feeInvoice->amount_in_cent,
        ];
    }
}
