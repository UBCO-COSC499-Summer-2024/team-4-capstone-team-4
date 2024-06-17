<div>
    
    <x-import-modal />

    <form action="{{ route('import') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="form-group">
            <label for="file">Choose file to upload</label>
            <input type="file" name="file" id="file" class="form-control">
        </div>
        <button   button type="submit" class="btn btn-primary mt-3">Upload</button>
    </form>
</div>