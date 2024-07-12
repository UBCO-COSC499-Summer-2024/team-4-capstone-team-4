<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use App\Models\UserRole;

class InstructorReportExport implements FromView
{
    protected $instructor_id;
    protected $year;

    public function __construct($instructor_id, $year)
    {
        $this->instructor_id = $instructor_id;
        $this->year = $year;
    }
    public function view(): View{
        $instructor = UserRole::findOrFail($this->instructor_id);

        $year = $this->year;

        $courses = $instructor->teaches()->whereHas('courseSection', function($query) {
            $query->where('year', $this->year);
        })->get();
    
        $performance = $instructor->instructorPerformances()->where('year', $this->year)->first();

        $svcroles = $instructor->serviceRoles()->where('year', $this->year)->get();

        $extraHours = $instructor->extraHours()->where('year', $this->year)->get();
        
        return view('reports.export-csv', compact('instructor', 'courses', 'performance', 'svcroles', 'extraHours', 'year'));
    }
}
