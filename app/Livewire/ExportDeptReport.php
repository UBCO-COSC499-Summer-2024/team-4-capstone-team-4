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
    protected $listeners = ['pdfSaved' => 'handlePdfSaved'];

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
        
        $file = Excel::download(new DeptReportExport($this->year), $name.'.xlsx');
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
