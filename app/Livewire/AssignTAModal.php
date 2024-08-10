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

    public function mount()
    {
        $this->loadData();
    }

    public function loadData()
    {
        $this->tas = TeachingAssistant::all();
        $this->instructors = User::whereHas('roles', function ($query) {
            $query->where('role', 'instructor');
        })->get();
    }

    public function openModal()
    {
        $this->showModal = true;
        $this->resetForm();
    }

    public function closeModal()
    {
        $this->showModal = false;
    }

    public function openConfirmationModal()
    {
        $this->showConfirmationModal = true;
    }

    public function closeConfirmationModal()
    {
        $this->showConfirmationModal = false;
    }

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

    public function addMore()
    {
        $this->selectedTAs[] = ['ta_id' => '', 'instructor_id' => '', 'course_id' => ''];
    }

    public function resetForm()
    {
        $this->selectedTAs = [['ta_id' => '', 'instructor_id' => '', 'course_id' => '']];
        $this->selectedCourses = [];
    }

    public function updatedSelectedTAs($value, $name)
{
    Log::info('updatedSelectedTAs triggered:', ['value' => $value, 'name' => $name]);

    if (str_contains($name, 'instructor_id')) {
        $id = (int) explode('.', $name)[1];  // Extracting the numeric index
        Log::info('Instructor changed, updating courses for index:', ['index' => $id]);
        $this->updateCourses($id);
    }
}
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



    public function render()
    {
        return view('livewire.assign-t-a-modal');
    }
   
    }
