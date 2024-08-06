<?php

namespace App\Livewire\Components;

use App\Models\RoleAssignment;
use App\Models\ServiceRole;
use App\Models\User;
use App\Models\UserRole;
use Livewire\Component;

class AssignInstructorModal extends Component {
    public $allServiceRoles;
    public $instructors;
    public $svcrole; // if specified, set default selected to this service role
    public $selectedServiceRoles = [];
    public $selectedInstructors = [];
    public $showAssignInstructorModal;
    public $instructorSearchQuery = '';
    public $serviceRoleSearchQuery = '';
    public $audit_user;

    protected $listeners = ['assignInstructor'];

    public function mount($showAssignInstructorModal = true, $svcrole = null) {
        $this->showAssignInstructorModal = $showAssignInstructorModal;
        $this->svcrole = $svcrole;
        $this->audit_user = User::find((int) auth()->user()->id)->getName();
        $this->loadInstructors();
        $this->loadServiceRoles();
    }

    public function loadInstructors() {
        $this->instructors = UserRole::where('role', 'instructor')
            ->whereHas('user', function($query) {
                $query->where('firstname', 'like', '%' . $this->instructorSearchQuery . '%')
                    ->orWhere('lastname', 'like', '%' . $this->instructorSearchQuery . '%');
            })
            ->get();
    }

    public function loadServiceRoles() {
        $this->allServiceRoles = ServiceRole::where('name', 'like', '%' . $this->serviceRoleSearchQuery . '%')->get();
    }

    public function selectServiceRole($id, $name) {
        if (array_key_exists($id, $this->selectedServiceRoles)) {
            unset($this->selectedServiceRoles[$id]);
        } else {
            $this->selectedServiceRoles[$id] = $name;
        }
    }

    public function selectInstructor($id, $name) {
        if (array_key_exists($id, $this->selectedInstructors)) {
            unset($this->selectedInstructors[$id]);
        } else {
            $this->selectedInstructors[$id] = $name;
        }
    }

    public function removeInstructor($id) {
        unset($this->selectedInstructors[$id]);
    }

    public function removeServiceRole($id) {
        unset($this->selectedServiceRoles[$id]);
    }

    public function updatedInstructorSearchQuery() {
        $this->loadInstructors();
    }

    public function updatedServiceRoleSearchQuery() {
        $this->loadServiceRoles();
    }

    public function render() {
        return view('livewire.components.assign-instructor-modal');
    }

    public function assignInstructor($instructor) {
        // RoleAssignments
        try {
            $assigned = []; // Array of assignments with status and messages
            $newValues = []; // Array to store new values for auditing

            // Retrieve existing assignments for the instructor
            $existingAssignments = RoleAssignment::where('instructor_id', $instructor)
                ->pluck('service_role_id')
                ->toArray();

            foreach ($this->selectedServiceRoles as $serviceRoleId => $serviceRoleName) {
                // Check if the instructor is already assigned to this service role
                if (in_array($serviceRoleId, $existingAssignments)) {
                    $assigned[] = [
                        'status' => 'warning',
                        'message' => 'Instructor is already assigned to ' . $serviceRoleName
                    ];
                    continue; // Skip this assignment
                }

                // Create a new assignment
                $assignment = new RoleAssignment();
                $assignment->instructor_id = $instructor;
                $assignment->service_role_id = $serviceRoleId;

                // Get the assigner's UserRole ID, excluding the instructor role, use highest role (dept_staff < dept_head < admin)
                $assignerRoles = UserRole::where('user_id', auth()->user()->id)
                    ->where('role', '<>', 'instructor')
                    ->pluck('id');

                if ($assignerRoles->isEmpty()) {
                    // toast
                    $this->dispatch('show-toast', [
                        'message' => 'You do not have the necessary permissions to assign an instructor.',
                        'type' => 'error'
                    ]);

                    // audit
                    RoleAssignment::audit('assign error', ['operation_type' => 'UPDATE'], $this->audit_user . ' does not have the necessary permissions to assign an instructor');

                    return;
                }

                // Select the first available role (or apply custom logic if needed)
                $assignment->assigner_id = $assignerRoles->first();
                $assignment->save();
                $newValues[] = [
                    'assignmentId' => $assignment->id,
                    'value' => $assignment->getAttributes()
                ];
                $assigned[] = [
                    'status' => 'success',
                    'message' => 'Instructor assigned to ' . $serviceRoleName
                ];
            }

            // Provide feedback to the user
            $this->dispatch('show-toast', [
                'message' => 'Instructor assigned successfully.',
                'type' => 'success'
            ]);

            // count where assigned is success
            $assignedto = count(array_filter($assigned, function($a) {
                return $a['status'] === 'success';
            }));

            // Audit the new assignments
            $newValue = json_encode($newValues);
            RoleAssignment::audit('assign', [
                'operation_type' => 'CREATE',
                'new_value' => $newValue
            ], $this->audit_user . ' assigned an instructor to ' . $assignedto . ' service roles');


        } catch (\Exception $e) {
            // Handle any errors
            $this->dispatch('show-toast', [
                'message' => 'An error occurred while assigning the instructor.',
                'type' => 'error'
            ]);
            RoleAssignment::audit('assign error', ['operation_type' => 'UPDATE'], $this->audit_user . ' encountered an error while assigning an instructor: ' . $e->getMessage());
        }
    }

    public function assign() {
        foreach ($this->selectedInstructors as $instructorId => $instructorName) {
            $this->assignInstructor($instructorId);
        }
        // reset
        $this->selectedServiceRoles = [];
        $this->selectedInstructors = [];
        $this->loadInstructors();
        $this->loadServiceRoles();
    }
}
