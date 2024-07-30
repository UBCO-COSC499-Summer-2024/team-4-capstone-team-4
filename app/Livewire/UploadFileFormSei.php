<?php

namespace App\Livewire;

use App\Models\CourseSection;
use Livewire\Component;

class UploadFileFormSei extends Component
{
    public $rows = [];
    public $finalCSVs = [];

    public function mount($finalCSVs)
    {
        $this->finalCSVs = $finalCSVs;

        foreach ($finalCSVs as $index => $finalCSV) {
            $this->rows[$index] = [
                'course' => $finalCSV['Course'] ?? '',
                'cid' => $this->getCourseIdByName($finalCSV['Prefix'], $finalCSV['Number'], $finalCSV['Section'], $finalCSV['Session'], $finalCSV['Term'], $finalCSV['Year']),
                'q1' => $finalCSV['Q1'] ?? '',
                'q2' => $finalCSV['Q2'] ?? '',
                'q3' => $finalCSV['Q3'] ?? '',
                'q4' => $finalCSV['Q4'] ?? '',
                'q5' => $finalCSV['Q5'] ?? '',
                'q6' => $finalCSV['Q6'] ?? '',
            ];
        }

        // dd($this->rows);

        session()->forget('finalCSVs');
    }

    public function getCourseIdByName($prefix, $number, $section, $session, $term, $year) {
        $course_id = CourseSection::where('prefix', $prefix)
            ->where('number', $number)
            ->where('section', $section)
            ->where('session', $session)
            ->where('term', $term)
            ->where('year', $year)
            ->pluck('id');

        return $course_id;
    }

    public function render()
    {
        $courses = CourseSection::leftJoin('sei_data', 'course_sections.id', '=', 'sei_data.course_section_id')
        ->whereNull('sei_data.course_section_id')
        ->select('course_sections.*')
        ->orderBy('course_sections.year')
        ->orderBy('course_sections.session')
        ->orderBy('course_sections.term')
        ->orderBy('course_sections.prefix')
        ->orderBy('course_sections.number')
        ->orderBy('course_sections.section')
        ->get();

        // if(!$courses->isEmpty()) {
        //     $this->hasCourses = true;
        // } else {
        //     $this->hasCourses = false;
        // }

        return view('livewire.upload-file-form-sei', [
            'courses' => $courses,
        ]);
    }
}
