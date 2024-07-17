@vite(['resources/css/department-performance.css'])

<div class="department-container">
    <div class="column hours-column">
        <div class="hours-container">
            <div class="hours-row">
                <div class="hours-col">
                    <div class="row-head">{{ $currentMonth }} Total Hours:</div>
                    <div class="row-item">{{ json_decode($deptPerformance->total_hours, true)[$currentMonth] }}</div>
                </div>
                <div class="hours-col">
                    <div class="row-head">Service Role Count:</div>
                    <div class="row-item">{{ $deptAssignmentCount[0] }}</div>
                </div>
                <div class="hours-col">
                    <div class="row-head">Extra Hours Count:</div>
                    <div class="row-item">{{ $deptAssignmentCount[2] }}</div>
                </div>
                <div class="hours-col">
                    <div class="row-head">Course Section Count:</div>
                    <div class="row-item">{{ $deptAssignmentCount[4] }}</div>
                </div>
            </div>
        </div>
        <div class="line-chart-container">
            <x-chart :chart="$chart1"/>
        </div>
    </div>
    <div class="column performance-column">
        <div class="leader-board">
            <div class="leaderboard-title">LEADERBOARD TOP 5</div>
            <table class="leaderboard-table">
                <thead>
                    <tr>
                        <th>Rank</th>
                        <th>Instructor</th>
                        <th>Score</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($leaderboard as $index => $entry)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $entry['name'] }}</td>
                                <td>{{ $entry['score'] }}</td>
                            </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="course-performance">
            <div class="course-metric glass">
                <div class="metric-header">SEI Avg.</div>
                <div class="metric-value">{{ $deptPerformance->sei_avg }} / 5</div>
            </div>
            <div class="course-metric glass">
                <div class="metric-header">Enrolled Avg.</div>
                <div class="metric-value">{{ $deptPerformance->enrolled_avg }}%</div>
            </div>
            <div class="course-metric glass">
                <div class="metric-header">Dropped Avg.</div>
                <div class="metric-value">{{ $deptPerformance->dropped_avg }}%</div>
            </div>
        </div>
    </div>
</div>

