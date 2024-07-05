<div>

    <div class="italic">*IM = Interpolated Medium</div>
    <form wire:submit="handleSubmit" class="import-form relative">
        <div class="header flex justify-between p-2 bg-gray-200">
            <div class="w-1/12 text-center">#</div>
            <div class="w-6/12 text-center">ID</div>
            <div class="w-3/12 text-center">Q1 (IM)</div>
            <div class="w-3/12 text-center">Q2 (IM)</div>
            <div class="w-3/12 text-center">Q3 (IM)</div>
            <div class="w-3/12 text-center">Q4 (IM)</div>
            <div class="w-3/12 text-center">Q5 (IM)</div>
            <div class="w-3/12 text-center">Q6 (IM)</div>
            <div class="w-3/12 text-center"></div>
        </div>

        @foreach ($rows as $index => $row)
        <div class="import-form-row flex justify-between items-center p-2 border-b">
            <div class="w-1/12 pl-2">{{ $index + 1 }}</div>
            <div class="import-input w-6/12">
                <select wire:model="rows.{{$index}}.cid" class="p-1 w-full">
                    <option value="">Select Course</option>
                    @foreach ($courses as $course)
                        <option value="{{ $course->id }}">{{ $course->name }} {{ $course->section }} - {{ $course->year }}{{ $course->session }}{{ $course->term }}</option>
                    @endforeach
                </select>
                @error('rows.'.$index.'.cid')<span class="import-error">{{ $message }}</span>@enderror
            </div>
            <div class="import-input w-3/12">
                <input type="number" step="0.1" min="1" max="5" placeholder="#" wire:model="rows.{{$index}}.q1" class="p-1 w-full">
                @error('rows.'.$index.'.q1')<span class="import-error">{{ $message }}</span>@enderror
            </div>
            <div class="import-input w-3/12">
                <input type="number" step="0.1" min="1" max="5" placeholder="#" wire:model="rows.{{$index}}.q2" class="p-1 w-full">
                @error('rows.'.$index.'.q2')<span class="import-error">{{ $message }}</span>@enderror
            </div>
            <div class="import-input w-3/12">
                <input type="number" step="0.1" min="1" max="5" placeholder="#" wire:model="rows.{{$index}}.q3" class="p-1 w-full">
                @error('rows.'.$index.'.q3')<span class="import-error">{{ $message }}</span>@enderror
            </div>
            <div class="import-input w-3/12">
                <input type="number" step="0.1" min="1" max="5" placeholder="#" wire:model="rows.{{$index}}.q4" class="p-1 w-full">
                @error('rows.'.$index.'.q4')<span class="import-error">{{ $message }}</span>@enderror
            </div>
            <div class="import-input w-3/12">
                <input type="number" step="0.1" min="1" max="5" placeholder="#" wire:model="rows.{{$index}}.q5" class="p-1 w-full">
                @error('rows.'.$index.'.q5')<span class="import-error">{{ $message }}</span>@enderror
            </div>
            <div class="import-input w-3/12">
                <input type="number" step="0.1" min="1" max="5" placeholder="#" wire:model="rows.{{$index}}.q6" class="p-1 w-full">
                @error('rows.'.$index.'.q6')<span class="import-error">{{ $message }}</span>@enderror
            </div>

            <div class="w-3/12 flex justify-center border-l border-gray-300">
                <button type="button" wire:click.prevent="deleteRow({{ $index }})" class="flex items-center bg-red-500 text-black p-1 rounded hover:bg-red-600">
                    <span class="material-symbols-outlined">delete</span>
                </button>
            </div>
        </div>    

        @endforeach
    
        <div class="mt-4 flex justify-end space-x-2">
            <button type="button" wire:click="addRow" class="bg-blue-500 text-white p-2 rounded hover:bg-blue-600">Add Row</button>
            <button type="submit" class="bg-green-500 text-white p-2 rounded hover:bg-green-600">Save</button>
        </div>
    </form>

    <div wire:loading wire:target="handleSubmit" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
        <div class="text-white text-xl text-center m-80">Saving...</div>
    </div>

    @if(session('success'))
        @if($showModal) 
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
            <x-import-modal moreText="Insert More" />
        </div>
        @endif
    @endif
</div>
