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
    public $activeInstructor;
    public $activeTA;
    public $showModal = false;
    public $showConfirmationModal = false;

    protected $listeners = [
        'select-ta' => 'selectTA',
        'select-instructor' => 'selectInstructor',
    ];

    public function mount()
    {
        $this->loadData();
        $this->resetForm();
    }

    public function loadData()
    {
        $this->tas = TeachingAssistant::all();
        $this->instructors = User::whereHas('roles', function ($query) {
            $query->where('role', 'instructor');
        })->get();
    }

    public function selectTA($taId)
    {
        dd($taId, 'ta');
        $this->selectedTAs[] = ['ta_id' => $taId, 'instructor_id' => '', 'course_id' => ''];
    }

    public function selectInstructor($instructorId)
    {
        dd($instructorId, 'instructor');
        $this->instructors[] = ['instructor_id' => $instructorId, 'course_id' => ''];
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
                $courseSection->teachingAssistants()->attach($taId);
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
        if (str_contains($name, 'instructor_id')) {
            $index = explode('.', $name)[1];
            $this->updateCourses($index);
        }
    }

    public function updateCourses($index)
    {
        $instructorId = $this->selectedTAs[$index]['instructor_id'] ?? null;
        dd($index, $instructorId);
        if ($instructorId) {
            $courses = CourseSection::whereHas('teaches', function ($query) use ($instructorId) {
                $query->where('instructor_id', $instructorId);
            })->get();

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
            Log::info('Fetched courses: ', $formattedCourses);
        } else {
            $this->selectedCourses[$index] = [];
        }
    }

    public function render()
    {
        return view('livewire.assign-t-a-modal');
    }
    }
