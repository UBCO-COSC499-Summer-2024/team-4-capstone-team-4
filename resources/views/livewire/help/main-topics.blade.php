<section class="main-topics">
    <h2 class="main-topics-title">
        {{ __('Main Topics') }}
    </h2>
    <div class="main-topics-list">
        @foreach ($topics as $topic)
            <div class="main-topics-item glass">
                <a href="{{ $topic['url'] }}" class="main-topics-link">
                    <span class="material-symbols-outlined">{{ $topic['icon'] }}</span>
                    <span class="main-topics-title">{{ $topic['title'] }}</span>
                </a>
            </div>
        @endforeach
    </div>
</section>
