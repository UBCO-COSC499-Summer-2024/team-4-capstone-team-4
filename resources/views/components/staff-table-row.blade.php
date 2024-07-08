<tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
    <td class="w-3 px-6 py-4">
        <div class="flex items-center">
            <x-checkbox wire:model="staffCheckboxes" name="staff-checkboxes[]" value="{{ $email }}" class="staff-checkbox"/>
        </div>
    </td>
    <td class="flex items-center px-0 py-4 text-gray-900 whitespace-nowrap dark:text-white">
        <img class="h-12 w-12 flex-none rounded-full bg-gray-50" src="{{ $src }}" alt="">
        <div class="ps-3 min-w-0 flex-auto">
            <a href="{{ route('instructor-report', ['instructor_id' => $instructorId]) }}" class="block">
                <p class="text-sm font-semibold leading-6 text-gray-900">{{ $fullname }}</p>
            </a>
            <a href="{{ route('instructor-report',  ['instructor_id' => $instructorId]) }}" class="block mt-1 truncate text-xs leading-5 text-gray-500">
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
        @if (is_numeric($targetHours))
            <div class="flex items-center justify-center h-full">
                {{ $targetHours }} ({{ number_format($targetHours / 12, 2) }}/month)
            </div>
        @else
            <div class="flex items-center justify-center h-full">
                {{ $targetHours }} 
            </div>
        @endif

    </td>
    <td class="px-6 py-4">
        <div class="flex items-center justify-center h-full">{{ $rating }}</div>
    </td>
</tr>