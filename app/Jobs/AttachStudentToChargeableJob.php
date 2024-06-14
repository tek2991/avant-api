<?php

namespace App\Jobs;

use App\Models\Chargeable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class AttachStudentToChargeableJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $chargeable;
    public $students;
    public $method;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Chargeable $chargeable, $students = null, $method = 'attach')
    {
        $this->chargeable = $chargeable;
        $this->students = $students;
        $this->method = $method;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->chargeable->students()->sync($this->students);
    }
}
