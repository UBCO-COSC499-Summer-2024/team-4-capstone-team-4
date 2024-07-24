<?php

namespace App\Livewire;

use Livewire\Component;

class UploadFileForm extends Component
{
    public $rows = [];

    public $finalCSVs = [];

    public function mount($finalCSVs)
    {
        $this->finalCSVs = $finalCSVs;

        foreach ($finalCSVs as $index => $finalCSV) {
            $this->rows[$index] = [
                'area' => $finalCSV['Area'] ?? '',
                'number' => $finalCSV['Number'] ?? '',
                'section' => $finalCSV['Section'] ?? '',
                'year' => $finalCSV['Year'] ?? '',
                'session' => $finalCSV['Session'] ?? '',
                'enrolled' => $finalCSV['Enrolled'] ?? '',
                // Add other fields as necessary
            ];
        }
    }

    public function render()
    {
        // dd($this->finalCSVs);
        return view('livewire.upload-file-form');
    }
}
