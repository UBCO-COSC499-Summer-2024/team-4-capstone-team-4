<?php

namespace App\Livewire;

use App\Models\Area;
use App\Models\AreaPerformance;
use App\Models\CourseSection;
use App\Models\Department;
use App\Models\User;
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

    public function mount() {
        if(Session::has('workdayFormData')) {
            $this->rows = Session::get('workdayFormData');
        } else {
            $this->rows = [
                ['number' => '', 'area_id' => '', 'enroll_start' => '', 'enroll_end' => '', 'capacity' => '', 'session' => '', 'term' => '', 'year' => '', 'section' => '', 'room' => '', 'time_start' => '', 'time_end' => ''],
            ];
        }
    }

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

    public function updated($propertyName)
    {
        // Save form data to session
        Session::put('workdayFormData', $this->rows);
    }

    //Form functionality for bulk import  
    public function addRow() {
        $this->resetValidation();

        $this->rows[] =   ['number' => '', 'area_id' => '', 'enroll_start' => '', 'enroll_end' => '', 'capacity' => '', 'session' => '', 'term' => '', 'year' => '', 'section' => '', 'room' => '', 'time_start' => '', 'time_end' => ''];
        Session::put('workdayFormData', $this->rows);
    }

    public function addManyRows() {
        for($i=0; $i<$this->rowAmount; $i++) {
            $this->addRow();
        }
    }

    public function deleteRow($row) {
        $this->resetValidation();

        unset($this->rows[$row]);
        $this->rows = array_values($this->rows);
        Session::put('workdayFormData', $this->rows);
    }

    public function deleteManyRows() {
        for($i=0; $i<$this->rowAmount; $i++) {
            $count = count($this->rows);
            $this->deleteRow($count-1);
        }
    }

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

    public function userConfirmDuplicate() {
        $this->userConfirms = true;
        $this->showConfirmModal = false;
    }

    
    public function closeModal() {
        $this->showModal = false;
    }

    public function closeConfirmModal() {
        $this->showConfirmModal = false;
        $this->duplicateCourses = [];
    }

    public function handleSubmit() {
        $user = User::find((int) Auth::id())->getName();
        $this->courseExists = false;

        // dd($this->rows);
        $this->validate();

        if (!$this->validateUniqueRows()) {
            return;
        }
    
        foreach ($this->rows as $index => $row) {
            $prefix = '';
            // dd($row);

            //manually set prefix based on area_id. Will have to refactor if other areas or departments are added
            
            switch ($row['area_id']) {
                case 1:
                    $prefix = 'COSC';
                    break;
                case 2:
                    $prefix = 'MATH';
                    break;
                case 3:
                    $prefix = 'PHYS';
                    break;
                case 4:
                    $prefix = 'STAT';
                    break;
            }

            $course = CourseSection::where('prefix', $prefix)
                ->where('number', $row['number'])
                ->where('area_id', $row['area_id'])
                ->where('year', $row['year'])
                ->where('term', $row['term'])
                ->where('session', $row['session'])
                ->where('section' , $row['section'])
                ->where('room', $row['room'])
                ->first();

            if ($course != null) {
                $this->addError("rows.{$index}.exists", 'Warning: This course has already been created. Saving will overwrite the existing data. Delete or update the row to prevent this action');
                $this->courseExists = true;
                $this->duplicateCourses[] = $course;
            }
        
        }

        if($this->courseExists && !$this->userConfirms) {
            $this->showConfirmModal = true;
        } else if (!$this->courseExists) {
            $this->userConfirms = true;
        }

        //duplicate course but user has clicked "I Understand"
        if($this->userConfirms) {
            foreach($this->rows as $index => $row) {
                $dropped = 0;
                $prefix = '';
            
                switch ($row['area_id']) {
                    case 1:
                        $prefix = 'COSC';
                        break;
                    case 2:
                        $prefix = 'MATH';
                        break;
                    case 3:
                        $prefix = 'PHYS';
                        break;
                    case 4:
                        $prefix = 'STAT';
                        break;
                }
                
                $dropped = CourseSection::calculateDropped($row['enroll_start'], $row['enroll_end']);

                $courseExists = CourseSection::where('prefix', $prefix)
                ->where('number', $row['number'])
                ->where('area_id', $row['area_id'])
                ->where('year', $row['year'])
                ->where('term', $row['term'])
                ->where('session', $row['session'])
                ->where('section' , $row['section'])
                ->where('room', $row['room'])
                ->first();

                $course = CourseSection::updateOrCreate([
                    'number' => $row['number'],
                    'section' => $row['section'],
                    'area_id' => $row['area_id'],
                    'year' => $row['year'],
                    'session' => $row['session'],
                    'term' => $row['term'], 
                    'room' => $row['room'],    
                ], 
                [
                    'prefix' => $prefix,
                    'number' => $row['number'],
                    'section' => $row['section'], 
                    'area_id' => $row['area_id'], 
                    'session' => $row['session'], 
                    'term' => $row['term'], 
                    'year' => $row['year'], 
                    'room' => $row['room'], 
                    'time_start' => $row['time_start'], 
                    'time_end' => $row['time_end'], 
                    'enroll_start' => $row['enroll_start'], 
                    'enroll_end' => $row['enroll_end'], 
                    'dropped' => $dropped,
                    'capacity' => $row['capacity'], 
                    'archived' => false,  
                ]);

                $this->rows = [
                    ['number' => '', 'area_id' => '', 'enroll_start' => '', 'enroll_end' => '', 'capacity' => '', 'session' => '', 'term' => '', 'year' => '', 'section' => '', 'room' => '', 'time_start' => '', 'time_end' => ''],
                ];

                if($courseExists) {
                    $course->log_audit('Update Course Section', ['operation_type' => 'UPDATE', 'old_value' => json_encode($courseExists->getAttributes()) ,'new_value' => json_encode($course->getAttributes())], 'Update Course Section ' . $course->prefix . ' ' . $course->number . ' ' . $course->section . '-' . $course->year . $course->session . $course->term);
                } else if (!$courseExists) {
                    $course->log_audit('Add Course Section', ['operation_type' => 'CREATE', 'new_value' => json_encode($course->getAttributes())], 'Created Course Section ' . $course->prefix . ' ' . $course->number . ' ' . $course->section . '-' . $course->year . $course->session . $course->term);
                }
                
                Session::forget('workdayFormData');

                $this->showModal = true;
                $this->userConfirms = false;
                $this->duplicateCourses = [];
                $this->resetValidation();

                session()->flash('success', $this->showModal);
            }
        }
    }

    public function render()
    {   

        $user = Auth::user();
        $dept_id = null;

        if ($user) {
            $userRole = UserRole::where('user_id', $user->id)->first();
            if ($userRole) {
                $dept_id = $userRole->department_id;
            } else {
                // Handle the case where there is no UserRole for the user
                $dept_id = null; 
            }
        }

        $areas = Area::where('dept_id', $dept_id)->get();

        return view('livewire.import-workday-form', [
            'areas' => $areas,
            'duplicate_courses' => $this->duplicateCourses,
        ]);

        
    }
}
