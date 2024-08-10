<?php

namespace App\Livewire;

use App\Models\Area;
use App\Models\AreaPerformance;
use App\Models\CourseSection;
use App\Models\DepartmentPerformance;
use App\Models\SeiData;
use App\Models\Teach;
use App\Models\InstructorPerformance;
use Livewire\Component;
use Illuminate\Support\Facades\Session;

class ImportSeiForm extends Component
{
    // Properties for storing form data, UI state, and validation results
    public $rows = [];
    public $filteredCourses;
    public $isDuplicate = false;
    public $showModal = false;
    public $showCourseModal = false;
    public $hasCourses = false;
    public $rowAmount = 0;
    public $selectedIndex = -1;
    public $searchTerm = '';

    /**
     * Initialize component data.
     * Populates `$rows` from session data if available, otherwise sets default values.
     */
    public function mount() {
        if(Session::has('seiFormData')) {
            $this->rows = Session::get('seiFormData');
        } else {
            $this->rows = [
                ['cid' => '', 'q1' => '', 'q2' => '', 'q3' => '', 'q4' => '', 'q5' => '', 'q6' => '', 'course' => ''],
            ];
        }
    }

    /**
     * Define validation rules for the form fields based on `$rows`.
     * @return array
     */
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

    /**
     * Define custom validation messages for the form fields based on `$rows`.
     * @return array
     */
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

    /**
     * Check for duplicate course selections in `$rows`.
     * Sets `$isDuplicate` flag and adds errors if duplicates are found.
     */
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

        Session::put('seiFormData', $this->rows);
    }

    /**
     * Add a new row to `$rows` and save it to session.
     */
    public function addRow() {
        $this->rows[] =  ['cid' => '', 'q1' => '', 'q2' => '', 'q3' => '', 'q4' => '', 'q5' => '', 'q6' => '', 'course' => ''];
        Session::put('seiFormData', $this->rows);
    }

    /**
     * Add multiple rows to `$rows` based on `$rowAmount`.
     */
    public function addManyRows() {
        for($i=0; $i<$this->rowAmount; $i++) {
            $this->addRow();
        }
    }

    /**
     * Delete a specific row from `$rows` and update session.
     * @param int $row The index of the row to delete.
     */
    public function deleteRow($row) {
        unset($this->rows[$row]);
        $this->rows = array_values($this->rows);

        $this->checkDuplicate();
        Session::put('seiFormData', $this->rows);
    }

    /**
     * Delete multiple rows from `$rows` based on `$rowAmount`.
     */
    public function deleteManyRows() {
        for($i=0; $i<$this->rowAmount; $i++) {
            $count = count($this->rows);
            $this->deleteRow($count-1);
        }
    }

    /**
     * Open the course modal and set the selected index.
     * @param int $index The index of the row to select.
     */
    public function openCourseModal($index) {
        $this->selectedIndex = $index;
        $this->showCourseModal = true;
    }

    /**
     * Close the course modal.
     */
    public function closeCourseModal() {
        $this->showCourseModal = false;
    }

    /**
     * Close the general modal.
     */
    public function closeModal() {
        $this->showModal = false;
    }

    /**
     * Retrieve available courses that are not yet associated with SEI data.
     * @return \Illuminate\Database\Eloquent\Collection
     */
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

    /**
     * Select a course and update the row at the specified index.
     * @param int $id The ID of the selected course.
     * @param string $prefix The course prefix.
     * @param string $number The course number.
     * @param string $section The course section.
     * @param string $year The course year.
     * @param string $session The course session.
     * @param string $term The course term.
     * @param int $selectedIndex The index of the row to update.
     */
    public function selectCourse($id, $prefix, $number, $section, $year, $session, $term, $selectedIndex) {
        $this->rows[$selectedIndex]['cid'] = $id;
        $this->rows[$selectedIndex]['course'] = "{$prefix} {$number} {$section} {$year} {$session} {$term}";
        $this->closeCourseModal();
        $this->checkDuplicate();
    }

    /**
     * Filter available courses based on search term.
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getFilteredCourses() {
        if ($this->searchTerm !== '') {
            return $this->getAvailableCourses()->filter(function($course) {
                return str_contains(strtolower($course->course_full_name), strtolower($this->searchTerm));
            });
        }

        return collect();
    }

    /**
     * Render the component view.
     * @return \Illuminate\View\View
     */
    public function render() {
        return view('livewire.import-sei-form', [
            'courses' => $this->getFilteredCourses(),
        ]);
    }
}

