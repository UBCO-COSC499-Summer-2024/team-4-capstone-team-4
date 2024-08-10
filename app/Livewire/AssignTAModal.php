<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\TeachingAssistant;
use App\Models\User;
use App\Models\CourseSection;
use Illuminate\Support\Facades\Log;

class AssignTAModal extends Component
{
    public $tas = [];
    public $instructors = [];
    public $selectedCourses = [];
    public $selectedTAs = [];
    public $showModal = false;
    public $showConfirmationModal = false;

    /**
     * Initialize component and load data.
     */
    public function mount()
    {
        $this->loadData();
    }

    /**
     * Load teaching assistants and instructors into component properties.
     */
    public function loadData()
    {
        $this->tas = TeachingAssistant::all();
        $this->instructors = User::whereHas('roles', function ($query) {
            $query->where('role', 'instructor');
        })->get();
    }

    /**
     * Open the modal for assigning teaching assistants.
     */
    public function openModal()
    {
        $this->showModal = true;
        $this->resetForm();
    }

    /**
     * Close the modal for assigning teaching assistants.
     */
    public function closeModal()
    {
        $this->showModal = false;
    }

    /**
     * Open the confirmation modal after successful assignment.
     */
    public function openConfirmationModal()
    {
        $this->showConfirmationModal = true;
    }

    /**
     * Close the confirmation modal.
     */
    public function closeConfirmationModal()
    {
        $this->showConfirmationModal = false;
    }

    /**
     * Assign selected teaching assistants to courses.
     */
    public function assignTA()
    {
        foreach ($this->selectedTAs as $selected) {
            $taId = $selected['ta_id'] ?? null;
            $instructorId = $selected['instructor_id'] ?? null;
            $courseId = $selected['course_id'] ?? null;

            if ($taId && $instructorId && $courseId) {
                $courseSection = CourseSection::find($courseId);
                if ($courseSection) {
                    $courseSection->teachingAssistants()->attach($taId);
                }
            }
        }

        $this->closeModal();
        $this->openConfirmationModal();
        $this->emit('taAssigned');
    }

    /**
     * Add a new entry for a teaching assistant assignment to the form.
     */
    public function addMore()
    {
        $this->selectedTAs[] = ['ta_id' => '', 'instructor_id' => '', 'course_id' => ''];
    }

    /**
     * Reset the form fields to their initial state.
     */
    public function resetForm()
    {
        $this->selectedTAs = [['ta_id' => '', 'instructor_id' => '', 'course_id' => '']];
        $this->selectedCourses = [];
    }

    /**
     * Handle updates to the selected teaching assistants.
     * 
     * @param mixed $value The new value.
     * @param string $name The name of the field being updated.
     */
    public function updatedSelectedTAs($value, $name)
    {
        Log::info('updatedSelectedTAs triggered:', ['value' => $value, 'name' => $name]);

        if (str_contains($name, 'instructor_id')) {
            $id = (int) explode('.', $name)[1];  // Extracting the numeric index
            Log::info('Instructor changed, updating courses for index:', ['index' => $id]);
            $this->updateCourses($id);
        }
    }

    /**
     * Update the list of courses based on the selected instructor and TA.
     * 
     * @param int $index The index of the selected TA.
     */
    public function updateCourses($index)
    {
        $instructorId = $this->selectedTAs[$index]['instructor_id'] ?? null;
        $taId = $this->selectedTAs[$index]['ta_id'] ?? null;

        if ($instructorId && $taId) {
            // Fetch courses assigned to both the selected instructor and TA
            $courses = CourseSection::whereHas('teaches', function ($query) use ($instructorId) {
                $query->where('instructor_id', $instructorId);
            })
            ->whereHas('teachingAssistants', function ($query) use ($taId) {
                $query->where('ta_id', $taId);
            })
            ->get();

            $formattedCourses = $courses->map(function ($course) {
                return [
                    'id' => $course->id,
                    'formattedName' => sprintf('%s %s %s - %s%s %s',
                        $course->prefix,
                        $course->number,
                        $course->section,
                        $course->year,
                        $course->session,
                        $course->term
                    ),
                ];
            })->toArray();

            $this->selectedCourses[$index] = $formattedCourses;
            Log::info('Fetched courses for instructor ID ' . $instructorId . ' and TA ID ' . $taId, $formattedCourses);
        } else {
            $this->selectedCourses[$index] = [];
            Log::info('No valid instructor or TA selected, cleared courses for index: ' . $index);
        }
    }

    /**
     * Render the view for the component.
     * 
     * @return \Illuminate\View\View
     */
    public function render()
    {
        return view('livewire.assign-t-a-modal');
    }
}

