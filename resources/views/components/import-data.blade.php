<div>
    @if (session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
        <div class="mt-3">
            <a href="{{ route('dashboard') }}" class="btn btn-success">{{ __('Go to Dashboard') }}</a>
            <a href="{{ route('import.form') }}" class="btn btn-primary">{{ __('Upload Another File') }}</a>
        </div>
    </div>
    @endif

    @if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif


    <form action="{{ route('import.form') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="form-group">
            <label for="file">Choose file to upload</label>
            <input type="file" name="file" id="file" class="form-control">
        </div>
        <button   button type="submit" class="btn btn-primary mt-3">Upload</button>
    </form>
</div>