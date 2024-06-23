<?php

namespace App\Livewire;

use App\Models\SeiData;
use Livewire\Attributes\Rule;
use Livewire\Component;

class ImportWorkdayForm extends Component
{
    public $testCid = 123456;
    public $rows = [];

    public function mount() {
        $this->rows = [
            ['cid' => '', 'q1' => '', 'q2' => '', 'q3' => '', 'q4' => '', 'q5' => '', 'q6' => ''],
        ];
    }

    public function rules() {
        $rules = [];

     
        foreach ($this->rows as $index => $row) {
            $rules["rows.{$index}.cid"] = 'required|min:1';
            $rules["rows.{$index}.q1"] = '';
            $rules["rows.{$index}.q2"] = '';
            $rules["rows.{$index}.q3"] = '';
            $rules["rows.{$index}.q4"] = '';
            $rules["rows.{$index}.q5"] = '';
            $rules["rows.{$index}.q6"] = '';
        }

        return $rules;
    }

    public function addRow() {
        $this->rows[] =  ['cid' => '', 'q1' => '', 'q2' => '', 'q3' => '', 'q4' => '', 'q5' => '', 'q6' => ''];
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

            SeiData::create([
                'course_section_id' => $row['cid'],
                // 'questions' => json_encode([
                //     'q1' => $row['q1'],
                //     'q2' => $row['q2'],
                //     'q3' => $row['q3'],
                //     'q4' => $row['q4'],
                //     'q5' => $row['q5'],
                //     'q6' => $row['q6'],
                // ]),
            ]);

        }

        $this->rows = [
            ['cid' => '', 'q1' => '', 'q2' => '', 'q3' => '', 'q4' => '', 'q5' => '', 'q6' => ''],
        ];

        session()->flash('success', 'Successfully Created!');

    }


    public function render()
    {
        return view('livewire.import-workday-form');
    }
}
