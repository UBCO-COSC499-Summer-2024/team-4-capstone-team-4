<div x-data="{
    search: @entangle('search'),
    selectedFilter: @entangle('selectedFilter'),
    filters: @entangle('filters'),
    clearFilters() {
        this.selectedFilter = {
            'Users': [],
            'Actions': [],
            'Schemas': [],
            'Operations': []
        };
        this.search = '';
        @this.dispatch('clear-filters');
    },
}">
    <section class="w-full audit-logs-section grid-sticky">
        <div class="grid grid-cols-1 toolbar md:grid-cols-2">
            <section class="flex w-full toolbar-section left">
                <div class="flex-grow toolbar-search-container">
                    <span class="material-symbols-outlined icon toolbar-search-icon">
                        search
                    </span>
                    <input id="toolbar-search"
                        class="flex-grow toolbar-search"
                        type="search" placeholder="Search..."
                        x-on:input="search = $event.target.value; @this.dispatch('change-search-query', [search])"
                        />
                    <span class="material-symbols-outlined icon toolbar-clear-search"
                        x-on:click="search = ''; @this.dispatch('changeSearchQuery', [search]);"
                        >
                        clear
                    </span>
                </div>
            </section>
            <section class="right toolbar-section">
                <div class="filter">
                    <div class="filter-title filter-btn nos">
                        <span class="filter-title-text">
                            Filters
                        </span>
                        <span
                            class="material-symbols-outlined icon filter-title-icon">
                            filter_alt
                        </span>
                    </div>
                    <div class="filter-items-holder glass">
                        <button class="filter-clear-btn"
                            x-on:click="@this.dispatch('clear-filters')"
                            x-show="Object.keys(selectedFilter).some(category => selectedFilter[category].length > 0)"
                            x-cloak>
                            <span class="btn-title">
                                Clear Filters
                            </span>
                            <span class="material-symbols-outlined icon filter-clear">
                                clear
                            </span>
                        </button>
                        @foreach ($filters as $category => $filter)
                            <div class="filter-category">
                                <span class="filter-category-title">
                                    {{ $category }}
                                </span>
                                <div class="filter-items">
                                    @foreach ($filter as $item)
                                        <div class="filter-item nos"
                                            x-data="{
                                                category: '{{ $category }}',
                                                item: '{{ $item }}',
                                                isChecked: selectedFilter['{{ $category }}'].includes('{{ $item }}'),
                                                toggleCheck() {
                                                    this.isChecked = !this.isChecked;
                                                    if (this.isChecked) {
                                                        selectedFilter[this.category].push(this.item);
                                                    } else {
                                                        selectedFilter[this.category] = selectedFilter[this.category].filter(i => i !== this.item);
                                                    }
                                                    @this.dispatch('change-filter', [this.category, this.item, this.isChecked]);
                                                }
                                            }"
                                            x-on:click.stop="toggleCheck"
                                        >
                                            <input type="checkbox"
                                                id="{{ $category }}-{{ $item }}"
                                                class="rounded-sm"
                                                x-on:click.stop
                                                x-on:change.stop
                                                x-bind:checked="isChecked"
                                                disabled
                                            />
                                            <span class="filter-item-text" data-text="{{$item}}">{{ $item }}</span>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </section>
        </div>
    </section>
    <section class="audit-logs-section">
        <livewire:audit-log-table />
    </section>
</div>
