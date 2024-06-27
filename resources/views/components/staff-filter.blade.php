<div class="flex items-center justify-center p-4 relative">
    <button id="filterButton" data-dropdown-toggle="dropdown" class="inline-flex items-center text-gray-500 bg-white border border-gray-300 focus:outline-none hover:bg-gray-100 focus:ring-4 focus:ring-gray-100 font-medium rounded-lg text-sm px-3 py-1.5 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:bg-gray-700 dark:hover:border-gray-600 dark:focus:ring-gray-700" type="button">
        <span class="sr-only">Filter</span>
        Filter by Subarea
        <svg class="w-2.5 h-2.5 ms-2.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 6">
            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 4 4 4-4"/>
        </svg>
    </button>
  
    <!-- Dropdown menu -->
    <div id="filterDropdown" class="z-10 hidden w-56 p-3 bg-white rounded-lg shadow dark:bg-gray-700 absolute top-full mt-2">
        <h6 class="mb-3 text-sm font-medium text-gray-900 dark:text-white">
            Subareas
        </h6>
        <ul class="space-y-2 text-sm" aria-labelledby="dropdownDefault">
            @php
                $allAreas = App\Models\Area::all();
            @endphp
            @foreach ($allAreas as $area)
                <li class="block w-full px-4 py-2 text-start text-sm leading-5 text-gray-700 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 transition duration-150 ease-in-out">
                    <x-checkbox wire:model="selectedAreas" class="filter-checkbox" name="areas[]" value="{{ $area->name }}" />
                    {{ $area->name }}
                </li>
            @endforeach
            <x-button wire:click="filter">Filter</x-button>
        </ul>
    </div>
</div>
