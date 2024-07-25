<?php

namespace App\Livewire;

use App\Models\Area;
use App\Models\CourseSection;
use Livewire\Component;

class UploadFileForm extends Component
{
    public $rows = [];
    public $finalCSVs = [];

    public $showModal = false;

    public function mount($finalCSVs)
    {
        $this->finalCSVs = $finalCSVs;

        foreach ($finalCSVs as $index => $finalCSV) {
            $this->rows[$index] = [
                'area' => $finalCSV['Area'] ?? '',
                'area_id' => $this->getAreaIdByName($finalCSV['Area'] ?? ''),
                'number' => $finalCSV['Number'] ?? '',
                'section' => $finalCSV['Section'] ?? '',
                'session' => $finalCSV['Session'] ?? '',
                'term' => $finalCSV['Term'] ?? '',
                'year' => $finalCSV['Year'] ?? '',
                'session' => $finalCSV['Session'] ?? '',
                'enrolled' => $finalCSV['Enrolled'] ?? '',
                'dropped' => $finalCSV['Dropped'] ?? '',
                'capacity' => $finalCSV['Capacity'] ?? '',
                // Add other fields as necessary
            ];
        }

        session()->forget('finalCSVs');
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

                // $messages["rows.{$index}.area.integer"] = 'Must be a number';
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

    public function handleSubmit() {
        $this->validate();

        // dd($this->rows);

        foreach($this->rows as $row) {
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

                // dd($row);

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
            }

        $this->showModal = true;

        session()->flash('success', $this->showModal);
    }

    public function closeModal() {
        $this->showModal = false;
    }


    public function getAreaIdByName($areaName)
    {
        $area = Area::where('name', $areaName)->first();
        return $area ? $area->id : null;
    }

    public function render()
    {
        $areas = Area::all();

        // dd($this->finalCSVs);
        return view('livewire.upload-file-form', [
            'areas' => $areas,
        ]);
    }
}
