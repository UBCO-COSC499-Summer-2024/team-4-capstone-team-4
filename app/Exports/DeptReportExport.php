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

    public function __construct($year)
    {
        $this->year = $year;
    }

    public function view(): View{
        $year = $this->year;

        $user = Auth::user();
        $dept_id = UserRole::find($user->id)->department_id;
        $dept = Department::find($dept_id);

        $areas = $dept->areas;

        $deptPerformance = DepartmentPerformance::where('dept_id', $dept->id)->where('year', $year)->first();

        return view('reports.dept-export-xlsx', compact('dept', 'areas', 'year', 'deptPerformance'));
    }

    
}
