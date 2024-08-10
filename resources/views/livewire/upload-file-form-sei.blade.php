<div>
    <div class="relative overflow-x-auto shadow-sm rounded-md">
        <div class="py-3 flex justify-between bg-[#3b4779] text-white">
            <div class="w-1/12 text-center mx-2">#</div>
            <div class="w-6/12 text-center mx-2">Course Section</div>
            <div class="w-6/12 text-center mx-2"></div>
            <div class="w-3/12 text-center mx-2">Q1</div>
            <div class="w-3/12 text-center mx-2">Q2</div>
            <div class="w-3/12 text-center mx-2">Q3</div>
            <div class="w-3/12 text-center mx-2">Q4</div>
            <div class="w-3/12 text-center mx-2">Q5</div>
            <div class="w-3/12 text-center mx-2">Q6</div>
            <div class="w-3/12 text-center mx-2"></div>
        </div>

        @if (!empty($finalCSVs))
        <form wire:submit.prevent="handleSubmit" class="relative text-sm">
            @foreach($rows as $index => $row)
                <div class="import-form-row">
                    <div class="w-1/12 text-center">{{ $index + 1 }}</div>
                    <div class="w-6/12 text-center"> 
                        {{-- basic select to fall back on --}}

                        {{-- <select wire:model="rows.{{ $index }}.cid" wire:change='checkDuplicate' class="import-form-select">
                            <option value="">Select</option>
                            @foreach ($courses as $course)
                                <option value="{{ $course->id }}" {{ $rows[$index]['cid'] == $course->id ? 'selected' : '' }}>
                                    {{ $course->prefix }} {{$course->number}} {{ $course->section }} - {{ $course->year }}{{ $course->session }}{{ $course->term }}
                                </option>
                            @endforeach
                        </select> --}}
                        @if(empty($row['course']))
                        <div class="text-gray-400">No Course Selected</div>
                        @else
                        <div class="text-[#2e3c75]">{{$row['course']}}</div>
                        @endif
                        @error('rows.'.$index.'.cid')<span class="import-error">{{ $message }}</span>@enderror
                    </div>
                    <div class="w-6/12">
                        <button type="button" wire:click="openCourseModal({{$index}})" class="import-form-add-button">Select Course Section</button>
                    </div>
                    <div class="w-3/12">
                        <input type="number" step="0.1" min="1" max="5" placeholder="#" wire:model="rows.{{$index}}.q1" class="import-form-input">
                        @error('rows.'.$index.'.q1')<span class="import-error">{{ $message }}</span>@enderror
                    </div>
                    <div class="w-3/12">
                        <input type="number" step="0.1" min="1" max="5" placeholder="#" wire:model="rows.{{$index}}.q2" class="import-form-input">
                        @error('rows.'.$index.'.q2')<span class="import-error">{{ $message }}</span>@enderror
                    </div>
                    <div class="w-3/12">
                        <input type="number" step="0.1" min="1" max="5" placeholder="#" wire:model="rows.{{$index}}.q3" class="import-form-input">
                        @error('rows.'.$index.'.q3')<span class="import-error">{{ $message }}</span>@enderror
                    </div>
                    <div class="w-3/12">
                        <input type="number" step="0.1" min="1" max="5" placeholder="#" wire:model="rows.{{$index}}.q4" class="import-form-input">
                        @error('rows.'.$index.'.q4')<span class="import-error">{{ $message }}</span>@enderror
                    </div>
                    <div class="w-3/12">
                        <input type="number" step="0.1" min="1" max="5" placeholder="#" wire:model="rows.{{$index}}.q5" class="import-form-input">
                        @error('rows.'.$index.'.q5')<span class="import-error">{{ $message }}</span>@enderror
                    </div>
                    <div class="w-3/12">
                        <input type="number" step="0.1" min="1" max="5" placeholder="#" wire:model="rows.{{$index}}.q6" class="import-form-input">
                        @error('rows.'.$index.'.q6')<span class="import-error">{{ $message }}</span>@enderror
                    </div>

                    <div class="w-3/12 flex justify-center">
                        <button type="button" wire:click.prevent="deleteRow({{ $index }})" class="import-form-delete-button">
                            <span class="material-symbols-outlined">delete</span>
                        </button>
                    </div>
                </div>   
                @endforeach

                <div class="mt-4 flex justify-end space-x-2">
                    <button type="submit" @if($isDuplicate) disabled class="import-form-save-button border-gray-300 text-gray-300 hover:bg-white hover:border-gray-300 hover:text-gray-300" @endif  class="import-form-save-button" >
                        <span class="material-symbols-outlined">save</span>
                        Save
                    </button>
                </div>
           
        </form>
        @endif

        @if($showCourseModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
            <x-custom-search-course-modal :availableCourses="$availableCourses" :filteredCourses="$filteredCourses" :selectedIndex="$selectedIndex" />
        </div>
        @endif

        <div wire:loading wire:target="handleSubmit" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
            <div class="text-white text-xl text-center m-80">Saving...</div>
        </div>


        @if($showModal) 
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
            <x-import-modal moreText="Upload Another File"/>
        </div>
        @endif
    </div>
</div>
