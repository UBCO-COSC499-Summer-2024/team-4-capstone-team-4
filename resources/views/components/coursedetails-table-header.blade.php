<thead class="bg-gray-100">
    <tr>
        @foreach(['courseNames' => 'Course Name', 'departmentName' => 'Department Name', 'enrolledStudents' => 'Enrolled Students', 'droppedStudents' => 'Dropped Students', 'courseCapacity' => 'Course Capacity', 'seiData' => 'SEI Data'] as $field => $label)
            <th scope="col" class="sortable {{$sortField === $field ? ($sortDirection === 'asc' ? 'th-sort-asc' : 'th-sort-desc') : ''}} px-6 py-3 text-left text-xs font-large text-gray-5000 uppercase tracking-wider">
                <div class="flex items-center">
                    <span>{{$label}}</span>
                    <div class="sort-icons ml-1">
                        <span class="material-symbols-outlined sort-icon {{$sortField === $field && $sortDirection === 'asc' ? 'active' : ''}}" data-field="{{$field}}" data-direction="{{$sortDirection}}">{{ $sortField === $field ? ($sortDirection === 'asc' ? 'arrow_drop_up' : 'arrow_drop_down') : 'unfold_more' }}</span>
                    </div>
                </div>
            </th>
        @endforeach
    </tr>
</thead>
