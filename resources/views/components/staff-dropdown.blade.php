<x-dropdown>   
    <x-slot name="trigger">
        <button id="dropdownActionButton" data-dropdown-toggle="dropdownAction" class="inline-flex items-center text-gray-500 bg-white border border-gray-300 focus:outline-none hover:bg-gray-100 focus:ring-4 focus:ring-gray-100 font-medium rounded-lg text-sm px-3 py-1.5 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:bg-gray-700 dark:hover:border-gray-600 dark:focus:ring-gray-700" type="button">
            <span class="sr-only">Action button</span>
            Actions
            <svg class="w-2.5 h-2.5 ms-2.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 6">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 4 4 4-4"/>
            </svg>
        </button>
    </x-slot>

    <x-slot name="content">
        <x-dropdown-link href="#" id="add-target-hours" >
            {{ __('Add Target Hours') }}
        </x-dropdown-link>
        <x-dropdown-link href="#" id="edit-target-hours" >
            {{ __('Edit mode') }}
        </x-dropdown-link>
    </x-slot>
</x-dropdown>