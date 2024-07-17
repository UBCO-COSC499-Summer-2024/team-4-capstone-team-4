@props(['sortField', 'sortDirection'])

<thead class="sticky top-20 text-xs text-white uppercase bg-[#3b4779] dark:bg-gray-700 dark:text-gray-400">  
   <tr>
        <th scope="col" class="px-6 py-3">
            <div class="flex items-center">
                <x-checkbox id="staff-select-all"/>
            </div>
        </th>
        <th scope="col" class="px-6 py-3">
            <div class="flex items-center">
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
        <th scope="col" class="px-6 py-3">
            <div class="flex items-center">
                Sub-Area(s)
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
        <th scope="col" class="px-6 py-3">
            <div class="flex items-center">
                Completed Hours - {{ date('F') }}
                <button wire:click="sort('total_hours')">
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
        <th scope="col" class="px-6 py-3">
            <div class="flex items-center">
                Target Hours - {{ date('Y') }}
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
        <th scope="col" class="px-6 py-3">
            <div class="flex items-center">
                Rating
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