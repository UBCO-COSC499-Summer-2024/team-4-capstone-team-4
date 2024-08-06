@php
    $admin = false;
    $user = Auth::user();
    if($user->hasRole('admin')){
        $admin = true;
    }
@endphp

@if($admin)
<tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
    <td class="w-3 px-6 py-4">
        <div class="flex items-center justify-center h-full">
            <x-checkbox wire:model="staffCheckboxes" name="staff-checkboxes[]" value="{{ $email }}" class="staff-checkbox"/>
        </div>
    </td>
    <td class="flex items-center px-0 py-4 text-gray-900 whitespace-nowrap dark:text-white">
        <div class="ps-3 min-w-0 flex-auto">
            @if($editMode )
            <div class="flex flex-wrap mb-4">
                <!-- Firstname Input and Error -->
                <div class="w-full md:w-1/2 pr-2">
                    <input type="text" 
                           wire:model.lazy="firstnames.{{$userid}}" 
                           wire:change="updateFirstname('{{ $userid }}', $event.target.value)" 
                           class="border border-solid border-[#3b4779] bg-white rounded-lg w-full mb-1" 
                           value="{{ $firstname }}" />
                    @error('firstnames.'.$userid)
                        <span class="import-error text-red-500 block">{{ $message }}</span>
                    @enderror
                </div>
            
                <!-- Lastname Input and Error -->
                <div class="w-full md:w-1/2 pl-2">
                    <input type="text" 
                           wire:model.lazy="lastnames.{{$userid}}" 
                           wire:change="updateLastname('{{ $userid }}', $event.target.value)" 
                           class="border border-solid border-[#3b4779] bg-white rounded-lg w-full mb-1" 
                           value="{{ $lastname }}" />
                    @error('lastnames.'.$userid)
                        <span class="import-error text-red-500 block">{{ $message }}</span>
                    @enderror
                </div>
            </div>
            
            <div class="mb-4">
                <input type="email" 
                       wire:model.lazy="emails.{{$userid}}" 
                       wire:change="updateEmail('{{ $userid }}', $event.target.value)" 
                       class="border border-solid border-[#3b4779] bg-white rounded-lg w-full mb-1" 
                       value="{{ $email }}" />
                @error('emails.'.$userid)
                    <span class="import-error text-red-500 block">{{ $message }}</span>
                @enderror
            </div>            

            @else
                <div class="block">
                    <p class="text-lg font-semibold leading-6 text-gray-900">{{ $fullname }}</p>
                </div>
                <div class="block mt-1 truncate text-base leading-5 text-gray-500">
                    {{ $email }}
                </div>
            @endif
        </div>
    </td>
    <td class="px-6 py-4">
        <div class="flex items-center justify-center h-full">{{ $dept }}</div>
    </td> 
    <td class="px-6 py-4">
        @if($editMode)
            <div class="flex flex-col">
                <div class="flex items-center gap-1">
                    <input type="checkbox" wire:model.defer="instructors" value="{{ $userid }}" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 dark:focus:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                    <label for="role-{{ $userid }}">Instructor</label>
                </div>
                <div class="flex items-center gap-1">
                    <input type="checkbox" wire:model.defer="deptHeads" value="{{ $userid }}" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 dark:focus:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                    <label for="role-{{ $userid }}">Department Head</label>
                </div>
                <div class="flex items-center gap-1">
                    <input type="checkbox" wire:model.defer="deptStaffs" value="{{ $userid }}" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 dark:focus:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                    <label for="role-{{ $userid }}">Department Staff</label>
                </div>
                <div class="flex items-center gap-1">
                    <input type="checkbox" wire:model.defer="admins" value="{{ $userid }}" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 dark:focus:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                    <label for="role-{{ $userid }}">Admin</label>
                </div>
            </div>
        @else
            <div class="flex items-center justify-center h-full">{{ empty($roles) ? '-' : implode(', ', $roles) }}</div>
        @endif
    </td>
    <td class="px-6 py-4">
        @if($editMode)
            <div class="flex items-center justify-center h-full">
                <label class="inline-flex items-center cursor-pointer">
                    <input type="checkbox" wire:model.defer="enabledUsers" value="{{ $userid }}" class="sr-only peer"  name="status{{$userid}}">
                    <div class="relative w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-blue-600"></div>
                    <span class="ms-3">Enabled</span>
                </label>
            </div>
        @else
            <div class="flex items-center justify-center h-full">
                @if($active)
                    <div class="text-green-500">Enabled</div>
                @else
                    <div class="text-red-500">Disabled</div>
                @endif
            </div>
        @endif
    </td>     
    <td class="px-6 py-4">
        <div class="flex items-center justify-center h-full">
            <button wire:click="setDelete({{$userid}})"><span class="material-symbols-outlined text-red-500" style="font-size: 1.875rem;" title="Delete">delete</span></button>
            <button wire:click="sendReset({{$userid}})"><span class="material-symbols-outlined text-[#3b4779]" style="font-size: 1.875rem;" title="Send Reset Link">mail_lock</span></button>
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
            <a href="{{ route('performance', ['instructor_id' => $instructorId, 'name' => $fullname]) }}" title="View Performance" class="block hover:underline">
                <p class="text-lg font-semibold leading-6 text-gray-900 hover:text-[#3b4779]">{{ $fullname }}</p>
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
            <a href="{{ route('instructor-report', ['instructor_id' => $instructorId, 'name' => $fullname]) }}"><span class="material-symbols-outlined text-3xl hover:text-[#3b4779]" title="Report">description</span></a>
        </div>
    </td>
</tr>
@endif