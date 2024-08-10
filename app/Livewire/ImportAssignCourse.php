<?php

namespace App\Livewire;

use App\Models\Area;
use App\Models\AreaPerformance;
use App\Models\CourseSection;
use App\Models\DepartmentPerformance;
use App\Models\InstructorPerformance;
use App\Models\Teach;
use App\Models\User;
use App\Models\UserRole;
use Livewire\Component;

class ImportAssignCourse extends Component
{
    public $assignments = [];
    public $filteredInstructors;

    public $showModal = false;
    public $showInstructorModal = false;
    public $hasCourses = false;
    
    public $selectedIndex = -1;
    public $searchTerm = '';

    /**
     * Initialize the component by setting up the available courses.
     */
    public function mount() {
        $this->assignments = $this->getAvailableCourses()->map(function($course) {
            return [
                'course_section_id' => $course->id,
                'instructor' => '',
                'instructor_id' => null,
                'year' => $course->year,
            ];
        })->toArray();
    }

    /**
     * Handle the submission of course assignments.
     * This method creates or updates performance records for instructors, areas, and departments.
     */
    public function handleSubmit() { 
        foreach ($this->assignments as $assignment) {
            $instructor_id = (int) $assignment['instructor_id'];
            $year = $assignment['year'];

            if ($instructor_id !== null) {
                // Assign course to instructor
                Teach::create([
                    'course_section_id' => $assignment['course_section_id'],
                    'instructor_id' => $instructor_id,
                ]);

                // Retrieve related IDs
                $area_id = CourseSection::where('id', $assignment['course_section_id'])->value('area_id');
                $dept_id = Area::where('id', $area_id)->value('dept_id');

                // Update or create instructor performance
                $instructorPerformance = InstructorPerformance::where('instructor_id', $instructor_id )->where('year', $year )->first();
                if ($instructorPerformance !== null) {
                    InstructorPerformance::updatePerformance($instructor_id, $year);
                } else {
                    InstructorPerformance::create([
                        'instructor_id'=> $instructor_id,
                        'score' => 0,
                        'total_hours' => json_encode(array_fill_keys([
                            'January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'
                        ], 0)),
                        'target_hours' => null,
                        'sei_avg' => 0,
                        'enrolled_avg'=> 0,
                        'dropped_avg'=> 0,
                        'year' => $year,
                    ]);
                    InstructorPerformance::updatePerformance($instructor_id, $year);
                }

                // Update or create area performance
                $areaPerformance = AreaPerformance::where('area_id', $area_id)->where('year', $year)->first();
                if ($areaPerformance !== null) {
                    AreaPerformance::updateAreaPerformance($area_id, $year);
                } else {
                    AreaPerformance::create([
                        'area_id'=> $area_id,
                        'total_hours' => json_encode(array_fill_keys([
                            'January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'
                        ], 0)),
                        'sei_avg' => 0,
                        'enrolled_avg'=> 0,
                        'dropped_avg'=> 0,
                        'year' => $year,
                    ]);
                    AreaPerformance::updateAreaPerformance($area_id, $year);
                }

                // Update or create department performance
                $departmentPerformance = DepartmentPerformance::where('dept_id', $dept_id)->where('year', $year)->first();
                if ($departmentPerformance !== null) {
                    DepartmentPerformance::updateDepartmentPerformance($dept_id, $year);
                } else {
                    DepartmentPerformance::create([
                        'dept_id'=> $dept_id,
                        'total_hours' => json_encode(array_fill_keys([
                            'January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'
                        ], 0)),
                        'sei_avg' => 0,
                        'enrolled_avg'=> 0,
                        'dropped_avg'=> 0,
                        'year' => $year,
                    ]);
                    DepartmentPerformance::updateDepartmentPerformance($dept_id, $year);
                }
            }
        }

        // Reset the form
        $this->mount();

        // Display success message
        session()->flash('success', 'Instructors assigned successfully!');

        if (session()->has('success')) {
            $this->showModal = true;
        }
    }
    
    /**
     * Open the instructor selection modal.
     *
     * @param int $index The index of the assignment being edited.
     */
    public function openInstructorModal($index) {
        $this->selectedIndex = $index;
        $this->showInstructorModal = true;
    }

    /**
     * Close the instructor selection modal.
     */
    public function closeInstructorModal() {
        $this->showInstructorModal = false;
    }

    /**
     * Close the success message modal.
     */
    public function closeModal() {
        $this->showModal = false;
    }

    /**
     * Select an instructor and assign them to the selected course.
     *
     * @param int $id The ID of the selected instructor.
     * @param string $firstname The first name of the selected instructor.
     * @param string $lastname The last name of the selected instructor.
     * @param int $selectedIndex The index of the assignment being edited.
     */
    public function selectInstructor($id, $firstname, $lastname, $selectedIndex) {
        $this->assignments[$selectedIndex]["instructor_id"] = $id;
        $this->assignments[$selectedIndex]["instructor"] = $firstname . " " . $lastname;

        $this->closeInstructorModal();
    }

    /**
     * Update the filtered list of instructors based on the search term.
     */
    public function updateSearch() {
        $availableInstructors = $this->getAvailableInstructors();

        $this->filteredInstructors = $availableInstructors->filter(function ($instructor) {
            $name = $instructor->firstname . ' ' . $instructor->lastname;
            return stripos($name, $this->searchTerm) !== false;
        });
    }

    /**
     * Retrieve a list of available courses that are not yet assigned.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAvailableCourses() {
        $assignedCourseIds = Teach::pluck('course_section_id');

        return CourseSection::whereNotIn('id', $assignedCourseIds)->get();
    }

    /**
     * Retrieve a list of available instructors.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAvailableInstructors() {
        return User::join('user_roles', 'users.id', '=', 'user_roles.user_id')
            ->where('role', 'instructor')
            ->orderByRaw('LOWER(users.lastname)')
            ->orderByRaw('LOWER(users.firstname)')
            ->get();
    }

    /**
     * Render the component view.
     *
     * @return \Illuminate\View\View
     */
    public function render()
    {
        $this->updateSearch();

        $this->hasCourses = !$this->getAvailableCourses()->isEmpty();

        return view('livewire.import-assign-course', [
            'availableInstructors' => $this->getAvailableInstructors(),
            'availableCourses' => $this->getAvailableCourses(),
            'filteredInstructors' => $this->filteredInstructors,
            'selectedIndex' => $this->selectedIndex,
        ]);
    }
}

