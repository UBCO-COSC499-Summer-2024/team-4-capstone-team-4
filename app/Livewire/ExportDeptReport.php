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
    // Public property to store the selected year
    public $year;

    // Define the event listeners for this component
    protected $listeners = ['pdfSaved' => 'handlePdfSaved'];

    /**
     * Initialize the component with default values.
     * Set the year property to the current year.
     */
    public function mount(){
        $this->year = date('Y');
    }

    /**
     * Render the view for exporting department reports.
     * Retrieve department and performance data based on the current user's role and year.
     *
     * @return \Illuminate\View\View
     */
    public function render() {
        $year = $this->year;
        $user = Auth::user();

        // Retrieve the department ID based on the user's role
        $deptRole = UserRole::where('user_id', $user->id)
            ->where(function($query) {
                $query->where('role', 'dept_head')
                      ->orWhere('role', 'dept_staff');
            })->first();
        $dept_id = $deptRole ? $deptRole->department_id : null;

        // Find the department and its areas
        $dept = Department::find($dept_id);
        $areas = $dept ? $dept->areas : [];

        // Get the department performance for the selected year
        $deptPerformance = DepartmentPerformance::where('dept_id', $dept->id)
            ->where('year', $year)
            ->first();

        // Return the view with the necessary data
        return view('livewire.export-dept-report', compact('dept', 'areas', 'year', 'deptPerformance'));
    }

    /**
     * Export the department report as an Excel file.
     * Generate a file name based on the department name and year, and trigger the download.
     *
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse|null
     */
    public function exportAsExcel(){
        $user = Auth::user();

        // Retrieve the department ID and name
        $dept_id = UserRole::find($user->id)->department_id;
        $dept = Department::find($dept_id);
        $name = $dept->name . " Department Report - " . $this->year;

        // Create the Excel file and trigger the download
        $file = Excel::download(new DeptReportExport($this->year), $name.'.xlsx');
        if ($file) {
            // Dispatch a success message if the file is successfully created
            $this->dispatch('show-toast', [
                'message' => 'Excel ' . $name . '.xlsx has been saved successfully!',
                'type' => 'success'
            ]);
            return $file;
        } else {
            // Dispatch an error message if file creation fails
            $this->dispatch('show-toast', [
                'message' => 'Failed to generate Excel ' . $name . '.xlsx',
                'type' => 'error'
            ]);
            return;
        }
    }

    /**
     * Handle the event when a PDF is successfully saved.
     * Dispatch a success message with the file name.
     *
     * @param string $fileName
     */
    public function handlePdfSaved($fileName){
        $this->dispatch('show-toast', [
            'message' => 'PDF ' . $fileName . ' has been saved successfully!',
            'type' => 'success'
        ]);
    }
}
