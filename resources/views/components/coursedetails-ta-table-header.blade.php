<thead class="sticky-header">
    <tr class="svcr-list-header">
        @foreach(['name' => 'TA Name', 'rating' => 'Rating', 'taCourses' => 'Assigned Courses', 'instructorName' => 'Instructor Name'] as $field => $label)
            <th scope="col" class="sortable {{ $sortField === $field ? ($sortDirection === 'asc' ? 'th-sort-asc' : 'th-sort-desc') : '' }} p-4 text-left text-lg font-bold text-white uppercase tracking-wider svcr-list-header-item" style="padding: 0.5rem;">
                <div class="flex items-center">
                    <span>{{ $label }}</span>
                    <div class="ml-1 sort-icons">
                        <span class="material-symbols-outlined sort-icon {{ $sortField === $field && $sortDirection === 'asc' ? 'active' : '' }}" data-field="{{ $field }}" data-direction="{{ $sortDirection }}">{{ $sortField === $field ? ($sortDirection === 'asc' ? 'arrow_drop_up' : 'arrow_drop_down') : 'unfold_more' }}</span>
                    </div>
                </div>
            </th>
        @endforeach
    </tr>
</thead>
