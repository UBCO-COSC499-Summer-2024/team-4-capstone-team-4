<div class="flex items-center justify-center p-4 relative">
    <button id="filterButton" data-dropdown-toggle="dropdown" class="inline-flex items-center text-[#3b4779] bg-white border border-[#3b4779] focus:outline-none hover:text-white hover:bg-[#3b4779] focus:ring-4 focus:ring-gray-100 font-medium rounded-lg text-sm px-3 py-1.5 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:bg-gray-700 dark:hover:border-gray-600 dark:focus:ring-gray-700" type="button">
        <span class="sr-only">Filter</span>
        Filter
        <svg class="w-2.5 h-2.5 ms-2.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 6">
            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 4 4 4-4"/>
        </svg>
    </button>
  
    <!-- Dropdown menu -->
    <div id="filterDropdown" class="hidden z-50 w-56 p-3 bg-white rounded-lg shadow dark:bg-gray-700 absolute top-full mt-2">
        <h6 class="mb-3 text-sm font-medium text-gray-900 dark:text-white">
            Departments
        </h6>
        <ul class="space-y-2 text-sm" aria-labelledby="dropdownDefault">
            @php
                $depts =\App\Models\Department::all();
            @endphp
            @foreach ($depts as $dept)
                <li class="block w-full px-4 py-2 text-start text-sm leading-5 text-gray-700 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 transition duration-150 ease-in-out">
                    <x-checkbox wire:model="selectedDepts" class="filter-checkbox" name="depts[]" value="{{ $dept->name }}" />
                    {{ $dept->name }}
                </li>
            @endforeach
        </ul>
        <h6 class="mb-3 text-sm font-medium text-gray-900 dark:text-white">
            Roles
        </h6>
        <ul class="space-y-2 text-sm" aria-labelledby="dropdownDefault">
            <li class="block w-full px-4 py-2 text-start text-sm leading-5 text-gray-700 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 transition duration-150 ease-in-out">
                <x-checkbox wire:model="selectedRoles" class="filter-checkbox" name="roles[]" value="instructor" />
                Instructor
            </li>
            <li class="block w-full px-4 py-2 text-start text-sm leading-5 text-gray-700 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 transition duration-150 ease-in-out">
                <x-checkbox wire:model="selectedRoles" class="filter-checkbox" name="roles[]" value="dept_head" />
                Department Head
            </li>
            <li class="block w-full px-4 py-2 text-start text-sm leading-5 text-gray-700 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 transition duration-150 ease-in-out">
                <x-checkbox wire:model="selectedRoles" class="filter-checkbox" name="roles[]" value="dept_staff" />
                Department Staff
            </li>
            <li class="block w-full px-4 py-2 text-start text-sm leading-5 text-gray-700 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 transition duration-150 ease-in-out">
                <x-checkbox wire:model="selectedRoles" class="filter-checkbox" name="roles[]" value="admin" />
                Admin
            </li>
        </ul>
        <div>
            <x-staff-button wire:click="filter">Filter</x-staff-button>
            <x-staff-button wire:click="clearAdminFilter">Clear</x-staff-button>
        </div>
    </div>
</div>
