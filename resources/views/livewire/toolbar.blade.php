<section id="{{ $id }}" class="toolbar {{ $class }}">
    @foreach ($order as $section)
        <section class="toolbar-section">
            @foreach ($section as $feature)
                @if ($features[$feature] ?? false)
                    @switch($feature)
                        @case('viewMode')
                            <livewire:dropdown-element
                                title="View"
                                id="viewModeDropdown"
                                pre-icon="view_comfy"
                                name="viewMode"
                                :values="$viewModes"
                                value="{{ $viewMode }}"
                                wire:model.live="viewMode"
                                :key="'viewModeDropdown-'.time()"
                            />
                            @break

                        @case('pageMode')
                            <livewire:dropdown-element
                                title="Page Mode"
                                id="pageModeDropdown"
                                pre-icon="view_list"
                                name="pageMode"
                                :values="$pageModes"
                                value="{{ $pageMode }}"
                                wire:model.live="pageMode"
                                :key="'pageModeDropdown-'.time()"
                            />
                            @break

                        @case('searchCategory')
                            <livewire:dropdown-element
                                title="Category"
                                id="searchCategoryDropdown"
                                pre-icon="category"
                                name="searchCategory"
                                value="{{ $searchCategory }}"
                                :values="$filterBy"
                                wire:model.live="searchCategory"
                                :key="'searchCategoryDropdown-'.time()"
                            />
                            @break

                        @case('search')
                            <div class="toolbar-search-container">
                                <input type="text" id="toolbar-search" placeholder="Search..." class="toolbar-search" wire:model.live.debounce.300ms="searchQuery" />
                                <span class="material-symbols-outlined icon toolbar-search-icon">search</span>
                                <button wire:click="clearSearch" class="toolbar-clear-search">
                                    <span class="material-symbols-outlined">close</span>
                                </button>
                            </div>
                            @break

                        @case('filter')
                            <livewire:dropdown-element
                                title="Filter"
                                id="filterDropdown"
                                pre-icon="filter_list"
                                name="filter"
                                value="{{ $filter }}"
                                :values="$filterBy"
                                wire:model.live="filter"
                                :key="'filterDropdown-'.time()"
                            />
                            @break

                        @case('filterValue')
                            <input type="text" placeholder="Filter Value..."
                                class="toolbar-filter-value"
                                id="toolbar-filter-value"
                                wire:model.live.debounce.300ms="filterValue" />
                            @break

                        @case('sort')
                            <livewire:dropdown-element
                                title="Sort"
                                id="sortDropdown"
                                pre-icon="sort"
                                name="sort"
                                value="{{ $sort }}"
                                :values="$sortBy"
                                wire:model.live="sort"
                                :key="'sortByDropdown-'.time()"
                            />

                            <livewire:dropdown-element
                                title="Order"
                                id="sortOrderDropdown"
                                pre-icon="sort_by_alpha"
                                name="sortOrder"
                                :values="$sortOrder"
                                wire:model.live="sortOrder"
                                :key="'sortOrderDropdown-'.time()"
                            />
                            @break

                        @case('group')
                            <livewire:dropdown-element
                                title="Group"
                                id="groupByDropdown"
                                pre-icon="group"
                                name="group"
                                value="{{ $group }}"
                                :values="$groupBy"
                                wire:model.live="group"
                                :key="'groupByDropdown-'.time()"
                            />
                            @break

                        @case('actions')
                            <livewire:dropdown-element
                                title="Actions"
                                id="actionsDropdown"
                                pre-icon="list_alt"
                                name="selectedActions"
                                :values="$actions"
                                wire:model.live="selectedActions"
                                {{-- multiple --}}
                                :key="'actionsDropdown-'.time()"
                            />

                            <button wire:click="applyActions" class="toolbar-apply">
                                <span class="material-symbols-outlined icon">done</span>
                            </button>
                            @break

                        @case('export')
                            <livewire:dropdown-element
                                title="Export"
                                id="exportDropdown"
                                pre-icon="save_alt"
                                name="export"
                                :values="$exports"
                                wire:model.live="export"
                                :key="'exportDropdown-'.time()"
                            />
                            @break

                        @case('import')
                            <livewire:dropdown-element
                                title="Import"
                                id="importDropdown"
                                pre-icon="file_upload"
                                name="import"
                                :values="$imports"
                                wire:model.live="import"
                                :key="'importDropdown-'.time()"
                            />
                            @break

                        @case('settings')
                            <button class="toolbar-settings" id="toolbar-settings" wire:click="$dispatch('openSettings')">
                                <span class="material-symbols-outlined icon">settings</span>
                            </button>
                            @break
                    @endswitch
                @endif
            @endforeach
        </section>
    @endforeach
</section>
<script type="text/javascript">
    document.addEventListener('DOMContentLoaded', initElementActions);
    document.addEventListener('livewire:init', initElementActions);
    document.addEventListener('livewire:load', initElementActions);
    document.addEventListener('livewire:update', initElementActions);

    function initElementActions() {
        // for the search inputs, focus on the input when the icon is clicked and clear the search when the close icon is clicked
        const searchIcon = document.querySelector('.toolbar-search-icon');
        const searchInput = document.querySelector('.toolbar-search');
        const clearSearch = document.querySelector('.toolbar-clear-search');

        const filterValue = document.querySelector('.toolbar-filter-value');
        const closeFilter = document.querySelector('.toolbar-clear-filter');
        const filterIcon = document.querySelector('.toolbar-filter-icon');

        if (searchIcon) {
            searchIcon.addEventListener('click', () => {
                searchInput.focus();
            });
        }

        if (clearSearch) {
            clearSearch.addEventListener('click', () => {
                searchInput.value = '';
                searchInput.focus();
            });
        }

        if (filterIcon) {
            filterIcon.addEventListener('click', () => {
                filterValue.focus();
            });
        }

        if (closeFilter) {
            closeFilter.addEventListener('click', () => {
                filterValue.value = '';
                filterValue.focus();
            });
        }

    }
</script>
