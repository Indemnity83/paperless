<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('All Files') }}
        </h2>
    </x-slot>

    <livewire:file-browser :hash="$hash"/>

</x-app-layout>
