<?php

namespace App\Livewire;

use App\Models\Area;
use App\Models\CourseSection;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class CoursedetailsTableRow extends Component {
    // State variables
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

    // Event listeners
    protected $listeners = [
        'toggleEdit' => 'toggleEdit',
        'saveItem' => 'saveItem',
        'enableEdit' => 'enableEdit',
        'cancelEdit' => 'cancelEdit',
        'archiveCourse' => 'archiveCourse',
    ];

    // Validation rules
    protected $rules = [
        'departmentId' => 'required|exists:areas,id',
        'enrolledStudents' => 'required|numeric',
        'droppedStudents' => 'required|numeric',
        'courseCapacity' => 'required|numeric',
        'room' => 'required|string',
    ];

    /**
     * Initialize component with course data.
     *
     * @param  \App\Models\CourseSection  $course
     * @return void
     */
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

    /**
     * Toggle the editing state for the course.
     *
     * @param  int  $id
     * @return void
     */
    public function toggleEdit($id) {
        if ($this->id == $id) {
            $this->isEditing = !$this->isEditing;
        }
    }

    /**
     * Enable editing mode for the course.
     *
     * @param  int  $id
     * @return void
     */
    public function enableEdit($id) {
        if ($this->id == $id) {
            $this->isEditing = true;
        }
    }

    /**
     * Cancel editing mode for the course.
     *
     * @param  int  $id
     * @return void
     */
    public function cancelEdit($id) {
        if ($this->id == $id) {
            $this->isEditing = false;
        }
    }

    /**
     * Save the updated course details.
     *
     * @param  int  $id
     * @return void
     */
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

                // Notify user of successful save
                $this->dispatch('show-toast', [
                    'message' => 'Changes saved successfully',
                    'type' => 'success'
                ]);

                // Log audit information
                CourseSection::audit('update success', [
                    'operation_type' => 'UPDATE',
                    'old_value' => json_encode($oldValue),
                    'new_value' => json_encode($course),
                ], $this->user->getName() . ' updated courses successfully');

                $this->isEditing = false;
            } catch(\Illuminate\Validation\ValidationException $e) {
                // Handle validation exceptions
                throw $e;
            } catch(\Exception $e) {
                // Notify user of an error
                $this->dispatch('show-toast', [
                    'message' => 'An error occurred while saving the changes',
                    'type' => 'error'
                ]);

                // Log audit information for errors
                CourseSection::audit('update error', [
                    'operation_type' => 'UPDATE',
                ], $this->user->getName() . ' failed to update courses. Error: ' . $e->getMessage());
            }
        }
    }

    /**
     * Archive the course.
     *
     * @param  int  $id
     * @return void
     */
    public function archiveCourse($id) {
        $id = (int) $id;
        if ($this->id === $id) {
            try {
                $course = CourseSection::find($id);
                $oldValue = $course->toArray();
                $course->archived = true;
                $course->save();

                // Notify user of successful archive
                $this->dispatch('show-toast', [
                    'message' => 'Course archived successfully',
                    'type' => 'success'
                ]);

                // Log audit information
                CourseSection::audit('archive success', [
                    'operation_type' => 'UPDATE',
                    'old_value' => json_encode($oldValue),
                    'new_value' => json_encode($course),
                ], $this->user->getName() . ' archived course successfully');
            } catch(\Exception $e) {
                // Notify user of an error
                $this->dispatch('show-toast', [
                    'message' => 'An error occurred while archiving the course',
                    'type' => 'error'
                ]);

                // Log audit information for errors
                CourseSection::audit('archive error', [
                    'operation_type' => 'UPDATE',
                ], $this->user->getName() . ' failed to archive course. Error: ' . $e->getMessage());
            }
        }
    }

    /**
     * Render the component view.
     *
     * @return \Illuminate\View\View
     */
    public function render() {
        $user = Auth::user();
        $canEdit = $user->hasRoles(['admin', 'dept_head', 'dept_staff']);
        return view('livewire.coursedetails-table-row', ['canEdit' => $canEdit]);
    }
}

