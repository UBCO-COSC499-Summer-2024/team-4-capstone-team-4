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
        return Excel::download(new InstructorReportExport($this->instructor_id, $this->year), $name.'.xlsx');
    }
}
