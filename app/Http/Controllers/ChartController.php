<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Icehouseventures\LaravelChartJs\Facades\ChartJs;

class ChartController extends Controller
{
    public function showChart()
    {
        // Replace this with your actual data retrieval logic
        $data = [65, 59, 80, 81, 56];
        $labels = ['January', 'February', 'March', 'April', 'May'];

        $chart = app()
            ->chartjs->name("TestChart")
            ->type("line")
            ->size(["width" => 400, "height" => 200])
            ->labels($labels)
            ->datasets([
                [
                    "label" => "Testing...",
                    "backgroundColor" => "rgba(38, 185, 154, 0.31)",
                    "borderColor" => "rgba(38, 185, 154, 0.7)",
                    "data" => $data
                ]
            ]);

        return view('dashboard', compact('chart'));
    }
}
