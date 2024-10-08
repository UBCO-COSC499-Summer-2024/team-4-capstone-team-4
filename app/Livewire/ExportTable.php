<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Illuminate\Support\Facades\Log;
use App\Models\CourseSection;
use App\Models\Area;
use App\Models\SeiData;
use Illuminate\Support\Facades\Auth;

class ExportTable extends Component
{
    public $courseSections = [];
    public $user;

    public function mount($courseSections)
    {
        $this->user = Auth::user();
        $this->fetchCourseSections();
        Log::info('ExportTable::mount - courseSections:', ['courseSections' => $this->courseSections]);
    }
    
    public function fetchCourseSections()
    {
        $query = CourseSection::with(['area', 'teaches.instructor.user', 'seiData']);
        
        // Check if the user is an instructor and filter courses accordingly
        if ($this->user->hasRole('instructor')) {
            $query->whereHas('teaches', function ($q) {
                $q->where('instructor_id', $this->user->id);
            });
        }
        
        $courseSectionsQuery = $query->get();

        $this->courseSections = $courseSectionsQuery->map(function ($section) {
            $seiData = $section->seiData->first() ?? null;
            $averageRating = $seiData ? $this->calculateAverageRating($seiData->questions) : 0;

            $formattedName = sprintf('%s %s %s - %s%s %s',
                $section->prefix,
                $section->number,
                $section->section,
                $section->year,
                $section->session,
                $section->term
            );

            // If no instructor is assigned, display 'No Instructors'
            $instructorName = 'No Instructors';
            if ($section->teaches && $section->teaches->instructor && $section->teaches->instructor->user) {
                $instructorName = $section->teaches->instructor->user->getName();
            }

            $timings = sprintf('%s - %s', $section->time_start, $section->time_end);

            return [
                'id' => $section->id,
                'prefix' => $section->prefix,
                'number' => $section->number,
                'section' => $section->section,
                'year' => $section->year,
                'session' => $section->session,
                'term' => $section->term,
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
        Log::info('ExportTable::exportCSV - courseSections:', ['courseSections' => $this->courseSections]);

        $response = new StreamedResponse(function () {
            $handle = fopen('php://output', 'w');

            // Add the header of the CSV file
            fputcsv($handle, ['ID', 'Course Name', 'Enrolled Students', 'Dropped Students', 'Course Capacity', 'Room', 'Timings', 'SEI Data']);

            // Add the data of the CSV file
            foreach ($this->courseSections as $section) {
                Log::info('ExportTable::exportCSV - section:', ['section' => $section]);

                fputcsv($handle, [
                    $section['id'] ?? 'N/A',
                    $this->formatCourseName($section),
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
        $response->headers->set('Content-Disposition', 'attachment; filename="Insight_course_sections.csv"');

        return $response;
    }

    private function formatCourseName($section)
    {
        $prefix = $section['prefix'] ?? 'N/A';
        $number = $section['number'] ?? 'N/A';
        $sectionNumber = $section['section'] ?? 'N/A';
        $year = $section['year'] ?? 'N/A';
        $session = $section['session'] ?? 'N/A';
        $term = $section['term'] ?? 'N/A';

        Log::info('ExportTable::formatCourseName - data:', compact('prefix', 'number', 'sectionNumber', 'year', 'session', 'term'));

        return sprintf('%s %s %s - %s%s %s', $prefix, $number, $sectionNumber, $year, $session, $term);
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
        return view('livewire.export-table');
    }
}
