@vite(['resources/css/instructor-performance.css'])

<div class="instructor-container">
    <div class="column hours-column">
        <div class="hours-container">
            <div class="hours-row">
                <div class="hours-col">
                    <div class="row-head">{{ $currentMonth }} Total Hours:</div>
                    <div class="row-item">{{ $currentMonthHours }}</div>
                </div>
                <div class="hours-col">
                    <div class="row-head">{{ $currentMonth }} Service Role Hours:</div>
                    <div class="row-item">{{ $roleHoursTotal }}</div>
                </div>
                <div class="hours-col">
                    <div class="row-head">{{ $currentMonth }} Extra Hours:</div>
                    <div class="row-item">{{ $extraHoursTotal }}</div>
                </div>
            </div>
        </div>
        <div class="line-chart-container">
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

