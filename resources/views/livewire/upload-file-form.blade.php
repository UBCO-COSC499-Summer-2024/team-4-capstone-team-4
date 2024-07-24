<div>
    <div class="relative overflow-x-auto shadow-sm rounded-md">
        <div class="py-3 flex justify-between bg-[#3b4779] text-white">
            <div class="w-1/12 text-center mx-2">#</div>
            <div class="w-2/12 text-center mx-2">Area</div>
            <div class="w-2/12 text-center mx-2">Number</div>
            <div class="w-2/12 text-center mx-2">Section</div>
            <div class="w-2/12 text-center mx-2">Session</div>
            <div class="w-2/12 text-center mx-2">Term</div>
            <div class="w-2/12 text-center mx-2">Year</div>
            <div class="w-2/12 text-center mx-2">Enrolled</div>
            <div class="w-2/12 text-center mx-2">Dropped</div>
            <div class="w-2/12 text-center mx-2">Capacity</div>
        </div>

        @if (!empty($finalCSVs))
        <form wire:submit.prevent="handleSubmit" class="relative">
            @foreach($finalCSVs as $index => $finalCSV)
                <div class="import-form-row">
                    <div class="w-1/12 text-center">{{ $index + 1 }}</div>
                    <div class="w-2/12">       
                        <select wire:model="rows.{{ $index }}.area" class="import-form-select">
                            <option value="">Select</option>
                            {{-- @foreach ($areas as $area)
                                <option value="{{ $area->id }}" {{ $rows['area'] == $area['id'] ? 'selected' : '' }}>
                                    {{ $area['name'] }}
                                </option>
                            @endforeach --}}
                            <option value="COSC" {{ $rows[$index]['area'] == 'COSC' ? 'selected' : '' }}>COSC</option>
                            <option value="MATH" {{ $rows[$index]['area'] == 'MATH' ? 'selected' : '' }}>MATH</option>
                            <option value="PHYS" {{ $rows[$index]['area'] == 'PHYS' ? 'selected' : '' }}>PHYS</option>
                            <option value="STAT" {{ $rows[$index]['area'] == 'STAT' ? 'selected' : '' }}>STAT</option>
                        </select>
                        @error('rows.'.$index.'.area')<span class="import-error">{{ $message }}</span>@enderror
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
                        <input type="number" step="1" min="1" max="9999" 
                               placeholder="Year" 
                               wire:model="rows.{{ $index }}.year" 
                               class="import-form-input" required>
                        @error('rows.'.$index.'.year')<span class="import-error">{{ $message }}</span>@enderror
                    </div>
                    
                    <div class="w-2/12">       
                        <input type="number" step="1" min="1" max="999" 
                               placeholder="Enrolled" 
                               wire:model="rows.{{ $index }}.enrolled" 
                               class="import-form-input" required>
                        @error('rows.'.$index.'.enrolled')<span class="import-error">{{ $message }}</span>@enderror
                    </div>

                    <div class="w-2/12">       
                        <input type="number" step="1" min="1" max="999" 
                               placeholder="Dropped" 
                               wire:model="rows.{{ $index }}.dropped" 
                               class="import-form-input" required>
                        @error('rows.'.$index.'.dropped')<span class="import-error">{{ $message }}</span>@enderror
                    </div>

                    <div class="w-2/12">       
                        <input type="number" step="1" min="1" max="999" 
                               placeholder="Capacity" 
                               wire:model="rows.{{ $index }}.capacity" 
                               class="import-form-input" required>
                        @error('rows.'.$index.'.capacity')<span class="import-error">{{ $message }}</span>@enderror
                    </div>
                </div>   
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
        </div>
        @endif
    </div>
</div>


{{-- @foreach ($finalCSVs as $finalCSV)
@foreach ($finalCSV as $key => $value)
<div>{{$key}}{{$value}}</div>
@endforeach
@endforeach --}}