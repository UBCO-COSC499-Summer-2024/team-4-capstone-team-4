<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\User;
use App\Models\UserRole;
use App\Models\InstructorPerformance;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Actions\Fortify\PasswordValidationRules;

class StaffList extends Component
{
    use WithPagination;
    use PasswordValidationRules;

    public $searchTerm = '';
    public $sortField = 'firstname'; // default 
    public $sortDirection = 'asc'; //default
    public $selectedAreas = [];
    public $changedInput = [];
    public $hours;
    public $staffCheckboxes = [];
    public $selectAll = false;
    public $selectedYear;
    public $selectedMonth;
    public $showModal = false;
    public $showSuccessModal = false;

    public $editMode = false;
    public $pagination;
    public $selectedDepts = [];
    public $selectedRoles = [];
    public $selectedStatus = [];

    public $firstname;
    public $lastname;
    public $email;
    public $password;
    public $password_confirmation;
    public $user_roles = [];
    public $confirmDelete = false;
    public $confirmAction;
    public $editUserId = null;
    public $enabledUsers = [];
    public $instructors = [];
    public $deptHeads = [];
    public $deptStaffs = [];
    public $admins = [];

    public $prevEnabledUsers = [];
    public $prevInstructors = [];
    public $prevDeptHeads = [];
    public $prevDeptStaffs = [];
    public $prevAdmins = [];

    protected $rules = [
        'hours' => 'required|numeric|min:0|max:2000',
        'staffCheckboxes' => 'required|array|min:1',
    ];

    public function mount(){
        $this->selectedYear = date('Y');
        $this->selectedMonth = date('F');
        $this->pagination = 10;

        $this->enabledUsers = $this->prevEnabledUsers = User::where('active', true)->pluck('id')->toArray();

        $this->instructors = $this->prevInstructors = UserRole::where('role', 'instructor')->pluck('user_id')->toArray();
        $this->deptHeads = $this->prevDeptHeads = UserRole::where('role', 'dept_head')->pluck('user_id')->toArray();
        $this->deptStaffs = $this->prevDeptStaffs = UserRole::where('role', 'dept_staff')->pluck('user_id')->toArray();
        $this->admins = $this->prevAdmins = UserRole::where('role', 'admin')->pluck('user_id')->toArray();

    }

