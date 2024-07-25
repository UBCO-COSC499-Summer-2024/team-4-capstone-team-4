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
    {{-- Optional meta information
    <div class="help-result-meta">
        <span class="help-result-meta-item">
            <span class="material-symbols-outlined">visibility</span>
            <span class="help-result-meta-text">{{ $result['views'] ?? '0' }}</span>
        </span>
        <span class="help-result-meta-item">
            <span class="material-symbols-outlined">thumb_up</span>
            <span class="help-result-meta-text">{{ $result['likes'] ?? '0' }}</span>
        </span>
        <span class="help-result-meta-item">
            <span class="material-symbols-outlined">thumb_down</span>
            <span class="help-result-meta-text">{{ $result['dislikes'] ?? '0' }}</span>
        </span>
    </div>
    --}}
</div>
