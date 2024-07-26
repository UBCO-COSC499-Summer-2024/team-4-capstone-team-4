<tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
    <td class="px-6 py-4">
        <div class="flex items-center justify-center h-full">{{ $rank }}</div>
    </td>
    <td class="px-6 py-4 text-gray-900 whitespace-nowrap dark:text-white">
        <div class="flex items-center">
            <img class="h-12 w-12 flex-none rounded-full bg-gray-50" src="{{ $src }}" alt="">
            <div class="ps-3 min-w-0 flex-auto">
                <a href="{{ route('performance', ['instructor_id' => $instructorId, 'name' => $fullname]) }}" class="block">
                    <p class="text-sm font-semibold leading-6 text-gray-900">{{ $fullname }}</p>
                </a>
                <a href="{{ route('performance', ['instructor_id' => $instructorId, 'name' => $fullname]) }}" class="block mt-1 truncate text-xs leading-5 text-gray-500">
                    {{ $email }}
                </a>
            </div>
        </div>
    </td>
    <td class="px-6 py-4">
        <div class="flex items-center justify-center h-full">{{ $score }}</div>
    </td>    
    <td class="px-6 py-4">
        <div class="flex items-left justify-left">
            <x-badge :rank="$rankString" :score="$score" :standing="($rank/$count) * 100"/>
        </div>
    </td>
</tr>
