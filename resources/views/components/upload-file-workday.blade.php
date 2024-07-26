@php
    $finalCSVs = session('finalCSVs', []);
@endphp

<livewire:drag-and-drop route="upload.file.workday"/>

@if (session('finalCSVs'))
<livewire:upload-file-form-workday :finalCSVs="session('finalCSVs')" />
@endif