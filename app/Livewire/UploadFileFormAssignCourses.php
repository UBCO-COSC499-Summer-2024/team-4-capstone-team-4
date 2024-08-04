<?php

namespace App\Livewire;

use App\Models\CourseSection;
use App\Models\Teach;
use App\Models\User;
use Livewire\Component;

class UploadFileFormAssignCourses extends Component
{
    public $finalCSVs = [];
    public $assignments = [];
    public $rows = [];

    public function mount($finalCSVs) {
        $this->finalCSVs = $finalCSVs;

        $this->assignments = $this->getAvailableCourses()->map(function($course) {
            return [
                'course_section_id' => $course->id,
                'instructor_id' => null,
                'year' => $course->year,
            ];
        })->toArray();
    }

    public function getAvailableCourses() {
        $courses = collect();
        $assignedCourseIds = Teach::pluck('course_section_id');

  
        foreach($this->finalCSVs as $finalCSV) {
            $coursesFromCSV = "{$finalCSV['Prefix']} {$finalCSV['Number']} {$finalCSV['Section']} - {$finalCSV['Year']}{$finalCSV['Session']}{$finalCSV['Term']}";
            // dd($coursesFromCSV);

            $course = CourseSection::whereNotIn('id', $assignedCourseIds)
                ->where('prefix', $finalCSV['Prefix'])
                ->where('number', $finalCSV['Number'])
                ->where('section', $finalCSV['Section'])
                ->where('year', $finalCSV['Year'])
                ->where('session', $finalCSV['Session'])
                ->where('term', $finalCSV['Term'])
                ->get();

                $courses = $courses->merge($course);
        }

        // dd($courses, CourseSection::whereNotIn('id', $assignedCourseIds)->get());

        // return CourseSection::whereNotIn('id', $assignedCourseIds)->get();

        return $courses;
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
        return view('livewire.upload-file-form-assign-courses', [
            'availableCourses' => $this->getAvailableCourses(),
            'availableInstructors' => $this->getAvailableInstructors(),
        ]);
    }
}
