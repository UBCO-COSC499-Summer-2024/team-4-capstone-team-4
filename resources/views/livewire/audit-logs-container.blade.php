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
                        x-on:click="search = ''; @this.call('changeSearchQuery', '');">
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
                                            <span class="filter-item-text">{{ $item }}</span>
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
    <script>
        document.addEventListener('DOMContentLoaded', () => initToolbar());
        document.addEventListener('livewire:init', () => initToolbar());
        document.addEventListener('livewire:update', () => initToolbar());
        document.addEventListener('livewire:load', () => initToolbar());

        function initToolbar() {
            if (document.querySelector('.toolbar.init')) return;
            const toolbar = document.querySelector('.toolbar');
            if (!toolbar) return;
            toolbar.classList.add('init');
            const search = document.getElementById('toolbar-search');
            const clearSearch = document.querySelector('.toolbar-clear-search');
            const filterBtn = document.querySelector('.filter-btn');
            const filterClear = document.querySelector('.filter-clear');

            clearSearch.addEventListener('click', () => {
                search.value = '';
                @this.dispatch('change-search-query', '');
            });

            filterBtn.addEventListener('click', () => {
                const filterItemsHolder = document.querySelector('.filter-items-holder');
                filterItemsHolder.classList.toggle('open');
            });

            document.addEventListener('click', (e) => {
                const filterItemsHolder = document.querySelector('.filter-items-holder');
                if (!filterItemsHolder) return;
                if (!filterItemsHolder.contains(e.target) && !filterBtn.contains(e.target)) {
                    filterItemsHolder.classList.remove('open');
                }
            });
        }
    </script>
</div>