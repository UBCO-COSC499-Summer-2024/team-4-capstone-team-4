<x-app-layout>
    <div class="z-0 p-4 content">
        <h1 class="mb-4 text-2xl font-bold header-title content-title nos">{{ __('COURSES') }}</h1>
        <div class="flex items-center justify-between mb-4">
            <div class="flex-1 mr-4">
                <input type="text" id="searchInput" data-route="{{ route('courses.details.id', ['user' => $user->id]) }}" placeholder="Search for courses..." class="search-bar block p-2 text-sm text-gray-900 w-80 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500" />
            </div>
            @if($user->id < 4)
            <div class="flex items-center space-x-4">
                <x-assign-button />
                <x-edit-button id="editButton"/>
                <x-save-button style="display: none;" />
                <x-cancel-button style="display: none;" />
            </div>
            @endif
        </div>
        <div class="relative overflow-x-auto sm:rounded-lg">
            <div class="fixed-header">
                <form id="editForm" class="w-full" method="POST" action="{{ route('courses.details.save') }}">
                    @csrf
                    <div class="overflow-auto max-h-[calc(100vh-200px)]">
                        <table class="min-w-full divide-y divide-gray-200 svcr-table">
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
                                        <td colspan="7" class="py-4 text-center text-gray-500">No course sections found.</td>
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
