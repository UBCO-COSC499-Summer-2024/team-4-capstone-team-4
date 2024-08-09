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
        Log::info('updatedSelectedTAs triggered: ', ['value' => $value, 'name' => $name]);
        if (str_contains($name, 'instructor_id')) {
            $parts = explode('.', $name);
            if (isset($parts[1])) {
                $index = (int)$parts[1];
                Log::info('Instructor changed, updating courses for index: ', ['index' => $index]);
                $this->updateCourses($index);
            }
        }
    }
    public function updateCourses($index)
{
    $instructorId = $this->selectedTAs[$index]['instructor_id'] ?? null;
    Log::info('Updating courses for instructor: ', ['index' => $index, 'instructor_id' => $instructorId]);
    if ($instructorId) {
        $courses = CourseSection::whereHas('teaches', function ($query) use ($instructorId) {
            $query->where('instructor_id', $instructorId);
        })->get();
        Log::info('Fetched courses from DB: ', ['courses' => $courses]);

        $formattedCourses = $courses->map(function($course) {
            return [
                'id' => $course->id,
                'formattedName' => sprintf('%s %s %s - %s%s %s',
                    $course->prefix,
                    $course->number,
                    $course->section,
                    $course->year,
                    $course->session,
                    $course->term
                )
            ];
        })->toArray();

        $this->selectedCourses[$index] = $formattedCourses;
        Log::info('Formatted courses: ', ['index' => $index, 'courses' => $formattedCourses]);
    } else {
        $this->selectedCourses[$index] = [];
        Log::info('No instructor selected, cleared courses for index: ', ['index' => $index]);
    }
}


    public function render()
    {
        return view('livewire.assign-t-a-modal');
    }
    }
