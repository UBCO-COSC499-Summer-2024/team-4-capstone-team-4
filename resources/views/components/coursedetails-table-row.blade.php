<tr data-id="{{ $sectionId }}">
    <td class="px-6 py-4 whitespace-nowrap">
        <input type="checkbox" wire:model="selectedCourses" value="{{ $sectionId }}" class="form-checkbox">
    </td>
    <td class="px-6 py-4 whitespace-nowrap">{{ $courseName }}</td>
    <td class="px-6 py-4 whitespace-nowrap">{{ $departmentName }}</td>
    <td class="px-6 py-4 whitespace-nowrap">{{ $instructorName }}</td>
    <td class="px-6 py-4 whitespace-nowrap" contenteditable="false">{{ $enrolledStudents }}</td>
    <td class="px-6 py-4 whitespace-nowrap" contenteditable="false">{{ $droppedStudents }}</td>
    <td class="px-6 py-4 whitespace-nowrap" contenteditable="false">{{ $courseCapacity }}</td>
    <td class="px-6 py-4 whitespace-nowrap">{{ $room }}</td>
    <td class="px-6 py-4 whitespace-nowrap">{{ $timings }}</td>
    <td class="px-6 py-4 whitespace-nowrap">{{ $seiData }}</td>
</tr>
