<div>
    <form wire:submit.prevent="handleClick" class="import-form relative">
        <div class="header flex justify-between p-2 bg-gray-200">
            <div class="w-1/12 text-center">#</div>
            <div class="w-4/12 text-center">ID</div>
            <div class="w-3/12 text-center">Q1</div>
            <div class="w-3/12 text-center">Q2</div>
            <div class="w-3/12 text-center">Q3</div>
            <div class="w-3/12 text-center">Q4</div>
            <div class="w-3/12 text-center">Q5</div>
            <div class="w-3/12 text-center">Q6</div>
            <div class="w-3/12 text-center"></div>
        </div>

        @foreach ($rows as $index => $row)
        <div class="import-form-row flex justify-between items-center p-2 border-b">
            <div class="w-1/12 pl-2">{{ $index + 1 }}</div>
            <div class="import-input w-4/12">
                <input type="text" placeholder="123456" wire:model="rows.{{$index}}.cid" class="p-1 w-full">
                @error('rows.'.$index.'.cid')<span>{{ $message }}</span>@enderror
            </div>
            <div class="import-input w-3/12">
                <input type="text" placeholder="#" wire:model="rows.{{$index}}.q1" class="p-1 w-full">
                @error('rows.'.$index.'.q1')<span>{{ $message }}</span>@enderror
            </div>
            <div class="import-input w-3/12">
                <input type="text" placeholder="#" wire:model="rows.{{$index}}.q2" class="p-1 w-full">
                @error('rows.'.$index.'.q2')<span>{{ $message }}</span>@enderror
            </div>
            <div class="import-input w-3/12">
                <input type="text" placeholder="#" wire:model="rows.{{$index}}.q3" class="p-1 w-full">
                @error('rows.'.$index.'.q3')<span>{{ $message }}</span>@enderror
            </div>
            <div class="import-input w-3/12">
                <input type="text" placeholder="#" wire:model="rows.{{$index}}.q4" class="p-1 w-full">
                @error('rows.'.$index.'.q4')<span>{{ $message }}</span>@enderror
            </div>
            <div class="import-input w-3/12">
                <input type="text" placeholder="#" wire:model="rows.{{$index}}.q5" class="p-1 w-full">
                @error('rows.'.$index.'.q5')<span>{{ $message }}</span>@enderror
            </div>
            <div class="import-input w-3/12">
                <input type="text" placeholder="#" wire:model="rows.{{$index}}.q6" class="p-1 w-full">
                @error('rows.'.$index.'.q6')<span>{{ $message }}</span>@enderror
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
