@props(['sortField', 'sortDirection', 'userRole'])

<thead class="sticky-header">
    <tr class="svcr-list-header">
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
        @foreach(['enrolled' => 'Enrolled', 'dropped' => 'Dropped', 'capacity' => 'Capacity', 'averageRating' => 'SEI Data'] as $field => $label)
            <th scope="col" class="sortable {{ $sortField === $field ? ($sortDirection === 'asc' ? 'th-sort-asc' : 'th-sort-desc') : '' }} p-4 text-left text-lg font-bold text-white uppercase tracking-wider svcr-list-header-item" style="padding: 0.5rem;">
                <div class="flex items-center">
                    <span>{{ $label }}</span>
                    @if($field === 'averageRating' && !in_array($userRole, ['instructor']))
                        <x-sei-edit-button :userRole="$userRole" />
                    @endif
                    <div class="ml-1 sort-icons">
                        <span class="material-symbols-outlined sort-icon {{ $sortField === $field && $sortDirection === 'asc' ? 'active' : '' }}" data-field="{{ $field }}" data-direction="{{ $sortDirection }}">{{ $sortDirection === 'asc' ? 'arrow_drop_up' : 'arrow_drop_down' }}</span>
                    </div>
                </div>
            </th>
        @endforeach
    </tr>
</thead>
