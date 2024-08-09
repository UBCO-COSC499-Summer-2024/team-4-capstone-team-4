@php
    $canEdit = false;
    $user = Auth::user();
    if($user->hasRoles(['admin', 'dept_head', 'dept_staff'])){
        $canEdit = true;
    }
@endphp

<div class="relative overflow-x-auto sm:rounded-lg">
    <div class="flex justify-between items-center mb-2">
        @if($canEdit)
        <div class="flex flex-row space-y-0">
            @livewire('assign-t-a-modal')
            @livewire('create-t-a-modal')
        </div>
         @endif
         <button id="AssignTAsButton" type="button" 
             class="ubc-blue hover:text-white focus:ring-1 focus:outline-none font-bold rounded-lg text-sm px-5 py-2 text-center me-1 mb-2" 
             onclick="window.location.href='http://localhost/upload-file/assign-tas'"
             wire:click="{{route('upload.file.assign.tas')}}">
             Assign TAs via CSV
         </button>
        </div>
        <div class="flex justify-between items-center mb-2">
            <div class="flex-grow">
                <input type="text" id="searchInput" wire:model.live="searchTerm" placeholder="Search for TAs..." class="search-bar block p-2 text-sm text-gray-900 w-80 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500"/>
            </div>
        @if($canEdit)
            <div class="flex items-center space-x-2">
                <div class="filter-area">
                    <select wire:model.change="areaId" id="areaFilter" name="area_id" class="form-select">
                        <option value="">Filter By Area</option>
                        @foreach($areas as $area)
                            <option value="{{ $area->id }}" {{ $areaId == $area->id ? 'selected' : '' }}>
                                {{ $area->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
        @endif
    </div>

    <div class="fixed-header">
        <form>
            @csrf
            <div class="overflow-auto max-h-[calc(100vh-200px)]">
                <div id="tabContent">
                    <table class="min-w-full divide-y divide-gray-200 svcr-table" id="taTable">
                        <x-coursedetails-ta-table-header :sortField="$sortField" :sortDirection="$sortDirection" />
                        <tbody>
                            @if($tas->count())
                                @foreach ($tas as $ta)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $ta->name }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $ta->rating }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $ta->taCourses }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $ta->instructorName }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $ta->email }}</td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="5" class="py-4 text-center text-gray-500 no-tas-message">No TAs yet.</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                    <div class="flex items-center justify-end">
                        TAs per page: 
                        <select wire:model.live="pagination" class="w-auto min-w-[70px] text-[#3b4779] bg-white border border-[#3b4779] focus:outline-none hover:text-white hover:bg-[#3b4779] focus:ring-4 focus:ring-gray-100 font-medium rounded-lg text-sm px-3 py-1.5">
                            <option value="10">10</option>
                            <option value="25">25</option>
                            <option value="50">50</option>
                            <option value="100">100</option>
                            <option value="all">All</option>
                        </select>
                        <div>
                            @if($pagination !== 'all')
                                {{ $tas->links() }}
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
    <x-create-ta-modal />
    <style>
        .no-tas-message {
            font-size: 1.25rem; /* Increase the font size */
            font-weight: bold; /* Make the text bold */
        }
    </style>
</div>

