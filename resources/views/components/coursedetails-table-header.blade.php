<thead class="sticky-header">
    <tr class="svcr-list-header">
        @foreach(['courseNames' => 'Course Name', 'departmentName' => 'Department Name', 'enrolledStudents' => 'Enrolled', 'droppedStudents' => 'Dropped', 'courseCapacity' => 'Course Capacity', 'seiData' => 'SEI Data'] as $field => $label)
            <th scope="col" class="sortable {{$sortField === $field ? ($sortDirection === 'asc' ? 'th-sort-asc' : 'th-sort-desc') : ''}} p-8 text-left text-xs font-large text-white uppercase tracking-wider svcr-list-header-item" style="padding: 0.5rem;">
                <div class="flex items-center">
                    <span>{{$label}}</span>
                    <div class="ml-1 sort-icons">
                        <span class="material-symbols-outlined sort-icon {{$sortField === $field && $sortDirection === 'asc' ? 'active' : ''}}" data-field="{{$field}}" data-direction="{{$sortDirection}}">{{ $sortField === $field ? ($sortDirection === 'asc' ? 'arrow_drop_up' : 'arrow_drop_down') : 'unfold_more' }}</span>
                    </div>
                </div>
            </th>
        @endforeach
    </tr>
</thead>
