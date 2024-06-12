<div class="dash-profile-preview glass">
    <div class="profile-details">
        <h2>{{ $fullname }}</h2>
        @foreach ($data as $key => $value)
            <p>{{ $key }}: {{ $value }}</p>
            @if (!$loop->last)
                <hr>
            @endif
        @endforeach
    </div>
</div>