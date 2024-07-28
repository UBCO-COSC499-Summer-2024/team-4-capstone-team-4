@php
    $admin = false;
    $user = Auth::user();
    if($user->hasRole('admin')){
        $admin = true;
    }
@endphp

<div class="relative sm:rounded-lg">
    <div class="px-2 sticky top-0 z-10 flex flex-wrap items-center justify-between h-20 pb-4 space-y-4 bg-white md:flex-nowrap md:space-y-0 dark:bg-gray-900">
        <x-staff-search />
        <div class="flex items-center space-x-4">
            @if($admin)
                @if($editMode)
                    <x-admin-staff-filter />
                    <x-staff-button-green wire:click="edit" id="staff-save" name="staff-save">Save</x-staff-button-green>
                    <x-staff-button-red wire:click="check" id="staff-delete" name="staff-delete">Delete</x-staff-button-red>
                    <x-staff-button-red wire:click="exit" id="staff-exit" name="staff-exit">Cancel</x-staff-button-red>
                @else
                    <x-admin-staff-filter />
                    <x-staff-button wire:click="$set('showModal', true)"  name="add-user" name="add-user">Add</x-staff-button>
                    <x-staff-button wire:click="$set('editMode', true)"  name="add-user" name="add-user">Edit</x-staff-button>
                @endif
            @else
                @if($editMode)
                    <x-staff-filter />
                    <x-staff-button-green wire:click="save" id="staff-save" name="staff-save">Save</x-staff-button-green>
                    <x-staff-button-red wire:click="exit" id="staff-exit" name="staff-exit">Cancel</x-staff-button-red>
                @else
                    <x-staff-filter />
                    <x-staff-dropdown :selectedYear="$selectedYear" :selectedMonth="$selectedMonth"/>
                @endif
            @endif
        </div>
    </div>
   {{--  @if (session()->has('message'))
        <div class="text-sm text-green-600">
            {{ session('message') }}
        </div>
    @endif --}}
    {{-- @if(session()->has('error'))
        <div class="text-sm text-red-600">
            {{ session('error') }}
        </div>
    @endif --}}
    @if(session()->has('showSuccessModal') && session('showSuccessModal'))
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
            <x-staff-success-modal />
        </div>
    @endif
    @if($admin)
        <form wire:submit.prevent="addUser">
    @else
        <form wire:submit.prevent="submit">
    @endif
        @csrf
        @if($showModal == true)
            <div class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
                @if($admin)
                    <x-add-user :showModal="$showModal"/>
                @else
                    <x-staff-targethours :showModal="$showModal"/>
                @endif
            </div>
        @endif
        @if($confirmDelete)
            <div class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
                <x-confirm-delete :confirmDelete="$confirmDelete" :confirmAction="$confirmAction"/>
            </div>
        @endif
        <x-staff-table>
            <x-staff-table-header :sortField="$sortField" :sortDirection="$sortDirection" :selectedYear="$selectedYear" :selectedMonth="$selectedMonth" />
            <tbody>
                @if(isset($users))
                    @foreach ($users as $user)
                        @if($admin)
                            @php
                                $roles = [];
                                $user_roles = explode(', ', $user->roles_names);
                                foreach($user_roles as $role){
                                    switch($role){
                                        case 'instructor':
                                            if(!in_array('Instructor' ,$roles)){
                                                $roles[] = 'Instructor';
                                            }
                                            break;
                                        case 'dept_head':
                                            if(!in_array('Department Head',$roles)){
                                                $roles[] = 'Department Head';
                                            }
                                            break;
                                        case 'dept_staff':
                                            if(!in_array('Department Staff',$roles)){
                                                $roles[] = 'Department Staff';
                                            }
                                            break;
                                        case 'admin':
                                            if(!in_array('Admin',$roles)){
                                                $roles[] = 'Admin';
                                            }
                                            break;
                                        default:
                                            break;
                                    }
                                }
                            @endphp
                            <x-staff-table-row
                            fullname="{{ $user->firstname }} {{ $user->lastname }}"
                            email="{{ $user->email }}"
                            dept="{{ $user->department_name == '' ? '-' : $user->department_name }}"
                            :roles="$roles"
                            active="{{ $user->active }}"
                            editMode="{{ $editMode }}"
                            userid="{{ $user->id }}"
                            editUserId="{{ $editUserId }}"
                            />
                        @else
                            @php
                            $area_names = [];
                            $instructor = App\Models\UserRole::find($user->instructor_id);
                            $performance = App\Models\InstructorPerformance::where('instructor_id', $user->instructor_id)
                                                                            ->where('year', $selectedYear)
                                                                            ->first();
                            if($performance){
                                $totalHours = json_decode($performance->total_hours, true);
                                $currentMonthHours = $totalHours[$selectedMonth];
                            }else{
                                $currentMonthHours = null;
                            }

                            if ($instructor && $instructor->teaches) {
                                $course_ids = $instructor->teaches()
                                    ->whereHas('courseSection', function ($query) use ($selectedYear) {
                                        $query->where('year', $selectedYear);
                                    })
                                    ->pluck('course_section_id')
                                    ->all();

                                foreach ($course_ids as $course_id) {
                                    $course = App\Models\CourseSection::find($course_id);
                                    $area_name = $course->area->name ?? null;

                                    if ($area_name && !in_array($area_name, $area_names)) {
                                        $area_names[] = $area_name;
                                    }
                                }
                            }
                            @endphp

                            <x-staff-table-row
                                fullname="{{ $user->firstname }} {{ $user->lastname }}"
                                email="{{ $user->email }}"
                                subarea="{{ empty($area_names) ? '-' : implode(', ', $area_names) }}"
                                completedHours="{{ $currentMonthHours ?? '-' }}"
                                targetHours="{{ $performance ? ($performance->target_hours) ?? '-' : '-' }}"
                                src="{{ $user->profile_photo_url }}"
                                instructorId="{{ $user->instructor_id }}"
                                editMode="{{ $editMode }}"
                            />
                        @endif
                    @endforeach
                @else
                    <tr>
                        <td colspan="6" class="text-center">No users</td>
                    </tr>
                @endif
            </tbody>
        </x-staff-table>
        <div class="flex items-center justify-end">
            Staff per page: 
            <select wire:model.live="pagination" class="w-auto min-w-[70px] text-[#3b4779] bg-white border border-[#3b4779] focus:outline-none hover:text-white hover:bg-[#3b4779] focus:ring-4 focus:ring-gray-100 font-medium rounded-lg text-sm px-3 py-1.5">
                <option value="10">10</option>
                <option value="25">25</option>
                <option value="50">50</option>
                <option value="100">100</option>
                <option value="all">All</option>
            </select>
            <div>
                @if($pagination !== 'all')
                    {{ $users->links() }}
                @endif
            </div>
        </div>
    </form>
</div>

