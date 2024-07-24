<tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
    <td class="w-3 px-6 py-4">
        <div class="flex items-center justify-center h-full">
            <x-checkbox wire:model="staffCheckboxes" name="staff-checkboxes[]" value="{{ $email }}" class="staff-checkbox"/>
        </div>
    </td>
    <td class="flex items-center px-0 py-4 text-gray-900 whitespace-nowrap dark:text-white">
        <img class="h-14 w-14 flex-none rounded-full bg-gray-50" src="{{ $src }}" alt="">
        <div class="ps-3 min-w-0 flex-auto">
            <a href="{{ route('instructor-report', ['instructor_id' => $instructorId]) }}" title="View Performance" class="block hover:underline">
                <p class="text-lg font-semibold leading-6 text-gray-900 hover:text-[#3b4779] transform hover:scale-110 transition duration-300">{{ $fullname }}</p>
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