@php
    // $csvData = session('csvData');
    $trimCSV = session('trimCSV');
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

{{-- @foreach ($csvData as $key => $value)
    <div>{{$key}}</div>
    <div>{{$value}}</div>
@endforeach --}}

@foreach ($trimCSV as $key => $value)
    <div>{{$key}} : {{$value}}</div>
@endforeach


{{-- <div class="import-container">
    <div class="import-form-container">    
        <form action="{{ route('import') }}" method="POST" enctype="multipart/form-data">
       
        @csrf
            <div class="import-select">
                <label class="import-label" for="data">Choose a type:</label>

                <select name="data" id="data">
                    <option value="sei">SEI Data</option>
                    <option value="saab">Workday Data</option>
                </select> 
            </div>

            <div class="import-file">
                <label class="import-label">Upload a File:</label>
                <div class="drop-zone">
                    <span class="drop-zone-prompt">Drop file here or <div class="drop-zone-button">Browse</div></span>
                    <input type="file" name="file" class="drop-zone-input outline outline-blue-500">
                  </div>
                 
            </div>
            <span id="file-name" class="drop-zone-file-name"></span>
            <button type="submit" class="btn btn-primary mt-3">Upload</button>
        </form>

        @if ($errors->any())
        <div>
            <x-validation-errors />
        </div>  
        @endif
    </div> 


</div>

<script>
    document.querySelectorAll(".drop-zone-input").forEach((inputElement) => {
        const dropZoneElement = inputElement.closest(".drop-zone");

        dropZoneElement.addEventListener("click", (e) => {
            inputElement.click();
        });

        inputElement.addEventListener("change", (e) => {
            if (inputElement.files.length) {
                updateFileName(dropZoneElement, inputElement.files[0]);
            }
        });

        dropZoneElement.addEventListener("dragover", (e) => {
            e.preventDefault();
            dropZoneElement.classList.add("drop-zone-hover");
        });

        ["dragleave", "dragend"].forEach((type) => {
            dropZoneElement.addEventListener(type, (e) => {
                dropZoneElement.classList.remove("drop-zone-hover");
            });
        });

        dropZoneElement.addEventListener("drop", (e) => {
            e.preventDefault();

            if (e.dataTransfer.files.length) {
                inputElement.files = e.dataTransfer.files;
                updateFileName(dropZoneElement, e.dataTransfer.files[0]);
            }

            dropZoneElement.classList.remove("drop-zone-hover");
        });
    });

    function updateFileName(dropZoneElement, file) {
        const fileNameElement = document.getElementById("file-name");
        fileNameElement.textContent = `Selected file: ${file.name}`;
    }
</script> --}}