<?php

namespace App\Livewire;

use App\Models\Area;
use App\Models\AreaPerformance;
use App\Models\DepartmentPerformance;
use App\Models\InstructorPerformance;
use App\Models\ServiceRole;
use App\Models\ExtraHour;
use App\Models\RoleAssignment;
use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Livewire\WithPagination;

class ManageServiceRole extends Component
{
    use WithPagination;
    public $serviceRole;
    public $isEditing = false;
    public $year;
    public $showInstructorModal = false;
    public $showExtraHourModal = false;
    public $instructors;
    public $instructor_id;
    public $areas;
    public $allRoles;
    public $links;
    public $monthly_hours;

    protected $listeners = [
        'toggleEditMode' => 'toggleEditMode',
        'editServiceRole' => 'editServiceRole',
        'updateServiceRole' => 'saveServiceRole',
        'deleteServiceRole' => 'deleteServiceRole',
        'showInstructorModal' => 'showInstructorModal',
        'showExtraHourModal' => 'showExtraHourModal',
        'addExtraHour' => 'addExtraHour',
    ];

    protected $rules = [
        'serviceRole.name' => 'required|string|max:255',
        'serviceRole.description' => 'nullable|string',
        'serviceRole.year' => 'required|integer',
        'serviceRole.monthly_hours.*' => 'required|array|min:0|max:744',
        'serviceRole.area_id' => 'required|exists:areas,id',
    ];

    public function mount(ServiceRole $serviceRole, $links = [])
    {
        $this->serviceRole = $serviceRole;
        $this->links = $links;
        $this->year = date('Y');
        $this->areas = Area::all();
        $this->allRoles = ServiceRole::all();
        $this->instructors = $this->serviceRole->instructors;
        $this->fixMonthNames();
    }

    public function fixMonthNames()
    {
        // Decode the JSON string to an associative array
        $monthlyHours = json_decode($this->serviceRole->monthly_hours, true);

        // Define the mapping from short month names or numeric keys to full month names
        $monthMapping = [
            'jan' => 'January',
            0 => 'January',
            'feb' => 'February',
            1 => 'February',
            'mar' => 'March',
            2 => 'March',
            'apr' => 'April',
            3 => 'April',
            'may' => 'May',
            4 => 'May',
            'jun' => 'June',
            5 => 'June',
            'jul' => 'July',
            6 => 'July',
            'aug' => 'August',
            7 => 'August',
            'sep' => 'September',
            8 => 'September',
            'oct' => 'October',
            9 => 'October',
            'nov' => 'November',
            10 => 'November',
            'dec' => 'December',
            11 => 'December'
        ];

        // Initialize an array with full month names
        $fullMonthNames = [
            'January' => 0,
            'February' => 0,
            'March' => 0,
            'April' => 0,
            'May' => 0,
            'June' => 0,
            'July' => 0,
            'August' => 0,
            'September' => 0,
            'October' => 0,
            'November' => 0,
            'December' => 0,
        ];

        // Map the short month names or numeric keys to full month names if needed
        foreach ($monthlyHours as $key => $value) {
            $month = isset($monthMapping[$key]) ? $monthMapping[$key] : $key;
            $fullMonthNames[$month] = $value;
        }

        $this->monthly_hours = $fullMonthNames;
    }

    public function toggleEditMode($mode = null)
    {
        $this->isEditing = $mode ?? !$this->isEditing;
        if ($this->isEditing) {
            $this->dispatch('show-toast', [
                'message' => 'Edit mode toggled on for ' . $this->serviceRole->name,
                'type' => 'success'
            ]);
        } else {
            $this->dispatch('show-toast', [
                'message' => 'Edit mode toggled off for ' . $this->serviceRole->name,
                'type' => 'success'
            ]);
        }
    }

    public function incrementYear() {
        $this->year++;
    }

    public function decrementYear() {
        $this->year--;
    }

    public function editServiceRole()
    {
        $this->toggleEditMode(true);
    }

    public function saveServiceRole()
    {
        try {
            $this->validate();
            $this->serviceRole->save();
            $this->dispatch('show-toast', [
                'message' => 'Service Role updated successfully.',
                'type' => 'success'
            ]);
            $this->toggleEditMode(false);
        } catch(\Exception $e) {
            $this->dispatch('show-toast', [
                'message' => 'Failed to update Service Role. ' . $e->getMessage(),
                'type' => 'error'
            ]);
        }
    }

    public function deleteServiceRole()
    {
        try {
            $count = ServiceRole::destroy($this->serviceRole->id);
            DB::commit();
            if ($count > 0) {
                $this->dispatch('show-toast', [
                    'message' => 'Service Role deleted successfully.',
                    'type' => 'success'
                ]);
                $this->serviceRole = null;
            } else {
                $this->dispatch('show-toast', [
                    'message' => 'Failed to delete Service Role.',
                    'type' => 'error'
                ]);
            }
        } catch(\Exception $e) {
            DB::rollBack();
            $this->dispatch('show-toast', [
                'message' => 'Failed to delete Service Role. ' . $e->getMessage(),
                'type' => 'error'
            ]);
        }
    }

    public function showInstructorModal($instructorId = null)
    {
        $this->instructor_id = $instructorId;
        $this->showInstructorModal = true;
    }

    public function showExtraHourModal()
    {
        $this->showExtraHourModal = true;
    }

    public function addExtraHour($extraHour)
    {
        try {
            $extraHour = ExtraHour::create($extraHour);
            $this->dispatch('show-toast', [
                'message' => 'Extra Hour created successfully.',
                'type' => 'success'
            ]);
            $this->showExtraHourModal = false;
        } catch(\Exception $e) {
            $this->dispatch('show-toast', [
                'message' => 'Failed to create Extra Hour. ' . $e->getMessage(),
                'type' => 'error'
            ]);
        }
    }

    public function closeModal() {
        $this->showInstructorModal = false;
        $this->showExtraHourModal = false;
    }

    public function render()
    {
        return view('livewire.manage-service-role');
    }
}
