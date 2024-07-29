<x-app-layout>
    @vite(['resources/css/audits.css'])
    <div class="content">
        <h1 class="content-title">
            <span class="content-title-text">
                {{ __('Audit Logs') }}
            </span>
        </h1>
        <section class="w-full audit-logs-section">
            {{-- grid, 2 columns normally but on smaller screens 1 column --}}
            <div class="grid grid-cols-1 toolbar md:grid-cols-2">
                <section class="flex w-full toolbar-section left">
                    {{-- search --}}
                    <div class="flex-grow toolbar-search-container">
                        <span class="material-symbols-outlined icon toolbar-search-icon">
                            search
                        </span>
                        <input id="toolbar-search" class="flex-grow toolbar-search" type="search" placeholder="Search..." wire:model="search" />
                        <span class="material-symbols-outlined icon toolbar-clear-search">
                            clear
                        </span>
                    </div>
                </section>
                <section class="right toolbar-section">
                    <div class="filter">
                        <div class="filter-title filter-btn nos">
                            <span class="filter-title-tex">
                                Filters
                            </span>
                            <span class="material-symbols-outlined icon filter-clear">
                                filter_list
                            </span>
                        </div>
                        <div class="filter-items-holder glass">
                            @foreach ($filters as $category => $filter)
                                <div class="filter-category">
                                    <span class="filter-category-title">
                                        {{ $category }}
                                    </span>
                                    <div class="filter-items">
                                        @foreach ($filter as $item)
                                            <div class="filter-item">
                                                <input type="checkbox" wire:model.defer="selectedFilter.{{ $category }}.{{ $item }}" />
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
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', initToolbar());
        document.addEventListener('livewire:init', initToolbar());
        document.addEventListener('livewire:update', initToolbar());
        document.addEventListener('livewire:load', function () {
            Livewire.on('applyFilters', () => {
                axios.get('/audits', {
                    params: {
                        filters: Livewire.component('audit-log-table').filters
                    }
                }).then(response => {
                    // Handle the response if needed
                    console.log(response);
                });
            });
        });

        function initToolbar() {
            if (document.querySelector('.toolbar.init')) return;
            const toolbar = document.querySelector('.toolbar');
            if (!toolbar) return;
            toolbar.classList.add('init');
            const search = document.getElementById('toolbar-search');
            const clearSearch = document.querySelector('.toolbar-clear-search');
            const filterBtn = document.querySelector('.filter-btn');
            const filterClear = document.querySelector('.filter-clear');

            search.addEventListener('input', () => {
                Livewire.dispatch('change-search-query', search.value);
            });

            clearSearch.addEventListener('click', () => {
                search.value = '';
                Livewire.dispatch('change-search-query', '');
            });

            filterBtn.addEventListener('click', () => {
                const filterItemsHolder = document.querySelector('.filter-items-holder');
                filterItemsHolder.classList.toggle('open');
            });

            // click out
            document.addEventListener('click', (e) => {
                const filterItemsHolder = document.querySelector('.filter-items-holder');
                if (!filterItemsHolder) return;
                if (!filterItemsHolder.contains(e.target) && !filterBtn.contains(e.target)) {
                    filterItemsHolder.classList.remove('open');
                }
            });

            filterClear.addEventListener('click', () => {
                Livewire.dispatch('clearFilters');
            });
        }
    </script>
</x-app-layout>
