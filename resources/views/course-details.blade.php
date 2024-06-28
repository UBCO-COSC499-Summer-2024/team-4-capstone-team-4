<x-app-layout>
    <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
        <div class="flex items-center justify-between flex-wrap md:flex-nowrap space-y-4 md:space-y-0 pb-4 bg-white dark:bg-gray-900">
            <div class="flex items-center space-x-4">
                <h1 class="justify-between text-2xl font-bold text-gray-1000">{{ __('Department Details') }}</h1>
            </div>
            <div class="flex items-center space-x-4 ml-auto">
                <x-edit-button />
            </div>
        </div> 
        <x-coursedetails-table>
            <x-coursedetails-table-header :sortField="$sortField" :sortDirection="$sortDirection" />
            <tbody>
                @if(isset($courseSections) && !empty($courseSections))
                    @foreach ($courseSections as $section)
                        <x-coursedetails-table-row 
                            serialNumber="{{ $section->serialNumber }}"
                            courseName="{{ $section->name }}" 
                            departmentName="{{ $section->departmentName }}"
                            courseDuration="{{ $section->duration }} weeks" 
                            enrolledStudents="{{ $section->enrolled }}" 
                            droppedStudents="{{ $section->dropped }}" 
                            courseCapacity="{{ $section->capacity }}" 
                        />
                    @endforeach
                @else
                    <tr>
                        <td colspan="7" class="text-center text-gray-500 py-4">No course sections found.</td>
                    </tr>
                @endif
            </tbody>
        </x-coursedetails-table>
    </div> 
    <div class="flex items-center space-x-4 ml-auto">
        <x-save-button />
    </div>
</x-app-layout>
<script src="{{ asset('js/editButton.js') }}"></script>
