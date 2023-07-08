<?php

namespace App\Http\Livewire\Accountant;

use Livewire\Component;
use App\Models\Variable;
use Illuminate\Support\Collection;

class CounterReceiptReport extends Component
{
    public $start_date;
    public $end_date;
    public $variables;

    public $counter_receipts = [];
    public function mount()
    {
        $variables = [
            'ADDRESS_LINE_1' => Variable::where('key', 'ADDRESS_LINE_1')->first()->value,
            'ADDRESS_LINE_2' => Variable::where('key', 'ADDRESS_LINE_2')->first()->value,
            'ADDRESS_LINE_3' => Variable::where('key', 'ADDRESS_LINE_3')->first()->value,
            'SCHOOL_REG_ID' => Variable::where('key', 'SCHOOL_REG_ID')->first()->value,
        ];
        $this->variables = collect($variables);

        $this->start_date = date('Y-m-d');
        $this->end_date = date('Y-m-d');

        $this->generateReport();
    }

    public function generateReport()
    {
        $this->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        if ($this->start_date == $this->end_date) {
            $this->counter_receipts = \App\Models\CounterReceipt::whereDate('created_at', $this->start_date)->with('standard', 'student.user.userDetail')->get();
        } else {
            $this->counter_receipts = \App\Models\CounterReceipt::where('created_at', '>=', $this->start_date)->where('created_at', '<=', $this->end_date)->with('standard', 'student.user.userDetail')->get();
        }
    }

    // when the date range is changed
    public function updated($field)
    {
        if ($field == 'start_date' || $field == 'end_date')
            $this->generateReport();
    }

    public function render()
    {
        return view('livewire.accountant.counter-receipt-report');
    }
}
