<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Area;
use App\Models\Department;
use App\Models\DepartmentPerformance;
use App\Exports\DeptReportExport;
use Maatwebsite\Excel\Facades\Excel;

class ExportDeptReport extends Component
{
    public $year;

    public function mount(){
        $this->year = date('Y');
    }

    public function render(){
        $year = $this->year;

        $dept = Department::find(1);

        $areas = Area::all();

        $deptPerformance = DepartmentPerformance::where('dept_id', $dept->id)->where('year', $year)->first();

        return view('livewire.export-dept-report', compact('dept', 'areas', 'year', 'deptPerformance'));
        
    }

    public function exportAsExcel(){
        $dept = Department::find(1);
        $name = $dept->name . " Department Report - " . $this->year;
        return Excel::download(new DeptReportExport($this->year), $name.'.xlsx');
    }
}
