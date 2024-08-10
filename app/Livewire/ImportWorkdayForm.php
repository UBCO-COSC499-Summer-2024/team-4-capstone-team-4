<?php

namespace App\Livewire;

use App\Models\Area;
use App\Models\AreaPerformance;
use App\Models\CourseSection;
use App\Models\Department;
use App\Models\UserRole;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Rule;
use Livewire\Attributes\Session as OtherSession;
use Livewire\Component;
use Illuminate\Support\Facades\Session;
use PHPUnit\Framework\Constraint\IsTrue;

class ImportWorkdayForm extends Component
{
    #[Session]
    public $rows = [];
    public $duplicateCourses = [];

    public $showModal = false;
    public $showConfirmModal = false;
    public $courseExists = false;
    public $userConfirms = false;
    public $rowAmount = 0;

    /**
     * Initialize the component state.
     * Retrieve existing data from the session or initialize default rows.
     *
     * @return void
     */
    public function mount() {
        if(Session::has('workdayFormData')) {
            $this->rows = Session::get('workdayFormData');
        } else {
            $this->rows = [
                ['number' => '', 'area_id' => '', 'enroll_start' => '', 'enroll_end' => '', 'capacity' => '', 'session' => '', 'term' => '', 'year' => '', 'section' => '', 'room' => '', 'time_start' => '', 'time_end' => ''],
            ];
        }
    }

    /**
     * Define validation rules for the form inputs.
     *
     * @return array
     */
    public function rules() {
        $rules = [];

        foreach ($this->rows as $index => $row) {
            $rules["rows.{$index}.number"] = 'required|integer';
            $rules["rows.{$index}.section"] = 'required|string';
            $rules["rows.{$index}.area_id"] = 'required|integer';
            $rules["rows.{$index}.session"] = 'required|string';
            $rules["rows.{$index}.term"] = 'required|string';
            $rules["rows.{$index}.year"] = 'required|integer';
            $rules["rows.{$index}.room"] = 'required|string';
            $rules["rows.{$index}.time_start"] = ['required', 'string', 'regex:/^([0-1]?\d|2[0-3]):([0-5]\d)$/'];
            $rules["rows.{$index}.time_end"] = ['required', 'string', 'regex:/^([0-1]?\d|2[0-3]):([0-5]\d)$/'];
            $rules["rows.{$index}.enroll_start"] = 'required|integer|min:1|max:' . $row['capacity'] . '';
            $rules["rows.{$index}.enroll_end"] = 'required|integer|min:0|max:' . $row['capacity'] . '';
            $rules["rows.{$index}.capacity"] = 'required|integer|min:1|max:999';
        }

        return $rules;
    }

    /**
     * Define custom validation messages.
     *
     * @return array
     */
    public function messages() {
        $messages = [];

        foreach ($this->rows as $index => $row) {
            $messages["rows.{$index}.number.required"] = 'Please enter a course number';
            $messages["rows.{$index}.section.required"] = 'Please enter a course section';
            $messages["rows.{$index}.area_id.required"] = 'Please select a sub area';
            $messages["rows.{$index}.session.required"] = 'Please select a session';
            $messages["rows.{$index}.term.required"] = 'Please select a term';
            $messages["rows.{$index}.year.required"] = 'Please enter a year';
            $messages["rows.{$index}.room.required"] = 'Please enter a room';
            $messages["rows.{$index}.time_start.required"] = 'Please enter a time';
            $messages["rows.{$index}.time_end.required"] = 'Please enter a time';
            $messages["rows.{$index}.enroll_start.required"] = 'Please enter # of enrolled';
            $messages["rows.{$index}.enroll_end.required"] = 'Please enter # of enrolled';
            $messages["rows.{$index}.capacity.required"] = 'Please enter course capacity';

            $messages['rows.*.time_start.regex'] = 'The start time must be in military time format (HHMM or HH:MM).';
            $messages['rows.*.time_end.regex'] = 'The end time must be in military time format (HHMM or HH:MM).';

            $messages["rows.{$index}.area_id.integer"] = 'Must be a number';
            $messages["rows.{$index}.year.integer"] = 'Must be a number';
            $messages["rows.{$index}.enroll_start.integer"] = 'Must be a number';
            $messages["rows.{$index}.enroll_end.integer"] = 'Must be a number';
            $messages["rows.{$index}.dropped.integer"] = 'Must be a number';
            $messages["rows.{$index}.capacity.integer"] = 'Must be a number';
    
            $messages["rows.{$index}.number.min"] = 'Enter a number 1-999';
            $messages["rows.{$index}.duration.min"] = 'Enter a number 1-999';
            $messages["rows.{$index}.enroll_start.min"] = 'Enter a number 1-999';
            $messages["rows.{$index}.enroll_end.min"] = 'Enter a number 1-999';
            $messages["rows.{$index}.dropped.min"] = 'Enter a number 1-999';
            $messages["rows.{$index}.capacity.min"] = 'Must be greater than or equal to 1';
    
            $messages["rows.{$index}.number.max"] = 'Enter a number 1-999';
            $messages["rows.{$index}.duration.max"] = 'Enter a number 1-999';
            $messages["rows.{$index}.enroll_start.max"] = 'Must be lower than or equal to Capacity';
            $messages["rows.{$index}.enroll_end.max"] = 'Must be lower than or equal to Capacity';
            $messages["rows.{$index}.dropped.max"] = 'Enter a number 1-999';
            $messages["rows.{$index}.capacity.max"] = 'Enter a number 1-999';
        }
        return $messages;
    }

