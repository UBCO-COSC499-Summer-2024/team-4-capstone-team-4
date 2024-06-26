<thead class="bg-gray-50">
    <tr>
        <th scope="col" class="sortable {{$sortField==='serialNumber'?($sortDirection==='asc'?'th-sort-asc':'th-sort-desc'):''}} px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">#</th>
        <th scope="col" class="sortable {{$sortField==='courseName'?($sortDirection==='asc'?'th-sort-asc':'th-sort-desc'):''}} px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Course Name</th>
        <th scope="col" class="sortable {{$sortField==='departmentName'?($sortDirection==='asc'?'th-sort-asc':'th-sort-desc'):''}} px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Department Name</th>
        <th scope="col" class="sortable {{$sortField==='courseDuration'?($sortDirection==='asc'?'th-sort-asc':'th-sort-desc'):''}} px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Course Duration</th>
        <th scope="col" class="sortable {{$sortField==='enrolledStudents'?($sortDirection==='asc'?'th-sort-asc':'th-sort-desc'):''}} px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Enrolled Students</th>
        <th scope="col" class="sortable {{$sortField==='droppedStudents'?($sortDirection==='asc'?'th-sort-asc':'th-sort-desc'):''}} px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Dropped Students</th>
        <th scope="col" class="sortable {{$sortField==='courseCapacity'?($sortDirection==='asc'?'th-sort-asc':'th-sort-desc'):''}} px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Course Capacity</th>
    </tr>
</thead>