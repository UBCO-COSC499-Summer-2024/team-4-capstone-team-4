<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\ExtraHour;
use App\Models\Area;
use App\Models\AreaPerformance;
use App\Models\DepartmentPerformance;
use App\Models\InstructorPerformance;
use App\Models\ServiceRole;
use App\Models\User;
use App\Models\UserRole;

class ExtraHourForm extends Component
{
    public $name = '';
    public $description = '';
    public $hours = 0;
    public $year;
    public $month;
    public $assigner_id;
    public $instructor_id;
    public $area_id;
    public $areas;
    public $user_roles;
    public $room;
    public $roomB;
    public $roomN;
    public $roomS;
    public $showExtraHourForm;
    public $audit_user;
    public $show;

    protected $listeners = [
        'showExtraHourForm' => 'showForm'
    ];

    protected $rules = [
        'name' => 'required|string|max:255',
        'description' => 'nullable|string',
        'hours' => 'required|integer',
        'year' => 'required|integer',
        'month' => 'required|integer|min:1|max:12',
        'assigner_id' => 'required|exists:user_roles,id',
        'instructor_id' => 'nullable|exists:user_roles,id',
        'area_id' => 'required|exists:areas,id',
    ];

    /**
     * Initialize component properties and set default values.
     *
     * @param bool $showExtraHourForm
     * @return void
     */
    public function mount($showExtraHourForm = true) {
        $this->year = date('Y');
        $this->month = date('n');
        $this->user_roles = UserRole::where('role', 'instructor')->get();
        $this->areas = Area::all();
        $this->assigner_id = UserRole::where('user_id', auth()->id())->first()->id ?? null;
        $this->area_id = null;
        $this->showExtraHourForm = $showExtraHourForm;
        $this->audit_user = User::find(auth()->id())->getName();
    }

    /**
     * Concatenate room-related properties into a single room string.
     *
     * @return void
     */
    public function concatRoom() {
        $this->room = $this->roomB . ($this->roomN ? ' ' . $this->roomN : '') . ($this->roomS ? ' ' . $this->roomS : '');
        $this->room = trim($this->room);
    }

