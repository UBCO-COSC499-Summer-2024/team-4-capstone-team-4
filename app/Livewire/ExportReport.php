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

        $courses = $instructor->teaches()->whereHas('courseSection', function($query) {
            $query->where('year', $this->year);
        })->get();

        $performance = $instructor->instructorPerformances()->where('year', $this->year)->first();

        $svcroles = $instructor->serviceRoles()->where('year', $this->year)->get();

        $extraHours = $instructor->extraHours()->where('year', $this->year)->get();

        return view('livewire.export-report', compact('instructor', 'courses', 'performance', 'svcroles', 'extraHours', 'year'));
    }

    public function exportAsExcel(){
        $instructor = UserRole::findOrFail($this->instructor_id);
        $name = $instructor->user->firstname . " " . $instructor->user->lastname . "'s Report - " . $this->year;
        $file = Excel::download(new InstructorReportExport($this->instructor_id, $this->year), $name.'.xlsx');
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

    public function handlePdfSaved($fileName){
        $this->dispatch('show-toast', [
            'message' => 'PDF ' . $fileName . ' has been saved successfully!',
            'type' => 'success'
        ]);
        return;
    }
}
