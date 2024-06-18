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
                <div>Upload a File:</div>
                <div class="import-file-area">
                    <h3>Choose a file or drag & drop it here</h3>
                    <label class="import-file-input"> Browse Files
                    <input type="file" name="file" id="file" onchange="updateFileName()" />
                    </label>
                    <span id="file-name"></span>
                </div>
            </div>

            <button   button type="submit" class="btn btn-primary mt-3">Upload</button>
        </form>
    </div>



    <x-import-modal />
</div>

<script>
    function updateFileName() {
        var input = document.getElementById('file');
        var fileName = input.files[0].name;
        document.getElementById('file-name').textContent = fileName;
    }
</script>