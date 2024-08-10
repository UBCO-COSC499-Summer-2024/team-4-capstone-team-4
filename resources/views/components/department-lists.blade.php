@vite(['resources/css/department-lists.css'])

<div class="department-list">
    @if ($area && $area['id'] != null)
    <div class="department-preview-list glass">
    @else
    <div class="department-preview glass">
    @endif
        <x-chart :chart="$chart2"/>
    </div>
    
    @if ($area && $area['id'] != null)
    <div class="department-preview-list glass">
    @else
    <div class="department-preview glass">
    @endif
        <x-chart :chart="$chart3"/>
    </div>
    
    @if ($area && $area['id'] != null)
    <div class="department-preview-list glass">
        <div class="tb">
            <div class="inst-head">
                <div class="col">Course Sections</div>
            </div>
            @foreach($deptAssignmentCount[5] as $course)
            <div class="inst-item">
                <div class="col">{{ $course }}</div>
            </div>
            @endforeach
        </div>
    </div>
    @else
    <div class="department-preview glass">
        <x-chart :chart="$chart4"/>
    </div>
    @endif
</div>