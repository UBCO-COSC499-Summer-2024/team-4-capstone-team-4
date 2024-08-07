@php
    $finalCSVs = session('finalCSVs', []);
@endphp

<div class="absolute left-0 top-10">
    <button class="bg-white text-[#3b4779] border border-[#3b4779] px-3 py-2 mx-2 rounded-lg hover:bg-[#3b4779] hover:text-white" 
        onclick="location.href='{{ route('import') }}'">
        <span class="material-symbols-outlined">arrow_back</span>
        Back to Manual Import
    </button>
</div>

<livewire:drag-and-drop :action="'upload.file.workday'"/>

@if (session('finalCSVs'))
<livewire:upload-file-form-workday :finalCSVs="session('finalCSVs')" />
@endif