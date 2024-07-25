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
                    <div class="filter-instructor">
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
    <x-assign-course-modal />

    <div class="mb-4 border-b border-gray-200 dark:border-gray-700">
        <ul class="flex flex-wrap -mb-px text-sm font-medium text-center" id="default-tab" data-tabs-toggle="#default-tab-content" role="tablist">
            <li class="me-2" role="presentation">
                <button class="inline-block p-4 border-b-2 rounded-t-lg text-ubc-blue border-ubc-blue font-bold" id="courses-tab" data-tabs-target="#courses" type="button" role="tab" aria-controls="courses" aria-selected="true" onclick="showTab('coursesTable', 'courses-tab')">
                    Course Details
                </button>
            </li>
            <li class="me-2" role="presentation">
                <button class="inline-block p-4 border-b-2 rounded-t-lg text-gray-600 border-gray-300 font-bold" id="tas-tab" data-tabs-target="#tas" type="button" role="tab" aria-controls="tas" aria-selected="false" onclick="showTab('taTable', 'tas-tab')">
                    TAs
                </button>
            </li>
        </ul>
    </div>

    <script>
        function showTab(tabId, tabButtonId) {
            const coursesTable = document.getElementById('coursesTable');
            const taTable = document.getElementById('taTable');
            
            const editButton = document.getElementById('editButton');
            const createNewButton = document.getElementById('createNewButton');
            const assignButton = document.getElementById('assignButton');
            const createNewTAButton = document.getElementById('createNewTAButton');
            const assignTAButton = document.getElementById('assignTAButton');

            if (tabId === 'coursesTable') {
                coursesTable.classList.remove('hidden');
                taTable.classList.add('hidden');
                editButton.style.display = 'block';
                createNewButton.style.display = 'block';
                assignButton.style.display = 'block';
                createNewTAButton.style.display = 'none';
                assignTAButton.style.display = 'none';
            } else {
                coursesTable.classList.add('hidden');
                taTable.classList.remove('hidden');
                editButton.style.display = 'none';
                createNewButton.style.display = 'none';
                assignButton.style.display = 'none';
                createNewTAButton.style.display = 'block';
                assignTAButton.style.display = 'block';
            }

            document.querySelectorAll('button[data-tabs-target]').forEach(function (btn) {
                btn.classList.remove('text-ubc-blue', 'border-ubc-blue', 'font-bold');
                btn.classList.add('text-gray-600', 'border-gray-300');
            });

            const activeTabButton = document.getElementById(tabButtonId);
            activeTabButton.classList.add('text-ubc-blue', 'border-ubc-blue', 'font-bold');
            activeTabButton.classList.remove('text-gray-600', 'border-gray-300');
        }

        document.addEventListener('DOMContentLoaded', function () {
            const searchInput = document.getElementById('searchInput');
            const tableBody = document.getElementById('courseTableBody');
            const areaFilter = document.getElementById('areaFilter');

            const editButton = document.getElementById('editButton');
            const createNewButton = document.getElementById('createNewButton');
            const assignButton = document.getElementById('assignButton');
            const createNewTAButton = document.getElementById('createNewTAButton');
            const assignTAButton = document.getElementById('assignTAButton');

            if (!searchInput) {
                console.error('Element with ID "searchInput" is missing.');
            }
            if (!tableBody) {
                console.error('Element with ID "courseTableBody" is missing.');
            }
            if (!areaFilter) {
                console.error('Element with ID "areaFilter" is missing.');
            }
            if (!editButton) {
                console.error('Element with ID "editButton" is missing.');
            }
            if (!createNewButton) {
                console.error('Element with ID "createNewButton" is missing.');
            }
            if (!assignButton) {
                console.error('Element with ID "assignButton" is missing.');
            }
            if (!createNewTAButton) {
                console.error('Element with ID "createNewTAButton" is missing.');
            }
            if (!assignTAButton) {
                console.error('Element with ID "assignTAButton" is missing.');
            }

            if (!searchInput || !tableBody || !areaFilter || !editButton || !createNewButton || !assignButton || !createNewTAButton || !assignTAButton) {
                return; // Exit the script if any required element is missing
            }

            const courseDetailsRoute = searchInput.getAttribute('data-route');

            function fetchCourses(query, areaId) {
                const url = new URL(courseDetailsRoute);
                url.searchParams.append('search', query);
                if (areaId) {
                    url.searchParams.append('area_id', areaId);
                }
                fetch(url.toString(), {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    tableBody.innerHTML = '';
                    if (data.length > 0) {
                        data.forEach(section => {
                            const row = document.createElement('tr');
                            row.setAttribute('data-id', section.id);
                            row.innerHTML = `
                                <td class="px-6 py-4 whitespace-nowrap">${section.name}</td>
                                <td class="px-6 py-4 whitespace-nowrap">${section.departmentName}</td>
                                <td class="px-6 py-4 whitespace-nowrap">${section.enrolled}</td>
                                <td class="px-6 py-4 whitespace-nowrap">${section.dropped}</td>
                                <td class="px-6 py-4 whitespace-nowrap">${section.capacity}</td>
                                <td class="px-6 py-4 whitespace-nowrap">${section.averageRating}</td>
                            `;
                            tableBody.appendChild(row);
                        });
                    } else {
                        const row = document.createElement('tr');
                        row.innerHTML = `<td colspan="7" class="text-center text-gray-500 py-4">No course sections found.</td>`;
                        tableBody.appendChild(row);
                    }
                })
                .catch(error => console.error('Error fetching search results:', error));
            }

            searchInput.addEventListener('input', function () {
                const query = searchInput.value.trim();
                const areaId = areaFilter.value; // Get the selected area ID
                fetchCourses(query, areaId);
            });

            areaFilter.addEventListener('change', function () {
                const query = searchInput.value.trim();
                const areaId = areaFilter.value;
                fetchCourses(query, areaId);
            });

            showTab('coursesTable', 'courses-tab');
        });
    </script>
</x-app-layout>
