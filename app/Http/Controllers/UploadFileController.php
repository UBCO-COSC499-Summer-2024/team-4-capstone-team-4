<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\TestModel;
use Illuminate\Support\Facades\Validator;

class UploadFileController extends Controller {

    private function readCSV($filePath)
    {
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
    

        dd($finalCSVs);

        session()->flash('message', 'File uploaded successfully!');
        session()->put('csvData', $csvData);
        session()->put('trimCSV', $trimCSV);
        session()->put('finalCSVs', $finalCSVs);


        // Process the file as needed
        // For demonstration, just dump the file path
        // dd('File uploaded successfully!', $filePath);
        return redirect()->route('import');
    }












    // Method to show the import form
    // public function showImportForm() {
    //     return view('import');
    // }

    // Method to handle the import logic
    // public function uploadFile(Request $request) {
    //     // Validate the uploaded file

    //     // $request->validate([
    //     //     'file' => 'required|file|mimes:csv',
    //     // ]);

    //     // $file = $request->file('file');
    //     // $path = $file->getRealPath();
    //     // $csvData = array_map('str_getcsv', file($path));

    //     // $header = $csvData[0];
    //     // $rows = array_slice($csvData, 1);

    //     // $validator = Validator::make($rows, [
    //     //     '*.0' => 'required|unique:test_table,customer_id',
    //     //     '*.1' => 'required|string', 
    //     //     '*.2' => 'required|string', 
    //     // ], [
    //     //     '*.0.required' => 'The customer ID field is required.',
    //     //     '*.0.unique' => 'The customer ID ":input" already exists.',
    //     //     '*.1.required' => 'The firstname field is required.',
    //     //     '*.2.required' => 'The lastname field is required.',
    //     // ]);

    //     // if ($validator->fails()) {
    //     //     return redirect()->back()->withErrors($validator)->withInput();
    //     // }

    //     // foreach ($rows as $row) {
    //     //     TestModel::create([
    //     //         'customer_id' => $row[0],
    //     //         'firstname' => $row[1],
    //     //         'lastname' => $row[2],
    //     //     ]);
    //     // }
       
        
    //     // return back()->with('success', 'File uploaded successfully');
    //     // return redirect()->route('import')->with('success', 'File uploaded and data inserted successfully!');


    //     // ------- read from file ----------


    //     //  Validate the request
    //      $request->validate([
    //         'file' => 'required|file|mimes:csv,txt',
    //         'data' => 'required|string',
    //     ]);

    //     // Handle the uploaded file
    //     $file = $request->file('file');
    //     $dataType = $request->input('data');

    //     // Process the CSV file
    //     $csvData = $this->readCSV($file);

    //     // For demonstration, let's just return a response with the CSV data
    //     return response()->json([
    //         'message' => 'File uploaded successfully!',
    //         'fileName' => $file->getClientOriginalName(),
    //         'dataType' => $dataType,
    //         'csvData' => $csvData,
    //     ], 200);

    // }

    // private function readCSV($file)
    // {
    //     $csvData = [];
    //     if (($handle = fopen($file->getRealPath(), 'r')) !== false) {
    //         while (($row = fgetcsv($handle, 1000, ',')) !== false) {
    //             $csvData[] = $row;
    //         }
    //         fclose($handle);
    //     }
    //     return $csvData;
    // }
    
}