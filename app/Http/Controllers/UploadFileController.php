<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Area;
use App\Models\ServiceRole;
use App\Models\TestModel;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use League\Csv\Reader;
use League\Csv\Statement;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;
use PhpOffice\PhpSpreadsheet\Writer\Csv;

class UploadFileController extends Controller {

    public function showUploadFileWorkday()
    {
        return view('upload-file-workday');
    }

    public function showUploadFileSei()
    {
        return view('upload-file-sei');
    }
    
    public function showUploadFileAssignCourses()
    {
        return view('upload-file-assign-courses');
    }

    public function showUploadFileAssignTas()
    {
        return view('upload-file-assign-tas');
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
        //             case 'Room':
        //             case 'Time Start':
        //             case 'Time End':
        //             case 'Enrolled Start':
        //             case 'Enrolled End':
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
                        case 'Room':
                        case 'Time Start':
                        case 'Time End':
                        case 'Enrolled Start':
                        case 'Enrolled End':
                        case 'Capacity':
                            $trimCSV[$key] = $value;
                            break;
                    }
                }
                $finalCSVs[] = $trimCSV;
            }
        }

        // dd($finalCSVs);

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

    public function uploadAssignCourses(Request $request) {
        dd('post assign courses');
    }

    public function uploadSvcRoles(Request $request) {
        // Ensure that 'files' exists in the request
        // dd($request);
        // dd($request->all());
        // Log::info($request->all());
        // dd($request->file('files'));
        // dd($files);

        try {

            $request->validate([
                'files.*' => 'required|file|mimes:csv,xlsx,xls,json|max:2048',
            ]);

            $uploadedFiles = [];
            $formattedData = [];

            foreach ($request->file('files') as $index => $file) {
                if($file->isValid()) {
                    $uploadedFiles[] = [
                        'fileName' => $file->getClientOriginalName(),
                        'fileExtension' => $file->getClientOriginalExtension(),
                        'fileSize' => $file->getSize(),
                    ];

                    $fileData = $this->extractFileData($file);

                    foreach ($fileData as $record) {
                        $formattedData[] = $this->processRecord($record, count($formattedData) + 1);
                    }
                } else {
                    // Handle invalid file
                    // throw new \Exception("Invalid file: " . $file->getClientOriginalName());
                }
            }

            $request->session()->put('uploadedServiceRoles', [
                'uploadedFiles' => $uploadedFiles,
                'formattedData' => $formattedData,
            ]);

            return redirect()->route('svcroles.add');
        } catch(\Exception $e) {
            // Handle the exception
            return redirect()->route('svcroles.add');
        }
    }

    private function extractFileData($file) {
        $fileExtension = $file->getClientOriginalExtension();

        if (in_array($fileExtension, ['xlsx', 'xls'])) {
            $spreadsheet = IOFactory::load($file->getRealPath());
            $worksheet = $spreadsheet->getActiveSheet();
            $data = [];
            foreach ($worksheet->getRowIterator() as $row) {
                $rowData = [];
                foreach ($row->getCellIterator() as $cell) {
                    $rowData[] = $cell->getValue();
                }
                $data[] = $rowData;
            }
            return $data;
        } elseif ($fileExtension === 'csv') {
            $csv = Reader::createFromPath($file->getRealPath(), 'r');
            $csv->setHeaderOffset(0); // Assuming the first row contains headers
            return (new Statement())->offset(0)->process($csv);
        } elseif ($fileExtension === 'json') {
            return json_decode(file_get_contents($file->getRealPath()), true);
        } else {
            // Handle invalid file type
            throw new \Exception("Unsupported file type: " . $fileExtension);
        }
    }

    private function processRecord($record, $id) {
        // Assuming your database columns are lowercase and use snake_case

        $areaName = $record['area'] ?? $record['area_id'] ?? $record['department'] ?? $record['dept'] ?? $record['department_id'] ?? $record['dept_id'];
        $area = Area::where('name', $areaName)->orWhere('id', $areaName)->first();
        if (!$area) {
            // set to default 1
            $area = Area::find(1);
        }

        $serviceRoleData = [
            'name' => $record['name'] ?? null,
            'description' => $record['description'] ?? $record['desc'] ?? 'Default Description',
            'year' => $record['year'] ?? date('Y'),
            'monthly_hours' => $this->formatMonthlyHours($record),
            'area_id' => $area->id,
            'archived' => isset($record['archived']) ? (bool)$record['archived'] :
                         (isset($record['archive']) ? (bool)$record['archive'] :
                         (isset($record['active']) ? !(bool)$record['active'] : false)),
        ];

        // Check if a service role with the same name, area, and year already exists
        $existingServiceRole = ServiceRole::where('name', $serviceRoleData['name'])
                                            ->where('area_id', $serviceRoleData['area_id'])
                                            ->where('year', $serviceRoleData['year'])
                                            ->first();

        $serviceRoleData['updateMe'] = $existingServiceRole ? true : false;
        $serviceRoleData['original_area_name'] = $areaName;
        $serviceRoleData['id'] = $id;

        // Return the processed data (optional)
        return $serviceRoleData;
    }

    private function formatMonthlyHours($record) {
        $months = array_fill_keys([
            'January', 'February', 'March', 'April',
            'May', 'June', 'July', 'August',
            'September', 'October', 'November', 'December'
        ], 0);

        $monthMappings = [
            '/^jan(?:uary)?$/i' => 'January', '/^feb(?:ruary)?$/i' => 'February',
            '/^mar(?:ch)?$/i' => 'March', '/^apr(?:il)?$/i' => 'April',
            '/^may$/i' => 'May', '/^jun(?:e)?$/i' => 'June',
            '/^jul(?:y)?$/i' => 'July', '/^aug(?:ust)?$/i' => 'August',
            '/^sep(?:t(?:ember)?)?$/i' => 'September', '/^oct(?:ober)?$/i' => 'October',
            '/^nov(?:ember)?$/i' => 'November', '/^dec(?:ember)?$/i' => 'December',
            '/^1$/' => 'January', '/^2$/' => 'February', '/^3$/' => 'March',
            '/^4$/' => 'April', '/^5$/' => 'May', '/^6$/' => 'June',
            '/^7$/' => 'July', '/^8$/' => 'August', '/^9$/' => 'September',
            '/^10$/' => 'October', '/^11$/' => 'November', '/^12$/' => 'December'
        ];

        foreach ($record as $key => $value) {
            foreach ($monthMappings as $pattern => $month) {
                if (preg_match($pattern, $key)) {
                    $months[$month] = (int)$value;
                    break;
                }
            }
        }
        return json_encode($months);
    }
}

