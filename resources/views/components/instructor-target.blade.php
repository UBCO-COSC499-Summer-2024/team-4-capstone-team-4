@vite(['resources/css/instructor-target.css'])

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet">
    </head>
    <body>
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
    </body>
</html>




