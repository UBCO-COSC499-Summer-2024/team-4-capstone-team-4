{{-- @php
    $finalCSVs = session('finalCSVs', []);
@endphp --}}

<livewire:drag-and-drop route="upload.file.sei"/>
{{-- 
@if (session('finalCSVs'))
<livewire:upload-file-form-sei :finalCSVs="session('finalCSVs')" />
@endif --}}

<livewire:upload-file-form-sei/>
