<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Issue Receipts') }}
        </h2>
    </x-slot>

    <div class="my-12 p-4 bg-white rounded-md shadow-lg">
        <div>
            <h2 class="text-lg font-bold py-3">Receipt: {{ $counterReceipt->id }}</h2>
        </div>
        @livewire('accountant.edit-counter-receipt-form', ['counterReceipt' => $counterReceipt])
    </div>
</x-app-layout>
