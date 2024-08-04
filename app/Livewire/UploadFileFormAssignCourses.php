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
                'prefix' => $course->prefix,
                'number' => $course->number,
                'section' => $course->section,
                'year' => $course->year,
                'session' => $course->session,
                'term' => $course->term,
                'instructor_id' => '',
                'instructor' => '',
                'year' => $course->year,
            ];
        })->toArray();

        foreach($finalCSVs as $index => $finalCSV) {
            foreach($this->assignments as $assignment) {
                if( $finalCSV['Prefix'] == $assignment['prefix'] &&
                    $finalCSV['Number'] == $assignment['number'] &&
                    $finalCSV['Section'] == $assignment['section'] &&
                    $finalCSV['Year'] == $assignment['year'] &&
                    $finalCSV['Session'] == $assignment['session'] &&
                    $finalCSV['Term'] == $assignment['term']
                ){
                    $this->assignments[$index]['instructor'] = $finalCSV['Instructor'];
                }

                $instructor_id = User::whereRAW("CONCAT(firstname, ' ', lastname) = ?", $finalCSV['Instructor'])->pluck('id');
                $this->assignments[$index]['instructor_id'] = $instructor_id;
            }

        }
        // dd($this->assignments);
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
        // dd($this->assignments, $this->finalCSVs);

        return view('livewire.upload-file-form-assign-courses', [
            'availableCourses' => $this->getAvailableCourses(),
            'availableInstructors' => $this->getAvailableInstructors(),
        ]);
    }
}
