<?php

namespace App\Jobs;

use App\Models\ExamUser;
use App\Models\ExamUserState;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldBeUnique;

class UpdateExamUsersJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $user;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($user)
    {
        $this->user = $user;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $user = $this->user;
        $feeInvoices = $user->feeInvoices()->with('payment')->get();
        $unpaid_amount = 0;

        foreach ($feeInvoices as $feeInvoice) {
            if ($feeInvoice->payment == null) {
                $unpaid_amount += $feeInvoice->gross_amount_in_cent;
            }else if (in_array($feeInvoice->payment->payment_status_id, ['created', 'failed'])) {
                $unpaid_amount += $feeInvoice->gross_amount_in_cent;
            }
        }

        if($unpaid_amount < 10) {
            $exam_users = ExamUser::where('user_id', $user->id)->get();

            $exam_user_active_state_id = ExamUserState::where('name', 'Active')->first()->id;

            foreach ($exam_users as $exam_user) {
                if ($exam_user->exam_user_state_id != $exam_user_active_state_id) {
                    $exam_user->exam_user_state_id = $exam_user_active_state_id;
                    $exam_user->save();
                }
            }
        }
    }
}
