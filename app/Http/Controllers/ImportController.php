<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\TestModel;
use Illuminate\Support\Facades\Validator;

class ImportController extends Controller {
    // Method to show the import form
    public function showImportForm() {
        return view('import');
    }

    // Method to handle the import logic
    public function importData(Request $request) {
        // Validate the uploaded file

        // $request->validate([
        //     'file' => 'required|file|mimes:csv',
        // ]);

        // $file = $request->file('file');
        // $path = $file->getRealPath();
        // $csvData = array_map('str_getcsv', file($path));

        // $header = $csvData[0];
        // $rows = array_slice($csvData, 1);

        // $validator = Validator::make($rows, [
        //     '*.0' => 'required|unique:test_table,customer_id',
        //     '*.1' => 'required|string', 
        //     '*.2' => 'required|string', 
        // ], [
        //     '*.0.required' => 'The customer ID field is required.',
        //     '*.0.unique' => 'The customer ID ":input" already exists.',
        //     '*.1.required' => 'The firstname field is required.',
        //     '*.2.required' => 'The lastname field is required.',
        // ]);

        // if ($validator->fails()) {
        //     return redirect()->back()->withErrors($validator)->withInput();
        // }

        // foreach ($rows as $row) {
        //     TestModel::create([
        //         'customer_id' => $row[0],
        //         'firstname' => $row[1],
        //         'lastname' => $row[2],
        //     ]);
        // }
       
        
        // return back()->with('success', 'File uploaded successfully');
        // return redirect()->route('import')->with('success', 'File uploaded and data inserted successfully!');


        // ------- read from file ----------


         // Validate the request
         $request->validate([
            'file' => 'required|file|mimes:csv,txt',
            'data' => 'required|string',
        ]);

        // Handle the uploaded file
        $file = $request->file('file');
        $dataType = $request->input('data');

        // Process the CSV file
        $csvData = $this->readCSV($file);

        // For demonstration, let's just return a response with the CSV data
        return response()->json([
            'message' => 'File uploaded successfully!',
            'fileName' => $file->getClientOriginalName(),
            'dataType' => $dataType,
            'csvData' => $csvData,
        ], 200);

    }

    private function readCSV($file)
    {
        $csvData = [];
        if (($handle = fopen($file->getRealPath(), 'r')) !== false) {
            while (($row = fgetcsv($handle, 1000, ',')) !== false) {
                $csvData[] = $row;
            }
            fclose($handle);
        }
        return $csvData;
    }
    
}