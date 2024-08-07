@php
    $user = Auth::user();
@endphp

<div class="relative">
    <form wire:submit.prevent="handleSubmit">
        <div class="overflow-x-auto shadow-sm rounded-md">
            <div class="flex justify-between items-center">
                <div class="italic">*Note: Not all courses need to be assigned to save</div>
                <div class="flex justify-end space-x-2">
                <button type="submit" class="import-form-save-button">
                    <span class="material-symbols-outlined">save</span>
                    Save
                </button>
                </div>
            </div>

            <div class="py-3 flex justify-between bg-[#3b4779] text-white rounded-t-md">
                <div class="w-3/12"></div>
                <div class="w-10/12 text-center mx-2">Course Section</div>
                <div class="w-3/12 text-center mx-2">TA</div>
                <div class="w-6/12 text-center mx-2"></div>
                <div class="w-3/12"></div>
            </div>
            @if($finalCSVs)
                @foreach($assignments as $index => $assignment)
                <div class="import-form-row">
                    @php
                        // $course = $availableCourses->firstWhere('id', $assignment['course_section_id']);

                        $courseId = $assignment['course_section_id'] ?? null;
                        $course = $courseId ? $availableCourses->firstWhere('id', $courseId) : null;
                        // dd($course);
                    @endphp
                    @if($course)
                    <div class="w-3/12"></div>
                    <div class="w-10/12 text-center text-sm">
                        <div>{{ $course->prefix }} {{ $course->number }} {{ $course->section }} - {{ $course->year }}{{ $course->session }} Term {{ $course->term }}</div>
                    </div>
                    <div class="w-3/12 text-center">
                        {{-- basic select to fall back on --}}

                        {{-- <select wire:model="assignments.{{ $index }}.ta_id" class="import-form-select">
                            <option value="">Select TA</option>
                            @foreach($availableTas as $ta)
                                <option value="{{ $ta->id }}" {{ $assignments[$index]['ta'] == "{$ta->name}" ? 'selected' : '' }}>
                                    {{ $ta->name }}
                                </option>
                            @endforeach
                        </select>
                        @foreach($availableTas as $ta)
                            @if($assignments[$index]['ta'] == "{$ta->name}")
                                <div class="text-green-500">TA Found!</div>
                            @endif
                        @endforeach  --}}
                        {{-- <div class="flex items-center">
                            <span>{{ $assignment['ta'] ?? 'Select TA' }}</span>
                            <button type="button" class="ml-2 bg-gray-500 text-white p-2 rounded" wire:click="$emit('openModal', {{$availableTas}}, {{ $assignment['ta_id'] }}, {{ $index }})">
                                Select TA
                            </button>
                        </div> --}}
            
                        <div>{{$assignment['ta']}}</div>
    
                        {{-- <div>{{$assignment['ta_id']}}</div> --}}
                    </div>
                    <div class="w-6/12">
                        <button type="button" wire:click="openTaModal({{$index}})" class="import-form-add-button">Select Ta</button>
                    </div>
                    <div class="w-3/12"></div>
                    @endif
                </div>
                @endforeach 
            @endif
        </div>
    </form>

    @if($showTaModal)
    <div class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
        <x-custom-search-ta-modal :availableTas="$availableTas" :filteredTas="$filteredTas" :selectedIndex="$selectedIndex"/>
    </div>

    {{-- within same component to fall back on --}}

    {{-- <div class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
        <div class="relative bg-white rounded-lg shadow-lg w-11/12 max-w-lg">
            <button class="absolute text-black text-4xl font-bold right-3 top-1" wire:click="closeTaModal">
                <span class="material-symbols-outlined">close</span>
            </button>
            <input type="text" wire:model="searchTerm" wire:change="updateSearch" class="w-full p-2 border-b-2 border-gray-300" placeholder="Search for a TA...">
            @foreach($filteredTas as $ta)
                <div wire:click="selectTa({{ $ta->id }}, '{{ $ta->name }}', {{ $selectedIndex }})">
                    {{ $ta->name }}
                </div>
            @endforeach
        </div>
    </div> --}}

    @endif

    <div wire:loading wire:target="handleSubmit" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
        <div class="text-white text-xl text-center m-80">Saving...</div>
    </div>

    @if(session('success'))
        @if($showModal) 
            <div class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
                @include('components.import-modal', ['user' => $user, 'moreText' => 'Upload Another File'])
            </div>
        @endif
    @endif
</div>

