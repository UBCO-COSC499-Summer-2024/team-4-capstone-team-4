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

                <x-create-new-button id="createNewButton" />
                <x-assign-button id="assignButton" />
                <x-edit-button id="editButton" />
                <x-coursedetails-deleteButton />

                <x-save-button id="saveButton" style="display: none;" />
                <x-cancel-button id="cancelButton" style="display: none;" />
            </div>
        @endif
    </div>

    <div class="fixed-header">
        <form>
            @csrf
            <div class="overflow-auto max-h-[calc(100vh-200px)]">
                <div id="tabContent">
                    <table class="min-w-full divide-y divide-gray-200 svcr-table" id="coursesTable">
                        <x-coursedetails-table-header :sortField="$sortField" :sortDirection="$sortDirection" />
                        <tbody id="courseTableBody">
                            @if($courseSections->count())
                                @foreach ($courseSections as $section)
                                    <x-coursedetails-table-row
                                        :courseName="$section->name"
                                        :departmentName="$section->departmentName"
                                        :enrolledStudents="$section->enrolled"
                                        :droppedStudents="$section->dropped"
                                        :courseCapacity="$section->capacity"
                                        :room="$section->room"
                                        :timings="$section->timings"
                                        :sectionId="$section->id"
                                        :seiData="$section->averageRating"
                                        :instructorName="$section->instructorName ?? ''"
                                    />
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="7" class="py-4 text-center text-gray-500">No course sections found.</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                    <div class="flex items-center justify-end">
                        Courses per page: 
                        <select wire:model.live="pagination" class="w-auto min-w-[70px] text-[#3b4779] bg-white border border-[#3b4779] focus:outline-none hover:text-white hover:bg-[#3b4779] focus:ring-4 focus:ring-gray-100 font-medium rounded-lg text-sm px-3 py-1.5">
                            <option value="10">10</option>
                            <option value="25">25</option>
                            <option value="50">50</option>
                            <option value="100">100</option>
                            <option value="all">All</option>
                        </select>
                        <div>
                            @if($pagination !== 'all')
                                {{ $courseSections->links() }}
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
