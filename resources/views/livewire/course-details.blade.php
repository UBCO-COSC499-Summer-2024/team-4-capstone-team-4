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
            row.querySelectorAll('td').forEach((cell, index) => {
                if ([4, 5, 6].includes(index)) {
                    cell.setAttribute('contenteditable', 'true');
                    cell.classList.add('edit-highlight');
                }
            });
        });
    },
    validateInput: function() {
        let isValid = true;
        const rows = document.querySelectorAll('tr');
        rows.forEach(row => {
            row.querySelectorAll('td').forEach((cell, index) => {
                if ([4, 5, 6].includes(index)) {
                    const value = cell.innerText.trim();
                    if (isNaN(value) || value === '') {
                        cell.classList.add('error-input');
                        isValid = false;
                    } else {
                        cell.classList.remove('error-input');
                    }
                }
            });
            const enrolledStudents = row.children[3]?.innerText.trim();
            const courseCapacities = row.children[5]?.innerText.trim();
            if (!isNaN(enrolledStudents) && !isNaN(courseCapacities) && enrolledStudents !== '' && courseCapacities !== '') {
                if (parseInt(enrolledStudents) > parseInt(courseCapacities)) {
                    row.children[6].classList.add('error-input');
                    row.children[4].classList.add('error-input');
                    isValid = false;
                } else {
                    row.children[6].classList.remove('error-input');
                    row.children[4].classList.remove('error-input');
                }
            }
        });
        return isValid;
    },
    doneEdit: function () {
        this.editMode = false;
        const rows = document.querySelectorAll('tr');
        rows.forEach(row => {
            row.querySelectorAll('td').forEach(cell => {
                cell.setAttribute('contenteditable', 'false');
                cell.classList.remove('edit-highlight');
                cell.classList.remove('error-input');
            });
        });
    },
    saveItems: function () {
        $dispatch('save-changes');
        this.doneEdit();
        {{-- if (!this.validateInput()) {
            alert('Please enter valid numeric values in the editable fields.');
            return;
        }
    
        const confirmSave = confirm('Do you really want to save the changes?');
        if (!confirmSave) return;
    
        const form = document.querySelector('#editCourses');
        if (!form) {
            console.error('Form element not found.');
            return;
        }
    
        const rows = document.querySelectorAll('tr');
        const formData = new FormData(form);
    
        rows.forEach(row => {
            formData.append('ids[]', row.getAttribute('data-id'));
            formData.append('courseNames[]', row.children[0]?.innerText.trim().split(' - ')[0] || '');
            formData.append('enrolledStudents[]', row.children[4]?.innerText.trim() || '');
            formData.append('droppedStudents[]', row.children[5]?.innerText.trim() || '');
            formData.append('courseCapacities[]', row.children[6]?.innerText.trim() || '');
        });
    
        console.log('Form Data:', Array.from(formData.entries()));
        const token = document.querySelector(`meta[name='csrf-token']`).getAttribute('content');
        console.log(token);

        fetch(form.action, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': token
            },
            body: formData
        })
        .then(response => {
            if (!response.ok) {
                console.log(response, JSON.stringify(response));
                throw new Error('Network response was not ok.');
            }
            return response.json();
        })
        .then(result => {
            console.log('Server Response:', result);
            if (result.message === 'Courses updated successfully.') {
                alert('Successfully Saved!');
    
                result.updatedSections.forEach(updatedSection => {
                    const row = document.querySelector('tr[data-id=\'' + updatedSection.id + '\']');
                    if (row) {
                        row.children[0].innerText = `${updatedSection.prefix} ${updatedSection.number} ${updatedSection.section} - ${updatedSection.year}${updatedSection.session} ${updatedSection.term}`;
                        row.children[4].innerText = updatedSection.enroll_end;
                        row.children[5].innerText = updatedSection.dropped;
                        row.children[6].innerText = updatedSection.capacity;
                    }
                });
                this.doneEdit();
            } else {
                alert('Save failed. Please try again.');
                console.error('Save failed.');
            }
        })
        .catch(error => {
            console.error('Error:', error);
        }); --}}
    }
}">
    <div class="flex justify-between items-center mb-2">
        <div class="flex items-center space-x-4">
            @if($canEdit)
                <div class="flex items-center space-x-2">
                    <x-assign-button id="assignButton" wire:key="assign_btn" />
                    <x-create-new-button wire:key="create_btn" />
                    <x-button class="ubc-blue hover:text-white focus:ring-1 
                    focus:outline-none font-bold rounded-lg text-sm px-5 py-2 text-center me-1 mb-2" id="editBtn" wire:key="edit_btn" @click.prevent="enableEdit" x-show="editMode === false" x-cloak>Edit</x-button>
                    <x-button class="ubc-blue hover:text-white focus:ring-1 
                    focus:outline-none font-bold rounded-lg text-sm px-5 py-2 text-center me-1 mb-2" id="saveBtn" wire:key="save_btn" x-show="editMode" x-cloak @click.prevent="saveItems">Save</x-button>
                    <x-button class="ubc-blue hover:text-white focus:ring-1 
                    focus:outline-none font-bold rounded-lg text-sm px-5 py-2 text-center me-1 mb-2" id="cancelBtn" wire:key="cancel_btn" x-show="editMode" x-cloak @click.prevent="doneEdit">Cancel</x-button>
                    <x-coursedetails-archive-modal wire:key="archive_course_btn" />
                </div>
            @endif
        </div>
        <div class="flex items-center space-x-2 ml-auto">
            @if($canExport)
                <div class="relative inline-block text-left">
                    @if($user->hasRoles(['dept_head','dept_staff','admin']))
                        @livewire('export-department-report', key('exp-report'))
                    @endif
                    {{-- @if($user->hasRoles(['instructor']))
                        @livewire('export-instructor-report')
                    @endif --}}
                </div>
                <button wire:click="archiveCourses" id="archiveButton" type="button" class="btn-red hover:text-white focus:ring-1 focus:outline-none font-bold rounded-lg text-sm px-5 py-2 text-center me-1 mb-2">
                    <span class="material-symbols-outlined">
                        delete
                    </span>
                </button>
            @endif
        </div>
        @if($canExport)
        @php
            $courseSectionsJson = json_encode($courseSections);
        @endphp
        @endif
    </div>

    <div class="flex justify-between items-center mb-2">
        <div>
            <input type="text" id="searchInput" wire:model.live="searchTerm" placeholder="Search for courses..." class="search-bar block p-2 text-sm text-gray-900 w-80 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500" />
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
                                        wire:key="course_details_row_{{$section->name}}"
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
