<?php

namespace App\Livewire;

use App\Models\Area;
use App\Models\AreaPerformance;
use App\Models\CourseSection;
use App\Models\DepartmentPerformance;
use App\Models\InstructorPerformance;
use App\Models\SeiData;
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

    public function handleSubmit() { 

        foreach ($this->assignments as $assignment) {
            $instructor_id = (int) $assignment['instructor_id'];
            $year = $assignment['year'];
            //dd($assignment);

            if ($instructor_id != null) {
                $assign = Teach::create([
                    'course_section_id' => $assignment['course_section_id'],
                    'instructor_id' => $instructor_id,
                ]);

                $course = CourseSection::where('id', $assignment['course_section_id'])->first();
                $assign->log_audit('Assign Instructor', ['operation_type' => 'CREATE', 'new_value' => json_encode($assign->getAttributes())], 'Assign ' . $assignment['instructor'] . ' to ' . $course->prefix . ' ' . $course->number . ' ' . $course->section . '-' . $course->year . $course->session . $course->term);
             
                $area_id = CourseSection::where('id', $assignment['course_section_id'])->value('area_id');
                $dept_id = Area::where('id', $area_id)->value('dept_id');

                $instructorPerformance = InstructorPerformance::where('instructor_id', $instructor_id )->where('year', $year )->first();
                $areaPerformance = AreaPerformance::where('area_id', $area_id)->where('year', $year)->first();
                $departmentPerformance  = DepartmentPerformance::where('dept_id', $dept_id)->where('year', $year)->first();

                if($instructorPerformance != null){
                    InstructorPerformance::updatePerformance($instructor_id, $year);
                }else{
                    InstructorPerformance::create([
                        'instructor_id'=> $instructor_id,
                        'score' => 0,
                        'total_hours' => json_encode([
                            'January' => 0,
                            'February' => 0,
                            'March' => 0,
                            'April' => 0,
                            'May' => 0,
                            'June' => 0,
                            'July' => 0,
                            'August' => 0,
                            'September' => 0,
                            'October' => 0,
                            'November' => 0,
                            'December' => 0,
                        ]),
                        'target_hours' => null,
                        'sei_avg' => 0,
                        'enrolled_avg'=> 0,
                        'dropped_avg'=> 0,
                        'year' => $year,
                    ]);
                   InstructorPerformance::updatePerformance($instructor_id, $year);
                }

                if ($areaPerformance != null) {
                    AreaPerformance::updateAreaPerformance($area_id, $year);
                } else {
                    AreaPerformance::create([
                        'area_id'=> $area_id,
                        'total_hours' => json_encode([
                            'January' => 0,
                            'February' => 0,
                            'March' => 0,
                            'April' => 0,
                            'May' => 0,
                            'June' => 0,
                            'July' => 0,
                            'August' => 0,
                            'September' => 0,
                            'October' => 0,
                            'November' => 0,
                            'December' => 0,
                        ]),
                        'sei_avg' => 0,
                        'enrolled_avg'=> 0,
                        'dropped_avg'=> 0,
                        'year' => $year,
                    ]);
                    AreaPerformance::updateAreaPerformance($area_id, $year);
                }

                if ($departmentPerformance != null) {
                    DepartmentPerformance::updateDepartmentPerformance($dept_id, $year);
                } else {
                    DepartmentPerformance::create([
                        'dept_id'=> $dept_id,
                        'total_hours' => json_encode([
                            'January' => 0,
                            'February' => 0,
                            'March' => 0,
                            'April' => 0,
                            'May' => 0,
                            'June' => 0,
                            'July' => 0,
                            'August' => 0,
                            'September' => 0,
                            'October' => 0,
                            'November' => 0,
                            'December' => 0,
                        ]),
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

        session()->flash('success', 'Instructors assigned successfully!');

        if(session()->has('success')) {
            $this->showModal = true;
        }
    }
    
    public function openInstructorModal($index) {
        $this->selectedIndex = $index;
        // dd($this->selectedIndex);
        $this->showInstructorModal = true;
    }

    public function closeInstructorModal() {
        $this->showInstructorModal = false;
    }

    public function closeModal() {
        $this->showModal = false;
    }

    public function selectInstructor($id, $firstname, $lastname, $selectedIndex) {
        $this->assignments[$selectedIndex]["instructor_id"] = $id;
        $this->assignments[$selectedIndex]["instructor"] = $firstname . " " . $lastname;

        $this->closeInstructorModal();
  
        // dd($this->assignments);
    }

    public function updateSearch() {
        $availableInstructors = $this->getAvailableInstructors();

        $this->filteredInstructors = $availableInstructors->filter(function ($instructor) {
            $name = $instructor->firstname . ' ' . $instructor->lastname;
            return stripos($name, $this->searchTerm) !== false;
        });
    }

    public function getAvailableCourses() {
        $assignedCourseIds = Teach::pluck('course_section_id');

        return CourseSection::whereNotIn('id', $assignedCourseIds)->get();
    }

    public function getAvailableInstructors() {
        // $instructorRoleIds = UserRole::where('role', 'instructor')->pluck('user_id');

        return User::join('user_roles', 'users.id', '=', 'user_roles.user_id')
            ->where('role', 'instructor')
            ->orderByRaw('LOWER(users.lastname)')
            ->orderByRaw('LOWER(users.firstname)')
            ->get();

        // return User::join('user_roles', 'users.id', '=', 'user_roles.user_id')
        // ->where('role', 'instructor')
        // ->where(function ($query) {
        //     $query->whereRaw('users.firstname ILIKE ?', ["%{$this->instructorSearch}%"])
        //           ->orWhereRaw('users.lastname ILIKE ?', ["%{$this->instructorSearch}%"])
        //           ->orWhereRaw('CONCAT(users.firstname, \' \', users.lastname) ILIKE ?', ["%{$this->instructorSearch}%"]);
        // })
        // ->orderByRaw('LOWER(users.lastname)')
        // ->orderByRaw('LOWER(users.firstname)')
        // ->get();
    }

    public function render()
    {
        // InstructorPerformance::updatePerformance(8, 2000);
            // AreaPerformance::updateAreaPerformance(1, 2023);
            // DepartmentPerformance::updateDepartmentPerformance(1, 2023);

        // dd($this->assignments);
        $this->updateSearch();

        if(!$this->getAvailableCourses()->isEmpty()) {
            $this->hasCourses = true;
        } else {
            $this->hasCourses = false;
        }
        // dd($this->getAvailableInstructors());

        return view('livewire.import-assign-course', [
            'availableInstructors' => $this->getAvailableInstructors(),
            'availableCourses' => $this->getAvailableCourses(),
            'filteredInstructors' => $this->filteredInstructors,
            'selectedIndex' => $this->selectedIndex,
        ]);
    }
}
