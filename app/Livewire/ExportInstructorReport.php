<?php

namespace App\Livewire;

use Livewire\Component;
use Symfony\Component\HttpFoundation\StreamedResponse;
use App\Models\CourseSection;
use Illuminate\Support\Facades\Auth;

class ExportInstructorReport extends Component
{
    public function exportCSV()
    {
        $instructorId = Auth::user()->id;
        $courseSections = CourseSection::whereHas('teaches', function ($query) use ($instructorId) {
            $query->where('instructor_id', $instructorId);
        })->with(['area', 'teaches.instructor.user', 'seiData'])->get();

        $response = new StreamedResponse(function () use ($courseSections) {
            $handle = fopen('php://output', 'w');

            // Add the header of the CSV file
            fputcsv($handle, ['ID', 'Course Name', 'Enrolled Students', 'Dropped Students', 'Course Capacity', 'Room', 'Timings', 'SEI Data']);

            // Add the data of the CSV file
            foreach ($courseSections as $section) {
                fputcsv($handle, [
                    $section->id ?? 'N/A',
                    $section->formattedName ?? 'N/A',
                    $section->enroll_end ?? 'N/A',
                    $section->dropped ?? 'N/A',
                    $section->capacity ?? 'N/A',
                    $section->room ?? 'N/A',
                    $section->time_start . ' - ' . $section->time_end ?? 'N/A',
                    $section->seiData->first()->questions ?? 'N/A',
                ]);
            }

            fclose($handle);
        });

        $response->headers->set('Content-Type', 'text/csv');
        $response->headers->set('Content-Disposition', 'attachment; filename="Instructor_Report.csv"');

        return $response;
    }
    public function render()
    {
        return view('livewire.export-instructor-report');
    }
}
