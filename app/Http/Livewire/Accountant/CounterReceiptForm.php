<?php

namespace App\Http\Livewire\Accountant;

use Livewire\Component;

class CounterReceiptForm extends Component
{
    public $counterReceiptItemTypes;
    public $standards;
    public $students = [];

    public $state = [
        'student_id' => null,
        'standard_id' => null,
        'remarks' => null,

        'completed' => false,

        'created_by' => null,
    ];

    public $stateCounterReceiptItems = [];

    public function mount()
    {
        $this->standards = \App\Models\Standard::all();
    }

    public function updatedStateStandardId($standardId)
    {
        $this->students = \App\Models\Standard::find($standardId)->students()->with('user.userDetail')->orderBy('roll_no')->get();
    }

    public function submit()
    {
        $this->state['created_by'] = auth()->user()->id;

        $this->validate([
            'state.student_id' => 'required|exists:students,id',
            'state.standard_id' => 'required|exists:standards,id',
            'state.remarks' => 'nullable|string',

            'state.completed' => 'boolean',
            'state.created_by' => 'required|exists:users,id',
        ]);

        $counterReceipt = \App\Models\CounterReceipt::create([
            'student_id' => $this->state['student_id'],
            'standard_id' => $this->state['standard_id'],
            'remarks' => $this->state['remarks'],

            'completed' => $this->state['completed'],

            'created_by' => $this->state['created_by'],
        ]);

        return redirect()->route('accountant.counter-receipts.edit', $counterReceipt);
    }

    public function render()
    {
        return view('livewire.accountant.counter-receipt-form');
    }
}
