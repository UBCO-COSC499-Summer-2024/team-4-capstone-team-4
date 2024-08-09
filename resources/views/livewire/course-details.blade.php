@php
    $canEdit = false;
    $user = Auth::user();
    if($user->hasRoles(['admin', 'dept_head', 'dept_staff'])){
        $canEdit = true;
    }
    $canExport = $user->hasRoles(['admin', 'dept_head', 'dept_staff', 'instructor']);
@endphp

<div class="relative overflow-x-auto sm:rounded-lg" x-data="{
    editMode: false,
    enableEdit: function () {
        this.editMode = true;
        const rows = document.querySelectorAll('tr');
        rows.forEach(row => {
            const id = row.getAttribute('data-id');
            $wire.dispatch('enableEdit', {id: id});
        });
    },
    doneEdit: function () {
        this.editMode = false;
        const rows = document.querySelectorAll('tr');
        rows.forEach(row => {
            const id = row.getAttribute('data-id');
            $wire.dispatch('cancelEdit', {id: id});
        });
    },
    saveItems: function () {
        const rows = document.querySelectorAll('.svcr-list-item');
        rows.forEach(row => {
            const id = row.getAttribute('data-id');
            if (!id) {
            console.log(row);
            }
            $wire.dispatch('saveItem', {id: id});
            console.log(id);
        });
        this.doneEdit();
    }
}">
    <div class="flex items-center justify-between mb-2">
        <div class="flex items-center space-x-4">
            @if($canEdit)
                <div class="flex items-center space-x-2">
                    <x-assign-button id="assignButton" wire:key="assign_btn" />
                    <x-create-new-button wire:key="create_btn" />
                    <x-button class="px-5 py-2 mb-2 text-sm font-bold text-center rounded-lg ubc-blue hover:text-white focus:ring-1 focus:outline-none me-1" id="editBtn" wire:key="edit_btn" @click.prevent="enableEdit" x-show="editMode === false" x-cloak>Edit</x-button>
                    <x-button class="px-5 py-2 mb-2 text-sm font-bold text-center rounded-lg ubc-blue hover:text-white focus:ring-1 focus:outline-none me-1" id="saveBtn" wire:key="save_btn" x-show="editMode" x-cloak @click.prevent="saveItems">Save</x-button>
                    <x-button class="px-5 py-2 mb-2 text-sm font-bold text-center rounded-lg ubc-blue hover:text-white focus:ring-1 focus:outline-none me-1" id="cancelBtn" wire:key="cancel_btn" x-show="editMode" x-cloak @click.prevent="doneEdit">Cancel</x-button>
                    <x-coursedetails-archive-modal wire:key="archive_course_btn" />
                </div>
            @endif
        </div>
        <div class="flex items-center ml-auto space-x-2">
            @if($canExport)
                <div class="relative inline-block text-left">
                    @if($user->hasRoles(['dept_head','dept_staff','admin']))
                        @livewire('export-department-report', key('exp-report'))
                    @endif
                    {{-- @if($user->hasRoles(['instructor']))
                        @livewire('export-instructor-report')
                    @endif --}}
                </div>
                @if($canEdit)
                <button wire:click="archiveCourses" id="archiveButton" type="button" class="px-5 py-2 mb-2 text-sm font-bold text-center rounded-lg btn-red hover:text-white focus:ring-1 focus:outline-none me-1">
                    <span class="material-symbols-outlined">
                        delete
                    </span>
                </button>
                @endif
            @endif
        </div>
        @if($canExport)
        @php
            $courseSectionsJson = json_encode($courseSections);
        @endphp
        @endif
    </div>

    <div class="flex items-center justify-between mb-2">
        <div>
            <input type="text" id="searchInput" wire:model.live="searchTerm" placeholder="Search for courses..." class="block p-2 text-sm text-gray-900 rounded-lg search-bar w-80 bg-gray-50 focus:ring-blue-500 focus:border-blue-500" />
        </div>
        @if($canEdit)
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
        @endif
    </div>
    <div class="fixed-header">
        {{-- <form id="editForm" action="{{ route('courses.details.save') }}" method="POST"> --}}
            <form id="editCourses">
            @csrf
            <div class="overflow-auto max-h-[calc(100vh-200px)]">
                <div id="tabContent">
                    <table class="min-w-full divide-y divide-gray-200 svcr-table" id="coursesTable">
                        <livewire:coursedetails-table-header :sortField="$sortField" :sortDirection="$sortDirection" :canEdit="$canEdit"/>
                        <tbody id="courseTableBody">
                            @if(count($courseSections) > 0)
                                @foreach ($courseSections as $section)
                                    {{-- <x-coursedetails-table-row
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
                                        wire:key="course_details_row_{{$section->name}}"
                                    /> --}}
                                    <livewire:coursedetails-table-row :course="$section" :canEdit="$canEdit" :key="'course_details_row_'.$section->name" />
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
