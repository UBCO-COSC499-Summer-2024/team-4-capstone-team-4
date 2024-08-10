<?php

namespace App\Livewire;

use App\Exports\SvcroleExport;
use App\Models\Area;
use App\Models\AreaPerformance;
use App\Models\AuditLog;
use App\Models\DepartmentPerformance;
use App\Models\InstructorPerformance;
use App\Models\ServiceRole;
use App\Models\ExtraHour;
use App\Models\RoleAssignment;
use App\Models\User;
use App\Models\UserRole;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Livewire\WithPagination;
use Maatwebsite\Excel\Facades\Excel;

class ManageServiceRole extends Component
{
    use WithPagination;

    public $serviceRoleId = 1; // ID of the current service role
    public $serviceRole; // ServiceRole model instance
    public $isEditing = false; // Flag to indicate if the component is in edit mode
    public $year; // Year for the service role
    public $showInstructorModal = false; // Flag to show instructor modal
    public $showExtraHourModal = false; // Flag to show extra hour modal
    public $instructors; // List of instructors assigned to the service role
    public $allInstructors; // List of all instructors excluding those assigned
    public $instructor_id; // ID of the selected instructor
    public $role; // Role ID associated with the service role
    public $areas; // List of areas
    public $allRoles; // List of all service roles
    public $links; // Links to be used in the component
    public $assigner_id; // ID of the user assigning roles
    public $name; // Name of the service role
    public $description; // Description of the service role
    public $room; // Room for the service role
    public $roomB; // Building part of the room
    public $roomN; // Room number
    public $roomS; // Room suffix
    public $area_id; // Area ID for the service role
    public $extra_hours; // Extra hours for the service role
    public $audit_user; // Name of the user performing audit
    public $update; // Update flag for the service role
    protected $validExportOptions = ['csv', 'xlsx', 'pdf', 'text', 'print']; // Valid options for exporting data

    public $temp = [
        'name' => '',
        'description' => '',
        'year' => '',
        'monthly_hours' => [],
        'area_id' => '',
        'room' => '',
    ];
    public $monthly_hours; // Monthly hours for the service role

    protected $listeners = [
        'toggleEditMode' => 'toggleEditMode',
        'editServiceRole' => 'editServiceRole',
        'update-role' => 'saveServiceRole',
        'confirm-manage-delete' => 'confirmDelete',
        'confirm-manage-archive' => 'confirmArchive',
        'svcr-manage-delete' => 'deleteServiceRole',
        'svcr-manage-archive' => 'archiveServiceRole',
        'svcr-manage-unarchive' => 'unarchiveServiceRole',
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
        'refresh-component' => 'refresh',
    ];

    protected $rules = [
        'name' => 'required|string|max:255',
        'description' => 'nullable|string',
        'year' => 'required|integer',
        'monthly_hours.*' => 'nullable|integer|min:0|max:200',
        'area_id' => 'required|exists:areas,id',
        'roomB' => 'nullable|string|max:255',
        'roomN' => 'nullable|string|max:255',
        'roomS' => 'nullable|string|max:255',
        'room' => 'nullable|string|max:255',
    ];

    /**
     * Initialize the component with a service role ID and optional links.
     *
     * @param int $serviceRoleId
     * @param array $links
     */
    public function mount($serviceRoleId, $links = []) {
        $this->fetchServiceRole($serviceRoleId);
        $this->links = $links;
        $this->audit_user = User::find((int) Auth::id())->getName();
        $this->assigner_id = UserRole::where('user_id', Auth::id())->first()->id;
    }

