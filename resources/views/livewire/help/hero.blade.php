<section class="hero">
    <h1 class="hero-title">
        {{ $hero_title }}
    </h1>
    {{-- <div class="hero-search">
        <input
            type="text"
            class="hero-search-input glass"
            placeholder="{{ __('Search for help...') }}"
            wire:model.live.debounce.500ms="searchQuery"
            x-data="{ search: @entangle('searchQuery') }"
            x-init="$watch('search', value => {
                if (value.length > 0) {
                    @this.call('searchV2', value);
                } else {
                    @this.call('clearSearchResults');
                }
            })"
            x-on:keydown.enter="@this.call('searchV2', search)"
        />
    </div> --}}
</section>
