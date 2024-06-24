<div>
    @if(session('success'))
        {{ session('success') }}
    @endif

    <form wire:submit.prevent="handleClick" class="import-form relative">
        <div class="header flex justify-between p-2 bg-gray-200">
            <div class="w-1/12 text-center">#</div>
            <div class="w-4/12 text-center">Course Name</div>
            <div class="w-3/12 text-center">Area</div>
            <div class="w-3/12 text-center">Duration</div>
            <div class="w-3/12 text-center">Enrolled</div>
            <div class="w-3/12 text-center">Dropped</div>
            <div class="w-3/12 text-center">Capacity</div>
            <div class="w-3/12 text-center"></div>
        </div>

        @foreach ($rows as $index => $row)
        <div class="import-form-row flex justify-between items-center p-2 border-b">
            <div class="w-1/12 pl-2">{{ $index + 1 }}</div>
            <div class="import-input w-4/12">
                <input type="text" placeholder="123456" wire:model="rows.{{$index}}.course_name" class="p-1 w-full">
                @error('rows.'.$index.'.course_name')<span>{{ $message }}</span>@enderror
            </div>
            <div class="import-input w-3/12">
                <input type="text" placeholder="#" wire:model="rows.{{$index}}.area_id" class="p-1 w-full">
                @error('rows.'.$index.'.area_id')<span>{{ $message }}</span>@enderror
            </div>
            <div class="import-input w-3/12">
                <input type="text" placeholder="#" wire:model="rows.{{$index}}.duration" class="p-1 w-full">
                @error('rows.'.$index.'.duration')<span>{{ $message }}</span>@enderror
            </div>
            <div class="import-input w-3/12">
                <input type="text" placeholder="#" wire:model="rows.{{$index}}.enrolled" class="p-1 w-full">
                @error('rows.'.$index.'.enrolled')<span>{{ $message }}</span>@enderror
            </div>
            <div class="import-input w-3/12">
                <input type="text" placeholder="#" wire:model="rows.{{$index}}.dropped" class="p-1 w-full">
                @error('rows.'.$index.'.dropped')<span>{{ $message }}</span>@enderror
            </div>
            <div class="import-input w-3/12">
                <input type="text" placeholder="#" wire:model="rows.{{$index}}.capacity" class="p-1 w-full">
                @error('rows.'.$index.'.capacity')<span>{{ $message }}</span>@enderror
            </div>
            <div class="w-3/12 flex justify-center border-l border-gray-300">
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
</div>
