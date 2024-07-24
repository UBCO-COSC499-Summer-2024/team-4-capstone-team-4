@php
    // $csvData = session('csvData');
    // $trimCSV = session('trimCSV', []);
    $finalCSVs = session('finalCSVs', []);
@endphp


@if (session()->has('message'))
    <div class="alert alert-success">
        {{ session('message') }}
    </div>
@endif

<form action="{{ route('upload.file') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <input type="file" name="files[]" multiple>
    <button type="submit">Submit</button>
</form>

@if (session('finalCSVs'))
<livewire:upload-file-form :finalCSVs="session('finalCSVs')" />
@endif

{{-- @foreach ($csvData as $key => $value)
    <div>{{$key}}</div>
    <div>{{$value}}</div>
@endforeach --}}

{{-- @foreach ($trimCSV as $key => $value)
    <div>{{$key}} : {{$value}}</div>
@endforeach --}}

