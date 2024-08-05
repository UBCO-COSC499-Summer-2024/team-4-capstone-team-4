@php
    $finalCSVs = session('finalCSVs', []);
@endphp

<div class="absolute left-0 top-10">
    <button class="bg-white text-[#3b4779] border border-[#3b4779] px-3 py-2 mx-2 rounded-lg hover:bg-[#3b4779] hover:text-white" 
        onclick="location.href='{{ route('assign-courses') }}'">
        <span class="material-symbols-outlined">arrow_back</span>
        Manually Assign TAs
    </button>
</div>

<livewire:drag-and-drop :action="'upload.file.assign.tas'"/>

@if (session('finalCSVs'))
<livewire:upload-file-form-assign-courses :finalCSVs="session('finalCSVs')" />
@endif
