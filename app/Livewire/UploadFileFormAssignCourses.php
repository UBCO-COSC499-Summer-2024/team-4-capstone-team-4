<?php

namespace App\Livewire;

use App\Models\Area;
use App\Models\AreaPerformance;
use App\Models\CourseSection;
use App\Models\DepartmentPerformance;
use App\Models\InstructorPerformance;
use App\Models\Teach;
use App\Models\User;
use Livewire\Component;

class UploadFileFormAssignCourses extends Component
{
    public $finalCSVs = [];
    public $assignments = [];
    public $filteredInstructors;

    public $showModal = false;
    public $showInstructorModal = false;

    public $selectedIndex = -1;
    public $searchTerm = '';

    public function mount($finalCSVs) {
        $this->finalCSVs = $finalCSVs;

        $this->assignments = $this->getAvailableCourses()->map(function($course) {
            return [
                'course_section_id' => $course->id ?? '',
                'prefix' => $course->prefix ?? '',
                'number' => $course->number ?? '',
                'section' => $course->section ?? '',
                'year' => $course->year ?? '',
                'session' => $course->session ?? '',
                'term' => $course->term ?? '',
                'instructor_id' => '',
                'instructor' => '',
                'year' => $course->year ?? '',
            ];
        })->toArray();

        // dd($this->assignments, $this->finalCSVs);
        foreach($this->assignments as $index => $assignment) {
            foreach($this->finalCSVs as $finalCSV) {
                $requiredKeys = ['Prefix', 'Number', 'Section', 'Year', 'Session', 'Term', 'Instructor'];

                foreach ($requiredKeys as $key) {
                    if (!isset($finalCSV[$key])) {
                        continue 2;
                        }
                }

                    if( $finalCSV['Prefix'] == $assignment['prefix'] &&
                        $finalCSV['Number'] == $assignment['number'] &&
                        $finalCSV['Section'] == $assignment['section'] &&
                        $finalCSV['Year'] == $assignment['year'] &&
                        $finalCSV['Session'] == $assignment['session'] &&
                        $finalCSV['Term'] == $assignment['term']
                    ){
                        // $this->assignments[$index]['instructor'] = $finalCSV['Instructor'];
                        // dd(User::whereRAW("CONCAT(firstname, ' ', lastname) = ?", $finalCSV['Instructor'])->value('id'));
                        $instructor_id = User::whereRAW("CONCAT(firstname, ' ', lastname) = ?", $finalCSV['Instructor'])->value('id');
                        $instructors = $this->getAvailableInstructors();
                        // dd($instructors);
                        foreach($instructors as $instructor) {
                            if("{$instructor->firstname} {$instructor->lastname}" == $finalCSV['Instructor']) {
                                //get user id
                                $this->assignments[$index]['instructor'] = "$instructor->firstname" . " " . "$instructor->lastname";
                                $this->assignments[$index]['instructor_id'] = $instructor->id;

                            }
                        }   
                    }                
                }

            }
        // dd($this->assignments);

    }

