<x-app-layout>
    <div class="content z-0">
        <h1 class="header-title content-title nos">{{ __('COURSES') }}</h1>
        <div class="relative overflow-x-auto shadow-md sm:rounded-lg d-flex gap-1">
            <div class="fixed-header">
                <div class="header-search">
                    <input type="text" id="searchInput" data-route="{{ route('course-details.search') }}" placeholder="Search Courses..." />
                </div>
                @if($userRole !== 'guest' && $user->id < 4)
                <div class="flex items-center header-buttons">
                    <x-assign-button />
                    <x-edit-button />
                    <x-save-button />
                    <x-cancel-button />
                </div>
                @endif
            </div>
            <form id="editForm" method="POST" action="{{ route('course-details.save') }}">
                @csrf
                <div class="overflow-auto max-h-[calc(100vh-200px)]">
                    <table class="min-w-full divide-y divide-gray-200">
                        <x-coursedetails-table-header :sortField="$sortField" :sortDirection="$sortDirection" />
                        <tbody id="courseTableBody">
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
                                    <td colspan="7" class="text-center text-gray-500 py-4">No course sections found.</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </form>
        </div>
    </div>
    <x-save-confirm />
    <x-save-details-message />
    <x-assign-course-modal />
</x-app-layout>
