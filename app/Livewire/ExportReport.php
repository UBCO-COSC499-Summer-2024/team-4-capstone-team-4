<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\UserRole;
use App\Exports\InstructorReportExport;
use Maatwebsite\Excel\Facades\Excel;

class ExportReport extends Component
{
    public $instructor_id;
    public $year;

    protected $listeners = ['pdfSaved' => 'handlePdfSaved'];

    public function mount($instructor_id)
    {
        $this->instructor_id = $instructor_id;
        $this->year = date('Y');
    }

    public function render(){
        // Validate that instructor_id is a number
        if (!is_numeric($this->instructor_id)) {
            abort(400, 'Invalid instructor ID');
        }

        $instructor = UserRole::where('id', $this->instructor_id)->where('role', 'instructor')->first();
        if (!$instructor) {
            // Handle the case when the instructor_id is not for instructor role
            abort(404, 'Instructor not found');
        }

        //handle case where acount is disabled
        if($instructor->user->active == false){
            abort(404, 'Instructor account is disabled');
        }

        $year = $this->year;

         // Fetch the courses taught by the instructor in the current year
        $courses = $instructor->teaches()->whereHas('courseSection', function($query) {
            $query->where('year', $this->year);
        })->get();

        // Fetch the instructor's performance data for the current year
        $performance = $instructor->instructorPerformances()->where('year', $this->year)->first();

        // Fetch the instructor's service roles for the current year
        $svcroles = $instructor->serviceRoles()->where('year', $this->year)->get();

        // Fetch any extra hours assigned to the instructor for the current year
        $extraHours = $instructor->extraHours()->where('year', $this->year)->get();

        return view('livewire.export-report', compact('instructor', 'courses', 'performance', 'svcroles', 'extraHours', 'year'));
    }

     /**
     * This method handles the export of the instructor's report as an Excel file.
     * It prepares the data, generates the report, and then dispatches a success or
     * error message depending on the outcome.
     * 
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse | void - Returns the file download response or void on failure.
     */
    public function exportAsExcel(){
        // Fetch the instructor's role data using the instructor ID
        $instructor = UserRole::findOrFail($this->instructor_id);
        
        // Generate a name for the Excel file using the instructor's name and the current year
        $name = $instructor->user->firstname . " " . $instructor->user->lastname . "'s Report - " . $this->year;

         // Attempt to export the instructor's report as an Excel file
        $file = Excel::download(new InstructorReportExport($this->instructor_id, $this->year), $name.'.xlsx');

         // Check if the file was successfully created and downloaded and send toast accordingly
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
