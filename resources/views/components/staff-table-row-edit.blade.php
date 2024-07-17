@props(['targetHours', 'email', 'subarea', 'completedHours','rating', 'fullname', 'src'])

<tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
    <td class="w-3 px-6 py-4">
        <div class="flex items-center justify-center">
            <x-checkbox name="staff-checkboxes[]" value="{{ $email }}" class="staff-checkbox"/>
        </div>
    </td>
    <td class="flex items-center px-0 py-4 text-gray-900 whitespace-nowrap dark:text-white">
        <img class="h-12 w-12 flex-none rounded-full bg-gray-50" src="{{ $src }}" alt="">
        <div class="ps-3 min-w-0 flex-auto">
            <p class="text-sm font-semibold leading-6 text-gray-900">{{ $fullname }}</p>
            <p name="email" class="mt-1 truncate text-xs leading-5 text-gray-500">{{ $email }}</p>
        </div>
    </td>
    <td class="px-6 py-4">
        <div class="flex items-center justify-center">
            <div class="text-center">{{ $subarea }}</div>
        </div>
    </td>    
    <td class="px-6 py-4">
        <div class="flex items-center justify-center">
            <div class="text-center">{{ $completedHours }}</div>
        </div>
    </td>
    <td class="px-6 py-4">
        <div class="flex items-center justify-center">
            @if($targetHours === '-')
            <input wire:change="update('{{ $email }}', $event.target.value)" type="text" name="hours" class="border border-solid border-[#3b4779] bg-gray-50 rounded-lg w-3/4 p-2 text-center focus:ring-1 focus:ring-[#3b4779]" 
            value="" />
        @else
            <input wire:change="update('{{ $email }}', $event.target.value)" type="text" name="hours" class="border border-solid border-[#3b4779] bg-gray-50 rounded-lg w-3/4 p-2 text-center focus:ring-1 focus:ring-[#3b4779]" 
            value="{{ $targetHours }}" /> 
        @endif
        
        </div>
    </td>
    <td class="px-6 py-4">
        <div class="flex items-center justify-center">
            <div class="text-center">{{ $rating }}</div>
        </div>
    </td>
</tr>
