<?php

namespace App\Jobs;

use App\Models\Bill;
use App\Models\BillFee;
use App\Models\FeeInvoice;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldBeUnique;

class CreateBillWithInvoiceJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $bill;
    public $fees;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Bill $bill, $fees)
    {
        $this->onQueue('heavy');
        $this->bill = $bill;
        $this->fees = $fees;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        foreach($this->fees as $fee){
            $chargeables = $fee->chargeables;
            foreach($chargeables as $chargeable){
                $chargeable->FeeInvoiceItems()->create([
                    'name' => $chargeable->name,
                    'description' => $chargeable->description,
                    'bill_id' => $this->bill->id,
                    'amount_in_cent' => $chargeable->amount_in_cent,
                    'tax_rate' => $chargeable->tax_rate,
                    'gross_amount_in_cent' => $chargeable->gross_amount_in_cent,
                ]);
            }

            $standards = $fee->standards;
            foreach($standards as $standard){
                $students = $standard->students;
                foreach($students as $student){
                    $user = $student->user;

                    $feeInvoice = FeeInvoice::create([
                        'name' => $fee->name,
                        'user_id' => $user->id,
                        'bill_fee_id' => $this->bill->fees()->find($fee)->pivot->id,
                        'standard_id' => $student->sectionStandard->standard_id
                    ]);

                    
                    $chargeables = $student->chargeables;
                    foreach($chargeables as $chargeable){
                        $feeInvoiceItems = $chargeable->feeInvoiceItems->where('bill_id', $this->bill->id);                        
                        foreach($feeInvoiceItems as $feeInvoiceItem){
                            $feeInvoiceItem->feeInvoices()->attach($feeInvoice->id);
                        }
                    }

                    $feeInvoice->update([
                        'amount_in_cent' => $feeInvoice->feeInvoiceItems->sum('amount_in_cent'),
                        'tax_in_cent' => $feeInvoice->feeInvoiceItems->sum('gross_amount_in_cent') - $feeInvoice->feeInvoiceItems->sum('amount_in_cent'),
                        'gross_amount_in_cent' => $feeInvoice->feeInvoiceItems->sum('gross_amount_in_cent')
                    ]);
                }
            }

            $billFee = BillFee::where('bill_id', $this->bill->id)->where('fee_id', $fee->id)->first();
            
            $billFee->update([
                'amount_in_cent' => $billFee->feeInvoices->sum('amount_in_cent'),
                'tax_in_cent' => $billFee->feeInvoices->sum('gross_amount_in_cent') - $billFee->feeInvoices->sum('amount_in_cent'),
                'gross_amount_in_cent' => $billFee->feeInvoices->sum('gross_amount_in_cent')
            ]);
        }
    }
}
