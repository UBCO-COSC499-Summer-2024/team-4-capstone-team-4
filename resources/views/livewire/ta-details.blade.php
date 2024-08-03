@php
    $canEdit = false;
    $user = Auth::user();
    if($user->hasRoles(['admin', 'dept_head', 'dept_staff'])){
        $canEdit = true;
    }
@endphp

<div class="relative overflow-x-auto sm:rounded-lg">
    <div class="flex justify-between items-center mb-2">
        <div>
            <input type="text" id="searchInput" wire:model.live="searchTerm" placeholder="Search for courses..." class="search-bar block p-2 text-sm text-gray-900 w-80 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500" />
        </div>
        @if($canEdit)
            <div class="flex justify-end items-center space-x-2">
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

                <x-create-ta-button id="createNewTAButton" />
                <x-assign-ta-button id="assignTAButton"/>
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
                                    <td colspan="5" class="py-4 text-center text-gray-500">No TAs found.</td>
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
</div>

