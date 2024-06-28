<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ChartController extends Controller {
    public function showChart() {

        // Line Chart with Target Data
        $totalHours = [90, 105, 120, 114, 135, 82, 86, 82, 95, 93, 103, 125];
        $targetHours = [100, 110, 100, 90, 110, 100, 90, 80, 90, 80, 100, 110];
        $labels = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];

        $chart1 = app()
            ->chartjs->name("InstructorLineTarget")
            ->type("line")
            ->size(["width" => 600, "height" => 200])
            ->labels($labels)
            ->datasets([
                [
                    "label" => "Total Hours",
                    "backgroundColor" => "rgba(37, 41, 150, 0.31)",
                    "borderColor" => "rgba(37, 41, 150, 0.7)",
                    "data" => $totalHours
                ],
                [
                    "label" => "Target Hours",
                    'backgroundColor' => 'rgba(0, 0, 0, 0.12)',
                    'borderColor' => 'rgba(0, 0, 0, 0.25)',
                    "data" => $targetHours
                ]
            ])
            ->options([
                'plugins' => [
                    'title' => [
                        'display' => true,
                        'text' => 'Annual Hours',
                        'font' => [
                            'size' => 18,
                        ]
                    ]
                ]
            ]);

        // Progress Bar Data
        $hours = [50];
        $label = ['January Hours'];
        $hoursneed = [4];
        $chart2 = app()
            ->chartjs->name("ProgressBar")
            ->type("bar")
            ->size(["width" => 400, "height" => 75])
            ->labels($label)
            ->datasets([
                [
                    "label" => "Current Hours",
                    "backgroundColor" => "rgba(37, 41, 150, 0.7)",
                    "borderColor" => "rgba(37, 41, 150, 0.31)",
                    "borderWidth" => 0,
                    "borderSkipped" => false,
                    "borderRadius" => 5,
                    "barPercentage" => 3,
                    "categoryPercentage" => 0.8,
                    "data" => $hours
                ],
                [
                    "label" => "Hours Needed to Reach Target",
                    'backgroundColor' => 'rgba(0, 0, 0, 0.12)',
                    'borderColor' => 'rgba(0, 0, 0, 0.25)',
                    "borderWidth" => 0.1,
                    "borderSkipped" => false,
                    "borderRadius" => 3,
                    "barPercentage" => 2,
                    "categoryPercentage" => 0.8,
                    "data" => $hoursneed
                ]
            ])
            ->options([
                'indexAxis' => 'y',
                'plugins' => [
                    'legend' => [
                        'display' => false
                    ],
                    'title' => [
                        'display' => true,
                        'text' => strval($label[0]) . " Target",
                        'font' => [
                            'size' => 18,
                        ]
                    ]
                ],
                'scales' => [
                    'y' => [
                        'beginAtZero' => true,
                        'grid' => [
                            'display' => false,
                            'drawBorder' => false
                        ],
                        'ticks' => [
                            'display' => false
                        ],
                        'stacked' => true
                    ],
                    'x' => [
                        'beginAtZero' => true,
                        'grid' => [
                            'offset' => true,
                            'display' => true,
                            'drawBorder' => false
                        ],
                        'ticks' => [
                            'display' => true,
                            'autoSkip' => true,
                            'maxTicksLimit' => 2
                        ],
                        'title' => [
                            'display' => true,
                            'text' => strval(round(($hours[0] / ($hours[0] + $hoursneed[0])) * 100)) . "% Completed"
                        ],
                        'stacked' => true,
                        'max' => $hours[0] + $hoursneed[0]
                    ]
                ],
                'layout' => [
                    'padding' => [
                        'top' => 10,
                        'bottom' => 10
                    ]
                ]
            ]);

        // Line Chart without Target Data
        $totalHours = [90, 105, 120, 114, 135, 82, 86, 82, 95, 93, 103, 125];
        $labels = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];

        $chart3 = app()
            ->chartjs->name("InstructorLine")
            ->type("line")
            ->size(["width" => 600, "height" => 200])
            ->labels($labels)
            ->datasets([
                [
                    "label" => "Total Hours",
                    "backgroundColor" => "rgba(37, 41, 150, 0.31)",
                    "borderColor" => "rgba(37, 41, 150, 0.7)",
                    "data" => $totalHours
                ]
            ])
            ->options([
                'plugins' => [
                    'title' => [
                        'display' => true,
                        'text' => 'Annual Hours',
                        'font' => [
                            'size' => 18,
                        ]
                    ]
                ]
            ]);


        return view('dashboard', compact('chart1', 'chart2', 'chart3'));
    }
}
