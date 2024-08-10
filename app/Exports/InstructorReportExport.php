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

    /**
     * Constructor to initialize the instructor ID and year properties.
     * 
     * @param int $instructor_id - The ID of the instructor for whom the report will be generated.
     * @param int $year - The year for which the report will be generated.
     */
    public function __construct($instructor_id, $year)
    {
        $this->instructor_id = $instructor_id;
        $this->year = $year;
    }

    /**
     * This method generates the view to be used for creating the Excel export.
     * It retrieves the necessary data and passes it to the view.
     * 
     * @return \Illuminate\Contracts\View\View - Returns the view with the data for the Excel export.
     */
    public function view(): View{
        $instructor = UserRole::findOrFail($this->instructor_id);

        $year = $this->year;

         // Fetch the courses taught by the instructor in the specified year
        $courses = $instructor->teaches()->whereHas('courseSection', function($query) {
            $query->where('year', $this->year);
        })->get();
    
        // Fetch the instructor's performance data for the specified year
        $performance = $instructor->instructorPerformances()->where('year', $this->year)->first();

        // Fetch the instructor's service roles for the specified year
        $svcroles = $instructor->serviceRoles()->where('year', $this->year)->get();

        // Fetch any extra hours assigned to the instructor for the specified year
        $extraHours = $instructor->extraHours()->where('year', $this->year)->get();
        
        return view('reports.export-xlsx', compact('instructor', 'courses', 'performance', 'svcroles', 'extraHours', 'year'));
    }
}
