<?php

namespace App\Livewire;

use App\Models\Area;
use App\Models\CourseSection;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class CoursedetailsTableRow extends Component {
    public $isEditing = false;
    public $selected = false;
    public $id;
    public $course;
    public $courseName;
    public $departmentName;
    public $departmentId;
    public $instructorName;
    public $enrolledStudents;
    public $droppedStudents;
    public $courseCapacity;
    public $room;
    public $timings;
    public $user;
    public $areas;
    public $seiData;

    protected $listeners = [
        'toggleEdit' => 'toggleEdit',
        'saveItem' => 'saveItem',
        'enableEdit' => 'enableEdit',
        'cancelEdit' => 'cancelEdit',
    ];

    protected $rules = [
        'departmentId' => 'required|exists:areas,id',
        'enrolledStudents' => 'required|numeric',
        'droppedStudents' => 'required|numeric',
        'courseCapacity' => 'required|numeric',
        'room' => 'required|string',
    ];

    public function mount($course) {
        $this->course = $course;
        $this->areas = Area::all();
        $this->user = User::find(Auth::id());
        $this->isEditing = false;
        $this->selected = false;
        $this->id = $course->id;
        $this->courseName = $course->name;
        $this->departmentName = $course->departmentName;
        $this->departmentId = Area::where('name', $this->departmentName)->first()->id;
        $this->enrolledStudents = $course->enrolled;
        $this->droppedStudents = $course->dropped;
        $this->courseCapacity = $course->capacity;
        $this->room = $course->room;
        $this->timings = $course->timings;
        $this->seiData = $course->averageRating;
        $this->instructorName = $course->instructorName;
    }

    public function toggleEdit($id) {
        if ($this->id == $id) {
            $this->isEditing = !$this->isEditing;
        }
    }

    public function enableEdit($id) {
        if ($this->id == $id) {
            $this->isEditing = true;
        }
    }

    public function cancelEdit($id) {
        if ($this->id == $id) {
            $this->isEditing = false;
        }
    }

    public function saveItem($id) {
        $id = (int) $id;
        if ($this->id === $id) {
            try {
                $course = CourseSection::find($id);
                $oldValue = $course->toArray();
                $this->validate();
                $course->area_id = $this->departmentId;
                $course->enroll_end = $this->enrolledStudents;
                $course->dropped = $this->droppedStudents;
                $course->capacity = $this->courseCapacity;
                $course->room = $this->room;
                $course->save();
                $this->dispatch('show-toast', [
                    'message' => 'Changes saved successfully',
                    'type' => 'success'
                ]);
                CourseSection::audit('update success', [
                    'operation_type' => 'UPDATE',
                    'old_value' => json_encode($oldValue),
                    'new_value' => json_encode($course),
                ], $this->user->getName() . ' updated courses successfully');

                $this->isEditing = false;
            } catch(\Illuminate\Validation\ValidationException $e) {
                throw $e;
            } catch(\Exception $e) {
                $this->dispatch('show-toast', [
                    'message' => 'An error occurred while saving the changes',
                    'type' => 'error'
                ]);
                CourseSection::audit('update error', [
                    'operation_type' => 'UPDATE',
                ], $this->user->getName() . ' failed to update courses. Error: ' . $e->getMessage());
            }
        }
    }

    public function render() {
        return view('livewire.coursedetails-table-row');
    }
}