    public function render(){
        $query = $this->searchTerm;
        $areas = $this->selectedAreas;
        $depts = $this->selectedDepts;
        $roles = $this->selectedRoles;
        $status = $this->selectedStatus;

        $usersQuery = User::query();

        $user = Auth::user();
        $dept_id = null;
        
        if($user->hasRole('admin')){
            $usersQuery->leftJoin('user_roles', 'users.id', '=', 'user_roles.user_id')
            ->leftJoin('teaches', 'user_roles.id', '=', 'teaches.instructor_id')
            ->leftJoin('course_sections', 'teaches.course_section_id', '=', 'course_sections.id')
            ->leftJoin('areas', 'course_sections.area_id', '=', 'areas.id')
            ->leftJoin('departments as areas_dept', 'areas.dept_id', '=', 'areas_dept.id')
            ->leftJoin('departments as user_dept', 'user_roles.department_id', '=', 'user_dept.id')
            ->leftJoin('role_assignments', 'user_roles.id', '=', 'role_assignments.instructor_id')
            ->leftJoin('service_roles', 'service_roles.id', '=', 'role_assignments.service_role_id')
            ->leftJoin('areas as svc_areas', 'svc_areas.id', '=', 'service_roles.area_id')
            ->leftJoin('departments as svc_dept', 'svc_areas.dept_id', '=', 'svc_dept.id');

        }else{
            $dept_id = UserRole::find($user->id)->department_id;
            $year = $this->selectedYear;

            $usersQuery->distinct()
            ->join('user_roles', 'users.id', '=', 'user_roles.user_id')
            ->leftJoin('teaches', 'user_roles.id', '=', 'teaches.instructor_id')
            ->leftJoin('course_sections', function ($join) use ($year){
                $join->on('teaches.course_section_id', '=', 'course_sections.id')
                     ->where('course_sections.year', '=', $year);
            })
            ->leftJoin('areas', 'course_sections.area_id', '=', 'areas.id')
            ->leftJoin(DB::raw("(SELECT * FROM instructor_performance WHERE year = $year) as instructor_performance"), 'user_roles.id', '=', 'instructor_performance.instructor_id')
            ->where('user_roles.role', 'instructor')  // Ensure the user is an instructor
            ->where(function ($query) use ($dept_id) {
                $query->where('areas.dept_id', $dept_id)  // Ensure the user belongs to the department
                      ->orWhereNull('course_sections.id');  // Course section is optional
            })
            ->where('active', true); //Ensure user is enabled
        }

        //apply search query if it is not empty
        if (!empty($query)) {
            $usersQuery->where(function ($queryBuilder) use ($query) {
                $queryBuilder->where('firstname', 'ILIKE', "%{$query}%")
                             ->orWhere('lastname', 'ILIKE', "%{$query}%")
                             ->orWhere('email', 'ILIKE', "%{$query}%")
                             ->orWhereRaw("CONCAT(firstname, ' ', lastname) ILIKE ?", ["%{$query}%"]);
            });
        }        
        //dd($this->selectedAreas);
        //filter for selectedAreas if set
        if(!empty($areas)){
            $usersQuery->whereHas('teaches.courseSection.area', function ($queryBuilder) use ($areas){
                $queryBuilder->whereIn('name', $areas);
            });
        }
        //filter by roles if set
        if(!empty($roles)){
            $usersQuery->whereHas('roles', function ($queryBuilder) use ($roles){
                $queryBuilder->whereIn('role', $roles);
            });
        }

        //filter by depts if set
        if (!empty($depts)) {
            $usersQuery->where(function ($query) use ($depts) {
                $query->whereIn('areas_dept.name', $depts)
                      ->orWhereIn('user_dept.name', $depts);
            });
        }
        
        //filter by status if set
        if(!empty($status)){
            $usersQuery->whereIn('active', $status);
        }
        
        // Sort according to sort fields
        $currentMonth = $this->selectedMonth; 
        switch ($this->sortField) {
            case 'dept':
                $usersQuery->select('users.*', DB::raw("STRING_AGG(DISTINCT COALESCE(areas_dept.name, svc_dept.name, user_dept.name), ', ') AS department_name"), DB::raw("STRING_AGG(user_roles.role, ', ') AS roles_names"))
                                ->groupBy('users.id')
                                ->orderBy('department_name', $this->sortDirection);
                break;
            case 'role':
                $usersQuery->select('users.*', DB::raw("STRING_AGG(DISTINCT COALESCE(areas_dept.name, svc_dept.name, user_dept.name), ', ') AS department_name"), DB::raw("STRING_AGG(user_roles.role, ', ') AS roles_names"))
                ->groupBy('users.id')
                ->orderBy('roles_names', $this->sortDirection);
                break;
            case 'active':
                $usersQuery->select('users.*', DB::raw("STRING_AGG(DISTINCT COALESCE(areas_dept.name, svc_dept.name, user_dept.name), ', ') AS department_name"), DB::raw("STRING_AGG(user_roles.role, ', ') AS roles_names"))
                ->groupBy('users.id')
                ->orderBy('users.active', $this->sortDirection);
                break;
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
            default: 
                if ($user->hasRole('admin')) {
                    $usersQuery->select('users.*', DB::raw("STRING_AGG(DISTINCT COALESCE(areas_dept.name, svc_dept.name, user_dept.name), ', ') AS department_name"), DB::raw("STRING_AGG(user_roles.role, ', ') AS roles_names"))
                    ->groupBy('users.id')
                    ->orderBy('firstname', $this->sortDirection);
                } else {
                    $usersQuery->select('users.*', 'user_roles.id as instructor_id')
                                ->orderBy('firstname', $this->sortDirection);
                }
                break;          
        }

        //pagination
        switch ($this->pagination) {
            case 25:
                $users = $usersQuery->paginate(25);
                break;
            case 50:
                $users = $usersQuery->paginate(50);
                break;
            case 100:
                $users = $usersQuery->paginate(100);
                break;
            case 'all':
                $users = $usersQuery->get();
                break;
            default: 
                $users = $usersQuery->paginate(10);
                break;
        }
        
        //dd($users);
        return view('livewire.staff-list', [
            'users'=> $users, 
            'showModal'=> $this->showModal, 
            'selectedYear'=>$this->selectedYear, 
            'selectedMonth'=>$this->selectedMonth, 
            'editMode'=>$this->editMode, 
            'pagination' => $this->pagination,
            'confirmDelete' => $this->confirmDelete,
            'confirmAction' => $this->confirmAction,
            'editUserId' => $this->editUserId
        ]);
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

    public function clearAdminFilter(){
        $this->selectedRoles = [];
        $this->selectedDepts = [];
        $this->selectedStatus = [];
    }

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

    //add target hours
    public function submit(){
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

    //save edited target hours
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
        $this->selectAll = false;
        $this->staffCheckboxes = [];
        $this->editMode = false;
        $this->showSuccessModal = true;
        session()->flash('showSuccessModal', $this->showSuccessModal); 
    }

    public function exit(){
        $this->editMode = false;
    }

    public function addUser(){
        
        $data = [
            'firstname' => $this->firstname,
            'lastname' => $this->lastname,
            'email' => $this->email,
            'password' => $this->password,
            'password_confirmation' => $this->password_confirmation,  // Add this line
            'user_roles' => $this->user_roles,
        ];
    
        Validator::make($data, [
            'firstname' => ['required', 'string', 'max:255'],
            'lastname' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email:rfc,strict', 'max:255', 'unique:users'],
            'password' => $this->passwordRules(),
            'user_roles' => ['required', 'array', 'min:1'],
        ])->validate();

        // Create a new user
        $user = User::create([
            'firstname' => $this->firstname,
            'lastname' => $this->lastname,
            'email' => $this->email,
            'password' => Hash::make($this->password),
        ]);

        // Create the user role(s)
        foreach($this->user_roles as $role){
            UserRole::create([
                'user_id' => $user->id,
                'department_id' => null,
                'role' => $role,
            ]);
        }
        // Clear form fields and close modal
        $this->showModal = false; 
        $this->firstname = '';
        $this->lastname = '';
        $this->email = '';
        $this->password = '';
        $this->password_confirmation = '';
        $this->user_roles = [];

        $this->dispatch('show-toast', [
            'message' => 'New user ' .$this->firstname. ' ' .$this->lastname. ' created successfully',
            'type' => 'success'
        ]); 
        
    }

    //bulk edit
    public function edit(){
        // Determine changes for enabled status
        $addedUsers = array_diff($this->enabledUsers, $this->prevEnabledUsers);
        $removedUsers = array_diff($this->prevEnabledUsers, $this->enabledUsers);
        $enabledCount = 0;
        $disabledCount = 0;
        // Update status
        foreach($addedUsers as $userid){
            $user = User::find($userid);
            $user->update(['active' => true]);
            $enabledCount++;
        }
        foreach($removedUsers as $userid){
            $user = User::find($userid);
            $user->update(['active' => false]);
            $disabledCount++;
        }

        //Update previous enabled to current enabled
        $this->prevEnabledUsers = $this->enabledUsers;

        //Determines changes and make updates for each role
        $instructorCounts = $this->updateRole('instructor', $this->instructors, $this->prevInstructors);
        $headCounts = $this->updateRole( 'dept_head', $this->deptHeads, $this->prevDeptHeads);
        $staffCounts = $this->updateRole('dept_staff', $this->deptStaffs, $this->prevDeptStaffs);
        $adminCounts = $this->updateRole('admin', $this->admins, $this->prevAdmins);

        // Update previous roles to current roles
        $this->prevInstructors = $this->instructors;
        $this->prevDeptHeads = $this->deptHeads;
        $this->prevDeptStaffs = $this->deptStaffs;
        $this->prevAdmins = $this->admins;

        //reset and send toast
        $message = sprintf(
            "%d users enabled, %d disabled.\n%d instructors added, %d removed.\n%d department heads added, %d removed.\n%d department staff added, %d removed.\n%d admins added, %d removed.",
            $enabledCount, $disabledCount,
            $instructorCounts[0], $instructorCounts[1],
            $headCounts[0], $headCounts[1],
            $staffCounts[0], $staffCounts[1],
            $adminCounts[0], $adminCounts[1]
        );

        $this->editMode = false;
        $this->dispatch('show-toast', [
            'message' => $message,
            'type' => 'success'
        ]);
    }

    private function updateRole($role, $currentRoles, $previousRoles){
        $addedRoles = array_diff($currentRoles, $previousRoles);
        $removedRoles = array_diff($previousRoles, $currentRoles);
        $addCount = 0;
        $removeCount = 0;

        foreach($addedRoles as $userid){
            $user_roles = UserRole::where('user_id', $userid)->pluck('role');
            if (!$user_roles->contains($role)) {
                UserRole::create([
                    'user_id' => $userid,
                    'department_id' => null,
                    'role' => $role,
                ]);
                $addCount++;
            }
        }

        foreach($removedRoles as $userid){
            $user_roles = UserRole::where('user_id', $userid)->pluck('role');
            if ($user_roles->contains($role)) {
                UserRole::where('user_id', $userid)->where('role', $role)->delete();
            }
            $removeCount++;
        }

        return [$addCount, $removeCount];
    }

    //single edit
    public function editStaff($userid) {
        $user = User::find($userid);
        $fullname = $user->firstname . ' ' . $user->lastname;
        $user_roles = UserRole::where('user_id', $user->id)->pluck('role');
        
        // Update status
        $user->update(['active' => in_array($userid, $this->enabledUsers)]);
    
        // Roles to check
        $roles = [
            'instructor' => $this->instructors,
            'dept_head' => $this->deptHeads,
            'dept_staff' => $this->deptStaffs,
            'admin' => $this->admins
        ];
    
        // Update roles
        foreach ($roles as $role => $roleArray) {
            $this->updateUserRole($user_roles, $userid, $role, $roleArray);
        }
    
        $this->editUserId = null;
        $this->dispatch('show-toast', [
            'message' => 'User ' . $fullname . ' updated!',
            'type' => 'success'
        ]);
    }
    
    private function updateUserRole($user_roles, $userid, $role, $roleArray) {
        if (in_array($userid, $roleArray)) {
            if (!$user_roles->contains($role)) {
                UserRole::create([
                    'user_id' => $userid,
                    'department_id' => null,
                    'role' => $role,
                ]);
            }
        } else {
            if ($user_roles->contains($role)) {
                UserRole::where('user_id', $userid)->where('role', $role)->delete();
            }
        }
    }
    
    public function cancelStaff(){
        $this->editUserId = null;
    }

    //single delete
    public function setDelete($userid){
        $this->confirmDelete = true;
        $this->confirmAction = 'deleteStaff('. $userid . ')';
    }
    public function deleteStaff($userid){
        $user = User::find($userid);
        $fullname = $user->firstname . ' ' . $user->lastname;
        try{
            $user->delete();
        }catch(Exception $e){
            $this->dispatch('show-toast', [
                'message' => 'Failed to delete user(s):' . $e->getMessage(),
                'type' => 'error'
            ]); 
        }
        $this->confirmDelete = false;

        $this->dispatch('show-toast', [
            'message' => 'User ' .$fullname. ' deleted!',
            'type' => 'success'
        ]);  
    }

    public function check(){
        //$this->confirmDelete = true;
        if(count($this->staffCheckboxes) > 0){
            $this->confirmDelete = true;
            $this->confirmAction = 'delete';
        }else{
            $this->dispatch('show-toast', [
                'message' => 'No user selected.',
                'type' => 'error'
            ]); 
        }
    }

    // bulk delete
    public function delete(){
        $staff_checkboxes = $this->staffCheckboxes;

        foreach($staff_checkboxes as $email){
            $user = User::where('email', $email)->first();
            try{
                $user->delete();
            }catch(Exception $e){
                $this->dispatch('show-toast', [
                    'message' => 'Failed to delete user(s):' . $e->getMessage(),
                    'type' => 'error'
                ]); 
            }
        }

        $this->confirmDelete = false;
        $this->editMode = false;
        $this->staffCheckboxes = [];

        $this->dispatch('show-toast', [
            'message' => count($staff_checkboxes). ' user(s) deleted!',
            'type' => 'success'
        ]); 
    }
}
