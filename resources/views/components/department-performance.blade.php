@vite(['resources/css/department-performance.css'])

<div class="instructor-container">
    <div class="column hours-column">
        <div class="hours-container total-hours">
            <!-- Display total hours here -->
            <div class="row-head">
                <div class="col">{{($currentMonth . ' Total Hours: 120') }}</div>
            </div>
            <div class="row-item">
                <div class="col">Service Role Hours: 115</div>
            </div>
            <div class="row-item">
                <div class="col">Extra Hours: 5</div>
            </div>
        </div>
        <div class="hours-container line-chart">
            <x-chart :chart="$chart3"/>
        </div>
    </div>
    <div class="column performance-column">
        <div class="leader-board">
            <!-- Leaderboard content here -->
        </div>
        <div class="course-performance">
            <div class="course-metric">
                <x-sei-score />
            </div>
            <div class="course-metric">
                <x-enrolled-rate />
            </div>
            <div class="course-metric">
                <x-dropped-rate />
            </div>
        </div>
    </div>
</div>