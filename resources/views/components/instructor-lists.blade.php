@vite(['resources/css/instructor-lists.css'])

<div class="instructor-list">
    <div class="instructor-preview glass">
        <x-chart :chart="$chart2"/>
    </div>
    <div class="instructor-preview glass">
        <x-chart :chart="$chart3"/>
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

