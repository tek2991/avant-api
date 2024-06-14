<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class FeeInvoiceFeeInvoiceItem extends Pivot
{
    protected $fillable = [
        'amount_in_cent',
        'tax_rate',
        'gross_amount_in_cent',
    ];

}