    public function handleSubmit() { 
        // dd($this->assignments);
        foreach ($this->assignments as $assignment) {
            $instructor_id = (int) $assignment['instructor_id'];
            $year = $assignment['year'];
            //dd($assignment);

            if ($instructor_id != null) {
                $assign = Teach::create([
                    'course_section_id' => $assignment['course_section_id'],
                    'instructor_id' => $instructor_id,
                ]);

                $course = CourseSection::where('id', $assignment['course_section_id'])->first();
                $assign->log_audit('Assign Instructor', ['operation_type' => 'CREATE', 'new_value' => json_encode($assign->getAttributes())], 'Assign ' . $assignment['instructor'] . ' to ' . $course->prefix . ' ' . $course->number . ' ' . $course->section . '-' . $course->year . $course->session . $course->term);

             
                $area_id = CourseSection::where('id', $assignment['course_section_id'])->value('area_id');
                $dept_id = Area::where('id', $area_id)->value('dept_id');

                $instructorPerformance = InstructorPerformance::where('instructor_id', $instructor_id )->where('year', $year )->first();
                $areaPerformance = AreaPerformance::where('area_id', $area_id)->where('year', $year)->first();
                $departmentPerformance  = DepartmentPerformance::where('dept_id', $dept_id)->where('year', $year)->first();

                if($instructorPerformance != null){
                    InstructorPerformance::updatePerformance($instructor_id, $year);
                }else{
                    InstructorPerformance::create([
                        'instructor_id'=> $instructor_id,
                        'score' => 0,
                        'total_hours' => json_encode([
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
                        ]),
                        'target_hours' => null,
                        'sei_avg' => 0,
                        'enrolled_avg'=> 0,
                        'dropped_avg'=> 0,
                        'year' => $year,
                    ]);
                   InstructorPerformance::updatePerformance($instructor_id, $year);
                }

                if ($areaPerformance != null) {
                    AreaPerformance::updateAreaPerformance($area_id, $year);
                } else {
                    AreaPerformance::create([
                        'area_id'=> $area_id,
                        'total_hours' => json_encode([
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
                        ]),
                        'sei_avg' => 0,
                        'enrolled_avg'=> 0,
                        'dropped_avg'=> 0,
                        'year' => $year,
                    ]);
                    AreaPerformance::updateAreaPerformance($area_id, $year);
                }

                if ($departmentPerformance != null) {
                    DepartmentPerformance::updateDepartmentPerformance($dept_id, $year);
                } else {
                    DepartmentPerformance::create([
                        'dept_id'=> $dept_id,
                        'total_hours' => json_encode([
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
                        ]),
                        'sei_avg' => 0,
                        'enrolled_avg'=> 0,
                        'dropped_avg'=> 0,
                        'year' => $year,
                    ]);
                    DepartmentPerformance::updateDepartmentPerformance($dept_id, $year);
                }
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

    public function openInstructorModal($index) {
        $this->selectedIndex = $index;
        // dd($this->selectedIndex);
        $this->showInstructorModal = true;
    }

    public function closeInstructorModal() {
        $this->showInstructorModal = false;
    }

    public function closeModal() {
        $this->showModal = false;
    }

    public function selectInstructor($id, $firstname, $lastname, $selectedIndex) {
        $this->assignments[$selectedIndex]["instructor_id"] = $id;
        $this->assignments[$selectedIndex]["instructor"] = $firstname . " " . $lastname;

        $this->closeInstructorModal();
  
        // dd($this->assignments);
    }

    public function updateSearch() {
        $availableInstructors = $this->getAvailableInstructors();

        $this->filteredInstructors = $availableInstructors->filter(function ($instructor) {
            $name = $instructor->firstname . ' ' . $instructor->lastname;
            return stripos($name, $this->searchTerm) !== false;
        });
    }


    public function getAvailableCourses() {
        $courses = collect();
        $assignedCourseIds = Teach::pluck('course_section_id');

  
        foreach($this->finalCSVs as $finalCSV) {
            // dd($coursesFromCSV);
            $requiredKeys = ['Prefix', 'Number', 'Section', 'Year', 'Session', 'Term', 'Instructor'];

             foreach ($requiredKeys as $key) {
                if (!isset($finalCSV[$key])) {
                    continue 2;
                    }
            }

            $course = CourseSection::whereNotIn('id', $assignedCourseIds)
                ->where('prefix', $finalCSV['Prefix'])
                ->where('number', $finalCSV['Number'])
                ->where('section', $finalCSV['Section'])
                ->where('year', $finalCSV['Year'])
                ->where('session', $finalCSV['Session'])
                ->where('term', $finalCSV['Term'])
                ->get();

                $courses = $courses->merge($course);
        }

        // dd($courses, CourseSection::whereNotIn('id', $assignedCourseIds)->get());

        // return CourseSection::whereNotIn('id', $assignedCourseIds)->get();

        return $courses;
    }

    public function getAvailableInstructors() {
        // $instructorRoleIds = UserRole::where('role', 'instructor')->pluck('user_id');

        return User::join('user_roles', 'users.id', '=', 'user_roles.user_id')
            ->where('role', 'instructor')
            // ->where(function ($query) {
            //     $query->whereRaw('LOWER(users.firstname) LIKE ?', ["%{$this->instructorSearch}%"])
            //         ->orWhereRaw('LOWER(users.lastname) LIKE ?', ["%{$this->instructorSearch}%"])
            //         ->orWhereRaw('LOWER(CONCAT(users.firstname, \' \', users.lastname)) LIKE ?', ["%{$this->instructorSearch}%"]);
            // })
            ->orderByRaw('LOWER(users.lastname)')
            ->orderByRaw('LOWER(users.firstname)')
            ->get();
    }

    public function render()
    {
        // dd($this->assignments, $this->finalCSVs);
        // dd($this->getAvailableCourses());

        // dd($this->getAvailableInstructors());

        $this->updateSearch();

        // dd($this->filteredInstructors);

        return view('livewire.upload-file-form-assign-courses', [
            'availableCourses' => $this->getAvailableCourses(),
            'availableInstructors' => $this->getAvailableInstructors(),
            'filteredInstructors' => $this->filteredInstructors,
            'selectedIndex' => $this->selectedIndex,
        ]);
    }
}
