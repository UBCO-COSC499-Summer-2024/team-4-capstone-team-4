<?php

namespace App\Livewire;

use Livewire\Component;
use Symfony\Component\HttpFoundation\StreamedResponse;
use App\Models\CourseSection;
use Illuminate\Support\Facades\Auth;

class ExportInstructorReport extends Component
{
    /**
     * Export the instructor's course sections to a CSV file.
     *
     * @return \Symfony\Component\HttpFoundation\StreamedResponse
     */
    public function exportCSV()
    {
        // Retrieve the current authenticated user's ID
        $instructorId = Auth::user()->id;
        
        // Query to get course sections for the current instructor
        $courseSections = CourseSection::whereHas('teaches', function ($query) use ($instructorId) {
            $query->where('instructor_id', $instructorId);
        })->with(['area', 'teaches.instructor.user', 'seiData'])->get();

        // Create a streamed response to generate the CSV file
        $response = new StreamedResponse(function () use ($courseSections) {
            // Open output stream for writing
            $handle = fopen('php://output', 'w');

            // Add the header of the CSV file
            fputcsv($handle, [
                'ID', 
                'Course Name', 
                'Enrolled Students', 
                'Dropped Students', 
                'Course Capacity', 
                'Room', 
                'Timings', 
                'SEI Data'
            ]);

            // Add the data rows to the CSV file
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

            // Close the file handle
            fclose($handle);
        });

        // Set headers to force download of the CSV file
        $response->headers->set('Content-Type', 'text/csv');
        $response->headers->set('Content-Disposition', 'attachment; filename="Instructor_Report.csv"');

        return $response;
    }

    /**
     * Render the Livewire component view.
     *
     * @return \Illuminate\View\View
     */
    public function render()
    {
        return view('livewire.export-instructor-report');
    }
}

