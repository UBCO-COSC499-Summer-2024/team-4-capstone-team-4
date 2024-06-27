<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ChartController extends Controller {
    public function showChart() {
        // Chart 1 data
        $totalHours = [90, 105, 120, 114, 135, 82, 86, 82, 95, 93, 103, 125];
        $targetHours = [100, 110, 100, 90, 110, 100, 90, 80, 90, 80, 100, 110];
        $labels = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];

        $chart1 = app()
            ->chartjs->name("AnnualHoursChart")
            ->type("line")
            ->size(["width" => 600, "height" => 200])
            ->labels($labels)
            ->datasets([
                [
                    "label" => "Total Hours",
                    "backgroundColor" => "rgba(38, 185, 154, 0.31)",
                    "borderColor" => "rgba(38, 185, 154, 0.7)",
                    "data" => $totalHours
                ],
                [
                    "label" => "Target Hours",
                    'backgroundColor' => 'rgba(255, 99, 132, 0.2)',
                    'borderColor' => 'rgba(255, 99, 132, 1)',
                    "data" => $targetHours
                ]
            ]);

        // Chart 2 data
        $data = [3, 6, 9, 12, 15, 18, 21, 24, 27, 30, 33, 36];
        $chart2 = app()
            ->chartjs->name("AnotherChart")
            ->type("bar")
            ->size(["width" => 400, "height" => 100])
            ->labels($labels)
            ->datasets([
                [
                    "label" => "Data Set",
                    "borderColor" => "rgba(54, 162, 235, 0.2)",
                    "backgroundColor" => "rgba(54, 162, 235, 1)",
                    "borderWidth" => 0,
                    "borderSkipped" => false,
                    "borderRadius" => 5,
                    "barPercentage" => 0.8,
                    "categoryPercentage" => 0.8,
                    "data" => $data
                ]
            ])
            ->options([
                'indexAxis' => 'y',
                'plugins' => [
                    'legend' => [
                        'display' => false
                    ],
                    'progressBar' => [
                        'display' => true
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
                        ]
                    ],
                    'x' => [
                        'beginAtZero' => true,
                        'grid' => [
                            'display' => false,
                            'drawBorder' => false
                        ],
                        'ticks' => [
                            'display' => false
                        ]
                    ]
                ]
            ]);


        return view('dashboard', compact('chart1', 'chart2'));
    }
}
