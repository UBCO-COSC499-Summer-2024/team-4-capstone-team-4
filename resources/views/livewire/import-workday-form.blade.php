<div>
    <form wire:submit="handleClick" class="import-form relative">
        @foreach ($rows as $index => $row)
        <div class="import-form-row">

            <span for="" class="pr-2 border-r-2 border-r-gray-300">{{ $index + 1 }}</span>

            <div class="ml-2">
                <label for="">ID:</label>
                <input type="text" placeholder="123456" wire:model="rows.{{$index}}.id">

                <label for="">Firstname:</label>
                <input type="text" placeholder="John" wire:model="rows.{{$index}}.firstname">

                <label for="">Lastname:</label>
                <input type="text" placeholder="Doe" wire:model="rows.{{$index}}.lastname">
            </div>
            

            <button type="button" wire:click.prevent="deleteRow({{ $index }})" class="absolute right-4 bg-red-500 flex items-center p-2 rounded-sm hover:bg-red-600 hover:cursor-pointer">
                <span class="material-symbols-outlined">delete</span>
            </button>
        </div>    
        @endforeach
        
        <div class="absolute right-0">
            <button type="button" wire:click="addRow" class="bg-blue-500 hover:bg-blue-600 import-button">Add More</button>
            <button type="submit" class="bg-green-500 hover:bg-green-600 import-button">Submit</button>
        </div>
    </form>
</div>
