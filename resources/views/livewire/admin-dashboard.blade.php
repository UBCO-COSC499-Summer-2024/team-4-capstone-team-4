<section class="pages">
    <div class="pages-list">
        @foreach ($pages as $page)
            <div class="pages-item glass">
                <a href="{{ $page['url'] }}" class="pages-link">
                    <span class="material-symbols-outlined">{{ $page['icon'] }}</span>
                    <span class="pages-title">{{ $page['title'] }}</span>
                </a>
            </div>
        @endforeach
    </div>
</section>