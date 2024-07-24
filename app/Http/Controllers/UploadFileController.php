<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\TestModel;
use Illuminate\Support\Facades\Validator;

class UploadFileController extends Controller {

    public function showUploadFile()
    {
        return view('upload-file');
    }

    private function readCSV($filePath) {
        $csvData = [];
        $result = '';
        if (($handle = fopen($filePath, 'r')) !== false) {
            $firstLine = fgets($handle);

            $firstHyphenPos = strpos($firstLine, '-');

            // Find the position of the second hyphen
                $secondHyphenPos = strpos($firstLine, '-', $firstHyphenPos + 1);

                // Extract the substring up to the second hyphen
                if ($secondHyphenPos !== false) {
                    $result = substr($firstLine, 0, $secondHyphenPos);
                    echo $result;
                } else {
                    echo "Second hyphen not found.";
                }

            if ($result) {
                // Use a regular expression to extract the area, number, and section
                if (preg_match('/^([A-Z]{4})_([A-Z])\s(\d{3})-(\d{3})/', trim($result), $matches)) {
                    $csvData['Area'] = $matches[1];
                    $csvData['Number'] = $matches[3];
                    $csvData['Section'] = $matches[4];
                }
                $csvData['Course Name'] = trim($firstLine);
            }

            while (($row = fgetcsv($handle, 1000, ',')) !== false) {
                // Ensure each row has at least two elements to form a key-value pair
                if (count($row) >= 2) {
                    $key = $row[0];
                    $value = $row[1];
                    $csvData[$key] = $value;
                }
            }
            fclose($handle);

            if(array_key_exists('Academic Period', $csvData)) {
                if($csvData["Academic Period"]) {
         
                    if(preg_match('/^(\d{4}) (Summer|Winter)/' , $csvData["Academic Period"], $matches)) {
                        $csvData['Year'] = $matches[1];
                        if($matches[2] == 'Winter') {
                            $csvData['Session'] = 'W';
                        } else if($matches[2] == 'Summer') {
                            $csvData['Session'] = 'S';
                        }
                    }
                }
            }
            
            if(array_key_exists("Enrolled/Capacity", $csvData)) {
                if($csvData["Enrolled/Capacity"]) {
                    if(preg_match('/^(\d+)\//', $csvData["Enrolled/Capacity"], $matches)) {
                        $csvData['Enrolled'] = $matches[1];
                    }
                }
    
            }
            
            // dd($csvData);
        }

        return $csvData;
    }

    public function upload(Request $request) {
        $finalCSVs = [];
        $uploadedFiles = [];
     
        $request->validate([
            'files.*' => 'required|file|mimes:csv,txt|max:2048',
        ]);

        foreach ($request->file('files') as $file) {
            $filePath = $file->getRealPath();
            $csvData = $this->readCSV($filePath);
        
            $uploadedFiles[] = [
                'fileName' => $file->getClientOriginalName(),
                'csvData' => $csvData,
            ];
        }

        foreach ($uploadedFiles as $uploadedFile) {
            $trimCSV = [];
            $trimCSV['File'] = $uploadedFile['fileName'];
            foreach ($uploadedFile['csvData'] as $key => $value) {
                switch ($key) {
                    case 'Area':
                    case 'Number':
                    case 'Section':
                    case 'Year':
                    case 'Session':
                    case 'Enrolled':
                        $trimCSV[$key] = $value;
                        break;
                }
            }
            $finalCSVs[] = $trimCSV;
        }

        // dd($trimCSV);
    

        // dd($finalCSVs);

        $request->session()->put('finalCSVs', $finalCSVs);
        return redirect()->route('upload.file');

        session()->flash('message', 'File uploaded successfully!');
        // session()->put('csvData', $csvData);
        // session()->put('trimCSV', $trimCSV);
        session()->put('finalCSVs', $finalCSVs);


        // return straight to
        // return view('livewire.import-workday-form', [
        //     'finalCSVs' => $finalCSVs,
        // ]);


        // Process the file as needed
        // For demonstration, just dump the file path
        // dd('File uploaded successfully!', $filePath);
        // return redirect()->route('import');
    }
    
}

