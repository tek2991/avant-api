<?php

namespace App\Exports;

use App\Models\BillFee;
use Maatwebsite\Excel\Concerns\FromCollection;

class BillFeeExport implements FromCollection
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
        $loadedBillFee = BillFee::where('id', $this->billFee)->get();
        return $loadedBillFee;
    }
}
