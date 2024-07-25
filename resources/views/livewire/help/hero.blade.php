<section class="hero">
    <h1 class="hero-title">
        {{ $hero_title }}
    </h1>
    <div class="hero-search">
        <input type="text" class="hero-search-input glass" placeholder="{{ __('Search for help...') }}" wire:model.debounce.500ms="searchQuery">
    </div>
</section>
<script>
    document.addEventListener('DOMContentLoaded', initSearch);
    document.addEventListener('livewire:init', initSearch);
    document.addEventListener('livewire:load', initSearch);
    document.addEventListener('livewire:update', initSearch);

    function initSearch() {
        if (document.querySelector('.hero-search-input.init')) return;
        const searchInput = document.querySelector('.hero-search-input');
        if (!searchInput) return;
        searchInput.classList.add('init');
        console.log('Search input initialized:', searchInput);
        searchInput.addEventListener('input', () => {
            const query = searchInput.value;
            console.log('Search query:', query);
            @this.dispatch('search', {
                'q': query
            });
        });
    }
</script>
