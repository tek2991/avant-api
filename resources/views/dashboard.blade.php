<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="flex-auto flex space-x-3">
            <a href="{{ url('/telescope') }}" target="_blank"
                class="w-1/3 h-16 flex items-center justify-center rounded-md border border-gray-300">
                Telescope
            </a>
            <a href="{{ url('/horizon') }}" target="_blank"
                class="w-1/3 h-16 flex items-center justify-center rounded-md border border-gray-300">
                Horizon
            </a>
            <a href="{{ url('/phpinfo') }}" target="_blank"
                class="w-1/3 h-16 flex items-center justify-center rounded-md border bg-black text-white">
                PHP INFO
            </a>
        </div>
    </div>
</x-app-layout>
