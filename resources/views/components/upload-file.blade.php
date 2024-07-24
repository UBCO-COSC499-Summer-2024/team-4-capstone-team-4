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
    <div class="mb-4">
        <input type="file" name="files[]" id="fileInput" multiple class="text-center p-20 border-2 border-dashed border-[#3b4779] rounded-md block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:bg-[#3b4779] file:text-white hover:file:bg-[#3b4779] hover:cursor-pointer hover:bg-gray-100 focus:outline-none focus:border-dashed focus:border-[#3b4779]">
    </div>
    <div class="flex justify-end">
        <button type="submit" class="import-form-add-button">Save</button>
    </div>
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

