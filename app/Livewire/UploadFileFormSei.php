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

    public $isDuplicate = false;
    public $showModal = false;

    public function mount($finalCSVs) {
        $this->finalCSVs = $finalCSVs;

        foreach ($finalCSVs as $index => $finalCSV) {
            $this->rows[$index] = [
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

        // Session::put('seiFormData', $this->rows);
    }

    public function closeModal() {
        $this->showModal = false;
    }

    public function handleSubmit() {
        $this->checkDuplicate();
        
        $this->validate();

        foreach ($this->rows as $row) {
    
            SeiData::create([
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

            // $teach = Teach::where('course_section_id', $row['cid'])->first();
            
            // if($teach){
            //     $instructor_id = $teach->instructor_id;   
            //     $area_id = CourseSection::where('id', $row['cid'])->pluck('area_id');
            //     $dept_id = Area::where('id', $area_id)->pluck('dept_id');
            //     $year = CourseSection::find($row['cid'])->year;         
               
            //     InstructorPerformance::updatePerformance($instructor_id, $year);
            //     AreaPerformance::updateAreaPerformance($area_id, $year);
            //     DepartmentPerformance::updateDepartmentPerformance($dept_id, $year);
            // }

        }

        $this->showModal = true;

        $this->resetData();
        session()->flash('success', $this->showModal);
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
