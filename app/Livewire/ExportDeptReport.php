<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\UserRole;
use App\Models\Area;
use App\Models\Department;
use App\Models\DepartmentPerformance;
use App\Exports\DeptReportExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Auth;

class ExportDeptReport extends Component
{
    public $year;

    public function mount(){
        $this->year = date('Y');
    }

    public function render(){
        $year = $this->year;

        $user = Auth::user();

        $dept_id = UserRole::find($user->id)->department_id;
        $dept = Department::find($dept_id);

        $areas = $dept->areas;

        $deptPerformance = DepartmentPerformance::where('dept_id', $dept->id)->where('year', $year)->first();

        return view('livewire.export-dept-report', compact('dept', 'areas', 'year', 'deptPerformance'));
        
    }

    public function exportAsExcel(){
        $user = Auth::user();

        $dept_id = UserRole::find($user->id)->department_id;
        $dept = Department::find($dept_id);

        $name = $dept->name . " Department Report - " . $this->year;
        
        return Excel::download(new DeptReportExport($this->year), $name.'.xlsx');
    }
}