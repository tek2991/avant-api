<?php

namespace App\Exports;

use App\Models\BillFee;
use Maatwebsite\Excel\Concerns\FromCollection;

class BillFeeExport implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return BillFee::all();
    }
}
