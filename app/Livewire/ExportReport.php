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

    // Define listeners for component events
    protected $listeners = ['pdfSaved' => 'handlePdfSaved'];

    /**
     * Initialize the component with default values and set the instructor ID.
     *
     * @param int $instructor_id
     */
    public function mount($instructor_id)
    {
        $this->instructor_id = $instructor_id;
        $this->year = date('Y');
    }

    /**
     * Render the component view and gather necessary data.
     *
     * @return \Illuminate\View\View
     */
    public function render()
    {
        // Validate that instructor_id is a number
        if (!is_numeric($this->instructor_id)) {
            abort(400, 'Invalid instructor ID');
        }

        // Fetch the instructor based on the provided ID and role
        $instructor = UserRole::where('id', $this->instructor_id)
            ->where('role', 'instructor')
            ->first();

        // Handle case when the instructor ID does not correspond to an instructor
        if (!$instructor) {
            abort(404, 'Instructor not found');
        }

        // Handle case where the instructor's account is disabled
        if ($instructor->user->active == false) {
            abort(404, 'Instructor account is disabled');
        }

        // Retrieve data for the specified year
        $year = $this->year;

        $courses = $instructor->teaches()->whereHas('courseSection', function ($query) {
            $query->where('year', $this->year);
        })->get();

        $performance = $instructor->instructorPerformances()->where('year', $this->year)->first();

        $svcroles = $instructor->serviceRoles()->where('year', $this->year)->get();

        $extraHours = $instructor->extraHours()->where('year', $this->year)->get();

        // Return the view with the collected data
        return view('livewire.export-report', compact('instructor', 'courses', 'performance', 'svcroles', 'extraHours', 'year'));
    }

    /**
     * Export the instructor report as an Excel file.
     *
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse|null
     */
    public function exportAsExcel()
    {
        $instructor = UserRole::findOrFail($this->instructor_id);
        $name = $instructor->user->firstname . " " . $instructor->user->lastname . "'s Report - " . $this->year;
        
        $file = Excel::download(new InstructorReportExport($this->instructor_id, $this->year), $name.'.xlsx');
        
        // Dispatch a success or error message based on the outcome
        if ($file) {
            $this->dispatch('show-toast', [
                'message' => 'Excel ' . $name . '.xlsx has been saved successfully!',
                'type' => 'success'
            ]);
            return $file;
        } else {
            $this->dispatch('show-toast', [
                'message' => 'Failed to generate Excel ' . $name . '.xlsx',
                'type' => 'error'
            ]);
            return;
        }
    }

    /**
     * Handle the event when a PDF is saved.
     *
     * @param string $fileName
     */
    public function handlePdfSaved($fileName)
    {
        $this->dispatch('show-toast', [
            'message' => 'PDF ' . $fileName . ' has been saved successfully!',
            'type' => 'success'
        ]);
        return;
    }
}

