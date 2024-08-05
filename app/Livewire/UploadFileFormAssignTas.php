<?php

namespace App\Livewire;

use App\Models\CourseSection;
use App\Models\Teach;
use App\Models\TeachingAssistant;
use App\Models\User;
use Livewire\Component;

class UploadFileFormAssignTas extends Component
{
    public $finalCSVs = [];
    public $assignments = [];

    public $showModal = false;

    public function mount($finalCSVs)
    {
        $this->finalCSVs = $finalCSVs;

        // dd($this->finalCSVs);

        foreach ($this->finalCSVs as $finalCSV) {
            $course = CourseSection::where('prefix', $finalCSV['Prefix'])
                ->where('number', $finalCSV['Number'])
                ->where('section', $finalCSV['Section'])
                ->where('year', $finalCSV['Year'])
                ->where('session', $finalCSV['Session'])
                ->where('term', $finalCSV['Term'])
                ->first();

            if ($course) {
                foreach ($finalCSV['TAs'] as $ta) {
                    $ta_id = TeachingAssistant::where("name", $ta)->value('id');
                    $this->assignments[] = [
                        'course_section_id' => $course->id,
                        'prefix' => $course->prefix,
                        'number' => $course->number,
                        'section' => $course->section,
                        'year' => $course->year,
                        'session' => $course->session,
                        'term' => $course->term,
                        'ta_id' => $ta_id,
                        'ta' => $ta,
                    ];
                }
            }
        }

        // dd($this->assignments);
    }

    public function getAvailableCourses()
    {
        $courses = collect();
        // $assignedCourseIds = Teach::pluck('course_section_id');

        foreach ($this->finalCSVs as $finalCSV) {
            $course = CourseSection::where('prefix', $finalCSV['Prefix'])
                ->where('number', $finalCSV['Number'])
                ->where('section', $finalCSV['Section'])
                ->where('year', $finalCSV['Year'])
                ->where('session', $finalCSV['Session'])
                ->where('term', $finalCSV['Term'])
                ->get();

            $courses = $courses->merge($course);
        }

        return $courses;
    }

    public function getAvailableTas()
    {
        return TeachingAssistant::get();
    }

    public function render()
    {
        // dd($this->finalCSVs);

        // dd($this->getAvailableTas());


        return view('livewire.upload-file-form-assign-tas', [
            'availableCourses' => $this->getAvailableCourses(),
            'availableTas' => $this->getAvailableTas(),
        ]);
    }
}
