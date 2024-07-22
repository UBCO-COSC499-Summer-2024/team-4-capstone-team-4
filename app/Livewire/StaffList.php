<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\User;
use App\Models\UserRole;
use App\Models\InstructorPerformance;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class StaffList extends Component
{
    public $searchTerm = '';
    public $sortField = 'firstname'; // default 
    public $sortDirection = 'asc'; //default
    public $selectedAreas = [];
    public $changedInput = [];
    public $hours;
    public $staffCheckboxes = [];
    public $selectAll = false;
    public $currentUsers;
    public $selectedYear;
    public $selectedMonth;
    public $showModal = false;
    public $showSuccessModal = false;

    public $editMode = false;
    protected $rules = [
        'hours' => 'required|numeric|min:0|max:2000',
        'staffCheckboxes' => 'required|array|min:1',
    ];

    public function mount()
    {
        $this->selectedYear = date('Y');
        $this->selectedMonth = date('F');
    }

    public function render()
    {
        $query = $this->searchTerm;
        $areas = $this->selectedAreas;

        $user = Auth::user();
        $dept_id = UserRole::find($user->id)->department_id;
      
        $usersQuery = User::query();
        //find all users that are instructors in the department
        $usersQuery->whereHas('roles', function ($queryBuilder) {
            $queryBuilder->where('role', 'instructor');
        });
        //apply search query if it is not empty
        if(!empty($query)){
            $usersQuery->where(function ($queryBuilder) use ($query) {
                $queryBuilder->where('firstname', 'ILIKE', "%{$query}%")
                            ->orWhere('lastname', 'ILIKE', "%{$query}%")
                            ->orWhere('email', 'ILIKE', "%{$query}%");
            });
        }
        //dd($this->selectedAreas);
        //filter for selectedAreas if set
        if(!empty($areas)){
            $usersQuery->whereHas('teaches.courseSection.area', function ($queryBuilder) use ($areas){
                $queryBuilder->whereIn('name', $areas);
            });
        }
        //join all the tables
        $currentYear = $this->selectedYear;
        $usersQuery = $usersQuery->distinct()
        ->join('user_roles', 'users.id', '=', 'user_roles.user_id')
        ->leftJoin('teaches', 'user_roles.id', '=', 'teaches.instructor_id')
        ->leftJoin('course_sections', function($join) use ($currentYear) {
            $join->on('teaches.course_section_id', '=', 'course_sections.id')
                ->where('course_sections.year', '=', $currentYear);
        })
        ->orWhereNull('course_sections.id')
        ->leftJoin('areas', 'course_sections.area_id', '=', 'areas.id')
        ->leftJoin(DB::raw("(SELECT * FROM instructor_performance WHERE year = $currentYear) as instructor_performance"), 'user_roles.id', '=', 'instructor_performance.instructor_id')
        ->where('areas.dept_id', $dept_id);

        // Sort according to sort fields
        $currentMonth = $this->selectedMonth; 
        switch ($this->sortField) {
            case 'area':
                $usersQuery->select('users.*', 'user_roles.id as instructor_id', DB::raw("STRING_AGG(areas.name, ', ') as area_names"))
                ->groupBy('users.id', 'user_roles.id')          
                            ->orderBy('area_names', $this->sortDirection);
                break;
            case 'total_hours':
                //extract hours of the current month
                $usersQuery->select('users.*', 'user_roles.id as instructor_id', DB::raw("CAST(instructor_performance.total_hours->>'$currentMonth' AS INTEGER) AS month_hours"))
                           ->orderBy('month_hours', $this->sortDirection);
                break;
            case 'target_hours':
                $usersQuery->select('users.*', 'user_roles.id as instructor_id','instructor_performance.target_hours')
                            ->orderBy('instructor_performance.target_hours', $this->sortDirection);
                break;
            case 'score' :
                $usersQuery->select('users.*', 'user_roles.id as instructor_id', 'instructor_performance.score')
                            ->orderBy('instructor_performance.score', $this->sortDirection);
                break;                
            default: // by firstname
                $usersQuery->select('users.*', 'user_roles.id as instructor_id')
                           ->orderBy('firstname', $this->sortDirection);
        }

        $users = $usersQuery->get();
        $this->currentUsers = $users;
        //dd($users);
        return view('livewire.staff-list', ['users'=> $users, 'showModal'=> $this->showModal, 'selectedYear'=>$this->selectedYear, 'selectedMonth'=>$this->selectedMonth, 'editMode'=>$this->editMode]);
    }

    public function sort($field){
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
    }

    public function filter(){
        $this->selectedAreas = $this->selectedAreas;
    }
    public function clearFilter(){
        $this->selectedAreas = [];
    }

   /*  public function updatedSelectAll($value)
    {
        if ($value) {
            $this->staffCheckboxes = $this->currentUsers->pluck('email')->toArray();
        } else {
            $this->staffCheckboxes = [];
        }
    } */

    public function showTargetModal(){
        if(count($this->staffCheckboxes) > 0){
            $this->showModal = true;
        }else{
            $this->dispatch('show-toast', [
                'message' => 'Please select at least one staff member',
                'type' => 'error'
            ]); 
        }
    }

    public function submit()
    {
        $this->validate();

        $hours = $this->hours;
        $staff_checkboxes = $this->staffCheckboxes;

        foreach($staff_checkboxes as $email){
            $user = User::where('email', $email)->first();
            $instructor = $user->roles->where('role', 'instructor')->first();
            $performance = $instructor->instructorPerformances()->where('year', $this->selectedYear)->first();
            if ($performance) {
                $performance->update(['target_hours' => $hours]);
            } else {
                InstructorPerformance::factory()->create([
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
                    'target_hours' => $hours,
                    'sei_avg' => 0,
                    'enrolled_avg'=> 0,
                    'dropped_avg'=> 0,
                    'year' => $this->selectedYear,
                    'instructor_id' => $instructor->id,
                ]); 
            }
        }

        $this->selectAll = false;
        $this->staffCheckboxes = [];
        $this->hours = '';
        //session()->flash('success', 'Target hours added successfully.');
        $this->showModal = false;
        $this->showSuccessModal = true;
        session()->flash('showSuccessModal', $this->showSuccessModal);   
    }

    public function closeSuccessModal(){
        $this->showSuccessModal = false;
    }

    public function update($email, $hours){
        $this->changedInput[$email] = $hours;
    }

    public function save()
    {   //validate input parameters
        //dd($this->changedInput);
        foreach ($this->changedInput as $email => $hours) {
            if(!empty($hours)){
                if (!is_numeric($hours) || $hours < 0) {
                    $this->dispatch('show-toast', [
                        'message' => 'Target hours must be a non-negative number.',
                        'type' => 'error'
                    ]); 
                    return;
                }
                if ($hours > 2000) {
                    $this->dispatch('show-toast', [
                        'message' => 'Target hours must not be greater than 2000.',
                        'type' => 'error'
                    ]); 
                    return;
                }
            }else{
                $this->changedInput[$email] = null;
            }
        }
        //update database with target hours
        foreach ($this->changedInput as $email => $hours) {
            $user = User::where('email', $email)->first();
            if ($user) {
                $instructor = $user->roles->where('role', 'instructor')->first();
                $performance = $instructor->instructorPerformances()->where('year', $this->selectedYear)->first();
                if ($performance) {
                    $performance->update(['target_hours' => $hours]);
                } else {
                    InstructorPerformance::factory()->create([
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
                        'target_hours' => $hours,
                        'sei_avg' => 0,
                        'enrolled_avg'=> 0,
                        'dropped_avg'=> 0,
                        'year' => $this->selectedYear,
                        'instructor_id' => $instructor->id,
                    ]);           
                }
            }
        }
        $this->editMode = false;
        $this->showSuccessModal = true;
        session()->flash('showSuccessModal', $this->showSuccessModal); 
    }

    public function exit(){
        $this->editMode = false;
    }

}
