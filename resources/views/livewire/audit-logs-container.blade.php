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
        @this.call('clearFilters');
    },
    isSelectedFilter(category, item) {
        {{-- console.log(this.selectedFilter[category], item);
        console.log(this.selectedFilter[category]);
        console.log(this.selectedFilter[category].includes(item));
        console.log(this.selectedFilter); --}}
        return this.selectedFilter[category].includes(item);
    },
    selectFilter(category, item) {
        if (this.selectedFilter[category].includes(item)) {
            this.selectedFilter[category] = this.selectedFilter[category].filter(i => i !== item);
        } else {
            this.selectedFilter[category].push(item);
        }
    },
}"
{{-- x-init="
    if (!this.selectedFilter) {
        this.selectedFilter = {
            'Users': [],
            'Actions': [],
            'Schemas': [],
            'Operations': []
        };
    } --}}
{{-- " --}}
>
    <section class="w-full audit-logs-section grid-sticky">
        {{-- grid, 2 columns normally but on smaller screens 1 column --}}
        <div class="grid grid-cols-1 toolbar md:grid-cols-2">
            <section class="flex w-full toolbar-section left">
                {{-- search --}}
                <div class="flex-grow toolbar-search-container">
                    <span class="material-symbols-outlined icon toolbar-search-icon">
                        search
                    </span>
                    <input id="toolbar-search"
                        class="flex-grow toolbar-search"
                        type="search" placeholder="Search..."
                        x-on:input="search = $event.target.value; @this.dispatch('change-search-query', search)"
                        />
                    <span class="material-symbols-outlined icon toolbar-clear-search">
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
                                        <div class="filter-item">
                                            <input type="checkbox"
                                                id="{{ $category }}-{{ $item }}"
                                                class="rounded-sm"
                                                x-on:change="@this.dispatch('change-filter', ['{{ $category }}', '{{ $item }}', $event.target.checked]);
                                                selectFilter('{{ $category }}', '{{ $item }}')"
                                                {{-- x-bind:checked="selectedFilter['{{ $category }}'].includes('{{ $item }}')" --}}
                                                @if ($selectedFilter[$category] && in_array($item, $selectedFilter[$category]))
                                                    checked
                                                @endif
                                            />
                                            <span class="filter-item-text">
                                                {{ $item }}
                                            </span>
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

            // search.addEventListener('input', () => {
            //     @this.dispatch('change-search-query', search.value);
            // });

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
