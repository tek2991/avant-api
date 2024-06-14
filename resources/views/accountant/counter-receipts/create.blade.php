<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Issue Receipts') }}
        </h2>
    </x-slot>

    <div class="my-12 p-4 bg-white rounded-md shadow-lg">
        @livewire('accountant.counter-receipt-form')
    </div>
</x-app-layout>
