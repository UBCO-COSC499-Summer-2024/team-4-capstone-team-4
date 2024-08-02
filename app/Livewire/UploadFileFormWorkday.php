<?php

namespace App\Livewire;

use App\Models\Area;
use App\Models\CourseSection;
use Livewire\Component;

class UploadFileFormWorkday extends Component
{
    public $rows = [];
    public $finalCSVs = [];
    public $duplicateCourses = [];

    public $showModal = false;
    public $showConfirmModal = false;
    public $courseExists = false;
    public $userConfirms = false;

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
                'room' => $finalCSV['Room'] ?? '',
                'time_start' => $finalCSV['Time Start'] ?? '',
                'time_end' => $finalCSV['Time End'] ?? '',
                'enroll_start' => $finalCSV['Enrolled Start'] ?? '',
                'enroll_end' => $finalCSV['Enrolled End'] ?? '',
                'capacity' => $finalCSV['Capacity'] ?? '',
                // Add other fields as necessary
            ];
        }

        // dd($this->rows);

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
            $rules["rows.{$index}.room"] = 'required|string';
            $rules["rows.{$index}.time_start"] = 'required|string';
            $rules["rows.{$index}.time_end"] = 'required|string';
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
                $messages["rows.{$index}.time_start.required"] = 'Please enter a time (military)';
                $messages["rows.{$index}.time_end.required"] = 'Please enter a time (military)';
                $messages["rows.{$index}.enroll_start.required"] = 'Please enter # of enrolled';
                $messages["rows.{$index}.enroll_end.required"] = 'Please enter # of enrolled';
                $messages["rows.{$index}.capacity.required"] = 'Please enter course capacity';

                $messages["rows.{$index}.area_id.integer"] = 'Must be a number';
                $messages["rows.{$index}.year.integer"] = 'Must be a number';
                $messages["rows.{$index}.enrolled.integer"] = 'Must be a number';
                $messages["rows.{$index}.capacity.integer"] = 'Must be a number';
        
                $messages["rows.{$index}.number.min"] = 'Enter a number 1-999';
                $messages["rows.{$index}.duration.min"] = 'Enter a number 1-999';
                $messages["rows.{$index}.enroll_start.min"] = 'Enter a number 1-999';
                $messages["rows.{$index}.enroll_end.min"] = 'Enter a number 0-999';
                $messages["rows.{$index}.capacity.min"] = 'Must be greater 0';
    
                $messages["rows.{$index}.number.max"] = 'Enter a number 1-999';
                $messages["rows.{$index}.duration.max"] = 'Enter a number 1-999';
                $messages["rows.{$index}.enroll_start.max"] = 'Must be lower than or equal to Capacity';
                $messages["rows.{$index}.enroll_end.max"] = 'Must be lower than or equal to Capacity';
                $messages["rows.{$index}.capacity.max"] = 'Enter a number 1-999';
        }
        return $messages;
    }

    public function deleteRow($row) {
        $this->resetValidation();

        unset($this->rows[$row]);
        $this->rows = array_values($this->rows);
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

    public function resetData()
    {
        session()->forget('finalCSVs');
        $this->finalCSVs = [];
        $this->rows = [];
    }

    public function handleSubmit() {
        // dd($this->rows, $this->duplicateCourses);
        $this->courseExists = false;

        $this->validate();

        if (!$this->validateUniqueRows()) {
            return;
        }

        foreach($this->rows as $index => $row) {
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

                CourseSection::updateOrCreate([
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

                $this->showModal = true;
                $this->userConfirms = false;
                $this->duplicateCourses = [];
                $this->resetValidation();

                $this->resetData();
                session()->flash('success', $this->showModal);
            }
        }
    }

    public function getAreaIdByName($areaName)
    {
        $area = Area::where('name', $areaName)->first();
        return $area ? $area->id : null;
    }

    public function render()
    {
        $areas = Area::all();

        // dd($areas);
        // dd($this->rows, $this->finalCSVs);

        // dd($this->finalCSVs);
        return view('livewire.upload-file-form-workday', [
            'areas' => $areas,
        ]);
    }
}
