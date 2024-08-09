{{-- @props(['sortField', 'sortDirection']) --}}

<thead class="sticky-header">

    <style>
        .form-checkbox {
            width: 20px;
            height: 20px;
            background-color: #f0f0f0; /* Light grey background */
            border: 2px solid #ccc; /* Slightly darker grey border */
            border-radius: 3px; /* Rounded corners */
        appearance: none; /* Remove default checkbox styling */
            cursor: pointer; /* Pointer cursor on hover */
        }

        .form-checkbox:checked {
            background-color: #007bff; /* Blue background when checked */
            border-color: #007bff; /* Blue border when checked */
            background-image: url("data:image/svg+xml,%3csvg viewBox='0 0 16 16' fill='none' xmlns='http://www.w3.org/2000/svg'%3e%3cpath d='M1 8.5L5.5 13L15 3.5' stroke='white' stroke-width='2'/%3e%3c/svg%3e");
            background-size: 80%; /* Adjust the size of the checkmark */
            background-position: center; /* Center the checkmark */
            background-repeat: no-repeat; /* Prevent repetition */
        }
    </style>
    <tr class="svcr-list-header">
        <!-- New column for selector -->
        <th scope="col" class="text-lg font-bold tracking-wider text-white uppercase svcr-list-header-item" style="padding: 0.5rem;">
            <input type="checkbox" class="form-checkbox" id="select-all">
        </th>

        @foreach(['name' => 'Course Name', 'departmentName' => 'Area'] as $field => $label)
            <th scope="col" class="sortable {{ $sortField === $field ? ($sortDirection === 'asc' ? 'th-sort-asc' : 'th-sort-desc') : '' }} p-4 text-left text-lg font-bold text-white uppercase tracking-wider svcr-list-header-item" style="padding: 0.5rem;">
                <div class="flex items-center">
                    <span>{{ $label }}</span>
                    <div class="ml-1 sort-icons">
                        <span class="material-symbols-outlined sort-icon {{ $sortField === $field && $sortDirection === 'asc' ? 'active' : '' }}" data-field="{{ $field }}" data-direction="{{ $sortDirection }}">{{ $sortField === $field ? ($sortDirection === 'asc' ? 'arrow_drop_up' : 'arrow_drop_down') : 'unfold_more' }}</span>
                    </div>
                </div>
            </th>
        @endforeach

        <!-- Always include the Instructor column -->
        <th scope="col" class="sortable {{ $sortField === 'instructorName' ? ($sortDirection === 'asc' ? 'th-sort-asc' : 'th-sort-desc') : '' }} p-4 text-left text-lg font-bold text-white uppercase tracking-wider svcr-list-header-item" style="padding: 0.5rem;">
            <div class="flex items-center">
                <span>Instructor</span>
                <div class="ml-1 sort-icons">
                    <span class="material-symbols-outlined sort-icon {{ $sortField === 'instructorName' && $sortDirection === 'asc' ? 'active' : '' }}" data-field="instructorName" data-direction="{{ $sortDirection }}">{{ $sortDirection === 'asc' ? 'arrow_drop_up' : 'arrow_drop_down' }}</span>
                </div>
            </div>
        </th>

        @foreach(['enrolled' => 'Enrolled', 'dropped' => 'Dropped', 'capacity' => 'Capacity', 'room' => 'Room', 'timings' => 'Timings', 'averageRating' => 'SEI Data'] as $field => $label)
            <th scope="col" class="sortable {{ $sortField === $field ? ($sortDirection === 'asc' ? 'th-sort-asc' : 'th-sort-desc') : '' }} p-4 text-left text-lg font-bold text-white uppercase tracking-wider svcr-list-header-item" style="padding: 0.5rem;">
                <div class="flex items-center">
                    <span>{{ $label }}</span>
                    <div class="ml-1 sort-icons">
                        <span class="material-symbols-outlined sort-icon {{ $sortField === $field && $sortDirection === 'asc' ? 'active' : '' }}" data-field="{{ $field }}" data-direction="{{ $sortDirection }}">{{ $sortDirection === 'asc' ? 'arrow_drop_up' : 'arrow_drop_down' }}</span>
                    </div>
                </div>
            </th>
        @endforeach
        {{-- actions column --}}
        <th scope="col" class="p-4 text-lg font-bold tracking-wider text-left text-white uppercase svcr-list-header-item" style="padding: 0.5rem;">
            <div class="flex items-center">
                <span>Actions</span>
            </div>
        </th>
    </tr>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const selectAllCheckbox = document.getElementById('select-all');
            const checkboxes = document.querySelectorAll('input[type="checkbox"].form-checkbox');
            if (!selectAllCheckbox && checkboxes.length === 0) return;
            selectAllCheckbox.addEventListener('change', function () {
                checkboxes.forEach(checkbox => {
                    checkbox.checked = selectAllCheckbox.checked;
                    checkbox.dispatchEvent(new Event('change'));
                });
            });

            checkboxes.forEach(checkbox => {
                checkbox.addEventListener('change', function () {
                    if (!checkbox.checked) {
                        selectAllCheckbox.checked = false;
                    } else if ([...checkboxes].every(cb => cb.checked)) {
                        selectAllCheckbox.checked = true;
                    }
                });
            });
        });

        document.addEventListener('DOMContentLoaded', function () {
            const archiveButton = document.getElementById('archiveButton');
            if (!archiveButton) return;

            archiveButton.addEventListener('click', function () {
                if (confirm('Are you sure you want to archive the selected courses?')) {
                    @this.archiveCourses();
                }
            });
        });
    </script>
</thead>
