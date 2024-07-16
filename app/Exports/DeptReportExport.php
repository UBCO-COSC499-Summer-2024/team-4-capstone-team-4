<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use App\Models\Area;
use App\Models\Department;
use App\Models\DepartmentPerformance;

class DeptReportExport implements FromView
{
    public $year;

    public function __construct($year)
    {
        $this->year = $year;
    }

    public function view(): View{
        $year = $this->year;

        $dept = Department::find(1);

        $areas = Area::all();

        $deptPerformance = DepartmentPerformance::where('dept_id', $dept->id)->where('year', $year)->first();

        return view('reports.dept-export-xlsx', compact('dept', 'areas', 'year', 'deptPerformance'));
    }
}
