<x-app-layout>
    <div class="content z-0 p-4">
        <h1 class="header-title content-title nos text-2xl font-bold mb-4">{{ __('COURSES') }}</h1>
        <div class="flex justify-between items-center mb-4">
            <div class="flex-1 mr-4">
                <input type="text" id="searchInput" data-route="{{ route('course-details', ['user' => $user->id]) }}" placeholder="Search Courses..." class="w-full px-4 py-2 border rounded-md"/>
            </div>
            @if($userRole !== 'guest' && $user->id < 4)
            <div class="flex items-center space-x-4">
                <x-assign-button />
                <x-edit-button />
                <x-save-button style="display: none;" />
                <x-cancel-button style="display: none;" />
            </div>
            @endif
        </div>
        <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
            <div class="fixed-header">
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
                                @endif
                            </tbody>
                        </table>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <x-save-confirm />
    <x-save-details-message />
    <x-assign-course-modal />
</x-app-layout>
