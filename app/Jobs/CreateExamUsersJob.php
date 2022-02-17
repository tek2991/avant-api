<?php

namespace App\Jobs;

use App\Models\User;
use App\Models\Student;
use App\Models\ExamUserState;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldBeUnique;

class CreateExamUsersJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $exam_id;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($exam_id)
    {
        $this->exam_id = $exam_id;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $user_ids = Student::all()->pluck('user_id');
        $exam_id = $this->exam_id;

        $exam_user_active_state_id = ExamUserState::where('name', 'Active')->first()->id;
        $exam_user_inactive_state_id = ExamUserState::where('name', 'Inactive')->first()->id;

        foreach ($user_ids as $user_id) {
            $user = User::find($user_id);
            $unpaid_amount = $user->unpaidDue();

            $exam_user_state_id = $unpaid_amount > 10 ? $exam_user_inactive_state_id : $exam_user_active_state_id;

            $user->exams()->attach($exam_id, [
                'exam_user_state_id' => $exam_user_state_id,
            ]);
        }
    }
}
