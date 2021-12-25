<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Tiny MCE Demo') }}
        </h2>
    </x-slot>
    
    <div class="py-12">
        <x-head.tinymce-config />
        <x-forms.tinymce-editor />
    </div>
</x-app-layout>
