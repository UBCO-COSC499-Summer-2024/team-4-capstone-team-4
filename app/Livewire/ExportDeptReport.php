<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\UserRole;
use App\Models\Area;
use App\Models\Department;
use App\Models\DepartmentPerformance;
use App\Exports\DeptReportExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Auth;

class ExportDeptReport extends Component
{
    public $year;
    protected $listeners = ['pdfSaved' => 'handlePdfSaved'];

    public function mount(){
        $this->year = date('Y');
    }

    public function render() {
        $year = $this->year;

        $user = Auth::user();

        //Find dept ID for authenticated user
        $deptRole = UserRole::where('user_id', $user->id)
        ->where(function($query) {
            $query->where('role', 'dept_head')
                ->orWhere('role', 'dept_staff');
        })->first();
        $dept_id = $deptRole ? $deptRole->department_id : null;

        $dept = Department::find($dept_id);

        // Get the list of areas associated with the department
        $areas = $dept->areas;

        // Fetch the department's performance data for the specified year
        $deptPerformance = DepartmentPerformance::where('dept_id', $dept->id)->where('year', $year)->first();

        return view('livewire.export-dept-report', compact('dept', 'areas', 'year', 'deptPerformance'));

    }

     /**
     * This method handles the export of the department report as an Excel file.
     * It prepares the file, generates the report, and then dispatches a success or
     * error message depending on the outcome.
     * 
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse | void - Returns the file download response or void on failure.
     */
    public function exportAsExcel(){
        $user = Auth::user(); // Get the authenticated user

        // Get the department and department ID for the authenticated user
        $dept_id = UserRole::find($user->id)->department_id;
        $dept = Department::find($dept_id);

        // Generate a name for the Excel file using the department name and current year
        $name = $dept->name . " Department Report - " . $this->year;

        // Attempt to export the department report as an Excel file
        $file = Excel::download(new DeptReportExport($this->year), $name.'.xlsx');

        // Check if the file was successfully created and downloaded
        if($file){
            $this->dispatch('show-toast', [
                'message' => 'Excel '. $name.'.xlsx has been saved successfully!',
                'type' => 'success'
            ]);
            return $file;
        }else{
            $this->dispatch('show-toast', [
                'message' => 'Failed to generate Excel '. $name.'.xlsx',
                'type' => 'error'
            ]);
            return;
        }
    }

     /**
     * This method handles the event triggered when a PDF report has been saved.
     * It dispatches a success message indicating that the PDF was saved successfully.
     * 
     * @param string $fileName - The name of the PDF file that was saved.
     * @return void
     */
    public function handlePdfSaved($fileName){
        // Dispatch a success message when the PDF file is successfully saved
        $this->dispatch('show-toast', [
            'message' => 'PDF ' . $fileName . ' has been saved successfully!',
            'type' => 'success'
        ]);
        return;
    }
}
