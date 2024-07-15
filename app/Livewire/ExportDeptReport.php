<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Area;
use App\Models\Department;
use App\Models\DepartmentPerformance;

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
}
