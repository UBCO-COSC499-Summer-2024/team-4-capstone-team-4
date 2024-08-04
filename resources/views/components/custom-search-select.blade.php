@props(['availableInstructors'])

<div x-data="{ open: false }" class="searchable-select flex items-center rounded-md border border-solid border-gray-500 max-w-[240px] ml-2">
    <input wire:model.live="instructorSearch" type="text" placeholder="Select an instructor..." class="rounded-md" @click="open = !open">
    <div class="flex items-center justify-center" @click="open = !open">
        <svg class="w-2.5 h-2.5 ms-2.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 6">
            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 4 4 4-4"/>
        </svg>
    </div>
    <ul x-show="open" @click.away="open = false" class="z-50 w-56 p-3 bg-white rounded-lg shadow-lg dark:bg-gray-700 absolute top-full mt-2 ring-1 ring-black ring-opacity-5 max-h-64 overflow-y-scroll">
        @foreach($availableInstructors as $instructor)
            <li value="{{ $instructor->id }}">{{ $instructor->firstname }} {{ $instructor->lastname }}</li>
        @endforeach
    </ul>
</div>