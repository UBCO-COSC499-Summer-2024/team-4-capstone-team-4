<?php

namespace App\Livewire;

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

    public $showModal = false;

    public function mount() {
        $this->assignments = $this->getAvailableCourses()->map(function($course) {
            return [
                'course_section_id' => $course->id,
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
                Teach::create([
                    'course_section_id' => $assignment['course_section_id'],
                    'instructor_id' => $instructor_id,
                ]);

                // dd($assignment['year']);
             
                $areaId = CourseSection::where('id', $assignment['course_section_id'])->value('area_id');

                $instructorPerformance = InstructorPerformance::where('instructor_id', $instructor_id )->where('year', $year )->first();
                $areaPerformance = AreaPerformance::where('area_id', $areaId)->where('year', $year)->first();
                // $departmetnPerformance 

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
                //    DepartmentPerformance::updateDepartmentPerformance($assignment['year']);
                }

                if ($areaPerformance != null) {
                    AreaPerformance::updateAreaPerformance($areaId, $year);
                } else {
                    AreaPerformance::create([
                        'area_id'=> $areaId,
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
                    AreaPerformance::updateAreaPerformance($areaId, $year);
                }

                // if ($departmetnPerformance != null) {
                //     DepartmentPerformance::updateDepartmentPerformance($assignment['year']);
                // }
            }
        }


        // Reset the form
        $this->mount();

        session()->flash('success', 'Instructors assigned successfully!');

        if(session()->has('success')) {
            $this->showModal = true;
        }
    }

    public function closeModal() {
        $this->showModal = false;
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
    }

    public function render()
    {
        // InstructorPerformance::updatePerformance(1, 2023);
            // AreaPerformance::updateAreaPerformance(1, 2023);


        return view('livewire.import-assign-course', [
            'availableInstructors' => $this->getAvailableInstructors(),
            'availableCourses' => $this->getAvailableCourses(),
        ]);
    }
}
