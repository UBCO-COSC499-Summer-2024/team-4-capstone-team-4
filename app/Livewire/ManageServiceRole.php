<?php

namespace App\Livewire;

use App\Exports\SvcroleExport;
use App\Models\Area;
use App\Models\AreaPerformance;
use App\Models\DepartmentPerformance;
use App\Models\InstructorPerformance;
use App\Models\ServiceRole;
use App\Models\ExtraHour;
use App\Models\RoleAssignment;
use App\Models\UserRole;
use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Livewire\WithPagination;
use Maatwebsite\Excel\Facades\Excel;

class ManageServiceRole extends Component
{
    use WithPagination;
    public $serviceRoleId = 1;
    public $serviceRole;
    public $isEditing = false;
    public $year;
    public $showInstructorModal = false;
    public $showExtraHourModal = false;
    public $instructors;
    public $allInstructors;
    public $instructor_id;
    public $role;
    public $areas;
    public $allRoles;
    public $links;
    public $assigner_id;
    public $name;
    public $description;
    public $area_id;
    public $extra_hours;
    protected $validExportOptions = [
        'csv', 'xlsx', 'pdf', 'text', 'print'
    ];
    public $temp = [
        'name' => '',
        'description' => '',
        'year' => '',
        'monthly_hours' => [],
        'area_id' => '',
    ];
    public $monthly_hours;

    protected $listeners = [
        'toggleEditMode' => 'toggleEditMode',
        'editServiceRole' => 'editServiceRole',
        'update-role' => 'saveServiceRole',
        'confirm-manage-delete' => 'confirmDelete',
        'confirm-manage-archive' => 'confirmArchive',
        'svcr-manage-delete' => 'deleteServiceRole',
        'svcr-manage-archive' => 'archiveServiceRole',
        'svcr-manage-unarchive' => 'archiveServiceRole',
        'showInstructorModal' => 'showInstructorModal',
        'showExtraHourModal' => 'showExtraHourModal',
        'addExtraHour' => 'addExtraHour',
        'closeModal' => 'closeModal',
        'save-instructor' => 'saveInstructor',
        'confirm-remove-instructor' => 'confirmDeleteInstructor',
        'sr-remove-instructor' => 'removeInstructor',
        'dec-year' => 'decrementYear',
        'inc-year' => 'incrementYear',
        'export-role' => 'export',
    ];

    protected $rules = [
        'name' => 'required|string|max:255',
        'description' => 'nullable|string',
        'year' => 'required|integer',
        'monthly_hours.*' => 'nullable|integer|min:0|max:744',
        'area_id' => 'required|exists:areas,id',
    ];

    public function mount($serviceRoleId, $links = [])
    {
        $this->fetchServiceRole($serviceRoleId);
        $this->links = $links;
        $this->assigner_id = UserRole::where('user_id', auth()->id())->first()->id;
    }

    public function fetchServiceRole($id) {
        $this->serviceRole = ServiceRole::find($id);
        if (!$this->serviceRole) {
            $this->dispatch('show-toast', [
                'message' => 'Service Role not found.',
                'type' => 'error'
            ]);
            return redirect()->route('svcroles');
        }
        $this->serviceRoleId = $id;
        $this->serviceRole = ServiceRole::find($this->serviceRole->id);
        $this->year = $this->serviceRole->year;
        $this->instructors = $this->serviceRole->instructors;
        $this->monthly_hours = $this->serviceRole->monthly_hours ?? [];
        $this->fixMonthNames();
        $this->temp = [
            'name' => $this->serviceRole->name,
            'description' => $this->serviceRole->description,
            'year' => $this->serviceRole->year,
            'monthly_hours' => $this->monthly_hours,
            'area_id' => $this->serviceRole->area_id,
        ];
        $this->allInstructors = UserRole::all()->where('role', 'instructor')
            ->whereNotIn('id', $this->instructors->pluck('id'));
        // where not already assigned
        $this->allRoles = ServiceRole::all();
        $this->areas = Area::all();
        $this->role = $this->serviceRole->id;
        $this->area_id = $this->serviceRole->area_id;
        $this->description = $this->serviceRole->description;
        $this->name = $this->serviceRole->name;
        $this->extra_hours = $this->serviceRole->extraHours;
    }

