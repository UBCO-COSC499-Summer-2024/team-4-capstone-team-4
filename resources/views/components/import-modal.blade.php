<div>
    @if (session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
        <div class="mt-3">
            <a href="{{ route('dashboard') }}" class="btn btn-success">{{ __('Go to Dashboard') }}</a>
            <a href="{{ route('import') }}" class="btn btn-primary">{{ __('Upload Another File') }}</a>
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
</div>