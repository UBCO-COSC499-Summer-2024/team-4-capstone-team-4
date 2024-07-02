<div class="relative">

    <form wire:submit.prevent="handleSubmit" class="import-form relative">
        <div class="header flex justify-between py-2 bg-gray-200">
            <div class="w-1/12 text-center px-1">#</div>
            <div class="w-4/12 text-center px-1">Course Name</div>
            <div class="w-2/12 text-center px-1">Section</div>
            <div class="w-4/12 text-center px-1">Area</div>
            <div class="w-2/12 text-center px-1">Session</div>
            <div class="w-2/12 text-center px-1">Term</div>
            <div class="w-3/12 text-center px-1">Year</div>
            <div class="w-2/12 text-center px-1">Enrolled</div>
            <div class="w-2/12 text-center px-1">Dropped</div>
            <div class="w-2/12 text-center px-1">Capacity</div>
            <div class="w-1/12 text-center px-1"></div>
        </div>

        @foreach ($rows as $index => $row)
        <div class="import-form-row flex justify-between items-center p-2 border-b">
            <div class="w-1/12 pl-2">{{ $index + 1 }}</div>
            <div class="import-input w-4/12">
                <input type="text" placeholder="COSC123" wire:model="rows.{{$index}}.course_name" class="p-1 w-full">
                @error('rows.'.$index.'.course_name')<span class="import-error">{{ $message }}</span>@enderror
            </div>
            <div class="import-input w-2/12">
                <input type="text" placeholder="001" wire:model="rows.{{$index}}.section" class="p-1 w-full">
                @error('rows.'.$index.'.section')<span class="import-error">{{ $message }}</span>@enderror
            </div>
            <div class="import-input w-4/12">
                <select wire:model="rows.{{$index}}.area_id" class="p-1 w-full">
                    <option value="">Select</option>
                    @foreach ($areas as $area)
                        <option value="{{ $area->id }}">{{ $area->name }}</option>
                    @endforeach
                </select>
                @error('rows.'.$index.'.area_id')<span class="import-error">{{ $message }}</span>@enderror
            </div>
            <div class="import-input w-2/12">
                <select wire:model="rows.{{$index}}.session" class="p-1 w-full">
                    <option value="">Select</option>
                    <option value="W">W</option>
                    <option value="S">S</option>
                </select>
                @error('rows.'.$index.'.session')<span class="import-error">{{ $message }}</span>@enderror
            </div>
            <div class="import-input w-2/12">
                <select wire:model="rows.{{$index}}.term" class="p-1 w-full">
                    <option value="">Select</option>
                    <option value="1">1</option>
                    <option value="2">2</option>
                    <option value="1-2">1 & 2</option>
                </select>                
                @error('rows.'.$index.'.term')<span class="import-error">{{ $message }}</span>@enderror
            </div>
            <div class="import-input w-3/12">
                <input type="number" placeholder="2024"  min="1901" max="2099" step="1" wire:model="rows.{{$index}}.year" class="p-1 w-full">
                @error('rows.'.$index.'.year')<span class="import-error">{{ $message }}</span>@enderror
            </div>
            <div class="import-input w-2/12">
                <input type="number" placeholder="#" wire:model="rows.{{$index}}.enrolled" class="p-1 w-full">
                @error('rows.'.$index.'.enrolled')<span class="import-error">{{ $message }}</span>@enderror
            </div>
            <div class="import-input w-2/12">
                <input type="number" placeholder="#" wire:model="rows.{{$index}}.dropped" class="p-1 w-full">
                @error('rows.'.$index.'.dropped')<span class="import-error"> {{ $message }}</span>@enderror
            </div>
            <div class="import-input w-2/12">
                <input type="number" placeholder="#" wire:model="rows.{{$index}}.capacity" class="p-1 w-full">
                @error('rows.'.$index.'.capacity')<span class="import-error">{{ $message }}</span>@enderror
            </div>
            <div class="w-1/12 flex justify-center border-l border-gray-300">
                <button type="button" wire:click.prevent="deleteRow({{ $index }})" class="flex items-center bg-red-500 text-black p-1 rounded hover:bg-red-600">
                    <span class="material-symbols-outlined">delete</span>
                </button>
            </div>
        </div>    

        @endforeach

       
        <div class="mt-4 flex justify-end space-x-2">
            <button type="button" wire:click="addRow" class="bg-blue-500 text-black p-2 rounded hover:bg-blue-600">Add Row</button>
            <button type="submit" class="bg-green-500 text-black p-2 rounded hover:bg-green-600">Save</button>
        </div>
    </form>

    <div wire:loading wire:target="handleSubmit" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
        <div class="text-white text-xl text-center m-80">Saving...</div>
    </div>

    @if(session('success'))
        @if($showModal) 
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
            <x-import-modal />
        </div>
        <div>
            <div>This is the toast text!</div>
            <a href="{{ route('assign-courses') }}"></a>
        </div>
        @endif
    @endif
</div>
