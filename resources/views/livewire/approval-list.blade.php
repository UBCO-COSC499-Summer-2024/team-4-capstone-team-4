<div class="sreq-list" x-data="{
    search: '',
    selectedFilter: @entangle('selectedFilter').defer,
    sortColumn(column) {
        if (column === this.selectedSort) {
            this.selectedSortOrder = this.selectedSortOrder === 'asc' ? 'desc' : 'asc';
        } else {
            this.selectedSort = column;
            this.selectedSortOrder = 'desc';
        }
        @this.call('sort-selected', this.selectedSort, this.selectedSortOrder);
    }
}">
    <section class="toolbar" id="toolbar">
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
                                        x-on:click="toggleCheck()">
                                        <span x-text="item"></span>
                                        <span x-show="isChecked" class="material-symbols-outlined icon filter-item-check">
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
    <section class="w-full">
        <livewire:approval-list-header :headers="$headers" :type="$type" />
        <tbody>
            @forelse ($approvals as $approval)
                <livewire:templates.approval-list-item :approval="$approval" :key="$type.$approval->id" :type="$type" />
            @empty
                <tr class="svcr-list-item">
                    <td class="svcr-list-item-cell" colspan="12">No approvals found.</td>
                </tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr class="svcr-table-footer">
                <td class="svcr-table-footer-item" colspan="100%">
                    @if ($approvals->count() > 0)
                        {{ $approvals->links() }}
                    @endif
                </td>
            </tr>
        </tfoot>
    </section>
</div>
