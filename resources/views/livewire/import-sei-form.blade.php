<div class="relative">
    <div class="italic">*IM = Interpolated Median</div>
    <form wire:submit="handleSubmit" class="relative">
        <div class="relative overflow-x-auto shadow-sm rounded-md">
            <div class="py-3 flex justify-between bg-[#3b4779] text-white">
                <div class="w-1/12 text-center mx-2">#</div>
                <div class="w-6/12 text-center mx-2">Course Section</div>
                <div class="w-6/12 text-center mx-2"></div>
                <div class="w-3/12 text-center mx-2">Q1 (IM)</div>
                <div class="w-3/12 text-center mx-2">Q2 (IM)</div>
                <div class="w-3/12 text-center mx-2">Q3 (IM)</div>
                <div class="w-3/12 text-center mx-2">Q4 (IM)</div>
                <div class="w-3/12 text-center mx-2">Q5 (IM)</div>
                <div class="w-3/12 text-center mx-2">Q6 (IM)</div>
                <div class="w-3/12 text-center mx-2"></div>
            </div>

            @if($hasCourses)
            @foreach ($rows as $index => $row)
            <div class="import-form-row">
                <div class="w-1/12 text-center">{{ $index + 1 }}</div>
                <div class="w-6/12 text-center">
                     {{-- basic select to fall back on --}}

                    {{-- <select wire:model="rows.{{$index}}.cid" wire:change='checkDuplicate' class="import-form-select">
                        <option value="">Select Course Section</option>
                        @foreach ($availableCourses as $course)
                            <option value="{{ $course->id }}">{{ $course->prefix }} {{$course->number}} {{ $course->section }} - {{ $course->year }}{{ $course->session }}{{ $course->term }}</option>
                        @endforeach
                    </select>
                    @error('rows.'.$index.'.cid')<span class="import-error">{{ $message }}</span>@enderror --}}
                    @if(empty($row['course']))
                    <div class="import-error italic">Please Select a Course</div>
                    @else
                    <div class="text-[#2e3c75]">{{$row['course']}}</div>
                    @endif
                    @error('rows.'.$index.'.cid')<span class="import-error">{{ $message }}</span>@enderror
                </div>
                <div class="w-6/12">
                    <button type="button" wire:click="openCourseModal({{$index}})" class="import-form-add-button">Select Course Section</button>
                </div>
                <div class="w-3/12">
                    <input type="number" step="0.1" min="1" max="5" placeholder="#" wire:model="rows.{{$index}}.q1" class="import-form-input" required>
                    @error('rows.'.$index.'.q1')<span class="import-error">{{ $message }}</span>@enderror
                </div>
                <div class="w-3/12">
                    <input type="number" step="0.1" min="1" max="5" placeholder="#" wire:model="rows.{{$index}}.q2" class="import-form-input" required>
                    @error('rows.'.$index.'.q2')<span class="import-error">{{ $message }}</span>@enderror
                </div>
                <div class="w-3/12">
                    <input type="number" step="0.1" min="1" max="5" placeholder="#" wire:model="rows.{{$index}}.q3" class="import-form-input" required>
                    @error('rows.'.$index.'.q3')<span class="import-error">{{ $message }}</span>@enderror
                </div>
                <div class="w-3/12">
                    <input type="number" step="0.1" min="1" max="5" placeholder="#" wire:model="rows.{{$index}}.q4" class="import-form-input" required>
                    @error('rows.'.$index.'.q4')<span class="import-error">{{ $message }}</span>@enderror
                </div>
                <div class="w-3/12">
                    <input type="number" step="0.1" min="1" max="5" placeholder="#" wire:model="rows.{{$index}}.q5" class="import-form-input" required>
                    @error('rows.'.$index.'.q5')<span class="import-error">{{ $message }}</span>@enderror
                </div>
                <div class="w-3/12">
                    <input type="number" step="0.1" min="1" max="5" placeholder="#" wire:model="rows.{{$index}}.q6" class="import-form-input" required>
                    @error('rows.'.$index.'.q6')<span class="import-error">{{ $message }}</span>@enderror
                </div>

                <div class="w-3/12 flex justify-center">
                    <button type="button" wire:click.prevent="deleteRow({{ $index }})" class="import-form-delete-button">
                        <span class="material-symbols-outlined">delete</span>
                    </button>
                </div>
            </div>    
            @endforeach
        </div>
        <div class="mt-4 flex justify-end space-x-2">
            <div>
                <input type="number" step="1" min="0" max="999" placeholder="#" wire:model='rowAmount' class="text-black import-form-number-input">
            </div>
            <button type="button" wire:click='addManyRows' class="import-form-add-button">
                <span class="material-symbols-outlined">add</span>   
                Add Many Rows
            </button>
            {{-- <button type="button" wire:click='deleteManyRows' class="rounded-lg import-form-delete-button">Delete Many</button> --}}
            <button type="button" wire:click="addRow" class="import-form-add-button">
                <span class="material-symbols-outlined">add</span>
                Add Row
            </button>
            <button type="submit" @if($isDuplicate) disabled class="import-form-save-button border-gray-300 text-gray-300 hover:bg-white hover:border-gray-300 hover:text-gray-300" @endif  class="import-form-save-button" >
                <span class="material-symbols-outlined">save</span>
                Save
            </button> 
        </div>
    </form>

    @if($showCourseModal)
    <div class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
        <x-custom-search-course-modal :availableCourses="$availableCourses" :filteredCourses="$filteredCourses" :selectedIndex="$selectedIndex" />
    </div>
    @endif

    <div wire:loading wire:target="handleSubmit" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
        <div class="text-white text-xl text-center m-80">Saving...</div>
    </div>
    @endif

    @if(!$hasCourses)
    <div class="flex flex-col items-center justify-center py-10">
        <div class="text-center text-4xl">No courses created yet</div>
        <div class="text-center text-xl">Navigate to the "Add Course Section" tab</div>
        
    </div>
    @endif

    @if(session('success'))
        @if($showModal) 
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
            <x-import-modal moreText="Insert More" />
        </div>
        @endif
    @endif
</div>
