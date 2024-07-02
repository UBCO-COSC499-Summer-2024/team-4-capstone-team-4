<?php

namespace App\Livewire;

use App\Models\CourseSection;
use App\Models\Teach;
use App\Models\User;
use App\Models\UserRole;
use Livewire\Component;

class ImportAssignCourse extends Component
{
    public $assignments = [];

    public function mount()
    {
        $this->assignments = $this->getAvailableCourses()->map(function($course) {
            return [
                'course_section_id' => $course->id,
                'instructor_id' => null,
            ];
        })->toArray();
    }

    public function getAvailableCourses() {
        $assignedCourseIds = Teach::pluck('course_section_id');

        return CourseSection::whereNotIn('id', $assignedCourseIds)->get();
    }

    public function getAvailableInstructors() {
       $instructorRoleIds = UserRole::where('role', 'instructor')->pluck('id');

       $assignedInstructorIds = Teach::whereIn('instructor_id', $instructorRoleIds)->pluck('instructor_id');

       return User::whereIn('id', $instructorRoleIds)
           ->whereNotIn('id', $assignedInstructorIds)
           ->get();
    }

    public function render()
    {
        return view('livewire.import-assign-course', [
            'availableInstructors' => $this->getAvailableInstructors(),
            'availableCourses' => $this->getAvailableCourses(),
        ]);
    }
}
