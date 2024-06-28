<thead class="bg-gray-50">
    <tr>
        <th scope="col" class="sortable {{$sortField==='serialNumber'?($sortDirection==='asc'?'th-sort-asc':'th-sort-desc'):''}} px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
            #
            <div class="sort-icons">
                <span class="material-symbols-outlined sort-icon {{$sortField === 'serialNumber' && $sortDirection === 'asc' ? 'active' : ''}}" data-field="serialNumber" data-direction="asc">arrow_upward</span>
                <span class="material-symbols-outlined sort-icon {{$sortField === 'serialNumber' && $sortDirection === 'desc' ? 'active' : ''}}" data-field="serialNumber" data-direction="desc">arrow_downward</span>
            </div>
        </th>
        <th scope="col" class="sortable {{$sortField==='courseName'?($sortDirection==='asc'?'th-sort-asc':'th-sort-desc'):''}} px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
            Course Name
            <div class="sort-icons">
                <span class="material-symbols-outlined sort-icon {{$sortField === 'courseName' && $sortDirection === 'asc' ? 'active' : ''}}" data-field="courseName" data-direction="asc">arrow_upward</span>
                <span class="material-symbols-outlined sort-icon {{$sortField === 'courseName' && $sortDirection === 'desc' ? 'active' : ''}}" data-field="courseName" data-direction="desc">arrow_downward</span>
            </div>
        </th>
        <th scope="col" class="sortable {{$sortField==='departmentName'?($sortDirection==='asc'?'th-sort-asc':'th-sort-desc'):''}} px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
            Department Name
            <div class="sort-icons">
                <span class="material-symbols-outlined sort-icon {{$sortField === 'departmentName' && $sortDirection === 'asc' ? 'active' : ''}}" data-field="departmentName" data-direction="asc">arrow_upward</span>
                <span class="material-symbols-outlined sort-icon {{$sortField === 'departmentName' && $sortDirection === 'desc' ? 'active' : ''}}" data-field="departmentName" data-direction="desc">arrow_downward</span>
            </div>
        </th>
        <th scope="col" class="sortable {{$sortField==='courseDuration'?($sortDirection==='asc'?'th-sort-asc':'th-sort-desc'):''}} px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
            Course Duration
            <div class="sort-icons">
                <span class="material-symbols-outlined sort-icon {{$sortField === 'courseDuration' && $sortDirection === 'asc' ? 'active' : ''}}" data-field="courseDuration" data-direction="asc">arrow_upward</span>
                <span class="material-symbols-outlined sort-icon {{$sortField === 'courseDuration' && $sortDirection === 'desc' ? 'active' : ''}}" data-field="courseDuration" data-direction="desc">arrow_downward</span>
            </div>
        </th>
        <th scope="col" class="sortable {{$sortField==='enrolledStudents'?($sortDirection==='asc'?'th-sort-asc':'th-sort-desc'):''}} px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
            Enrolled Students
            <div class="sort-icons">
                <span class="material-symbols-outlined sort-icon {{$sortField === 'enrolledStudents' && $sortDirection === 'asc' ? 'active' : ''}}" data-field="enrolledStudents" data-direction="asc">arrow_upward</span>
                <span class="material-symbols-outlined sort-icon {{$sortField === 'enrolledStudents' && $sortDirection === 'desc' ? 'active' : ''}}" data-field="enrolledStudents" data-direction="desc">arrow_downward</span>
            </div>
        </th>
        <th scope="col" class="sortable {{$sortField==='droppedStudents'?($sortDirection==='asc'?'th-sort-asc':'th-sort-desc'):''}} px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
            Dropped Students
            <div class="sort-icons">
                <span class="material-symbols-outlined sort-icon {{$sortField === 'droppedStudents' && $sortDirection === 'asc' ? 'active' : ''}}" data-field="droppedStudents" data-direction="asc">arrow_upward</span>
                <span class="material-symbols-outlined sort-icon {{$sortField === 'droppedStudents' && $sortDirection === 'desc' ? 'active' : ''}}" data-field="droppedStudents" data-direction="desc">arrow_downward</span>
            </div>
        </th>
        <th scope="col" class="sortable {{$sortField==='courseCapacity'?($sortDirection==='asc'?'th-sort-asc':'th-sort-desc'):''}} px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
            Course Capacity
            <div class="sort-icons">
                <span class="material-symbols-outlined sort-icon {{$sortField === 'courseCapacity' && $sortDirection === 'asc' ? 'active' : ''}}" data-field="courseCapacity" data-direction="asc">arrow_upward</span>
                <span class="material-symbols-outlined sort-icon {{$sortField === 'courseCapacity' && $sortDirection === 'desc' ? 'active' : ''}}" data-field="courseCapacity" data-direction="desc">arrow_downward</span>
            </div>
        </th>
    </tr>
</thead>