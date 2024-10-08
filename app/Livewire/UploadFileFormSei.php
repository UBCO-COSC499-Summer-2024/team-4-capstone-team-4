<?php

namespace App\Livewire;

use App\Models\Area;
use App\Models\AreaPerformance;
use App\Models\CourseSection;
use App\Models\DepartmentPerformance;
use App\Models\InstructorPerformance;
use App\Models\SeiData;
use App\Models\Teach;
use Livewire\Component;

class UploadFileFormSei extends Component
{
    public $rows = [];
    public $finalCSVs = [];
    public $filteredCourses;


    public $isDuplicate = false;
    public $showModal = false;
    public $showCourseModal = false;

    public $selectedIndex = -1;
    public $searchTerm = '';

    public function mount($finalCSVs) {
        $this->finalCSVs = $finalCSVs;

        foreach ($finalCSVs as $index => $finalCSV) {
            $prefix = $finalCSV['Prefix'] ?? null;
            $number = $finalCSV['Number'] ?? null;
            $section = $finalCSV['Section'] ?? null;
            $session = $finalCSV['Session'] ?? null;
            $term = $finalCSV['Term'] ?? null;
            $year = $finalCSV['Year'] ?? null;

            if (isset($prefix, $number, $section, $session, $term, $year)) {


                $this->rows[$index] = [
                    'cid' => $this->getCourseIdByName($prefix, $number, $section, $session, $term, $year),
                    'q1' => $finalCSV['Q1'] ?? '',
                    'q2' => $finalCSV['Q2'] ?? '',
                    'q3' => $finalCSV['Q3'] ?? '',
                    'q4' => $finalCSV['Q4'] ?? '',
                    'q5' => $finalCSV['Q5'] ?? '',
                    'q6' => $finalCSV['Q6'] ?? '',
                    'course' => '',
                ];

                $courses = $this->getAvailableCourses();
                foreach($courses as $course) {
                    if("{$course->prefix} {$course->number} {$course->section}" . ' - ' . "{$course->year}{$course->session}{$course->term}" ==  $prefix . ' ' . $number . ' ' . $section . ' - ' . $year . $session . $term) {
                        //get user id
                        $this->rows[$index]['course'] = "$course->prefix" . " " . "$course->number" . " " . $course->section . " - " . $course->year . $course->session . $course->term;
                        $this->rows[$index]['cid'] = $course->id;

                    }
                }  
            }
        }

        // dd($this->rows);    
        $this->checkDuplicate();
        session()->forget('finalCSVs');
    }

    public function rules() {
        $rules = [];

        foreach ($this->rows as $index => $row) {
            $rules["rows.{$index}.cid"] = 'required|integer';
            $rules["rows.{$index}.q1"] = 'required|numeric|min:1|max:5';
            $rules["rows.{$index}.q2"] = 'required|numeric|min:1|max:5';
            $rules["rows.{$index}.q3"] = 'required|numeric|min:1|max:5';
            $rules["rows.{$index}.q4"] = 'required|numeric|min:1|max:5';
            $rules["rows.{$index}.q5"] = 'required|numeric|min:1|max:5';
            $rules["rows.{$index}.q6"] = 'required|numeric|min:1|max:5';
        }

        return $rules;
    }

    public function messages() {
        $messages = [];

        foreach ($this->rows as $index => $row) {
                $messages["rows.{$index}.cid.required"] = 'Please select a course';
                $messages["rows.{$index}.q1.required"] = 'Missing entry for q1';
                $messages["rows.{$index}.q2.required"] = 'Missing entry for q2';
                $messages["rows.{$index}.q3.required"] = 'Missing entry for q3';
                $messages["rows.{$index}.q4.required"] = 'Missing entry for q4';
                $messages["rows.{$index}.q5.required"] = 'Missing entry for q5';
                $messages["rows.{$index}.q6.required"] = 'Missing entry for q6';
        
                $messages["rows.{$index}.q1.numeric"] = 'Must be a number';
                $messages["rows.{$index}.q2.numeric"] = 'Must be a number';
                $messages["rows.{$index}.q3.numeric"] = 'Must be a number';
                $messages["rows.{$index}.q4.numeric"] = 'Must be a number';
                $messages["rows.{$index}.q5.numeric"] = 'Must be a number';
                $messages["rows.{$index}.q6.numeric"] = 'Must be a number';
        
                $messages["rows.{$index}.q1.min"] = 'Enter a number 1-5';
                $messages["rows.{$index}.q2.min"] = 'Enter a number 1-5';
                $messages["rows.{$index}.q3.min"] = 'Enter a number 1-5';
                $messages["rows.{$index}.q4.min"] = 'Enter a number 1-5';
                $messages["rows.{$index}.q5.min"] = 'Enter a number 1-5';
                $messages["rows.{$index}.q6.min"] = 'Enter a number 1-5';
    
                $messages["rows.{$index}.q1.max"] = 'Enter a number 1-5';
                $messages["rows.{$index}.q2.max"] = 'Enter a number 1-5';
                $messages["rows.{$index}.q3.max"] = 'Enter a number 1-5';
                $messages["rows.{$index}.q4.max"] = 'Enter a number 1-5';
                $messages["rows.{$index}.q5.max"] = 'Enter a number 1-5';
                $messages["rows.{$index}.q6.max"] = 'Enter a number 1-5';
        }
        return $messages;
    }

