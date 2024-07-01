<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\User;

class StaffList extends Component
{
    public $searchTerm = '';
    public $sortField = 'firstname'; // default 
    public $sortDirection = 'asc'; //default
    public $selectedAreas = [];
    public $hours;
    public $staffCheckboxes = [];
    public $showModal = false;

    public $showSuccessModal = false;
    protected $rules = [
        'hours' => 'required|numeric|min:0',
        'staffCheckboxes' => 'required|array|min:1',
    ];

    public function render()
    {
        $query = $this->searchTerm;
        $areas = $this->selectedAreas;
      
        $usersQuery = User::query();
        //find all users that are instructors
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
        $usersQuery = $usersQuery->join('user_roles', 'users.id', '=', 'user_roles.user_id')
        ->leftJoin('teaches', 'user_roles.id', '=', 'teaches.instructor_id')
        ->leftJoin('course_sections', 'teaches.course_section_id', '=', 'course_sections.id')
        ->leftJoin('areas', 'course_sections.area_id', '=', 'areas.id')
        ->leftJoin('instructor_performance', 'user_roles.id', '=', 'instructor_performance.instructor_id');

        // Sort according to sort fields
        switch ($this->sortField) {
            case 'firstname':
                $usersQuery->orderBy('firstname', $this->sortDirection);
                break;
            case 'area':
                $usersQuery->orderBy('areas.name', $this->sortDirection);
                break;
            case 'total_hours':
                $usersQuery->orderBy('instructor_performance.total_hours', $this->sortDirection);
                break;
            case 'target_hours':
                $usersQuery->orderBy('instructor_performance.target_hours', $this->sortDirection);
                break;
            default:
                $usersQuery->orderBy('instructor_performance.score', $this->sortDirection);
        }
       
        $users = $usersQuery->distinct()->get();
        //dd($users);
        return view('livewire.staff-list', ['users'=> $users, 'showModal'=> $this->showModal]);
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

    public function submit()
    {
        $this->validate();

        $hours = $this->hours;
        $staff_checkboxes = $this->staffCheckboxes;

        foreach($staff_checkboxes as $email){
            $user = User::where('email', $email)->first();
            $instructor = $user->roles->where('role', 'instructor')->first();
            $performances = $instructor->instructorPerformances()->where('year', date('Y'))->get();
            if ($performances->isNotEmpty()) {
                foreach ($performances as $performance) {
                    $performance->update(['target_hours' => $hours]);
                }
            } else {
                return session()->flash('error', 'Instructor performance not found.');
            }
        }

        //session()->flash('success', 'Target hours added successfully.');
        $this->showModal = false;
        $this->showSuccessModal = true;
        session()->flash('showSuccessModal', $this->showSuccessModal);
    }

    public function closeSuccessModal(){
        $this->showSuccessModal = false;
    }

}
