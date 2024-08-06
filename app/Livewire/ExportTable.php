<?php


namespace App\Http\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ExportTable extends Component
{
    public function exportPDF()
    {
        // Logic to generate PDF
        $pdf = app()->make('dompdf.wrapper');
        $pdf->loadHTML('<h1>PDF Content</h1>');
        $pdfPath = 'exports/course_sections.pdf';
        Storage::put($pdfPath, $pdf->output());

        return response()->download(storage_path("app/{$pdfPath}"));
    }

    public function exportCSV()
    {
        // Logic to generate CSV
        $csvPath = 'exports/course_sections.csv';

        $response = new StreamedResponse(function () {
            $handle = fopen('php://output', 'w');
            // Add the header of the CSV file
            fputcsv($handle, ['ID', 'Course Name', 'Enrolled Students', 'Dropped Students', 'Course Capacity']);
            // Add the data of the CSV file (dummy data here)
            fputcsv($handle, [1, 'Course 1', 50, 5, 55]);
            fputcsv($handle, [2, 'Course 2', 45, 3, 48]);
            fclose($handle);
        });

        $response->headers->set('Content-Type', 'text/csv');
        $response->headers->set('Content-Disposition', 'attachment; filename="course_sections.csv"');

        return $response;
    }

    public function render()
    {
        return view('livewire.export-table');
    }
}