    /**
     * Fetch and set the details of a service role by ID.
     *
     * @param int $id
     * @return void
     */
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
            'room' => $this->serviceRole->room,
        ];
        $this->allInstructors = UserRole::all()->where('role', 'instructor')
            ->whereNotIn('id', $this->instructors->pluck('id'));
        // Get all roles and areas
        $this->allRoles = ServiceRole::all();
        $this->areas = Area::all();
        $this->role = $this->serviceRole->id;
        $this->area_id = $this->serviceRole->area_id;
        $this->description = $this->serviceRole->description;
        $this->name = $this->serviceRole->name;
        $this->extra_hours = $this->serviceRole->extraHours;
        $this->room = $this->serviceRole->room;
        $this->roomB = $this->serviceRole->getRoom()['building'];
        $this->roomN = $this->serviceRole->getRoom()['number'];
        $this->roomS = $this->serviceRole->getRoom()['suffix'];
        return;
    }

    /**
     * Concatenate room components into a single room string.
     *
     * @return void
     */
    public function concatRoom() {
        $this->room = $this->roomB . ($this->roomN ? ' ' . $this->roomN : '') . ($this->roomS ? ' ' . $this->roomS : '');
        $this->room = trim($this->room);
    }

    /**
     * Convert short month names or numeric keys to full month names.
     *
     * @return void
     */
    public function fixMonthNames() {
        $monthlyHours = is_string($this->serviceRole->monthly_hours) ? json_decode($this->serviceRole->monthly_hours, true) : $this->serviceRole->monthly_hours;

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

        foreach ($monthlyHours as $key => $value) {
            $month = isset($monthMapping[$key]) ? $monthMapping[$key] : $key;
            if (array_key_exists($month, $fullMonthNames)) {
                $fullMonthNames[$month] = $value;
            }
        }

        $this->monthly_hours = $fullMonthNames;
    }

    /**
     * Update the service role's properties.
     *
     * @return void
     */
    public function saveServiceRole() {
        $this->validate();

        $this->serviceRole->name = $this->name;
        $this->serviceRole->description = $this->description;
        $this->serviceRole->area_id = $this->area_id;
        $this->serviceRole->year = $this->year;
        $this->serviceRole->monthly_hours = json_encode($this->monthly_hours);
        $this->serviceRole->room = $this->room;
        $this->serviceRole->save();

        $this->dispatch('show-toast', [
            'message' => 'Service role updated successfully!',
            'type' => 'success'
        ]);
    }

    /**
     * Toggle edit mode for the service role.
     *
     * @return void
     */
    public function toggleEditMode() {
        $this->isEditing = !$this->isEditing;
    }

    /**
     * Prepare the component for editing a service role.
     *
     * @param int $id
     * @return void
     */
    public function editServiceRole($id) {
        $this->fetchServiceRole($id);
        $this->toggleEditMode();
    }

    /**
     * Show the instructor modal.
     *
     * @return void
     */
    public function showInstructorModal() {
        $this->showInstructorModal = true;
    }

    /**
     * Close any open modals.
     *
     * @return void
     */
    public function closeModal() {
        $this->showInstructorModal = false;
        $this->showExtraHourModal = false;
    }

    /**
     * Add extra hours to the service role.
     *
     * @return void
     */
    public function addExtraHour() {
        // Code to add extra hours
        $this->showExtraHourModal = false;
    }

    /**
     * Save the assigned instructor to the service role.
     *
     * @return void
     */
    public function saveInstructor() {
        // Code to save instructor
        $this->showInstructorModal = false;
    }

    /**
     * Confirm deletion of a service role.
     *
     * @return void
     */
    public function confirmDelete() {
        // Code to confirm deletion
    }

    /**
     * Delete a service role.
     *
     * @return void
     */
    public function deleteServiceRole() {
        // Code to delete service role
    }

    /**
     * Confirm archiving of a service role.
     *
     * @return void
     */
    public function confirmArchive() {
        // Code to confirm archiving
    }

    /**
     * Archive a service role.
     *
     * @return void
     */
    public function archiveServiceRole() {
        // Code to archive service role
    }

    /**
     * Unarchive a service role.
     *
     * @return void
     */
    public function unarchiveServiceRole() {
        // Code to unarchive service role
    }

    /**
     * Remove an instructor from the service role.
     *
     * @return void
     */
    public function removeInstructor() {
        // Code to remove instructor
    }

    /**
     * Confirm the removal of an instructor from the service role.
     *
     * @return void
     */
    public function confirmDeleteInstructor() {
        // Code to confirm removal of instructor
    }

    /**
     * Decrement the year by one.
     *
     * @return void
     */
    public function decrementYear() {
        $this->year--;
    }

    /**
     * Increment the year by one.
     *
     * @return void
     */
    public function incrementYear() {
        $this->year++;
    }

    /**
     * Export the service role data.
     *
     * @param string $type
     * @return void
     */
    public function export($type) {
        if (!in_array($type, $this->validExportOptions)) {
            $this->dispatch('show-toast', [
                'message' => 'Invalid export type.',
                'type' => 'error'
            ]);
            return;
        }

        return Excel::download(new SvcroleExport($this->serviceRole), "service_role_{$this->serviceRole->id}.{$type}");
    }

    /**
     * Refresh the component.
     *
     * @return void
     */
    public function refresh() {
        $this->fetchServiceRole($this->serviceRoleId);
    }

    /**
     * Render the component view.
     *
     * @return \Illuminate\View\View
     */
    public function render() {
        return view('livewire.manage-service-role');
    }
}

