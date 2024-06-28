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


