@vite(['resources/css/instructor-performance.css'])

<div class="instructor-container">
    <div class="column hours-column">
        <div class="hours-container">
            <div class="hours-row">
                <div class="hours-col">
                    <div class="row-head">{{ $currentMonth }} Total Hours:</div>
                    <div class="row-item">{{ json_decode($performance->total_hours, true)[$currentMonth] }}</div>
                </div>
                <div class="hours-col">
                    <div class="row-head">{{ $currentMonth }} Service Role Hours:</div>
                    <div class="row-item">{{ $assignmentCount[3] }}</div>
                </div>
                <div class="hours-col">
                    <div class="row-head">{{ $currentMonth }} Extra Hours:</div>
                    <div class="row-item">{{ $assignmentCount[4] }}</div>
                </div>
            </div>
        </div>
        <div class="line-chart-container">
            <x-chart :chart="$chart1"/>
        </div>
    </div>
    <div class="column performance-column">
        <div class="leader-board">
            <div class="leaderboard-title">ACHIEVEMENTS</div>
                    <table class="leaderboard-table">
                        <thead>
                            <tr>
                                <th>Rank:</th>
                                <th>Score:</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($ranking as $rank)
                                <tr>
                                    <td>{{ $rank['rank'] }}</td>
                                    <td>{{ $rank['score'] }}</td>
                                </tr>
                                <tr>
                                    <th colspan="2">
                                        <div class="badge-container">
                                            <x-badge :standing="$rank['standing']" :rank="$rank['rank']" />
                                        </div>
                                    </th>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
        </div>
        <div class="course-performance">
            <div class="course-metric glass">
                <div class="metric-header">SEI Score Avg.</div>
                <div class="metric-value">{{ $performance->sei_avg }} / 5</div>
            </div>
            <div class="course-metric glass">
                <div class="metric-header">Enrolled Avg.</div>
                <div class="metric-value">{{ $performance->enrolled_avg }}%</div>
            </div>
            <div class="course-metric glass">
                <div class="metric-header">Dropped Avg.</div>
                <div class="metric-value">{{ $performance->dropped_avg }}%</div>
            </div>
        </div>
    </div>
</div>

