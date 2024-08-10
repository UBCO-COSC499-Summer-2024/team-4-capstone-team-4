<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use App\Models\UserRole;
use App\Models\Area;
use App\Models\Department;
use App\Models\DepartmentPerformance;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Auth;

class DeptReportExport implements FromView
{
    public $year;

    /**
     * Constructor to initialize the year property.
     * 
     * @param int $year - The year for which the department report will be generated.
     */
    public function __construct($year)
    {
        $this->year = $year;
    }

     /**
     * This method is responsible for generating the view that will be used
     * to create the Excel export. It fetches the necessary data and passes it to the view.
     * 
     * @return \Illuminate\Contracts\View\View - Returns the view with the data for the Excel export.
     */
    public function view(): View{
        $year = $this->year;

        // Get authenticated user and department
        $user = Auth::user();
        $dept_id = UserRole::find($user->id)->department_id;
        $dept = Department::find($dept_id);

        // Get the list of areas associated with the department
        $areas = $dept->areas;

        // Fetch the department's performance data for the specified year
        $deptPerformance = DepartmentPerformance::where('dept_id', $dept->id)->where('year', $year)->first();

        return view('reports.dept-export-xlsx', compact('dept', 'areas', 'year', 'deptPerformance'));
    }

    
}
