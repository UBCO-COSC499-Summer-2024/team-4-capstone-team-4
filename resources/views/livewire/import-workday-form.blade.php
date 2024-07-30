<div class="relative">
    {{-- <button class="bg-white text-blue-500 border border-blue-500 py-2 px-4 mx-2 rounded-lg hover:bg-blue-500 hover:text-white" 
                    onclick="location.href='{{ route('upload-file') }}'">
                    Upload File
    </button> --}}
    <form wire:submit.prevent="handleSubmit" class="relative">
        <div class="relative overflow-x-auto shadow-sm rounded-md">
            <div class="py-3 flex justify-between bg-[#3b4779] text-white">
                <div class="w-1/12 text-center mx-2">#</div>
                <div class="w-4/12 text-center mx-2">Area</div>
                <div class="w-2/12 text-center mx-2">Number</div>
                <div class="w-2/12 text-center mx-2">Section</div>
                <div class="w-2/12 text-center mx-2">Session</div>
                <div class="w-2/12 text-center mx-2">Term</div>
                <div class="w-3/12 text-center mx-2">Year</div>
                <div class="w-3/12 text-center mx-2">Room</div>
                <div class="w-3/12 text-center mx-2">Time</div>
                <div class="w-2/12 text-center mx-2">Enrolled (Start)</div>
                <div class="w-2/12 text-center mx-2">Enrolled (End)</div>
                <div class="w-2/12 text-center mx-2">Capacity</div>
                <div class="w-1/12 text-center mx-2"></div>
            </div>

            @foreach ($rows as $index => $row)
            <div class="import-form-row">
                <div class="w-1/12 text-center">{{ $index + 1 }}</div>
                <div class="w-4/12">
                    <select wire:model="rows.{{$index}}.area_id" class="import-form-select">
                        <option value="">Select</option>
                        @foreach ($areas as $area)
                            <option value="{{ $area->id }}">{{ $area->name }}</option>
                        @endforeach
                    </select>
                    @error('rows.'.$index.'.area_id')<span class="import-error">{{ $message }}</span>@enderror
                </div>
                <div class="w-2/12 ">
                    <input type="text" placeholder="ex. 101" wire:model="rows.{{$index}}.number" class="import-form-input ">
                    @error('rows.'.$index.'.number')<span class="import-error">{{ $message }}</span>@enderror
                </div>
                <div class="w-2/12">
                    <input type="text" placeholder="ex. 001" wire:model="rows.{{$index}}.section" class="import-form-input">
                    @error('rows.'.$index.'.section')<span class="import-error">{{ $message }}</span>@enderror
                </div>
                <div class="w-2/12">
                    <select wire:model="rows.{{$index}}.session" class="import-form-select">
                        <option value="">Select</option>
                        <option value="W">W</option>
                        <option value="S">S</option>
                    </select>
                    @error('rows.'.$index.'.session')<span class="import-error">{{ $message }}</span>@enderror
                </div>
                <div class="w-2/12">
                    <select wire:model="rows.{{$index}}.term" class="import-form-select">
                        <option value="">Select</option>
                        <option value="1">1</option>
                        <option value="2">2</option>
                        <option value="1-2">1 & 2</option>
                    </select>                
                    @error('rows.'.$index.'.term')<span class="import-error">{{ $message }}</span>@enderror
                </div>
                <div class="w-3/12">
                    <input type="number" placeholder="ex. 2024"  min="1901" step="1" wire:model="rows.{{$index}}.year" class="import-form-input year-input">
                    @error('rows.'.$index.'.year')<span class="import-error">{{ $message }}</span>@enderror
                </div>
                <div class="w-3/12">
                    <input type="text" placeholder="ex. FIP200" wire:model="rows.{{$index}}.room" class="import-form-input year-input">
                    @error('rows.'.$index.'.room')<span class="import-error">{{ $message }}</span>@enderror
                </div>
                <div class="w-3/12">
                    <input type="text" placeholder="ex. 14:00" wire:model="rows.{{$index}}.time" class="import-form-input year-input">
                    @error('rows.'.$index.'.time')<span class="import-error">{{ $message }}</span>@enderror
                </div>
                <div class="w-2/12">
                    <input type="number" step="1" min="1" max="999" placeholder="#" wire:model="rows.{{$index}}.enroll_start" class="import-form-input" required>
                    @error('rows.'.$index.'.enroll_start')<span class="import-error">{{ $message }}</span>@enderror
                </div>
                <div class="w-2/12">
                    <input type="number" step="1" min="1" max="999" placeholder="#" wire:model="rows.{{$index}}.enroll_end" class="import-form-input" required>
                    @error('rows.'.$index.'.enroll_end')<span class="import-error">{{ $message }}</span>@enderror
                </div>
                <div class="w-2/12">
                    <input type="number" step="1" min="1" max="999" placeholder="#" wire:model="rows.{{$index}}.capacity" class="import-form-input" required>
                    @error('rows.'.$index.'.capacity')<span class="import-error">{{ $message }}</span>@enderror
                </div>
                <div class="w-1/12 flex justify-center">
                    <button type="button" wire:click.prevent="deleteRow({{ $index }})" class="import-form-delete-button">
                        <span class="material-symbols-outlined">delete</span>
                    </button>
                </div>
            </div>    

            @error('rows.'.$index.'.exists')<span class="import-error mx-2">{{ $message }}</span>@enderror
            @error('rows.'.$index.'.duplicate')<span class="import-error mx-2">{{ $message }}</span>@enderror

            @endforeach
        </div>
        <div class="mt-4 flex justify-end space-x-2">
         
            <input type="number" step="1" min="0" max="999" placeholder="#" wire:model='rowAmount' class="text-black">
            <button type="button" wire:click='addManyRows' class="import-form-add-button">Add Many</button>
            {{-- <button type="button" wire:click='deleteManyRows' class="rounded-lg import-form-delete-button">Delete Many</button> --}}
        
            <button type="button" wire:click="addRow" class="import-form-add-button">
                <span class="material-symbols-outlined">add</span>    
                Add Row
            </button>
            <button type="submit" class="import-form-save-button">Save</button>
        </div>
    </form>


    <div wire:loading wire:target="handleSubmit" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
        <div class="text-white text-xl text-center m-80">Saving...</div>
    </div>

    {{-- @if(session()->has('success') && session('success')) --}}
        @if($showModal) 
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
            <x-import-modal moreText="Insert More"/>
            {{-- temp --}}
            <div class="absolute right-5 top-16 bg-blue-50 rounded-sm shadow-lg px-6 py-4 flex flex-col ">
        
                <div>You Can Now Assign Instructors!</div>
                <div class="items-center justify-center text-center mt-2 p-2">
                    <button class="bg-white text-[#3b4779] border border-[#3b4779] py-2 px-4 mx-2 rounded-lg hover:bg-[#3b4779] hover:text-white" 
                    onclick="location.href='{{ route('assign-courses') }}'">
                    Assign
                    </button>
                </div>
            
            </div>
        </div>
        {{-- <div>
            <div>This is the toast text!</div>
            <a href="{{ route('assign-courses') }}"></a>
        </div> --}}

        @endif
    {{-- @endif --}}
    {{-- @foreach ($finalCSVs as $finalCSV)
        <div>hello</div>
    @endforeach --}}
</div>


<script>
    document.addEventListener('DOMContentLoaded', function() {
        const currentYear = new Date().getFullYear();
        document.querySelectorAll('.year-input').forEach(input => {
            input.setAttribute('max', currentYear);
        });
    });
</script>
