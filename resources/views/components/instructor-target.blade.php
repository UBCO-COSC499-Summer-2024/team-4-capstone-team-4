@vite(['resources/css/instructor-targets.css'])

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet">
    @vite(['resources/css/instructor-target.css'])
</head>
<body>
    <div class="instructor-container">
        <div class="column charts-column">
            <div class="chart-container progress-bar">
                <x-chart :chart="$chart4"/>
            </div>
            <div class="chart-container line-chart">
                <x-chart :chart="$chart1"/>
            </div>
        </div>
        <div class="column performance-column">
            <div class="leader-board flex items-center justify-center gap-4 p-2 flex-col">
                <h2 class="leaderboard-title flex items-center justify-center">ACHIEVEMENTS</h2>
                @foreach ($ranking as $rank)
                    @php
                        $icon = 'military_tech'; 
                        $description = '';

                        if ($rank['rank'] == '1st') {
                            $colorClass = 'trophy-gold';
                            $icon = 'trophy';
                            $description = 'Golden Champ! You’re at the top of your game!';
                        } elseif ($rank['rank'] == '2nd') {
                            $colorClass = 'trophy-silver';
                            $icon = 'trophy';
                            $description = 'Silver Star! Almost there, keep pushing!';
                        } elseif ($rank['rank'] == '3rd') {
                            $colorClass = 'trophy-bronze';
                            $icon = 'trophy';
                            $description = 'Bronze Boss! Great job, you’re on the podium!';
                        } elseif ($rank['standing'] <= 5) {
                            $colorClass = 'badge-blue';
                            $description = 'Top 5% Wonder! You’re one of the elite few!';
                        } elseif ($rank['standing'] <= 10) {
                            $colorClass = 'badge-red';
                            $description = 'Elite Top 10%! You’re in the top tier!';
                        } elseif ($rank['standing'] <= 25) {
                            $colorClass = 'badge-green';
                            $description = 'Top 25% Dynamo! Strong performance!';
                        } elseif ($rank['standing'] <= 50) {
                            $colorClass = 'badge-magenta';
                            $description = 'Top 50% Hero! You’re in the upper half!';
                        } elseif ($rank['standing'] <= 75) {
                            $colorClass = 'badge-purple';
                            $description = 'Top 75% Achiever! You’re making progress!';
                        } else {
                            $colorClass = 'badge-default';
                            $description = 'Keep Climbing! Greatness awaits!';
                        }
                    @endphp
                    <div class="rank-wrapper">
                        <div class="rank-circle-container flex justify-center items-center gap-8">
                            <div class="circle {{ $colorClass }}">{{ $rank['rank'] }}<div class="ldb-label">Rank</div></div>
                            <span class="material-symbols-outlined icon-circle custom-icon {{ $colorClass }}">{{ $icon }}</span>
                            <div class="circle {{ $colorClass }}">{{ $rank['score'] }}<div class="ldb-label">Score</div></div>
                        </div>
                        <div class="badge-desc flex justify-center items-center p-1">
                            {{ $description }}
                        </div>
                    </div>
                @endforeach
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


