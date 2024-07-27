@php
    $admin = false;
    $user = Auth::user();
    if($user->hasRole('admin')){
        $admin = true;
    }
@endphp

@if($admin)
<tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
    <td class="w-3 px-6 py-4">
        <div class="flex items-center justify-center h-full">
            <x-checkbox wire:model="staffCheckboxes" name="staff-checkboxes[]" value="{{ $email }}" class="staff-checkbox"/>
        </div>
    </td>
    <td class="flex items-center px-0 py-4 text-gray-900 whitespace-nowrap dark:text-white">
        <div class="ps-3 min-w-0 flex-auto">
            <div class="block hover:underline">
                <p class="text-lg font-semibold leading-6 text-gray-900 hover:text-[#3b4779] transform hover:scale-101 transition duration-300">{{ $fullname }}</p>
            </div>
            <div class="block mt-1 truncate text-base leading-5 text-gray-500 hover:text-[#3b4779] ">
                {{ $email }}
            </div>
        </div>
    </td>
    <td class="px-6 py-4">
        {{-- @if($editMode)
        @else
        @endif --}}
        <div class="flex items-center justify-center h-full">{{ $dept }}</div>
    </td> 
    <td class="px-6 py-4">
        @if($editMode)
            <div class="flex flex-col">
                @php
                    $allRoles = ['Instructor', 'Department Head', 'Department Staff', 'Admin'];
                @endphp
                @foreach($allRoles as $role)
                    <div class="flex items-center gap-1">
                        <x-checkbox wire:model="selectedRoles" name="role-{{ $role }}{{ $userid }}" value="{{ $role }}{{ $userid }}" :checked="in_array($role, $roles)"/>
                        <label for="role-{{ $role }}{{ $userid }}">{{ $role }}</label>
                    </div>
                @endforeach
            </div>
        @else
            <div class="flex items-center justify-center h-full">{{ implode(', ', $roles) }}</div>
        @endif
    </td>
    <td class="px-6 py-4">
        @if($editMode)
            <div class="flex items-center justify-center h-full">
                <label class="inline-flex items-center cursor-pointer">
                    <input wire:model="active" type="checkbox" value="{{$active}}" class="sr-only peer"  name="status{{$userid}}" {{$active ? 'checked' : ''}}>
                    <div class="relative w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-blue-600"></div>
                    <span class="ms-3">Enabled</span>
                </label>
            </div>
        @else
            <div class="flex items-center justify-center h-full">
                @if($active)
                    <div class="h-2.5 w-2.5 rounded-full bg-green-500 me-2"></div>
                @else
                    <div class="h-2.5 w-2.5 rounded-full bg-red-500 me-2"></div>
                @endif
                {{ $active ? 'Enabled' : 'Disabled'}}
            </div>
        @endif
    </td>     
    <td class="px-6 py-4">
        <div class="flex items-center justify-center h-full">
            <button wire:click="editStaff({{$userid}})"><span class="material-symbols-outlined text-gray-500" title="Edit">edit</span></button>
            <button wire:click="setDelete({{$userid}})"><span class="material-symbols-outlined text-red-500" title="Delete">delete</span></button>
            <button wire:click="sendReset"><span class="material-symbols-outlined text-gray-500" title="Send Reset Link">mail_lock</span></button>
        </div>
    </td>
</tr>
@else
<tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
    <td class="w-3 px-6 py-4">
        <div class="flex items-center justify-center h-full">
            <x-checkbox wire:model="staffCheckboxes" name="staff-checkboxes[]" value="{{ $email }}" class="staff-checkbox"/>
        </div>
    </td>
    <td class="flex items-center px-0 py-4 text-gray-900 whitespace-nowrap dark:text-white">
        <img class="h-14 w-14 flex-none rounded-full bg-gray-50" src="{{ $src }}" alt="">
        <div class="ps-3 min-w-0 flex-auto">
            <a href="{{ route('performance', ['instructor_id' => $instructorId]) }}" title="View Performance" class="block hover:underline">
                <p class="text-lg font-semibold leading-6 text-gray-900 hover:text-[#3b4779] transform hover:scale-101 transition duration-300">{{ $fullname }}</p>
            </a>
            <a href="mailto:{{ $email }}" title="Send Email" class="block mt-1 truncate text-base leading-5 text-gray-500 hover:text-[#3b4779] ">
                {{ $email }}
            </a>
        </div>
    </td>
    <td class="px-6 py-4">
        <div class="flex items-center justify-center h-full">{{ $subarea }}</div>
    </td>    
    <td class="px-6 py-4">
        <div class="flex items-center justify-center h-full">{{ $completedHours }}</div>
    </td>
    <td class="px-6 py-4">
        @if($editMode)
            @if($targetHours === '-')
                <input wire:change="update('{{ $email }}', $event.target.value)" type="text" name="hours" class="border border-solid border-[#3b4779] bg-gray-50 rounded-lg w-3/4 p-2 text-center focus:ring-1 focus:ring-[#3b4779]" 
                value="" />
            @else
                <input wire:change="update('{{ $email }}', $event.target.value)" type="text" name="hours" class="border border-solid border-[#3b4779] bg-gray-50 rounded-lg w-3/4 p-2 text-center focus:ring-1 focus:ring-[#3b4779]" 
                value="{{ $targetHours }}" /> 
            @endif
        @else
            @if (is_numeric($targetHours))
                <div class="flex items-center justify-center h-full">
                    {{ $targetHours }} ({{ number_format($targetHours / 12, 2) }}/month)
                </div>
            @else
                <div class="flex items-center justify-center h-full">
                    {{ $targetHours }} 
                </div>
            @endif
        @endif
    </td>
    <td class="px-6 py-4">
        <div class="flex items-center justify-center h-full">
            <a href="{{ route('instructor-report', ['instructor_id' => $instructorId]) }}"><span class="material-symbols-outlined hover:text-[#3b4779] transform hover:scale-110 transition duration-300" title="Report">description</span></a>
        </div>
    </td>
</tr>
@endif