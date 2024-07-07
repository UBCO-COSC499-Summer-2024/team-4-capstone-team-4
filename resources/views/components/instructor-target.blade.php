@vite(['resources/css/instructor-target.css'])

<div class="instructor-container">
    <div class="column charts-column">
        <div class="chart-container progress-bar">
            <x-chart :chart="$chart2"/>
        </div>
        <div class="chart-container line-chart">
            <x-chart :chart="$chart1"/>
        </div>
    </div>
    <div class="column performance-column">
        <div class="leader-board">
            Gamification Coming Soon...
        </div>
        <div class="course-performance">
            <div class="course-metric glass">
                <div class="metric-header">SEI Score Avg.</div>
                <div class="metric-value">{{ $seiAvg }} / 5</div>
            </div>
            <div class="course-metric glass">
                <div class="metric-header">Enrolled Avg.</div>
                <div class="metric-value">{{ $enrolledAvg }}%</div>
            </div>
            <div class="course-metric glass">
                <div class="metric-header">Dropped Avg.</div>
                <div class="metric-value">{{ $droppedAvg }}%</div>
            </div>
        </div>
    </div>
</div>



