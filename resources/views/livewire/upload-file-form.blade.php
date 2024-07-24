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
        <form>
            @foreach($finalCSVs as $index => $finalCSV)
                <div class="import-form-row">
                    <div class="w-1/12 text-center">{{ $index + 1 }}</div>
                    <div class="w-2/12">       
                        <input type="text" step="1" min="1" max="999" 
                               placeholder="Area" 
                               wire:model="rows.{{ $index }}.area" 
                               class="import-form-input" required>
                    </div>
                    
                    <div class="w-2/12">       
                        <input type="number" step="1" min="1" max="999" 
                               placeholder="Number" 
                               wire:model="rows.{{ $index }}.number" 
                               class="import-form-input" required>
                    </div>
                    
                    <div class="w-2/12">       
                        <input type="text" 
                               placeholder="Section" 
                               wire:model="rows.{{ $index }}.section" 
                               class="import-form-input" required>
                    </div>

                    <div class="w-2/12">       
                        <input type="text" 
                               placeholder="Session"  
                               wire:model="rows.{{ $index }}.session" 
                               class="import-form-input" required>
                    </div>

                    <div class="w-2/12">       
                        <input type="text" 
                               placeholder="Term"  
                               wire:model="rows.{{ $index }}.term" 
                               class="import-form-input" required>
                    </div>
                    
                    <div class="w-2/12">       
                        <input type="number" step="1" min="1" max="9999" 
                               placeholder="Year" 
                               wire:model="rows.{{ $index }}.year" 
                               class="import-form-input" required>
                    </div>
                    
                    <div class="w-2/12">       
                        <input type="number" step="1" min="1" max="999" 
                               placeholder="Enrolled" 
                               wire:model="rows.{{ $index }}.enrolled" 
                               class="import-form-input" required>
                    </div>

                    <div class="w-2/12">       
                        <input type="number" step="1" min="1" max="999" 
                               placeholder="Dropped" 
                               wire:model="rows.{{ $index }}.dropped" 
                               class="import-form-input" required>
                    </div>

                    <div class="w-2/12">       
                        <input type="number" step="1" min="1" max="999" 
                               placeholder="Capacity" 
                               wire:model="rows.{{ $index }}.capacity" 
                               class="import-form-input" required>
                    </div>
                </div>   
                @endforeach
           
        </form>
        @endif
    </div>
</div>


{{-- @foreach ($finalCSVs as $finalCSV)
@foreach ($finalCSV as $key => $value)
<div>{{$key}}{{$value}}</div>
@endforeach
@endforeach --}}