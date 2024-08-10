<?php

namespace App\Livewire;
use Livewire\Component;
use Symfony\Component\HttpFoundation\StreamedResponse;
use App\Models\CourseSection;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class ExportDepartmentReport extends Component
{
    public $courseSections = [];
    public $user;

    public function mount()
    {
        $this->user = Auth::user();
        $this->fetchCourseSections();
        Log::info('ExportDepartmentHead::mount - courseSections:', ['courseSections' => $this->courseSections]);
    }

    public function fetchCourseSections()
{
    $courseSectionsQuery = CourseSection::with(['area', 'teaches.instructor.user', 'seiData'])
        ->where('archived', false) // Only include unarchived courses
        ->get();

    $this->courseSections = $courseSectionsQuery->map(function ($section) {
        $seiData = $section->seiData ?? null;
        $averageRating = $seiData ? $this->calculateAverageRating($seiData->questions) : 0;

        $formattedName = sprintf('%s %s %s - %s%s %s',
            $section->prefix,
            $section->number,
            $section->section,
            $section->year,
            $section->session,
            $section->term
        );

        $instructorName = 'No Instructors';
        if ($section->teaches && $section->teaches->instructor && $section->teaches->instructor->user) {
            $instructorName = $section->teaches->instructor->user->getName();
        }

        $timings = sprintf('%s - %s', $section->time_start, $section->time_end);

        return [
            'id' => $section->id,
            'formattedName' => $formattedName,
            'departmentName' => $section->area->name ?? 'Unknown',
            'instructorName' => $instructorName,
            'enrolled' => $section->enroll_end,
            'dropped' => $section->dropped,
            'room' => $section->room,
            'timings' => $timings,
            'capacity' => $section->capacity,
            'averageRating' => $averageRating,
        ];
    })->toArray();
}



    public function exportCSV()
    {
        Log::info('ExportDepartmentHead::exportCSV - courseSections:', ['courseSections' => $this->courseSections]);

        $response = new StreamedResponse(function () {
            $handle = fopen('php://output', 'w');

            fputcsv($handle, ['ID', 'Course Name', 'Area', 'Instructor Name', 'Enrolled Students', 'Dropped Students', 'Course Capacity', 'Room', 'Timings', 'SEI Data']);

            foreach ($this->courseSections as $section) {
                fputcsv($handle, [
                    $section['id'] ?? 'N/A',
                    $section['formattedName'] ?? 'N/A',
                    $section['departmentName'] ?? 'N/A',
                    $section['instructorName'] ?? 'N/A',
                    $section['enrolled'] ?? 'N/A',
                    $section['dropped'] ?? 'N/A',
                    $section['capacity'] ?? 'N/A',
                    $section['room'] ?? 'N/A',
                    $section['timings'] ?? 'N/A',
                    $section['averageRating'] ?? 'N/A'
                ]);
            }

            fclose($handle);
        });

        $response->headers->set('Content-Type', 'text/csv');
        $response->headers->set('Content-Disposition', 'attachment; filename="Department_Report.csv"');

        return $response;
    }

    private function calculateAverageRating($questionsJson)
    {
        $questions = json_decode($questionsJson, true);
        if (is_array($questions) && !empty($questions)) {
            $ratings = array_filter(array_values($questions), function ($value) {
                return is_numeric($value);
            });
            $averageRating = count($ratings) > 0 ? array_sum($ratings) / count($ratings) : 0;
            return round($averageRating, 2);
        }
        return 0;
    }

    public function render()
    {
        return view('livewire.export-department-report');
    }
}
