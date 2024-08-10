<?php

namespace App\Livewire;

use App\Models\Area;
use App\Models\AreaPerformance;
use App\Models\CourseSection;
use App\Models\DepartmentPerformance;
use App\Models\SeiData;
use App\Models\Teach;
use App\Models\InstructorPerformance;
use Livewire\Attributes\Rule;
use Livewire\Attributes\Session as OtherSession;
use Livewire\Component;
use Illuminate\Support\Facades\Session;

use function PHPUnit\Framework\isEmpty;

class ImportSeiForm extends Component
{
    public $rows = [];
    public $filteredCourses;

    public $isDuplicate = false;
    public $showModal = false;
    public $showCourseModal = false;
    public $hasCourses = false;

    public $rowAmount = 0;

    public $selectedIndex = -1;
    public $searchTerm = '';

    public function mount() {
        if(Session::has('seiFormData')) {
            $this->rows = Session::get('seiFormData');
        } else {
            $this->rows = [
                ['cid' => '', 'q1' => '', 'q2' => '', 'q3' => '', 'q4' => '', 'q5' => '', 'q6' => '', 'course' => ''],
            ];
        }

        // dd($this->rows);
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

    // Ensure that two courses have not both been selected on the current page
    public function checkDuplicate() {
        $this->resetValidation();
        $selectedCourses = [];
        $duplicateIndices = [];

        foreach ($this->rows as $index => $row) {
            if ($row['cid'] !== "" && in_array($row['cid'], $selectedCourses)) {
                $duplicateIndices[] = $index;
            } else {
                $selectedCourses[] = $row['cid'];
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

        Session::put('seiFormData', $this->rows);
    }

    public function addRow() {
        $this->rows[] =  ['cid' => '', 'q1' => '', 'q2' => '', 'q3' => '', 'q4' => '', 'q5' => '', 'q6' => '', 'course' => ''];
        Session::put('seiFormData', $this->rows);
    }

    public function addManyRows() {
        for($i=0; $i<$this->rowAmount; $i++) {
            $this->addRow();
        }
    }

    public function deleteRow($row) {
        unset($this->rows[$row]);
        $this->rows = array_values($this->rows);

        $this->checkDuplicate();
        Session::put('seiFormData', $this->rows);
    }

    public function deleteManyRows() {
        for($i=0; $i<$this->rowAmount; $i++) {
            $count = count($this->rows);
            $this->deleteRow($count-1);
        }
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

    // Only returns courses that have not yet been assigned SEI data
    public function getAvailableCourses() {
        return CourseSection::leftJoin('sei_data', 'course_sections.id', '=', 'sei_data.course_section_id')
        ->whereNull('sei_data.course_section_id')
        ->select('course_sections.*')
        ->orderBy('course_sections.year')
        ->orderBy('course_sections.session')
        ->orderBy('course_sections.term')
        ->orderBy('course_sections.prefix')
        ->orderBy('course_sections.number')
        ->orderBy('course_sections.section')
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

        $this->checkDuplicate();
        
        $this->validate();
    
        foreach ($this->rows as $row) {
    
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

        $this->rows = [
            ['cid' => '', 'q1' => '', 'q2' => '', 'q3' => '', 'q4' => '', 'q5' => '', 'q6' => '', 'course' => ''],
        ];
        
        Session::forget('seiFormData');

        session()->flash('success', 'Successfully Created!');

        if(session()->has('success')) {
            $this->showModal = true;
        }

    }

    public function render()
    {

  
        $this->checkDuplicate();

        $availableCourses = $this->getAvailableCourses();

        if(!$availableCourses->isEmpty()) {
            $this->hasCourses = true;
        } else {
            $this->hasCourses = false;
        }

        // dd($courses);
        // dd($this->rows);

        
        $this->updateSearch();

        return view('livewire.import-sei-form', [
            "availableCourses" => $availableCourses,
            "filteredCourses" => $this->filteredCourses,
            "selectedIndex" => $this->selectedIndex,
        ]);
    }
}
