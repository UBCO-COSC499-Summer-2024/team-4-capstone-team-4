<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\TestModel;
use Illuminate\Support\Facades\Validator;

class UploadFileController extends Controller {

    public function showUploadFileWorkday()
    {
        return view('upload-file-workday');
    }

    public function showUploadFileSei()
    {
        return view('upload-file-sei');
    }

    private function readWorkdayCSV($filePath) {
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
            // if ever expands to other departments, will need to convert the prefix to the full name based on the area table in the db.

            
            if(array_key_exists("Area", $csvData)) {
            switch ($csvData['Area']) {
                    // cases for CMPS
                case 'COSC':
                    $csvData['Area'] = 'Computer Science';
                    break;
                case 'MATH':
                    $csvData['Area'] = 'Mathematics';
                    break;
                case 'PHYS':
                    $csvData['Area'] = 'Physics';
                    break;
                case 'STAT':
                    $csvData['Area'] = 'Statistics';
                    break;
                default:
                    //code block
                }

            }
            
            // dd($csvData);
        }

        return $csvData;
    }

    private function readCSV($filePath) {
        $header = null;
        $csvData = [];

        if (($handle = fopen($filePath, 'r')) !== false) {
            while (($row = fgetcsv($handle, 1000, ',')) !== false) {
                if (!array_filter($row)) {
                    continue; // Skip empty rows
                }

                if (!$header) {
                    $header = $row;
                } else {
                    $csvData[] = array_combine($header, $row);
                }
            }
            fclose($handle);
        }

        return $csvData;
    }

    public function uploadWorkday(Request $request) {
        $finalCSVs = [];
        $uploadedFiles = [];
     
        $request->validate([
            'files.*' => 'required|file|mimes:csv|max:2048',
        ]);


        foreach ($request->file('files') as $file) {
            $filePath = $file->getRealPath();

            // depending on CSV file format, you may need to adjust the readCSV function

            // $csvData = $this->readWorkdayCSV($filePath);
            $csvData = $this->readCSV($filePath);
        
            $uploadedFiles[] = [
                'fileName' => $file->getClientOriginalName(),
                'csvData' => $csvData,
            ];
        }

        // -- pulled straight from workday CSV format --

        // foreach ($uploadedFiles as $uploadedFile) {
        //     $trimCSV = [];
        //     $trimCSV['File'] = $uploadedFile['fileName'];
        //     foreach ($uploadedFile['csvData'] as $key => $value) {
        //         switch ($key) {
        //             case 'Area':
        //             case 'Number':
        //             case 'Section':
        //             case 'Session':
        //             case 'Term':
        //             case 'Year':
        //             case 'Session':
        //             case 'Enrolled':
        //             case 'Dropped':
        //             case 'Capacity':
        //                 $trimCSV[$key] = $value;
        //                 break;
        //         }
        //     }
        //     $finalCSVs[] = $trimCSV;
        // }

        // -- traditional csv format --

        foreach ($uploadedFiles as $uploadedFile) {
            $trimCSV = [];
            $trimCSV['File'] = $uploadedFile['fileName'];
            foreach ($uploadedFile['csvData'] as $csvData) {
                foreach ($csvData as $key => $value) {
                    switch ($key) {
                        case 'Area':
                        case 'Number':
                        case 'Section':
                        case 'Session':
                        case 'Term':
                        case 'Year':
                        case 'Session':
                        case 'Enrolled':
                        case 'Dropped':
                        case 'Capacity':
                            $trimCSV[$key] = $value;
                            break;
                    }
                }
                $finalCSVs[] = $trimCSV;
            }
        }

        $request->session()->put('finalCSVs', $finalCSVs);
        return redirect()->route('upload.file.show.workday');
    }

    public function uploadSei(Request $request) {
        $finalCSVs = [];
        $uploadedFiles = [];
     
        $request->validate([
            'files.*' => 'required|file|mimes:csv|max:2048',
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
            foreach ($uploadedFile['csvData'] as $csvData) {
                foreach ($csvData as $key => $value) {
                    switch ($key) {
                        case 'Prefix':
                        case 'Number':
                        case 'Section':
                        case 'Session':
                        case 'Term':
                        case 'Year':
                        case 'Q1':
                        case 'Q2':
                        case 'Q3':
                        case 'Q4':
                        case 'Q5':
                        case 'Q6':
                            $trimCSV[$key] = $value;
                            break;
                    }
                }
                $finalCSVs[] = $trimCSV;
               
            }
        }

        $request->session()->put('finalCSVs', $finalCSVs);
        return redirect()->route('upload.file.show.sei');
    }
}

