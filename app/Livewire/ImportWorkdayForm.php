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
    public $testCid = 123456;

    #[Session]
    public $rows = [];

    public $showModal = false;

    public function mount() {
        if(Session::has('workdayFormData')) {
            $this->rows = Session::get('workdayFormData');
        } else {
            $this->rows = [
                ['course_name' => '', 'area_id' => '', 'enrolled' => '', 'dropped' => '', 'capacity' => '', 'session' => '', 'term' => '', 'year' => '', 'section' => ''],
            ];
        }
    }

    public function rules() {
        $rules = [];


        foreach ($this->rows as $index => $row) {
            $rules["rows.{$index}.course_name"] = 'required|min:1|max:999';
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
                $messages["rows.{$index}.course_name.required"] = 'Please enter a course';
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
        
                $messages["rows.{$index}.course_name.min"] = 'Enter a number 1-999';
                $messages["rows.{$index}.duration.min"] = 'Enter a number 1-999';
                $messages["rows.{$index}.enrolled.min"] = 'Enter a number 1-999';
                $messages["rows.{$index}.dropped.min"] = 'Enter a number 1-999';
                $messages["rows.{$index}.capacity.min"] = 'Must be greater than or equal to Enrolled';
    
                $messages["rows.{$index}.course_name.max"] = 'Enter a number 1-999';
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
        $this->rows[] =  ['course_name' => '', 'area_id' => '', 'enrolled' => '', 'dropped' => '', 'capacity' => '', 'session' => '', 'term' => '', 'year' => '', 'section' => ''];
        Session::put('workdayFormData', $this->rows);
    }

    public function deleteRow($row) {
        unset($this->rows[$row]);
        $this->rows = array_values($this->rows);
        Session::put('workdayFormData', $this->rows);
    }

    public function handleSubmit() {

        // dd($this->rows);
        $this->validate();
    
        
        foreach ($this->rows as $row) {
            // dd($row);

            CourseSection::create([
                'name' => $row['course_name'],
                'section' => $row['section'], 
                'area_id' => $row['area_id'], 
                'session' => $row['session'], 
                'term' => $row['term'], 
                'year' => $row['year'], 
                'enrolled' => $row['enrolled'], 
                'dropped' => $row['dropped'], 
                'capacity' => $row['capacity'],        
            ]);

            // AreaPerformance::updateAreaPerformance($row['year']);

        }

        $this->rows = [
            ['course_name' => '', 'area_id' => '', 'enrolled' => '', 'dropped' => '', 'capacity' => '', 'session' => '', 'term' => '', 'year' => '', 'section' => ''],
        ];

        Session::forget('workdayFormData');

        $this->showModal = true;

        session()->flash('success', $this->showModal);

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
