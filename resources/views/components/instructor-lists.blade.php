@vite(['resources/css/instructor-lists.css'])
<div class="instructor-list">
    <div class="instructor-preview glass">
        <div class="tb">
            <div class="inst-head">
                <div class="col">Service Roles</div>
            </div>
            @foreach($assignmentCount[0] as $role)
            <div class="inst-item">
                <div class="col">{{ $role }}</div>
            </div>
            @endforeach
        </div>
    </div>
    
    <div class="instructor-preview glass">
        <div class="tb">
            <div class="inst-head">
                <div class="col">Extra Hours</div>
            </div>
            @foreach($assignmentCount[1] as $extra)
            <div class="inst-item">
                <div class="col">{{ $extra }}</div>
            </div>
            @endforeach
        </div>
    </div>
    
    <div class="instructor-preview glass">
        <div class="tb">
            <div class="inst-head">
                <div class="col">Course Sections</div>
            </div>
            @foreach($assignmentCount[2] as $course)
            <div class="inst-item">
                <div class="col">{{ $course }}</div>
            </div>
            @endforeach
        </div>
    </div>
</div>

