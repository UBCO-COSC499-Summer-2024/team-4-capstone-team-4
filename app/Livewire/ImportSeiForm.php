<?php

namespace App\Livewire;

use App\Models\CourseSection;
use App\Models\SeiData;
use Livewire\Attributes\Rule;
use Livewire\Attributes\Session as OtherSession;
use Livewire\Component;
use Illuminate\Support\Facades\Session;

class ImportSeiForm extends Component
{
    public $testCid = 123456;
    public $rows = [];

    public $showModal = false;

    public function mount() {

        if(Session::has('seiFormData')) {
            $this->rows = Session::get('seiFormData');
        } else {
            $this->rows = [
                ['cid' => '', 'q1' => '', 'q2' => '', 'q3' => '', 'q4' => '', 'q5' => '', 'q6' => ''],
            ];
        }
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

    public function updated($propertyName)
    {
        // Save form data to session
        Session::put('seiFormData', $this->rows);
    }

    public function addRow() {
        $this->rows[] =  ['cid' => '', 'q1' => '', 'q2' => '', 'q3' => '', 'q4' => '', 'q5' => '', 'q6' => ''];
        Session::put('seiFormData', $this->rows);
    }

    public function deleteRow($row) {
        unset($this->rows[$row]);
        $this->rows = array_values($this->rows);
        Session::put('seiFormData', $this->rows);
    }

    public function handleSubmit() {

        // dd($this->rows);
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

        }

        $this->rows = [
            ['cid' => '', 'q1' => '', 'q2' => '', 'q3' => '', 'q4' => '', 'q5' => '', 'q6' => ''],
        ];
        
        Session::forget('seiFormData');

        session()->flash('success', 'Successfully Created!');

        if(session()->has('success')) {
            $this->showModal = true;
        }

    }

    
    public function closeModal() {
        $this->showModal = false;
    }


    public function render()
    {
        $courses = CourseSection::all();

        return view('livewire.import-sei-form', [
            "courses" => $courses,
        ]);
    }
}