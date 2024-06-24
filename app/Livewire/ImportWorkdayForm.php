<?php

namespace App\Livewire;

use App\Models\CourseSection;;
use Livewire\Attributes\Rule;
use Livewire\Component;

class ImportWorkdayForm extends Component
{
    public $testCid = 123456;
    public $rows = [];

    public function mount() {
        $this->rows = [
            ['course_name' => '', 'area_id' => '', 'duration' => '', 'enrolled' => '', 'dropped' => '', 'capacity' => ''],
        ];
    }

    public function rules() {
        $rules = [];

     
        foreach ($this->rows as $index => $row) {
            $rules["rows.{$index}.course_name"] = 'required|min:1';
            $rules["rows.{$index}.area_id"] = '';
            $rules["rows.{$index}.duration"] = '';
            $rules["rows.{$index}.enrolled"] = '';
            $rules["rows.{$index}.dropped"] = '';
            $rules["rows.{$index}.capacity"] = '';
        }

        return $rules;
    }

    public function addRow() {
        $this->rows[] =  ['course_name' => '', 'area_id' => '', 'duration' => '', 'enrolled' => '', 'dropped' => '', 'capacity' => ''];
    }

    public function deleteRow($row) {
        unset($this->rows[$row]);
        $this->rows = array_values($this->rows); // Reindex array
    }

    public function handleClick() {

        // dd($this->rows);
        $this->validate();
    
        
        foreach ($this->rows as $row) {
            // dd($row);

            CourseSection::create([
                'name' => $row['course_name'], 
                'area_id' => $row['area_id'], 
                'duration' => $row['duration'], 
                'enrolled' => $row['enrolled'], 
                'dropped' => $row['dropped'], 
                'capacity' => $row['capacity'],        
            ]);

        }

        $this->rows = [
            ['course_name' => '', 'area_id' => '', 'duration' => '', 'enrolled' => '', 'dropped' => '', 'capacity' => ''],
        ];

        session()->flash('success', 'Successfully Created!');

    }


    public function render()
    {
        return view('livewire.import-workday-form');
    }
}
