<?php

namespace App\Livewire;

use Livewire\Component;
//use LivewireUI\Modal\ModalComponent;
//use Livewire\ModalComponent;
use App\Models\ExtraHour;
use App\Models\Area;
use App\Models\AreaPerformance;
use App\Models\DepartmentPerformance;
use App\Models\InstructorPerformance;
use App\Models\ServiceRole;
use App\Models\UserRole;

class ExtraHourForm extends Component
{
    public $name = '';
    public $description = '';
    public $hours = 0;
    public $year;
    public $month;
    public $assigner;
    public $instructor_id;
    public $area;
    public $areas;
    public $user_roles;
    public $serviceRoleId;
    public $showExtraHourForm = false;
    public $showExtraHourView = false;
    public $show = true;
    protected $listeners = [
        'showExtraHourForm' => 'showForm'
    ];

    protected $rules = [
        'name' => 'required|string|max:255',
        'description' => 'nullable|string',
        'hours' => 'required|integer',
        'year' => 'required|integer',
        'month' => 'required|integer|min:1|max:12',
        'assigner' => 'required|exists:user_roles,id',
        'instructor_id' => 'required|exists:user_roles,id',
        'area' => 'required|exists:areas,id',
    ];

    public function mount()
    {
        // $this->serviceRoleId = $serviceRoleId;
        $this->year = date('Y');
        $this->month = date('n');
        $this->user_roles = UserRole::where('role', 'instructor')->get();
        $this->areas = Area::all();
    }

    public function save()
    {
        $this->validate();

        $extraHour = ExtraHour::create([
            'name' => $this->name,
            'description' => $this->description,
            'hours' => $this->hours,
            'year' => $this->year,
            'month' => $this->month,
            'assigner' => $this->assigner,
            'instructor_id' => $this->instructor_id,
            'area' => $this->area,
        ]);

        // Update instructor performance data
        $serviceRole = ServiceRole::find($this->serviceRoleId);
        $instructors = $serviceRole->instructors->pluck('id')->toArray();
        foreach ($instructors as $instructorId => $hours) {
            $instructorPerformance = InstructorPerformance::where('instructor_id', $instructorId)->where('year', $this->year)->first();
            if ($instructorPerformance) {
                $totalHours = json_decode($instructorPerformance->total_hours, true);
                $totalHours[$extraHour->month] = ($totalHours[$extraHour->month] ?? 0) + $hours;
                $instructorPerformance->total_hours = json_encode($totalHours);
                $instructorPerformance->save();
            }
        }

        // Update area performance data
        $areaPerformance = AreaPerformance::where('area_id', $this->serviceRole->area_id)->where('year', $this->year)->first();
        if ($areaPerformance) {
            $totalHours = json_decode($areaPerformance->total_hours, true);
            // $totalHours[$extraHours['month']] = ($totalHours[$extraHours['month']] ?? 0) + array_sum($extraHours['instructors']);
            $totalHours[$extraHour->month] = ($totalHours[$extraHour->month] ?? 0) + array_sum($instructors);
            $areaPerformance->total_hours = json_encode($totalHours);
            $areaPerformance->save();
        }

        // Update department performance data
        $departmentPerformance = DepartmentPerformance::where('dept_id', $this->serviceRole->area->department->id)->where('year', $this->year)->first();
        if ($departmentPerformance) {
            $totalHours = json_decode($departmentPerformance->total_hours, true);
            // $totalHours[$extraHours['month']] = ($totalHours[$extraHours['month']] ?? 0) + array_sum($extraHours['instructors']);
            $totalHours[$extraHour->month] = ($totalHours[$extraHour->month] ?? 0) + array_sum($instructors);
            $departmentPerformance->total_hours = json_encode($totalHours);
            $departmentPerformance->save();
        }

        if ($extraHour->wasRecentlyCreated) {
            $this->dispatch('show-toast', [
                'message' => 'Extra Hour created successfully.',
                'type' => 'success'
            ]);
            $this->dispatch('close-modal');

            $this->resetForm();
        } else {
            $this->dispatch('show-toast', [
                'message' => 'Failed to create Extra Hour.',
                'type' => 'error'
            ]);
        }
    }

    public function cancel() {
        $this->dispatch('closeModal');
    }

    public function resetForm()
    {
        $this->name = '';
        $this->description = '';
        $this->hours = '';
        $this->year = '';
        $this->month = '';
        $this->assigner = '';
        $this->instructor_id = '';
        $this->area = '';
    }

    public function render()
    {
        return view('livewire.extra-hour-form');
    }
}
