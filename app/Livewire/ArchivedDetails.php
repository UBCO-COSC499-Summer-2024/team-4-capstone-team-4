<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\CourseSection;
use App\Models\TeachingAssistant;
use Illuminate\Http\Request;
use App\Models\SeiData;
use App\Models\User;
use App\Models\Teach;
use Illuminate\Support\Facades\Log;
use App\Models\Area;
use Mpdf\Mpdf;
use Illuminate\Support\Facades\Auth;

class ArchivedDetails extends Component
{
    use WithPagination;

    public $sortField = 'courseName'; // default 
    public $sortDirection = 'asc'; //default

    public $searchTerm = '';
    public $areaId = null;
    public $pagination;
    public $selectedCourses = [];

    public function mount()
    {
        $this->loadCourses();
    }

    public function showConfirmation()
    {
        $this->showConfirmationModal = true;
    }

    public function hideConfirmation()
    {
        $this->showConfirmationModal = false;
    }

    public function loadCourses()
    {
        $this->courses = CourseSection::where('archived', true)->get(); // Load only archived courses
    }

    public function render()
    {
        $user = Auth::user();
        if ($user->hasRole('instructor') && !$user->hasRoles(['dept_head', 'dept_staff', 'admin'])) {
            $userRole = 'instructor';
        } else {
            $userRole = 'other';
        }

        $query = $this->searchTerm;
        $areaId = $this->areaId;

        $courseSectionsQuery = CourseSection::with(['area', 'teaches.instructor.user'])
            ->where('archived', true) // Only show archived courses
            ->when($userRole === 'instructor', function ($queryBuilder) use ($user) {
                $queryBuilder->whereHas('teaches', function ($query) use ($user) {
                    $query->where('instructor_id', $user->roles->where('role', 'instructor')->first()->id);
                });
            })
            ->when($query, function ($queryBuilder) use ($query) {
                $queryBuilder->where(function ($q) use ($query) {
                    $q->whereRaw('LOWER(prefix || \' \' || number || \' \' || section || \' - \' || year || session || \' \' || term) LIKE ?', ['%' . strtolower($query) . '%']);
                });
            })
            ->when($areaId, function ($queryBuilder) use ($areaId) {
                $queryBuilder->where('area_id', $areaId);
            })->orderBy('updated_at', 'desc');

        // Pagination
        switch ($this->pagination) {
            case 25:
                $courseSections = $courseSectionsQuery->paginate(25);
                break;
            case 50:
                $courseSections = $courseSectionsQuery->paginate(50);
                break;
            case 100:
                $courseSections = $courseSectionsQuery->paginate(100);
                break;
            case 'all':
                $courseSections = $courseSectionsQuery->get();
                break;
            default:
                $courseSections = $courseSectionsQuery->paginate(10);
                break;
        }

        $courseSections->transform(function ($section) {
            $seiData = $section->seiData()->first() ?? null;
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
            $instructorName = empty($section->teaches) ? 'No Instructors' : $section->teaches->instructor->user->getName();

            $timings = sprintf('%s - %s', $section->time_start, $section->time_end);

            return (object)[
                'id' => $section->id,
                'name' => $formattedName,
                'departmentName' => $section->area->name ?? 'Unknown',
                'instructorName' => $instructorName,
                'enrolled' => $section->enroll_end,
                'dropped' => $section->dropped,
                'room' => $section->room,
                'timings' => $timings,
                'capacity' => $section->capacity,
                'averageRating' => $averageRating,
            ];
        });

        $areas = Area::all();

        $sortField = $this->sortField;
        $sortDirection = $this->sortDirection;
        $courses = CourseSection::all();

        return view('livewire.archived-details', compact('courseSections', 'sortField', 'sortDirection', 'areaId', 'areas', 'courses'));
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

    public function exportCSV()
    {
        // Your CSV export logic here
        $courseSections = CourseSection::with(['area', 'teaches.instructor.user'])
            ->where('archived', true) // Export only archived courses
            ->get();

        // Convert data to CSV format
        $csvData = "Course Name,Area,Instructor,Enrolled,Dropped,Capacity,SEI Data\n";
        foreach ($courseSections as $section) {
            $seiData = $section->seiData()->first() ?? null;
            $averageRating = $seiData ? $this->calculateAverageRating($seiData->questions) : 0;
            $formattedName = sprintf('%s %s %s - %s%s %s',
                $section->prefix,
                $section->number,
                $section->section,
                $section->year,
                $section->session,
                $section->term
            );

            $csvData .= "{$formattedName},{$section->area->name},{$section->teaches->instructor->user->getName()},{$section->enroll_end},{$section->dropped},{$section->capacity},{$averageRating}\n";
        }

        $csvFileName = 'archived_courses.csv';
        return response($csvData)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', "attachment; filename={$csvFileName}");
    }
}
