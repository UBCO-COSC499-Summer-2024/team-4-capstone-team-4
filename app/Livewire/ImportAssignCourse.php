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
            //dd($assignment);

            if ($assignment['instructor_id'] != null) {
                Teach::create([
                    'course_section_id' => $assignment['course_section_id'],
                    'instructor_id' => (int) $assignment['instructor_id'],
                ]);

                // dd($assignment['year']);

                $performance = InstructorPerformance::where('instructor_id', $assignment['instructor_id'] )->where('year', $assignment['year'] )->first();

                if($performance != null){
                   /*  $performance->update([
                        'target_hours' => SeiData::calculateTargetHours($assignment['course_section_id']),
                        'score' => SeiData::calculateScore($assignment['course_section_id']),
                    ]); */
                }else{
                    InstructorPerformance::create([
                        'instructor_id'=> $assignment['instructor_id'],
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
                        'capacity_avg'=> 0,
                        'year' => $assignment['year'],
                    ]);
                   // InstructorPerformance::updatePerformance($assignment['instructor_id'], $assignment['year']);
                }
            }
        }


        // Reset the form
        $this->mount();

        session()->flash('success', 'Instructors assigned successfully!');
    }

    public function getAvailableCourses() {
        $assignedCourseIds = Teach::pluck('course_section_id');

        return CourseSection::whereNotIn('id', $assignedCourseIds)->get();
    }

    public function getAvailableInstructors() {
    //    $instructorRoleIds = UserRole::where('role', 'instructor')->pluck('id');

    //    $assignedInstructorIds = Teach::whereIn('instructor_id', $instructorRoleIds)->pluck('instructor_id');

    //    return User::whereIn('id', $instructorRoleIds)
    //        ->whereNotIn('id', $assignedInstructorIds)
    //        ->get();

        $instructorRoleIds = UserRole::where('role', 'instructor')->pluck('user_id');

        return User::join('user_roles', 'users.id', '=', 'user_roles.user_id')->where('role', 'instructor')->get();
    }

    public function render()
    {

        // dd($this->getAvailableCourses(), $this->getAvailableInstructors());

        // DepartmentPerformance::updateDepartmentPerformance();
        // AreaPerformance::updateAreaPerformance();
        // InstructorPerformance::updatePerformance(1, 2024);
        // Teach::getInstructorsForCourses();
        // SeiData::calculateSEIAverages();

        return view('livewire.import-assign-course', [
            'availableInstructors' => $this->getAvailableInstructors(),
            'availableCourses' => $this->getAvailableCourses(),
        ]);
    }
}
