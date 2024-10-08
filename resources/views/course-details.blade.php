<x-app-layout>
    <div class="z-0 p-4 content">
        <div class="flex items-center justify-between mb-4">
            <h1 id="headerTitle" class="text-3xl font-bold header-title content-title nos">{{ __('COURSES') }}</h1>
            @if($user->id < 4)
                <div class="flex items-center space-x-2">
                    <x-create-new-button id="createNewButton" />
                    <x-assign-button id="assignButton" />
                    @if(in_array($userRole, ['admin', 'dept_head', 'dept_staff']))
                        <link rel="stylesheet" href="resources/css/course-details.css">
                        <x-edit-button id="editButton" />
                    @endif
                    <x-save-button id="saveButton" style="display: none;" />
                    <x-cancel-button id="cancelButton" style="display: none;" />
                    <x-create-ta-button id="createNewTAButton" style="display: none;" />
                    <x-assign-ta-button id="assignTAButton" style="display: none;" />
                </div>
            @endif
        </div>

        <div class="flex items-center justify-between mb-4">
            <div class="flex-1 mr-4">
                <x-coursedetails-tabs />
                @if(in_array($userRole, ['dept_head', 'dept_staff', 'admin']))
                    <input type="text" id="searchInput" data-route="{{ route('courses.details.id', ['user' => $user->id]) }}" placeholder="Search for courses..." class="block p-2 text-sm text-gray-900 rounded-lg search-bar w-80 bg-gray-50 focus:ring-blue-500 focus:border-blue-500" />
                @endif
            </div>
            <x-coursedetails-exportButton />
            @if($user->id < 4)
            <div class="flex items-center space-x-4">
                @php
                    $user = Auth::user();
                @endphp
                @if($user->hasRoles(['admin', 'dept_head']))
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
                    {{-- @section('content')
                        <livewire:archive-course-section />
                    @endsection --}}
                @endif
                </div>
            @endif
        </div>

        <div class="relative overflow-x-auto sm:rounded-lg">
            <div class="fixed-header">
                <form id="editForm" class="w-full" method="POST" action="{{ route('courses.details.save') }}">
                    @csrf
                    <input type="hidden" id="activeTab" name="activeTab" value="{{ $activeTab }}">
                    <div class="overflow-auto max-h-[calc(100vh-200px)]">
                        <div id="tabContent">
                            <table class="min-w-full divide-y divide-gray-200 svcr-table {{ $activeTab === 'coursesTable' ? '' : 'hidden' }}" id="coursesTable">
                                <livewire:coursedetails-table-header :sortField="$sortField" :sortDirection="$sortDirection" :userRole="$userRole" />
                                <tbody id="courseTableBody">
                                    @if($courseSections->count())
                                        @foreach ($courseSections as $section)
                                            <x-coursedetails-table-row
                                                :courseName="$section['formattedName']"
                                                :departmentName="$section['departmentName']"
                                                :enrolledStudents="$section['enrolled']"
                                                :droppedStudents="$section['dropped']"
                                                :courseCapacity="$section['capacity']"
                                                :room="$section['room'] ?? 'Not Assigned'"
                                                :timings="$section['timings']"
                                                :sectionId="$section['id']"
                                                :seiData="$section['averageRating']"
                                                :instructorName="$section['instructorName'] ?? ''"
                                            />
                                        @endforeach
                                    @else
                                        <tr>
                                            <td colspan="7" class="py-4 text-center text-gray-500">No course sections found.</td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                            <table class="min-w-full divide-y divide-gray-200 svcr-table {{ $activeTab === 'taTable' ? '' : 'hidden' }}" id="taTable">
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
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <x-save-confirm />
    <x-save-details-message />
    <x-create-ta-modal />
    <x-assign-ta-modal />
    <x-sei-edit-modal :courses="$courses" />
    <x-error-modal />
</x-app-layout>
