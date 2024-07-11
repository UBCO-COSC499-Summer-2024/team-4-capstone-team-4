<x-app-layout>
    <div class="content">
        <div></div>
        <div class="relative px-4 overflow-x-auto shadow-md sm:rounded-lg">
            <div class="flex flex-wrap items-center justify-between pb-4 space-y-4 bg-white md:flex-nowrap md:space-y-0 dark:bg-gray-900">
                <div class="flex items-center space-x-4">
                    <h1 class="justify-between text-2xl font-bold text-gray-1000 w-fit">{{ __('Department Details') }}</h1>
                </div>
                <div class="flex items-center ml-auto space-x-4">
                    <x-coursedetails-search />
                    <x-assign-button />
                    <x-edit-button />
                </div>
            </div>
            <form id="editForm" method="POST" action="{{ route('course-details.save') }}">
                @csrf
                <x-coursedetails-table>
                    <x-coursedetails-table-header :sortField="$sortField" :sortDirection="$sortDirection" />
                    <tbody>
                        @if(isset($courseSections) && !empty($courseSections))
                            @foreach ($courseSections as $section)
                                <x-coursedetails-table-row
                                :courseName="$section->name"
                                :departmentName="$section->departmentName"
                                :enrolledStudents="$section->enrolled"
                                :droppedStudents="$section->dropped"
                                :courseCapacity="$section->capacity"
                                :sectionId="$section->id"
                                :seiData="$section->averageRating"
                                />
                            @endforeach
                        @else
                            <tr>
                                <td colspan="7" class="py-4 text-center text-gray-500">No course sections found.</td>
                            </tr>
                        @endif
                    </tbody>
                </x-coursedetails-table>
                <div class="flex items-center ml-auto space-x-4">
                    <x-save-button />
                    <x-cancel-button />
                </div>
            </form>
        </div>
        <x-save-confirm />
        <x-save-details-message/>
        <x-assign-course-modal :courses="$courses" :instructors="$instructors"/>
    </div>
</x-app-layout>
