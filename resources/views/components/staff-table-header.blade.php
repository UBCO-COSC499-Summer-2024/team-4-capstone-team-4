@props(['sortField', 'sortDirection'])

<thead class="sticky top-20 text-xs text-white uppercase bg-[#3b4779] dark:bg-gray-700 dark:text-gray-400">
   <tr class="svcr-list-header">
        <th scope="col" class="px-6 py-3 svcr-list-header-item">
            <div class="flex items-center px-1.5 py-2 text-sm justify-center">
                <x-checkbox id="staff-select-all"/>
            </div>
        </th>
        <th scope="col" class="px-6 py-3 svcr-list-header-item">
            <div class="flex items-center px-1.5 py-2 text-sm">
                Name
                <button wire:click="sort('firstname')">
                    @if($sortField === 'firstname' && $sortDirection === 'asc')
                        <span class="material-symbols-outlined">
                            keyboard_arrow_up
                        </span>
                    @elseif($sortField === 'firstname' && $sortDirection === 'desc')
                        <span class="material-symbols-outlined">
                            keyboard_arrow_down
                        </span>
                    @else
                       <span class="material-symbols-outlined">
                            unfold_more
                        </span>
                    @endif
                </button>
            </div>
        </th>
        <th scope="col" class="px-6 py-3 svcr-list-header-item">
            <div class="flex items-center px-1.5 py-2 text-sm">
                Area(s)
                <button wire:click="sort('area')">
                    @if($sortField === 'area' && $sortDirection === 'asc')
                        <span class="material-symbols-outlined">
                            keyboard_arrow_up
                        </span>
                    @elseif($sortField === 'area' && $sortDirection === 'desc')
                        <span class="material-symbols-outlined">
                            keyboard_arrow_down
                        </span>
                    @else
                       <span class="material-symbols-outlined">
                            unfold_more
                        </span>
                    @endif
                </button>
            </div>
        </th>
        <th scope="col" class="px-6 py-3 svcr-list-header-item">
            <div class="flex items-center px-1.5 py-2 text-xs">
                <label for="month-select" class="mr-2">Completed Hours</label>
                <div class="relative">
                    <select id="month-select" wire:model.live="selectedMonth" class="appearance-none border border-white rounded-md px-1.5 py-1 text-xs text-white bg-[#3b4779] pr-8">
                        @foreach (['January', 'Febuary', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'] as $month)
                            <option value="{{ $month }}" {{ $month === date('F') ? 'selected' : '' }}>{{ substr($month, 0, 3) }}</option>
                        @endforeach
                    </select>
                    <span class="absolute inset-y-0 right-0 flex items-center pr-2 pointer-events-none">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </span>
                </div>
                <button wire:click="sort('total_hours')" class="ml-2">
                    @if($sortField === 'total_hours' && $sortDirection === 'asc')
                        <span class="material-symbols-outlined">
                            keyboard_arrow_up
                        </span>
                    @elseif($sortField === 'total_hours' && $sortDirection === 'desc')
                        <span class="material-symbols-outlined">
                            keyboard_arrow_down
                        </span>
                    @else
                       <span class="material-symbols-outlined">
                            unfold_more
                        </span>
                    @endif
                </button>
            </div>
        </th>              
        <th scope="col" class="px-6 py-3 svcr-list-header-item">
            <div class="flex items-center px-1.5 py-2 text-xs">
                <label for="year-select" class="mr-2">Target Hours</label>
                <div class="relative">
                    <select id="year-select" wire:model.live="selectedYear" class="appearance-none border border-white rounded-md px-1.5 py-1 text-xs text-white bg-[#3b4779] pr-8">
                        @php
                            $user = Auth::user();
                            $dept_id = \App\Models\UserRole::find($user->id)->department_id;
                            $years = \App\Models\DepartmentPerformance::where('dept_id', $dept_id)->pluck('year');
                        @endphp
                        @foreach ($years as $year)
                            <option value="{{ $year }}" {{ $year === date('Y') ? 'selected' : '' }}>{{ $year }}</option>
                        @endforeach
                    </select>
                    <span class="absolute inset-y-0 right-0 flex items-center pr-2 pointer-events-none">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </span>
                </div>
                <button wire:click="sort('target_hours')">
                    @if($sortField === 'target_hours' && $sortDirection === 'asc')
                        <span class="material-symbols-outlined">
                            keyboard_arrow_up
                        </span>
                    @elseif($sortField === 'target_hours' && $sortDirection === 'desc')
                        <span class="material-symbols-outlined">
                            keyboard_arrow_down
                        </span>
                    @else
                       <span class="material-symbols-outlined">
                            unfold_more
                        </span>
                    @endif
                </button>
            </div>
        </th>
        <th scope="col" class="px-6 py-3 svcr-list-header-item">
            <div class="flex items-center px-1.5 py-2 text-sm">
                Score
                <button wire:click="sort('score')">
                    @if($sortField === 'score' && $sortDirection === 'asc')
                        <span class="material-symbols-outlined">
                            keyboard_arrow_up
                        </span>
                    @elseif($sortField === 'score' && $sortDirection === 'desc')
                        <span class="material-symbols-outlined">
                            keyboard_arrow_down
                        </span>
                    @else
                       <span class="material-symbols-outlined">
                            unfold_more
                        </span>
                    @endif
                </button>
            </div>
        </th>
    </tr>
</thead>
