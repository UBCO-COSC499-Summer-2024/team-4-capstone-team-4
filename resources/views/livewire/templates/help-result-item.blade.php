<div class="help-result-item">
    <a href="{{ $result['url'] ?? route('help') }}" class="help-result-link">
        {{-- <span class="material-symbols-outlined">{{ $result['icon'] }}</span> --}}
        <span class="help-result-title">{{ $result['topic'] }}</span>
    </a>
    <p class="help-result-description">{!! $result['content'] ?? 'No description available' !!}</p>
    <div class="help-result-tags">
        @if (isset($result['tags']))
            @foreach ($result['tags'] as $tag)
                <span class="help-result-tag">{{ $tag }}</span>
            @endforeach
        @endif
    </div>
</div>
