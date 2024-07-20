<?php

namespace App\Livewire;

use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\WithFileUploads;

class ImportFile extends Component
{
    use WithFileUploads;

    public $file;
    public $data;
    public $csvData = [];

    public function upload()
    {
        $this->validate([
            'file' => 'required|file|mimes:csv,txt|max:2048',
            'data' => 'required|string',
        ]);

        $filePath = $this->file->getRealPath();
        $dataType = $this->data;

        $this->csvData = $this->readCSV($filePath);

        session()->flash('message', 'File uploaded and processed successfully!');
    }

    private function readCSV($filePath)
    {
        $csvData = [];
        if (($handle = fopen($filePath, 'r')) !== false) {
            while (($row = fgetcsv($handle, 1000, ',')) !== false) {
                $csvData[] = $row;
            }
            fclose($handle);
        }
        return $csvData;
    }

    public function render()
    {
        return view('livewire.import-file');
    }
}