    /**
     * Save form data to the session whenever a property is updated.
     *
     * @param string $propertyName The name of the updated property.
     * @return void
     */
    public function updated($propertyName)
    {
        Session::put('workdayFormData', $this->rows);
    }

    /**
     * Add a new row to the form and save to session.
     *
     * @return void
     */
    public function addRow() {
        $this->resetValidation();

        $this->rows[] = [
            'number' => '', 'area_id' => '', 'enroll_start' => '', 'enroll_end' => '', 
            'capacity' => '', 'session' => '', 'term' => '', 'year' => '', 
            'section' => '', 'room' => '', 'time_start' => '', 'time_end' => ''
        ];
        Session::put('workdayFormData', $this->rows);
    }

    /**
     * Add multiple rows to the form based on the specified quantity.
     *
     * @return void
     */
    public function addManyRows() {
        for($i = 0; $i < $this->rowAmount; $i++) {
            $this->addRow();
        }
    }

    /**
     * Delete a specific row from the form and update session data.
     *
     * @param int $row The index of the row to delete.
     * @return void
     */
    public function deleteRow($row) {
        $this->resetValidation();

        unset($this->rows[$row]);
        $this->rows = array_values($this->rows);
        Session::put('workdayFormData', $this->rows);
    }

    /**
     * Delete multiple rows from the form based on the specified quantity.
     *
     * @return void
     */
    public function deleteManyRows() {
        for($i = 0; $i < $this->rowAmount; $i++) {
            $count = count($this->rows);
            $this->deleteRow($count - 1);
        }
    }

    /**
     * Validate that each row has a unique combination of fields.
     *
     * @return bool
     */
    protected function validateUniqueRows() {
        $uniqueCombinations = [];

        foreach ($this->rows as $index => $row) {
            $combination = implode('-', [
                $row['number'],
                $row['area_id'],
                $row['section'],
                $row['term'],
                $row['session'],
                $row['year'],
                $row['room'],
            ]);

            if (in_array($combination, $uniqueCombinations)) {
                $this->addError("rows.{$index}.duplicate", 'This combination of fields has already been entered. Please create a different course');
                return false;
            }

            $uniqueCombinations[] = $combination;
        }

        return true;
    }

    /**
     * Import the form data into the database.
     *
     * @return void
     */
    public function import() {
        $this->resetErrorBag();
        $this->resetValidation();

        if ($this->validateUniqueRows() && $this->validate()) {
            foreach ($this->rows as $row) {
                $existingCourse = CourseSection::where([
                    'number' => $row['number'],
                    'section' => $row['section'],
                    'area_id' => $row['area_id'],
                    'session' => $row['session'],
                    'term' => $row['term'],
                    'year' => $row['year'],
                ])->first();

                if (!$existingCourse) {
                    CourseSection::create([
                        'number' => $row['number'],
                        'section' => $row['section'],
                        'area_id' => $row['area_id'],
                        'session' => $row['session'],
                        'term' => $row['term'],
                        'year' => $row['year'],
                        'room' => $row['room'],
                        'time_start' => $row['time_start'],
                        'time_end' => $row['time_end'],
                        'capacity' => $row['capacity'],
                        'enroll_start' => $row['enroll_start'],
                        'enroll_end' => $row['enroll_end'],
                    ]);
                } else {
                    $this->courseExists = true;
                    $this->duplicateCourses[] = $row;
                }
            }

            $this->showConfirmModal = count($this->duplicateCourses) > 0;
            $this->emit('importCompleted');
        }
    }

    /**
     * Confirm the import operation by accepting duplicate entries.
     *
     * @return void
     */
    public function confirmImport() {
        foreach ($this->duplicateCourses as $row) {
            $existingCourse = CourseSection::where([
                'number' => $row['number'],
                'section' => $row['section'],
                'area_id' => $row['area_id'],
                'session' => $row['session'],
                'term' => $row['term'],
                'year' => $row['year'],
            ])->first();

            if ($existingCourse) {
                $existingCourse->update([
                    'capacity' => $row['capacity'],
                    'enroll_start' => $row['enroll_start'],
                    'enroll_end' => $row['enroll_end'],
                    'room' => $row['room'],
                    'time_start' => $row['time_start'],
                    'time_end' => $row['time_end'],
                ]);
            }
        }

        $this->showConfirmModal = false;
        $this->emit('importCompleted');
    }

    /**
     * Render the Livewire component view.
     *
     * @return \Illuminate\View\View
     */
    public function render() {
        $areas = Area::all();
        $departments = Department::all();
        return view('livewire.import-workday-form', [
            'areas' => $areas,
            'departments' => $departments,
        ]);
    }
}

