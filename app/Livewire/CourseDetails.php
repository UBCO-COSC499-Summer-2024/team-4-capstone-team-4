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
use Illuminate\Support\Facades\Http;

class CourseDetails extends Component
{
    public $ids = []; // Array to store IDs of course sections
    public $courses = []; // Array to store course sections
    public $courseNames = []; // Array to store names of courses
    public $enrolledStudents = []; // Array to store number of enrolled students
    public $droppedStudents = []; // Array to store number of dropped students
    public $archivedCourses = []; // Array to store archived courses
    public $courseCapacities = []; // Array to store capacities of courses

    use WithPagination; // Include pagination functionality

    public $sortField = 'courseName'; // Default sorting field
    public $sortDirection = 'asc'; // Default sorting direction
    public $showDeleteButton = false; // Flag to show/hide delete button
    public $user; // Current user

    public $searchTerm = ''; // Term for searching courses
    public $areaId = null; // Selected area ID for filtering courses
    public $pagination; // Number of items per page for pagination
    public $selectedCourses = []; // Array to store selected course IDs
    public $coursesSelected = []; // Array to store selected courses
    public $showConfirmationModal = false; // Flag to show/hide confirmation modal
    protected $listeners = [
        'save-changes' => 'saveChanges', // Listener for saving changes
        'selectCourse' => 'selectCourse', // Listener for selecting a course
    ];

    /**
     * Initialize the component.
     */
    public function mount()
    {
        $this->user = User::find(Auth::id()); // Get the current authenticated user
        $this->loadCourses(); // Load course sections
    }

    /**
     * Save changes made to courses.
     */
    public function saveChanges()
    {
        try {
            // Save course changes via HTTP request (commented out)
            // $response = Http::post(route('courses.details.save'), [
            //     'courseNames' => $this->courseNames,
            //     'enrolledStudents' => $this->enrolledStudents,
            //     'droppedStudents' => $this->droppedStudents,
            //     'courseCapacities' => $this->courseCapacities,
            // ]);

            // Debug output for course data
            dd($this->courseNames, $this->enrolledStudents, $this->droppedStudents, $this->courseCapacities);
        } catch (\Exception $e) {
            // Log error and dispatch a toast notification on failure
            Log::error($e->getMessage());
            $this->dispatch('show-toast', [
                'message' => 'Failed to update courses.',
                'type' => 'error'
            ]);
            CourseSection::audit('update error', [
                'operation_type' => 'UPDATE',
            ], $this->user->getName() . ' failed to update courses. Error: ' . $e->getMessage());
        }
    }

    /**
     * Show the confirmation modal.
     */
    public function showConfirmation()
    {
        $this->showConfirmationModal = true;
    }

    /**
     * Hide the confirmation modal.
     */
    public function hideConfirmation()
    {
        $this->showConfirmationModal = false;
    }

    /**
     * Load the list of unarchived course sections.
     */
    public function loadCourses()
    {
        $this->courses = CourseSection::where('archived', false)->get(); // Load only unarchived courses
    }

    /**
     * Archive selected courses and update UI.
     */
    public function archiveCourses()
    {
        $archivedCourses = CourseSection::whereIn('id', $this->selectedCourses)->get(['prefix', 'number', 'section', 'year', 'session', 'term']);

        CourseSection::whereIn('id', $this->selectedCourses)->update(['archived' => true]);

        // Format archived courses for display
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

        $this->loadCourses(); // Reload courses
        $this->selectedCourses = []; // Clear selected courses
        $this->showDeleteButton = false; // Hide delete button
        $this->showConfirmationModal = false; // Hide confirmation modal

        // Dispatch a summary of archived courses
        $this->dispatch('show-archived-summary', ['courses' => $this->archivedCourses]);
    }

    /**
     * Select or deselect a course.
     *
     * @param int $id The ID of the course
     * @param bool $selected Whether the course is selected or not
     */
    public function selectCourse($id, $selected) {
        $id = (int) $id;
        if ($selected) {
            $this->selectedCourses[] = $id;
        } else {
            $this->selectedCourses = array_diff($this->selectedCourses, [$id]);
        }
        // Update the visibility of the delete button based on selection
        // $this->showDeleteButton = count($this->selectedCourses) > 0;
    }

    /**
     * Render the component view.
     *
     * @return \Illuminate\View\View The view to be rendered
     */
    public function render() {
        $user = User::find(Auth::id());
        if ($user->hasRole('instructor') && !$user->hasRoles(['dept_head', 'dept_staff', 'admin'])) {
            $userRole = 'instructor';
        } else {
            $userRole = 'other';
        }

        $query = $this->searchTerm;
        $areaId = $this->areaId;

        // Query to retrieve course sections based on filters and user role
        $courseSectionsQuery = CourseSection::with(['area', 'teaches.instructor.user'])
            ->where('archived', false) // Only show unarchived courses
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

        // Apply pagination
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

        // Transform course sections for display
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
            if (empty($section->teaches)) {
                $instructorName = 'No Instructors';
            } else {
                $instructorName = $section->teaches->instructor->user->getName();
            }

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

        // Get all areas for filtering
        $areas = Area::all();

        $sortField = $this->sortField;
        $sortDirection = $this->sortDirection;
        $courses = CourseSection::all();

        return view('livewire.course-details', compact('courseSections', 'sortField', 'sortDirection', 'areaId', 'areas', 'courses'));
    }

    /**
     * Calculate the average rating from SEI data.
     *
     * @param string $questionsJson JSON string of SEI questions
     * @return float The average rating
     */
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

    /**
     * Export course data to a CSV file.
     *
     * @return \Illuminate\Http\Response The CSV file response
     */
    public function exportCSV()
    {
        // Retrieve unarchived course sections
        $courseSections = CourseSection::with(['area', 'teaches.instructor.user'])
            ->where('archived', false) // Only export unarchived courses
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

        $csvFileName = 'unarchived_courses.csv';
        return response($csvData)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', "attachment; filename={$csvFileName}");
    }

}
