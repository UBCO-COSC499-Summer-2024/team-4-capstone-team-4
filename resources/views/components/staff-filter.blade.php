<div class="flex items-center justify-center p-4 relative" x-data="{ open: false }">
    <button 
        id="filterButton" 
        @click="open = !open" 
        class="inline-flex items-center text-[#3b4779] bg-white border border-[#3b4779] focus:outline-none hover:text-white hover:bg-[#3b4779] focus:ring-4 focus:ring-gray-100 font-medium rounded-lg text-sm px-3 py-1.5 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:bg-gray-700 dark:hover:border-gray-600 dark:focus:ring-gray-700"
        type="button">
        <span class="sr-only">Filter</span>
        Filter by Area
        <svg class="w-2.5 h-2.5 ms-2.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 6">
            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 4 4 4-4"/>
        </svg>
    </button>
  
    <!-- Dropdown menu -->
    <div 
        id="filterDropdown"
        x-show="open"
        @click.away="open = false"
        x-cloak
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="transform opacity-0 scale-95"
        x-transition:enter-end="transform opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-300"
        x-transition:leave-start="transform opacity-100 scale-100"
        x-transition:leave-end="transform opacity-0 scale-95"
        class="hidden z-50 w-56 p-3 bg-white rounded-lg shadow-lg dark:bg-gray-700 absolute top-full mt-2 ring-1 ring-black ring-opacity-5 max-h-64 overflow-y-scroll">
        <h6 class="mb-1 text-sm font-bold text-gray-900 dark:text-white">Areas</h6>
        <ul class="space-y-1 text-sm" aria-labelledby="dropdownDefault">
            @php
                $user = Auth::user();
                $dept_id = App\Models\UserRole::find($user->id)->department_id;
                $allAreas = App\Models\Area::where('dept_id', $dept_id)->get();
            @endphp
            @foreach ($allAreas as $area)
                <li class="block w-full px-4 py-2 text-start text-sm leading-5 text-gray-700 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 transition duration-150 ease-in-out">
                    <x-checkbox wire:model="selectedAreas" class="filter-checkbox" name="areas[]" value="{{ $area->name }}" />
                    {{ $area->name }}
                </li>
            @endforeach
            <div>
                <x-staff-button wire:click="filter" @click="open = false">Filter</x-staff-button>
                <x-staff-button wire:click="clearFilter" @click="open = false">Clear</x-staff-button>
            </div>
        </ul>
    </div>
</div>