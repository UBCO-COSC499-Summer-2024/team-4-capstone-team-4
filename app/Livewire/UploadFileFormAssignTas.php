<?php

namespace App\Livewire;

use App\Models\Assist;
use App\Models\CourseSection;
use App\Models\Teach;
use App\Models\TeachingAssistant;
use App\Models\User;
use Livewire\Component;

class UploadFileFormAssignTas extends Component
{
    public $finalCSVs = [];
    public $assignments = [];
    public $filteredTas;

    public $showModal = false;
    public $showTaModal = false;
    
    public $selectedIndex = -1;
    public $searchTerm = '';

    protected $listeners = ['updateAssignment'];

    public function mount($finalCSVs)
    {
        $this->finalCSVs = $finalCSVs;

        // dd($this->finalCSVs);

        foreach ($this->finalCSVs as $finalCSV) {
            $requiredKeys = ['Prefix', 'Number', 'Section', 'Year', 'Session', 'Term', 'TAs'];

             foreach ($requiredKeys as $key) {
                if (!isset($finalCSV[$key])) {
                    continue 2;
                    }
            }

            $course = CourseSection::where('prefix', $finalCSV['Prefix'])
                ->where('number', $finalCSV['Number'])
                ->where('section', $finalCSV['Section'])
                ->where('year', $finalCSV['Year'])
                ->where('session', $finalCSV['Session'])
                ->where('term', $finalCSV['Term'])
                ->first();

            if ($course) {
                foreach ($finalCSV['TAs'] as $ta) {
                    $ta = TeachingAssistant::where("name", $ta)->first();
                //    dd($ta);
                    $this->assignments[] = [
                        'course_section_id' => $course->id ?? '',
                        'prefix' => $course->prefix ?? '',
                        'number' => $course->number ?? '',
                        'section' => $course->section ?? '',
                        'year' => $course->year ?? '',
                        'session' => $course->session ?? '',
                        'term' => $course->term ?? '',
                        'ta_id' => $ta ? $ta->id : '',
                        'ta' => $ta ? $ta->name : '',
                    ];
                }
            }
        }

        // dd($this->assignments);
    }

    public function getAvailableCourses()
    {
        $courses = collect();
        // $assignedCourseIds = Teach::pluck('course_section_id');

        foreach ($this->finalCSVs as $finalCSV) {
            $requiredKeys = ['Prefix', 'Number', 'Section', 'Year', 'Session', 'Term'];

             foreach ($requiredKeys as $key) {
                if (!isset($finalCSV[$key])) {
                    continue 2;
                    }
            }

            $course = CourseSection::where('prefix', $finalCSV['Prefix'])
                ->where('number', $finalCSV['Number'])
                ->where('section', $finalCSV['Section'])
                ->where('year', $finalCSV['Year'])
                ->where('session', $finalCSV['Session'])
                ->where('term', $finalCSV['Term'])
                ->get();

            $courses = $courses->merge($course);
        }

        return $courses;
    }

    public function getAvailableTas()
    {
        return TeachingAssistant::get();
    }

    public function handleSubmit() {
        // dd($this->assignments);

        $seenCourses = [];
        $filteredCourses = [];
        
        foreach ($this->assignments as $course) {
            $courseIdentifier = $course['prefix'] . '-' . $course['number'] . '-' . $course['section'] . '-' . $course['session'] . '-' . $course['term'] . '-' . $course['year']. '-' . $course['ta_id'];
            // dd($courseIdentifier);
            if (!in_array($courseIdentifier, $seenCourses)) {
                $seenCourses[] = $courseIdentifier;
                $filteredCourses[] = $course;

            }
        }

        $this->assignments = $filteredCourses;
        // dd($this->assignments);

        foreach($this->assignments as $assignment) {
            if(isset($assignment['ta_id']) && $assignment['ta_id'] !== null && $assignment['ta_id'] !== "") {
                $assign = Assist::create([
                    'course_section_id' => $assignment['course_section_id'],
                    'ta_id' => (int) $assignment['ta_id'],
                    'rating' => 0
                ]);

                $course = CourseSection::where('id', $assignment['course_section_id'])->first();
                $assign->log_audit('Assign TA', ['operation_type' => 'CREATE', 'new_value' => json_encode($assign->getAttributes())], 'Assign TA' . $assignment['ta'] . ' to ' . $course->prefix . ' ' . $course->number . ' ' . $course->section . '-' . $course->year . $course->session . $course->term);
            }
        }

        $this->finalCSVs = [];
        $this->assignments = [];
        $this->mount($this->finalCSVs);

        session()->flash('success', 'Instructors assigned successfully!');

        if(session()->has('success')) {
            $this->showModal = true;
        }
    }

    public function closeModal() {
        $this->showModal = false;
    }
    
    public function openTaModal($index) {
        $this->selectedIndex = $index;
        // dd($this->selectedIndex);
        $this->showTaModal = true;
    }

    public function closeTaModal() {
        $this->showTaModal = false;
    }

    public function updateAssignment($index, $taId, $taName)
    {
        $this->assignments[$index]['ta_id'] = $taId;
        $this->assignments[$index]['ta'] = $taName;
    }

    public function selectTa($id, $name, $selectedIndex) {
        $this->assignments[$selectedIndex]["ta_id"] = $id;
        $this->assignments[$selectedIndex]["ta"] = $name;

        $this->closeTaModal();
  
        // dd($this->assignments);
    }

    public function updateSearch() {
        $availableTas = $this->getAvailableTas();

        $this->filteredTas = $availableTas->filter(function ($ta) {
            return stripos($ta->name, $this->searchTerm) !== false;
        });
    }

    public function render()
    {
        // dd($this->assignments);

        // dd($this->getAvailableTas());

        // dd($this->selectedIndex);
        $this->updateSearch();
   

        // dd($this->filteredTas);

        return view('livewire.upload-file-form-assign-tas', [
            'availableCourses' => $this->getAvailableCourses(),
            'availableTas' => $this->getAvailableTas(),
            'selectedIndex' => $this->selectedIndex,
            'filteredTas' => $this->filteredTas,
        ]);
    }
}
