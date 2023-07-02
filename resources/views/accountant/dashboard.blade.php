<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Accountant Dashboard') }}
        </h2>
    </x-slot>

    <div class="my-12 bg-white shadow-lg rounded-lg p-4">
        <div>
            <h2 class="text-lg font-bold my-6">Pending Receipts</h2>
        </div>
        @livewire('accountant.pending-counter-receipt-table')
    </div>
</x-app-layout>