    public function deleteRow($row) {
        $this->resetValidation();
        $this->checkDuplicate();

        unset($this->rows[$row]);
        $this->rows = array_values($this->rows);
    }

    public function getCourseIdByName($prefix, $number, $section, $session, $term, $year) {
        $course_id = CourseSection::where('prefix', $prefix)
            ->where('number', $number)
            ->where('section', $section)
            ->where('session', $session)
            ->where('term', $term)
            ->where('year', $year)
            ->pluck('id')
            ->first();

        if($course_id != null) {
            return $course_id;
        }
        return;
    }

    public function resetData()
    {
        session()->forget('finalCSVs');
        $this->finalCSVs = [];
        $this->rows = [];
    }

    // Ensure that two courses have not both been selected on the current page
    public function checkDuplicate() {
        $this->resetValidation();
        $selectedCourses = [];
        $duplicateIndices = [];

        foreach ($this->rows as $index => $row) {
            if(!empty($row['course'])) {
                if ($row['cid'] !== "" && in_array($row['cid'], $selectedCourses)) {
                    $duplicateIndices[] = $index;
                } else {
                    $selectedCourses[] = $row['cid'];
                }
            }
        }

        if (!empty($duplicateIndices)) {
            $this->isDuplicate = true;
            foreach ($duplicateIndices as $index) {
                $this->addError("rows.{$index}.cid", 'This course has already been selected.');
            }
        } else {
            $this->isDuplicate = false;
        }

        // dd($duplicateIndices, $selectedCourses);

        // Session::put('seiFormData', $this->rows);
    }

    public function openCourseModal($index) {
        $this->selectedIndex = $index;
        // dd($this->selectedIndex);
        $this->showCourseModal = true;
    }

    public function closeCourseModal() {
        $this->showCourseModal = false;
    }

    public function closeModal() {
        $this->showModal = false;
    }

    public function getAvailableCourses() {
        return CourseSection::leftJoin('sei_data', 'course_sections.id', '=', 'sei_data.course_section_id')
        ->whereNull('sei_data.course_section_id')
        ->select('course_sections.*')
        ->orderByDesc('course_sections.year')
        ->orderBy('course_sections.prefix')
        ->orderBy('course_sections.number')
        ->orderBy('course_sections.section')
        ->orderBy('course_sections.session')
        ->orderBy('course_sections.term')
        ->get();
    }

    public function selectCourse($id, $prefix, $number, $section, $year, $session, $term, $selectedIndex) {
        // dd('clicked');
        $this->rows[$selectedIndex]["cid"] = $id;
        $this->rows[$selectedIndex]["course"] = $prefix . ' ' . $number . ' ' . $section . ' - ' . $year . $session . $term;

        $this->closeCourseModal();
        $this->checkDuplicate();
  
        // dd($this->rows);
    }

    public function updateSearch() {
        $availableCourses = $this->getAvailableCourses();

        $this->filteredCourses = $availableCourses->filter(function ($course) {
            $course = $course->prefix . ' ' . $course->number . ' ' . $course->section . ' - ' . $course->year . $course->session . $course->term;
            return stripos($course, $this->searchTerm) !== false;
        });
    }

    public function handleSubmit() {
        // dd($this->rows);
        $this->checkDuplicate();
        
        $this->validate();

        foreach ($this->rows as $row) {
            if(!empty($row['course'])) {
                // dd('not empty', $row);
                $sei = SeiData::create([
                    'course_section_id' => $row['cid'],
                    'questions' => json_encode([
                        'q1' => $row['q1'],
                        'q2' => $row['q2'],
                        'q3' => $row['q3'],
                        'q4' => $row['q4'],
                        'q5' => $row['q5'],
                        'q6' => $row['q6'],
                    ]),
                ]);
            }

            $course = CourseSection::where('id', $row['cid'])->first();
            $teach = Teach::where('course_section_id', $row['cid'])->first();
            
           // If the course is already being taught, update the performances with SEI data
            if($teach){
                $instructor_id = $teach->instructor_id;   
                $area_id = CourseSection::where('id', $row['cid'])->pluck('area_id');
                $dept_id = Area::where('id', $area_id)->pluck('dept_id');
                $year = CourseSection::find($row['cid'])->year;         
               
                InstructorPerformance::updatePerformance($instructor_id, $year);
                AreaPerformance::updateAreaPerformance($area_id, $year);
                DepartmentPerformance::updateDepartmentPerformance($dept_id, $year);
            }

            $sei->log_audit('Add SEI Data', ['operation_type' => 'CREATE', 'new_value' => json_encode($sei->getAttributes())], 'Add SEI Data to  ' . $course->prefix . ' ' . $course->number . ' ' . $course->section . '-' . $course->year . $course->session . $course->term);

        }

        $this->showModal = true;

        $this->resetData();
        session()->flash('success', $this->showModal);
    }

    public function render()
    {
        $this->checkDuplicate();

        // if(!$courses->isEmpty()) {
        //     $this->hasCourses = true;
        // } else {
        //     $this->hasCourses = false;
        // }
        
        $this->updateSearch();
        // dd($this->filteredCourses);

        return view('livewire.upload-file-form-sei', [
            'availableCourses' => $this->getAvailableCourses(),
            "filteredCourses" => $this->filteredCourses,
            "selectedIndex" => $this->selectedIndex,
        ]);
    }
}
