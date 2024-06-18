<div class="import-container">
    <div class="import-form-container">    
        <form action="{{ route('import') }}" method="POST" enctype="multipart/form-data">
       
        @csrf
            <div class="import-select">
                <label for="data">Choose a type:</label>

                <select name="data" id="data">
                    <option value="volvo">SEI Data</option>
                    <option value="saab">Workday Data</option>
                </select> 
            </div>

            <div class="import-file">
                <div>Choose file to upload</div>
                <div class="import-file-input">
                    <input type="file" name="file" id="file">
                    <label for="file">label</label>
                </div>
            </div>

            <button   button type="submit" class="btn btn-primary mt-3">Upload</button>
        </form>
    </div>



    <x-import-modal />
</div>

{{-- <script>
    function updateFileName() {
        var input = document.getElementById('file');
        var fileName = input.files[0].name;
        document.getElementById('file-name').textContent = fileName;
    }
</script> --}}