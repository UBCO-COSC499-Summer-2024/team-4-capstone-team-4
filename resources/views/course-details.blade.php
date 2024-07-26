<x-app-layout>
    <div class="z-0 p-4 content">
        <h1 class="mb-4 text-5xl font-bold header-title content-title nos">{{ __('COURSES') }}</h1>
        <div class="flex items-center justify-between mb-4">
            <div class="flex-1 mr-4">
                <x-coursedetails-tabs />
                <input type="text" id="searchInput" data-route="{{ route('courses.details.id', ['user' => $user->id]) }}" placeholder="Search for courses..." class="search-bar block p-2 text-sm text-gray-900 w-80 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500" />
            </div>
            @if($user->id < 4)
            <div class="flex items-center space-x-4">
                @if(in_array($userRole, ['admin', 'dept_head']))
                    <div class="filter-area">
                        <select id="areaFilter" name="area_id" class="form-select">
                            <option value="">Filter By Area</option>
                            @foreach($areas as $area)
                                <option value="{{ $area->id }}" {{ request('area_id') == $area->id ? 'selected' : '' }}>
                                    {{ $area->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                @endif
                <x-create-new-button id="createNewButton" />
                <x-create-ta-button id="createNewTAButton" style="display: none;" />
                <x-assign-ta-button id="assignTAButton" style="display: none;" />
                <x-assign-button id="assignButton" />
                <x-edit-button id="editButton" />
                <x-save-button id="saveButton" style="display: none;" />
                <x-cancel-button id="cancelButton" style="display: none;" />
            </div>
            @endif
        </div>
        <div class="relative overflow-x-auto sm:rounded-lg">
            <div class="fixed-header">
                <form id="editForm" class="w-full" method="POST" action="{{ route('courses.details.save') }}">
                    @csrf
                    <div class="overflow-auto max-h-[calc(100vh-200px)]">
                        <div id="tabContent">
                            <table class="min-w-full divide-y divide-gray-200 svcr-table" id="coursesTable">
                                <x-coursedetails-table-header :sortField="$sortField" :sortDirection="$sortDirection" :userRole="$userRole" />
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
                                                @if(in_array($userRole, ['admin', 'dept_head']))
                                                    :instructorName="$section->instructorName"
                                                @endif
                                            />
                                        @endforeach
                                    @else
                                        <tr>
                                            <td colspan="7" class="py-4 text-center text-gray-500">No course sections found.</td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                            <table class="min-w-full divide-y divide-gray-200 svcr-table hidden" id="taTable">
                                <x-coursedetails-ta-table-header :sortField="$sortField" :sortDirection="$sortDirection" />
                                <tbody>
                                    @if(isset($tas) && !empty($tas))
                                        @foreach ($tas as $ta)
                                            <tr>
                                                <td class="px-6 py-4 whitespace-nowrap">{{ $ta->name }}</td>
                                                <td class="px-6 py-4 whitespace-nowrap">{{ $ta->rating }}</td>
                                                <td class="px-6 py-4 whitespace-nowrap">{{ $ta->taCourses }}</td>
                                                <td class="px-6 py-4 whitespace-nowrap">{{ $ta->instructorName }}</td>
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td colspan="5" class="py-4 text-center text-gray-500">No TAs found.</td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <x-save-confirm />
    <x-save-details-message />
    <x-assign-ta-modal />
</x-app-layout>
