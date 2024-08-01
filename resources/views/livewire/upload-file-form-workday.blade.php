<div>
    <div class="relative overflow-x-auto shadow-sm rounded-md">
        <div class="py-3 flex justify-between bg-[#3b4779] text-white">
            <div class="w-1/12 text-center mx-1">#</div>
            <div class="w-4/12 text-center mx-1">Area</div>
            <div class="w-2/12 text-center mx-1">Number</div>
            <div class="w-2/12 text-center mx-1">Section</div>
            <div class="w-2/12 text-center mx-1">Session</div>
            <div class="w-2/12 text-center mx-1">Term</div>
            <div class="w-2/12 text-center mx-1">Year</div>
            <div class="w-2/12 text-center mx-1">Room</div>
            <div class="w-5/12 text-center mx-1">Time</div>
            <div class="w-2/12 text-center mx-1">Enroll (Start)</div>
            <div class="w-2/12 text-center mx-1">Enroll (End)</div>
            <div class="w-2/12 text-center mx-1">Capacity</div>
            <div class="w-1/12 text-center mx-1"></div>
        </div>

        @if (!empty($finalCSVs))
        <form wire:submit.prevent="handleSubmit" class="relative">
            @foreach($rows as $index => $row)
                <div class="import-form-row">
                    <div class="w-1/12 text-center">{{ $index + 1 }}</div>
                    <div class="w-4/12">       
                        <select wire:model="rows.{{ $index }}.area_id" class="import-form-select">
                            <option value="">Select</option>
                            @foreach ($areas as $area)
                                <option value="{{ $area->id }}" {{ $rows[$index]['area'] == $area->name ? 'selected' : '' }}>
                                    {{ $area->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('rows.'.$index.'.area_id')<span class="import-error">{{ $message }}</span>@enderror
                    </div>
                    
                    <div class="w-2/12">       
                        <input type="number" step="1" min="1" max="999" 
                               placeholder="Number" 
                               wire:model="rows.{{ $index }}.number" 
                               class="import-form-input" required>
                        @error('rows.'.$index.'.number')<span class="import-error">{{ $message }}</span>@enderror
                    </div>
                    
                    <div class="w-2/12">       
                        <input type="text" 
                               placeholder="Section" 
                               wire:model="rows.{{ $index }}.section" 
                               class="import-form-input" required>
                        @error('rows.'.$index.'.section')<span class="import-error">{{ $message }}</span>@enderror
                    </div>

                    <div class="w-2/12">       
                        <select wire:model="rows.{{ $index }}.session" class="import-form-select">
                            <option value="">Select</option>
                            <option value="W" {{ $rows[$index]['session'] == 'W' ? 'selected' : '' }}>W</option>
                            <option value="S" {{ $rows[$index]['session'] == 'S' ? 'selected' : '' }}>S</option>
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
                    
                    <div class="w-2/12">       
                        <input type="number" step="1" min="1" 
                               placeholder="Year" 
                               wire:model="rows.{{ $index }}.year" 
                               class="import-form-input year-input" required>
                        @error('rows.'.$index.'.year')<span class="import-error">{{ $message }}</span>@enderror
                    </div>

                    <div class="w-2/12">       
                        <input type="text"
                               placeholder="" 
                               wire:model="rows.{{ $index }}.room" 
                               class="import-form-input year-input" required>
                        @error('rows.'.$index.'.room')<span class="import-error">{{ $message }}</span>@enderror
                    </div>
                    
                    <div class="w-5/12 flex flex-row items-center justify-center">
                        <div>
                            <input type="text" placeholder="ex. 11:00" wire:model="rows.{{$index}}.time_start" class="import-form-input year-input">
                            @error('rows.'.$index.'.time_start')<span class="import-error">{{ $message }}</span>@enderror
                        </div>
                        <div class="text-md text-black">to</div>
                        <div>
                            <input type="text" placeholder="ex. 14:30" wire:model="rows.{{$index}}.time_end" class="import-form-input year-input">
                            @error('rows.'.$index.'.time_end')<span class="import-error">{{ $message }}</span>@enderror
                        </div>
                    </div>

                    <div class="w-2/12">       
                        <input type="number" step="1" min="1" max="999" 
                               placeholder="#" 
                               wire:model="rows.{{ $index }}.enroll_start" 
                               class="import-form-input" required>
                        @error('rows.'.$index.'.enroll_start')<span class="import-error">{{ $message }}</span>@enderror
                    </div>

                    <div class="w-2/12">       
                        <input type="number" step="1" min="0" max="999" 
                               placeholder="#" 
                               wire:model="rows.{{ $index }}.enroll_end" 
                               class="import-form-input" required>
                        @error('rows.'.$index.'.enroll_end')<span class="import-error">{{ $message }}</span>@enderror
                    </div>

                    <div class="w-2/12">       
                        <input type="number" step="1" min="1" max="999" 
                               placeholder="Capacity" 
                               wire:model="rows.{{ $index }}.capacity" 
                               class="import-form-input" required>
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

                <div class="mt-4 flex justify-end space-x-2">
                    <button type="submit" class="import-form-save-button">Save</button>
                </div>
           
        </form>
        @endif

        <div wire:loading wire:target="handleSubmit" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
            <div class="text-white text-xl text-center m-80">Saving...</div>
        </div>

        @if($showModal) 
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
            <x-import-modal moreText="Upload Another File"/>
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
        @endif

        @if($showConfirmModal) 
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
            <x-import-confirm-modal :duplicateCourses="$duplicateCourses" />
        </div>
        @endif
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const currentYear = new Date().getFullYear();
        document.querySelectorAll('.year-input').forEach(input => {
            input.setAttribute('max', currentYear);
        });
    });
</script>
