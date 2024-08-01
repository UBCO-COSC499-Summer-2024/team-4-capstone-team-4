<section class="service-request-container" x-data="{
    search: @entangle('search'),
    selectedFilter: @entangle('selectedFilter'),
    filters: @entangle('filters'),
    changeSearchQuery(event) {
        this.search = event.detail[0];
    },
    changeFilter(event) {
        this.selectedFilter[event.detail[0]] = event.detail[1];
    },
    clearFilters() {
        this.selectedFilter = {};
    },
    sortColumn(column) {
        if (this.selectedSort === column) {
            this.selectedSortOrder = this.selectedSortOrder === 'asc' ? 'desc' : 'asc';
        } else {
            this.selectedSort = column;
            this.selectedSortOrder = 'asc';
        }
        @this.call('sortColumn', column, this.selectedSortOrder);
    },
}">
    <section class="z-50 toolbar" id="approval-toolbar">
        <section class="left toolbar-section">
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
            {{-- filter --}}
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
                        x-show="selectedFilter?.length > 0 && Object.keys(selectedFilter).some(category => selectedFilter[category].length > 0)"
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
                                        x-on:click="toggleCheck()">
                                        <span class="filter-item-text">
                                            {{ $item }}
                                        </span>
                                        <span class="material-symbols-outlined icon filter-item-icon"
                                            x-show="isChecked">
                                            check
                                        </span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    </section>

    <section class="service-request-table">
        <table class="svcr-table">
            <thead>
                <tr class="svcr-list-header">
                    <th class="text-center w-fit svcr-table-header-item svcr-list-header-item" data-column="select">
                        <input type="checkbox" class="m-auto svcr-item-check" wire:model="selectAll" />
                    </th>
                    @foreach ($headers as $index => $header)
                        @php
                            $column = $header['name'];
                            $label = $header['label'];
                        @endphp
                        <th class="svcr-list-header-item" data-column="{{ $column }}">
                            <div class="svcr-list-th">
                                <span class="svcr-list-th-text">{{ $label }}</span>
                                <span class="audit-list-sort">
                                    <span @click="sortColumn('{{ $column }}')" class="material-symbols-outlined icon audit-sort-icon">
                                        @if ($selectedSort === $column)
                                            @if ($selectedSortOrder === 'asc')
                                                arrow_drop_up
                                            @else
                                                arrow_drop_down
                                            @endif
                                        @else
                                            unfold_more
                                        @endif
                                    </span>
                                </span>
                            </div>
                        </th>
                    @endforeach
                    <th class="text-center w-fit svcr-table-header-item svcr-list-header-item" data-column="actions">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($types as $type)
                    <livewire:templates.approval-list-item :approval="$type" :type="'type'" wire:key="'approval_type_item_{{ $type->id }}'" :headers="$headers" :options="$itemOptions"/>
                @empty
                    <tr class="svcr-list-item">
                        <td class="svcr-list-item-cell empty" colspan="100%">
                            No approval types found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
            <tfoot>
                <tr class="svcr-list-item">
                    <td class="svcr-list-item-cell empty" colspan="100%">
                        @if (count($types) !== 0)
                            {{ $types->links() }}
                        @endif
                    </td>
                </tr>
            </tfoot>
        </table>
    </section>
</section>
