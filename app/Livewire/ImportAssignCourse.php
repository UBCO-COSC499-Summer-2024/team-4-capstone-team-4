<?php

namespace App\Livewire;

use App\Models\AreaPerformance;
use App\Models\CourseSection;
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
                'user_role_id' => null,
            ];
        })->toArray();
    }

    public function saveAssignments() {

        foreach ($this->assignments as $assignment) {

            if ($assignment['user_role_id'] != null) {
                Teach::create([
                    'course_section_id' => $assignment['course_section_id'],
                    'user_role_id' => (int) $assignment['user_role_id'],
                ]);
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

    //    $assignedInstructorIds = Teach::whereIn('user_role_id', $instructorRoleIds)->pluck('user_role_id');

    //    return User::whereIn('id', $instructorRoleIds)
    //        ->whereNotIn('id', $assignedInstructorIds)
    //        ->get();

        $instructorRoleIds = UserRole::where('role', 'instructor')->pluck('id');

        return User::whereIn('id', $instructorRoleIds)
            ->get();
    }

    public function render()
    {

        // dd($this->getAvailableCourses(), $this->getAvailableInstructors());

        AreaPerformance::updateAreaPerformance();
        InstructorPerformance::updatePerformance();
        // Teach::getInstructorsForCourses();
        // SeiData::calculateSEIAverages();

        return view('livewire.import-assign-course', [
            'availableInstructors' => $this->getAvailableInstructors(),
            'availableCourses' => $this->getAvailableCourses(),
        ]);
    }
}
