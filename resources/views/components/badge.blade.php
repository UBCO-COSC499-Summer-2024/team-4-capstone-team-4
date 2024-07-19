<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet">
    <style>
        .icon-circle {
            width: 64px; 
            height: 64px; 
            border-radius: 50%;
            display: flex;
        }

        .trophy-gold {
            color: gold;
            background-color: rgba(255, 223, 0, 0.2);
            border: 1px solid rgba(255, 223, 0, 1);
        }

        .trophy-silver {
            color: silver;
            background-color: rgba(192, 192, 192, 0.1);
            border: 1px solid rgba(192, 192, 192, 1);
        }

        .trophy-bronze {
            color: #cd7f32;
            background-color: rgba(205, 127, 50, 0.1);
            border: 1px solid rgba(205, 127, 50, 0.5);
        }

        .badge-blue {
            color: #007bff; 
            background-color: rgba(0, 123, 255, 0.1);
            border: 1px solid rgba(0, 123, 255, 0.5);
        }

        .badge-red {
            color: #dc3545; 
            background-color: rgba(220, 53, 69, 0.1);
            border: 1px solid rgba(220, 53, 69, 0.5);
        }

        .badge-green {
            color: #28a745; 
            background-color: rgba(40, 167, 69, 0.1);
            border: 1px solid rgba(40, 167, 69, 0.5);
        }

        .badge-magenta {
            color: #c2185b; 
            background-color: rgba(194, 24, 91, 0.1);
            border: 1px solid rgba(194, 24, 91, 0.5);
        }

        .badge-purple {
            color: #6f42c1; 
            background-color: rgba(111, 66, 193, 0.1);
            border: 1px solid rgba(111, 66, 193, 0.5);
        }

        .badge-default {
            color: #fd7e14; 
            background-color: rgba(253, 126, 20, 0.1);
            border: 1px solid rgba(253, 126, 20, 0.5);
        }

        .custom-icon {
            font-size: 48px !important;
            padding-left: 7px;
            padding-top: 6px;
        }

        .badge-desc {
            padding-left: 15px;
            padding-top: 20px;
        }
    </style>
</head>
<body>
    <?php
        $icon = 'military_tech'; 
        $description = '';

        if ($rank == '1st') {
            $colorClass = 'trophy-gold';
            $icon = 'trophy';
            $description = 'Golden Champ! You’re at the top of your game!';
        } elseif ($rank == '2nd') {
            $colorClass = 'trophy-silver';
            $icon = 'trophy';
            $description = 'Silver Star! Almost there, keep pushing!';
        } elseif ($rank == '3rd') {
            $colorClass = 'trophy-bronze';
            $icon = 'trophy';
            $description = 'Bronze Boss! Great job, you’re on the podium!';
        } elseif ($standing <= 5) {
            $colorClass = 'badge-blue';
            $description = 'Top 5% Wonder! You’re one of the elite few!';
        } elseif ($standing <= 10) {
            $colorClass = 'badge-red';
            $description = 'Elite Top 10%! You’re in the top tier!';
        } elseif ($standing <= 25) {
            $colorClass = 'badge-green';
            $description = 'Top 25% Dynamo! Strong performance!';
        } elseif ($standing <= 50) {
            $colorClass = 'badge-magenta';
            $description = 'Top 50% Hero! You’re in the upper half!';
        } elseif ($standing <= 75) {
            $colorClass = 'badge-purple';
            $description = 'Top 75% Achiever! You’re making progress!';
        } else {
            $colorClass = 'badge-default';
            $description = 'Keep Climbing! Greatness awaits!';
        }
    ?>
    <span class="material-symbols-outlined icon-circle custom-icon <?= $colorClass ?>"><?= $icon ?></span>
    
    <div class="badge-desc"><?= $description ?></div>
</body>
</html>


