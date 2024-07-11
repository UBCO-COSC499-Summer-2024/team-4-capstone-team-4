@vite(['resources/css/department-lists.css'])

<div class="department-list">
    <div class="department-preview glass">
        <div class="tb">
            <div class="dept-head">
                <div class="col">Total Service Roles by Area</div>
            </div>
            @foreach($deptAssignmentCount[1] as $role)
            <div class="dept-item">
                <div class="col">{{ $role[0] }}: {{ $role[1] }}</div>
            </div>
            @endforeach
        </div>
    </div>
    
    <div class="department-preview glass">
        <div class="tb">
            <div class="dept-head">
                <div class="col">Total Extra Hours by Area</div>
            </div>
            @foreach($deptAssignmentCount[3] as $extra)
            <div class="dept-item">
                <div class="col">{{ $extra[0] }}: {{ $extra[1] }}</div>
            </div>
            @endforeach
        </div>
    </div>
    
    <div class="department-preview glass">
        <div class="tb">
            <div class="dept-head">
                <div class="col">Total Course Sections by Area</div>
            </div>
            @foreach($deptAssignmentCount[5] as $course)
            <div class="dept-item">
                <div class="col">{{ $course[0] }}: {{ $course[1] }}</div>
            </div>
            @endforeach
        </div>
    </div>
</div>