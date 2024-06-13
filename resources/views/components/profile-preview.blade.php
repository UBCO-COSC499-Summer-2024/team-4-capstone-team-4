<div class="dash-profile-preview glass">
    <div class="profile-details">
        @if(!isset($fullname))
            <h2>{{ __('User') }}</h2>
        @else
            <h2>{{ $fullname }}</h2>
        @endif
        @if(isset($data))
            @foreach ($data as $key => $value)
                <p>{{ $key }}: {{ $value }}</p>
                @if (!$loop->last)
                    <hr>
                @endif
            @endforeach
        @endif
    </div>
</div>