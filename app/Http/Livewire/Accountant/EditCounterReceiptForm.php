<?php

namespace App\Http\Livewire\Accountant;

use Livewire\Component;
use App\Models\CounterReceiptItem;

class EditCounterReceiptForm extends Component
{

    public $counterReceipt;
    public $counterReceiptItemTypes;

    public $counterReceiptItems = [];

    public $confirmation = false;

    public $error;

    public $state = [
        'counter_receipt_item_type_id' => null,
        'amount_in_cents' => null,
        'amount' => null,
    ];


    public function mount($counterReceipt)
    {
        $this->counterReceipt = $counterReceipt;
        $this->counterReceiptItemTypes = \App\Models\CounterReceiptItemType::all();

        $this->refreshCounterReceiptItems();
    }

    public function refreshCounterReceiptItems()
    {
        $this->counterReceiptItems = CounterReceiptItem::where('counter_receipt_id', $this->counterReceipt->id)->get();
    }

    public function addCounterReceiptItem()
    {
        $this->state['amount_in_cents'] = $this->state['amount'] * 100;

        $this->validate([
            'state.counter_receipt_item_type_id' => 'required|exists:counter_receipt_item_types,id',
            'state.amount_in_cents' => 'required|integer|min:1',
        ]);

        CounterReceiptItem::create([
            'counter_receipt_id' => $this->counterReceipt->id,
            'counter_receipt_item_type_id' => $this->state['counter_receipt_item_type_id'],
            'amount_in_cents' => $this->state['amount_in_cents'],
            'remarks' => null,
        ]);

        $this->state = [
            'counter_receipt_item_type_id' => null,
            'amount_in_cents' => null,
        ];

        $this->refreshCounterReceiptItems();
    }

    public function removeCounterReceiptItem($counterReceiptItemId)
    {
        CounterReceiptItem::find($counterReceiptItemId)->delete();

        $this->refreshCounterReceiptItems();
    }

    public function confirmAndSave()
    {
        $this->validate([
            'confirmation' => 'required|accepted',
        ]);

        // Check if there is at least one counter receipt item.
        if (count($this->counterReceiptItems) < 1) {
            $this->error = 'There must be at least one counter receipt item.';
            return;
        }

        $this->counterReceipt->completed = true;
        $this->counterReceipt->save();

        return redirect()->route('accountant.counter-receipts.show', $this->counterReceipt);
    }

    public function render()
    {
        return view('livewire.accountant.edit-counter-receipt-form');
    }
}
