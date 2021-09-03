<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Imports') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <h5>Import Students</h5>
        <a href="{{ route('student.template') }}" target="_blank">Download Template</a>
        <a href="{{ route('attribute.export') }}" target="_blank">Download Attributes</a>
        <form action="">
            <input type="file" name="" id="">
            <button type="submit">Submit</button>
        </form>
    </div>
</x-app-layout>
