@vite(['resources/css/department-performance.css'])

<div class="department-container">
    <div class="column hours-column">
        <div class="hours-container">
            <div class="hours-row">
                <div class="hours-col">
                    <div class="row-head">{{ $currentMonth }} Total Hours:</div>
                    <div class="row-item">{{ $deptMonthHours }}</div>
                </div>
                <div class="hours-col">
                    <div class="row-head">Service Role Count:</div>
                    <div class="row-item">{{ $deptRolesTotal }}</div>
                </div>
                <div class="hours-col">
                    <div class="row-head">Extra Hours Count:</div>
                    <div class="row-item">{{ $deptExtrasTotal }}</div>
                </div>
                <div class="hours-col">
                    <div class="row-head">Course Section Count:</div>
                    <div class="row-item">{{ $deptCoursesTotal }}</div>
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
                <div class="metric-header">SEI Avg.</div>
                <div class="metric-value">{{ $deptSeiAvg }} / 5</div>
            </div>
            <div class="course-metric glass">
                <div class="metric-header">Enrolled Avg.</div>
                <div class="metric-value">{{ $deptEnrolledAvg }}%</div>
            </div>
            <div class="course-metric glass">
                <div class="metric-header">Dropped Avg.</div>
                <div class="metric-value">{{ $deptDroppedAvg }}%</div>
            </div>
        </div>
    </div>
</div>

