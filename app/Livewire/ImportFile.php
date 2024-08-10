<?php

namespace App\Livewire;

use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\WithFileUploads;

class ImportFile extends Component
{
    use WithFileUploads;

    /**
     * The file instance to be uploaded.
     *
     * @var \Livewire\TemporaryUploadedFile|null
     */
    public $file;

    /**
     * The additional data to be processed with the file.
     *
     * @var string|null
     */
    public $data;

    /**
     * The data read from the CSV file.
     *
     * @var array
     */
    public $csvData = [];

    /**
     * Validates the file input and processes the CSV file.
     *
     * @return void
     */
    public function upload()
    {
        // Validate the file and data inputs
        $this->validate([
            'file' => 'required|file|mimes:csv,txt|max:2048',
            'data' => 'required|string',
        ]);

        // Get the real path of the uploaded file
        $filePath = $this->file->getRealPath();

        // Get the additional data input
        $dataType = $this->data;

        // Read and store CSV data
        $this->csvData = $this->readCSV($filePath);

        // Flash a success message to the session
        session()->flash('message', 'File uploaded and processed successfully!');
    }

    /**
     * Reads and parses the CSV file into an array.
     *
     * @param string $filePath The path to the CSV file
     * @return array The parsed CSV data
     */
    private function readCSV($filePath)
    {
        $csvData = [];
        // Open the CSV file for reading
        if (($handle = fopen($filePath, 'r')) !== false) {
            // Read each row from the CSV file
            while (($row = fgetcsv($handle, 1000, ',')) !== false) {
                $csvData[] = $row;
            }
            // Close the file handle
            fclose($handle);
        }
        return $csvData;
    }

    /**
     * Renders the Livewire component view.
     *
     * @return \Illuminate\View\View The view for the import-file component
     */
    public function render()
    {
        return view('livewire.import-file');
    }
}

