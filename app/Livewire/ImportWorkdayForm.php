<?php

namespace App\Livewire;

use App\Models\Area;
use App\Models\AreaPerformance;
use App\Models\CourseSection;;
use Livewire\Attributes\Rule;
use Livewire\Attributes\Session as OtherSession;
use Livewire\Component;
use Illuminate\Support\Facades\Session;

class ImportWorkdayForm extends Component
{
    #[Session]
    public $rows = [];

    public $showModal = false;
    public $courseExists = false;

    public function mount() {
        if(Session::has('workdayFormData')) {
            $this->rows = Session::get('workdayFormData');
        } else {
            $this->rows = [
                ['number' => '', 'area_id' => '', 'enrolled' => '', 'dropped' => '', 'capacity' => '', 'session' => '', 'term' => '', 'year' => '', 'section' => ''],
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
            $rules["rows.{$index}.enrolled"] = 'required|integer|min:1|max:' . $row['capacity'] . '';
            $rules["rows.{$index}.dropped"] = 'required|integer|min:0|max:999';
            $rules["rows.{$index}.capacity"] = 'required|integer|min:' . $row['enrolled'] . '|max:999';
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
                $messages["rows.{$index}.enrolled.required"] = 'Please enter # of enrolled';
                $messages["rows.{$index}.dropped.required"] = 'Please enter # of dropped';
                $messages["rows.{$index}.capacity.required"] = 'Please enter course capacity';

                $messages["rows.{$index}.area_id.integer"] = 'Must be a number';
                $messages["rows.{$index}.year.integer"] = 'Must be a number';
                $messages["rows.{$index}.enrolled.integer"] = 'Must be a number';
                $messages["rows.{$index}.dropped.integer"] = 'Must be a number';
                $messages["rows.{$index}.capacity.integer"] = 'Must be a number';
        
                $messages["rows.{$index}.number.min"] = 'Enter a number 1-999';
                $messages["rows.{$index}.duration.min"] = 'Enter a number 1-999';
                $messages["rows.{$index}.enrolled.min"] = 'Enter a number 1-999';
                $messages["rows.{$index}.dropped.min"] = 'Enter a number 1-999';
                $messages["rows.{$index}.capacity.min"] = 'Must be greater than or equal to Enrolled';
    
                $messages["rows.{$index}.number.max"] = 'Enter a number 1-999';
                $messages["rows.{$index}.duration.max"] = 'Enter a number 1-999';
                $messages["rows.{$index}.enrolled.max"] = 'Must be lower than or equal to Capacity';
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

    public function addRow() {
        $this->resetValidation();

        $this->rows[] =  ['number' => '', 'area_id' => '', 'enrolled' => '', 'dropped' => '', 'capacity' => '', 'session' => '', 'term' => '', 'year' => '', 'section' => ''];
        Session::put('workdayFormData', $this->rows);
    }

    public function deleteRow($row) {
        $this->resetValidation();

        unset($this->rows[$row]);
        $this->rows = array_values($this->rows);
        Session::put('workdayFormData', $this->rows);
    }

    protected function validateUniqueRows()
    {
        $uniqueCombinations = [];

        foreach ($this->rows as $index => $row) {
            $combination = implode('-', [
                $row['number'],
                $row['area_id'],
                $row['section'],
                $row['term'],
                $row['session'],
                $row['year']
            ]);

            if (in_array($combination, $uniqueCombinations)) {
                $this->addError("rows.{$index}.duplicate", 'This combination of fields as already been entered. Please create a different course');
                return false;
            }

            $uniqueCombinations[] = $combination;
        }

        return true;
    }

    public function handleSubmit() {
        $this->courseExists = false;

        // dd($this->rows);
        $this->validate();

        if (!$this->validateUniqueRows()) {
            return;
        }
    
        foreach ($this->rows as $index => $row) {
            $prefix = '';
            // dd($row);

            
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
                ->first();

            if ($course != null) {
                $this->addError("rows.{$index}.exists", 'This course has already been created. You can view it and edit it on the Courses page');
                $this->courseExists = true;
            } else {
               
            }
            
            // AreaPerformance::updateAreaPerformance($row['year']);
            
        }

        if(!$this->courseExists) {
            foreach($this->rows as $index => $row) {
                CourseSection::create([
                    'prefix' => $prefix,
                    'number' => $row['number'],
                    'section' => $row['section'], 
                    'area_id' => $row['area_id'], 
                    'session' => $row['session'], 
                    'term' => $row['term'], 
                    'year' => $row['year'], 
                    'enrolled' => $row['enrolled'], 
                    'dropped' => $row['dropped'], 
                    'capacity' => $row['capacity'],        
                ]);

                $this->rows = [
                    ['number' => '', 'area_id' => '', 'enrolled' => '', 'dropped' => '', 'capacity' => '', 'session' => '', 'term' => '', 'year' => '', 'section' => ''],
                ];

                Session::forget('workdayFormData');

                $this->showModal = true;

                session()->flash('success', $this->showModal);
            }
        }

        // if (session()->has('success')) {
        //     $this->showModal = true;
        // }

    }

    public function closeModal() {
        $this->showModal = false;
    }


    public function render()
    {
        $areas = Area::all();

        return view('livewire.import-workday-form', [
            'areas' => $areas,
        ]);

        
    }
}