    /**
     * Save the extra hour data and update related records.
     *
     * @return void
     */
    public function save() {
        try {
            $this->concatRoom();

            $clear = false;
            $this->validate();

            $instructors = UserRole::instructors();

            if ($this->instructor_id == null) {
                $this->instructor_id = (int) $this->instructor_id;
                foreach ($instructors as $instructor) {
                    $extraHour = ExtraHour::create([
                        'name' => $this->name,
                        'description' => $this->description,
                        'hours' => $this->hours,
                        'year' => $this->year,
                        'month' => $this->month,
                        'room' => $this->room,
                        'assigner_id' => $this->assigner_id,
                        'instructor_id' => $instructor->id,
                        'area_id' => $this->area_id,
                    ]);

                    if ($extraHour) {
                        $this->dispatch('show-toast', [
                            'message' => 'Extra Hour added successfully.',
                            'type' => 'success'
                        ]);
                        $clear = true;
                        ExtraHour::audit('create', [
                            'operation_type' => 'CREATE',
                            'new_value' => json_encode($extraHour),
                        ], $this->audit_user . ' added a new Extra Hour');
                    } else {
                        $this->dispatch('show-toast', [
                            'message' => 'Failed to add Extra Hour.',
                            'type' => 'error'
                        ]);
                    }

                    // Update instructor performance data
                    $instructorPerformance = InstructorPerformance::where('instructor_id', $instructor->id)
                        ->where('year', $this->year)
                        ->first();
                    if ($instructorPerformance) {
                        $instructorPerformance->addHours($this->month, $this->hours);
                    } else {
                        $this->dispatch('show-toast', [
                            'message' => 'Failed to update Instructor Performance.',
                            'type' => 'error'
                        ]);
                    }
                }
            } else {
                $this->instructor_id = (int) $this->instructor_id;
                $extraHour = ExtraHour::create([
                    'name' => $this->name,
                    'description' => $this->description,
                    'hours' => $this->hours,
                    'year' => $this->year,
                    'month' => $this->month,
                    'room' => $this->room,
                    'assigner_id' => $this->assigner_id,
                    'instructor_id' => $this->instructor_id,
                    'area_id' => $this->area_id,
                ]);

                if ($extraHour) {
                    $this->dispatch('show-toast', [
                        'message' => 'Extra Hour added successfully.',
                        'type' => 'success'
                    ]);
                    $clear = true;

                    ExtraHour::audit('create', [
                        'operation_type' => 'CREATE',
                        'new_value' => json_encode($extraHour),
                    ], $this->audit_user . ' added a new Extra Hour');
                } else {
                    $this->dispatch('show-toast', [
                        'message' => 'Failed to add Extra Hour.',
                        'type' => 'error'
                    ]);
                }

                $instructorPerformance = InstructorPerformance::where('instructor_id', $this->instructor_id)
                    ->where('year', $this->year)
                    ->first();
                if ($instructorPerformance) {
                    $instructorPerformance->addHours($this->month, $this->hours);
                } else {
                    $this->dispatch('show-toast', [
                        'message' => 'Failed to update Instructor Performance.',
                        'type' => 'error'
                    ]);
                }
            }

            // Check if no instructors are assigned to the service role
            if (count($instructors) == 0) {
                $this->dispatch('show-toast', [
                    'message' => 'No instructor assigned to this service role.',
                    'type' => 'error'
                ]);
                return;
            }

            if ($this->area_id !== null) {
                $areaPerformance = AreaPerformance::where('area_id', $this->area_id)
                    ->where('year', $this->year)
                    ->first();
                if ($areaPerformance) {
                    $areaPerformance->addHours($this->month, $this->hours);
                } else {
                    $this->dispatch('show-toast', [
                        'message' => 'Failed to update Area Performance.',
                        'type' => 'error'
                    ]);
                }

                $departmentPerformance = Area::find($this->area_id)->department->departmentPerformance()
                    ->where('year', $this->year)
                    ->first();
                if ($departmentPerformance) {
                    $departmentPerformance->addHours($this->month, $this->hours);
                } else {
                    $this->dispatch('show-toast', [
                        'message' => 'Failed to update Department Performance.',
                        'type' => 'error'
                    ]);
                }
            } else {
                $this->dispatch('show-toast', [
                    'message' => 'Failed to update Area Performance.',
                    'type' => 'error'
                ]);
            }

            if ($clear) {
                $this->resetForm();
                $this->dispatch('closeModal');
            }
        } catch(\Illuminate\Validation\ValidationException $e) {
            throw $e;
        } catch (\Exception $e) {
            $this->dispatch('show-toast', [
                'message' => 'Failed to add Extra Hour.',
                'type' => 'error'
            ]);
            ExtraHour::audit('create error', [
                'operation_type' => 'CREATE',
            ], $this->audit_user . ' encountered an error while adding a new Extra Hour. Error: ' . $e->getMessage());
        }
    }

    /**
     * Close the modal form without saving.
     *
     * @return void
     */
    public function cancel() {
        $this->dispatch('closeModal');
    }

    /**
     * Show the extra hour form.
     *
     * @return void
     */
    public function showForm() {
        $this->showExtraHourForm = true;
    }

    /**
     * Reset the form fields to their default values.
     *
     * @return void
     */
    public function resetForm() {
        $this->name = '';
        $this->description = '';
        $this->hours = 0;
        $this->year = date('Y');
        $this->month = date('n');
        $this->room = null;
        $this->roomB = '';
        $this->roomN = '';
        $this->roomS = '';
        $this->assigner_id = UserRole::where('user_id', auth()->id())->first()->id ?? null;
        $this->instructor_id = null;
        $this->area_id = null;
    }

    /**
     * Render the view for the extra hour form component.
     *
     * @return \Illuminate\View\View
     */
    public function render() {
        return view('livewire.extra-hour-form');
    }
}
