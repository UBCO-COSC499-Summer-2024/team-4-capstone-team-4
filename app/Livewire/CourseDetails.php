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
class CourseDetails extends Component
{
    use WithPagination;

    public $sortField = 'courseName'; // default 
    public $sortDirection = 'asc'; //default

    public $searchTerm = '';
    public $areaId = null;
    public $pagination;
    public $selectedCourses = []; // Add this line

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
        $this->courses = CourseSection::where('archived', false)->get();
    }

    public function archiveCourses()
    {
        $archivedCourses = CourseSection::whereIn('id', $this->selectedCourses)->get(['prefix', 'number', 'section', 'year', 'session', 'term']);
    
        CourseSection::whereIn('id', $this->selectedCourses)->update(['archived' => true]);
    
        $this->archivedCourses = $archivedCourses->map(function($course) {
            return sprintf('%s %s %s - %s%s %s',
                $course->prefix,
                $course->number,
                $course->section,
                $course->year,
                $course->session,
                $course->term
            );
        })->toArray();
    
        $this->loadCourses();
        $this->selectedCourses = [];
        $this->showDeleteButton = false;
        $this->showConfirmationModal = false;
    
        $this->dispatch('show-archived-summary', ['courses' => $this->archivedCourses]);
    }

    public function render()
    {
        $user = Auth::user();
        if($user->hasRole('instructor') && !$user->hasRoles(['dept_head', 'dept_staff', 'admin'])){
            $userRole = 'instructor';
        }else{
            $userRole = 'other';
        }

        $query = $this->searchTerm;
        $areaId = $this->areaId;

        $courseSectionsQuery = CourseSection::with(['area', 'teaches.instructor.user'])
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
            });

        //pagination
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

            // if no instructor is assigned, display 'No Instructors'
            if (empty($section->teaches)) {
                $instructorName = 'No Instructors';
            } else {
                $instructorName = $section->teaches->instructor->user->getName();
            }

            $timings=sprintf('%s - %s', $section->time_start, $section->time_end);

            return (object)[
                'id' => $section->id,
                'name' => $formattedName,
                'departmentName' => $section->area->name ?? 'Unknown',
                'instructorName' => $instructorName,
                'enrolled' => $section->enroll_end,
                'dropped' => $section->dropped,
                'room'=>$section->room,
                'timings'=>$timings,
                'capacity' => $section->capacity,
                'averageRating' => $averageRating,
            ];
        });

        $areas = Area::all();
    
        $sortField = $this->sortField;
        $sortDirection = $this->sortDirection;
        $courses = CourseSection::all();
    
        return view('livewire.course-details', compact('courseSections', 'sortField', 'sortDirection', 'areaId', 'areas', 'courses'));
    }

    private function calculateAverageRating($questionsJson){
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

    public function exportCSV(){
        // Your CSV export logic here
        $courseSections = CourseSection::with(['area', 'teaches.instructor.user'])->get();

        // Convert data to CSV format
        $csvData = "Course Name,Area,Instructor,Enrolled,Dropped,Capacity,SEI Data\n";
        foreach ($courseSections as $section) {
            $csvData .= "{$section->name},{$section->area->name},{$section->teaches->instructor->user->name},{$section->enrolled},{$section->dropped},{$section->capacity},{$section->averageRating}\n";
        }

        $csvFileName = 'courses.csv';
        return response($csvData)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', "attachment; filename={$csvFileName}");
    }

}