    public function fixMonthNames()
    {
        // Decode the JSON string to an associative array
        // $monthlyHours = $this->serviceRole->monthly_hours;
        // if $this->serviceRole->monthly_hours is type of string then decode else leave as is
        $monthlyHours = is_string($this->serviceRole->monthly_hours) ? json_decode($this->serviceRole->monthly_hours, true) : $this->serviceRole->monthly_hours;

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

    public function confirmDelete() {
        $this->dispatch('confirmDelete', [
            'message' => 'Are you sure you want to delete this Service Role?',
            'id' => $this->serviceRole->id,
            'model' => 'sr_manage_delete'
        ]);
    }

    public function confirmArchive() {
        $isArchived = $this->serviceRole->archived;
        $this->dispatch('confirmArchive', [
            'message' => !$isArchived ? 'Are you sure you want to archive this Service Role?' : 'Are you sure you want to unarchive this Service Role?',
            'id' => $this->serviceRole->id,
            'model' => !$isArchived ? 'sr_manage_archive' : 'sr_manage_unarchive'
        ]);
    }

    public function navigate($route) {
        $url = $route;
        header("Location: $url");
        exit();
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

    public function save() {
        $this->saveServiceRole();
    }

    public function saveServiceRole() {
        try {
            $this->serviceRole->name = $this->name;
            $this->serviceRole->description = $this->description;
            $this->serviceRole->year = $this->year;
            $this->serviceRole->area_id = $this->area_id;
            $this->serviceRole->monthly_hours = is_array($this->monthly_hours) ? json_encode($this->monthly_hours) : $this->monthly_hours;
            $this->validate();
            $this->serviceRole->save();
            $this->serviceRole->refresh();
            $this->fetchServiceRole($this->serviceRole->id);
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

    public function deleteServiceRole($id) {
        try {
            $count = ServiceRole::destroy($id);
            DB::commit();
            if ($count > 0) {
                $this->dispatch('show-toast', [
                    'message' => 'Service Role deleted successfully.',
                    'type' => 'success'
                ]);
                $this->serviceRole = null;
                $this->navigate(route('svcroles'));
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
        $this->fetchServiceRole($this->serviceRoleId);
        return view('livewire.manage-service-role', [
            'serviceRole' => $this->serviceRole,
            'instructors' => $this->instructors,
            'allInstructors' => $this->allInstructors,
            'allRoles' => $this->allRoles,
            'areas' => $this->areas,
            'links' => $this->links,
        ]);
    }

    public function saveInstructor() {
        try {
            $role_assignment_rules = [
                'instructor_id' => 'required|exists:user_roles,id',
                'role' => 'required|exists:service_roles,id',
                'assigner_id' => 'required|exists:user_roles,id',
            ];
            $this->validate($role_assignment_rules);
            // check for duplicates
            $roleAssignment = RoleAssignment::where('instructor_id', $this->instructor_id)->where('service_role_id', $this->role)->first();
            if ($roleAssignment) {
                $this->dispatch('show-toast', [
                    'message' => 'Instructor already assigned to this role.',
                    'type' => 'error'
                ]);
                return;
            }
            $roleAssignment = new RoleAssignment();
            $roleAssignment->assigner_id = $this->assigner_id;
            $roleAssignment->instructor_id = $this->instructor_id;
            $roleAssignment->service_role_id = (int) $this->role;
            $roleAssignment->save();
            $this->instructors = $this->serviceRole->instructors;
            $this->dispatch('show-toast', [
                'message' => 'Instructor assigned successfully.',
                'type' => 'success'
            ]);
            $this->showInstructorModal = false;
            $instructorPerformance = InstructorPerformance::where('instructor_id', $this->instructor_id)->where('year', $this->year)->first();
            if ($instructorPerformance) {
                $instructorPerformance->updateTotalHours($this->monthly_hours);
                $this->dispatch('show-toast', [
                    'message' => 'Instructor Performance updated successfully.',
                    'type' => 'success'
                ]);

                $url = route('svcroles.manage.id', (int) $this->serviceRoleId);
                header("Location: $url");
                exit();
            } else {
                $this->dispatch('show-toast', [
                    'message' => 'Instructor Performance not found.',
                    'type' => 'error'
                ]);
            }
        } catch(\Exception $e) {
            // if development then toast full error message, else toast generic error message
            // check for error types for example duplicates, etc
            if (config('app.env') === 'local') {
                $this->dispatch('show-toast', [
                    'message' => 'Failed to assign Instructor. ' . $e->getMessage(),
                    'type' => 'error'
                ]);
            } else {
                $this->dispatch('show-toast', [
                    'message' => 'Failed to assign Instructor.',
                    'type' => 'error'
                ]);
            }
        }
    }

    public function confirmDeleteInstructor($id) {
        $this->dispatch('confirmDelete', [
            'message' => 'Are you sure you want to remove this Instructor?',
            'id' => $id,
            'model' => 'sr_role_assignment'
        ]);
    }

    public function removeInstructor($id) {
        try {
            $roleAssignment = RoleAssignment::find($id)->where('service_role_id', $this->role)->first();
            $roleAssignment->delete();
            $this->instructors = $this->serviceRole->instructors;
            $this->dispatch('show-toast', [
                'message' => 'Instructor removed successfully.',
                'type' => 'success'
            ]);
        } catch(\Exception $e) {
            $this->dispatch('show-toast', [
                'message' => 'Failed to remove Instructor. ' . $e->getMessage(),
                'type' => 'error'
            ]);
        }
    }

    public function export($format)
    {
        if (!in_array($format, $this->validExportOptions)) {
            $this->dispatch('show-toast', [
                'message' => 'Invalid export format.',
                'type' => 'error'
            ]);
            return;
        }

        if ($format === 'print') {
            $this->dispatch('toast', [
                'message' => 'Printing...',
                'type' => 'info'
            ]);
            return;
        }

        if ($format === 'text') {
            $this->dispatch('toast', [
                'message' => 'Text export not supported.',
                'type' => 'error'
            ]);
            return;
        }

        if ($format === 'pdf' || $format === 'xlsx' || $format === 'csv') {
            // $serviceRole = ServiceRole::find($this->serviceRole);
            return Excel::download(new SvcroleExport($this->serviceRole), 'service_roles.' . $format);
        }
    }

    public function archiveServiceRole($id) {
        try {
            $serviceRole = ServiceRole::find($id);
            $serviceRole->archived = !$serviceRole->archived;
            $serviceRole->save();
            $this->dispatch('show-toast', [
                'message' => 'Service Role archived successfully.',
                'type' => 'success'
            ]);
            $this->navigate(route('svcroles.manage.id', (int) $this->serviceRoleId));
        } catch(\Exception $e) {
            $this->dispatch('show-toast', [
                'message' => 'Failed to archive Service Role. ' . $e->getMessage(),
                'type' => 'error'
            ]);
        }
    }
}
