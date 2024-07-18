@php
    $viewModes = ['table' => 'Table', 'card' => 'Card'];
    $pageModes = ['pagination' => 'Pagination', 'infinite' => 'Infinite Scroll'];
    $filterBy = ['area' => 'Area', 'name' => 'Name'];
    $sortBy = ['name' => 'Name', 'area' => 'Area', 'created_at' => 'Created'];
    $searchCategories = ['name' => 'Name', 'area_id' => 'Area', 'description' => 'Description'];
    $sortOrder = ['asc' => 'Ascending', 'desc' => 'Descending'];
    $actions = [
        'edit' => 'Edit',
        'delete' => 'Delete',
        'archive' => 'Archive',
        'export' => 'Export All',
        'selected' => 'Export Selected',
        'allExcept' => 'Export All Except',
    ];
    $groupBy = ['area_id' => 'Area', 'name' => 'Name'];
    $features = [
        'viewMode' => true,
        'pageMode' => true,
        'search' => true,
        'filter' => true,
        'filterValue' => false,
        'sort' => true,
        'actions' => true,
    ];
@endphp
<div class="content" x-data="{ showExtraHourForm: @entangle('showExtraHourForm') }">
    <h1 class="nos content-title">
        <span class="content-title-text">Service Roles</span>
        <button class="right" onClick="window.location.href='{{ route('svcroles.add') }}'">
            <span class="button-title">Create New</span>
            <span class="material-symbols-outlined">add</span>
        </button>
    </h1>

    <div class="svcr-container">
        <section class="toolbar" id="svcr-toolbar" wire:key='toolbar'>
            <section class="toolbar-section">
                <select id="viewModeDropdown" class="toolbar-dropdown">
                    @foreach ($viewModes as $value => $name)
                        <option value="{{ $value }}"
                                @if ($viewMode == $value) selected @endif
                            >{{ $name }}</option>
                    @endforeach
                </select>

                <select id="pageModeDropdown" class="toolbar-dropdown">
                    @foreach ($pageModes as $value => $name)
                        <option value="{{ $value }}"
                                @if ($pageMode == $value) selected @endif
                            >{{ $name }}</option>
                    @endforeach
                </select>
            </section>
            <section class="toolbar-section">
                <div class="toolbar-search-container">
                    <span class="material-symbols-outlined icon toolbar-search-icon">search</span>
                    <input type="text" id="toolbar-search" placeholder="Search..." class="toolbar-search" wire:model="searchQuery" />
                    <span class="material-symbols-outlined icon toolbar-clear-search">close</span>
                </div>

                <select id="searchCategoryDropdown" class="toolbar-dropdown">
                    @foreach ($searchCategories as $value => $name)
                        <option value="{{ $value }}"
                                @if ($searchCategory == $value) selected @endif
                            >{{ $name }}</option>
                    @endforeach
                </select>
            </section>

            <section class="toolbar-section">
                <select id="filterDropdown" class="toolbar-dropdown">
                    @foreach ($filterBy as $value => $name)
                        <option value="{{ $value }}"
                                @if ($selectedFilter == $value) selected @endif
                            >{{ $name }}</option>
                    @endforeach
                </select>

                <div class="toolbar-search-container">
                    <span class="material-symbols-outlined icon toolbar-search-icon">search</span>
                    <input type="text" id="toolbar-filter-value" class="toolbar-search" placeholder="Filter Value" wire:model="filterValue" />
                    <span class="material-symbols-outlined icon toolbar-clear-search">close</span>
                </div>
            </section>

            {{-- <section class="toolbar-section">
                <select id="sortDropdown" class="toolbar-dropdown">
                    @foreach ($sortBy as $value => $name)
                        <option value="{{ $value }}"
                                @if ($selectedSort == $value) selected @endif
                            >{{ $name }}</option>
                    @endforeach
                </select>

                <select id="sortOrderDropdown" class="toolbar-dropdown">
                    @foreach ($sortOrder as $value => $name)
                        <option value="{{ $value }}"
                                @if ($selectedSortOrder == $value) selected @endif
                            >{{ $name }}</option>
                    @endforeach
                </select>
            </section> --}}

            <section class="toolbar-section">
                {{-- <select id="groupDropdown" class="toolbar-dropdown">
                    @foreach ($groupBy as $value => $name)
                        <option value="{{ $value }}"
                                @if ($selectedGroup == $value) selected @endif
                            >{{ $name }}</option>
                    @endforeach
                </select> --}}

                <select id="actionsDropdown" class="toolbar-dropdown">
                    <option>Actions</option>
                    @foreach ($actions as $value => $name)
                        <option value="{{ $value }}">{{ $name }}</option>
                    @endforeach
                </select>

                <button class="toolbar-button"
                    x-on:click="window.location.href = window.location.href;">
                    <span class="material-symbols-outlined">refresh</span>
                </button>
            </section>
        </section>
        <section class="svcr-items">
            <table id="svcr-table" x-show="$wire.viewMode === 'table'">
                <thead>
                    <tr class="svcr-list-header">
                        <th class="svcr-list-header-item">
                            <input type="checkbox" class="svcr-list-item-select" id="svcr-select-all" />
                        </th>
                        <th class="svcr-list-header-item">
                            <div class="flex">
                                    Service Role
                                    <div class="ml-1 sort-icons">
                                    <span class="material-symbols-outlined sort-icon " data-field="courseNames" data-direction="asc">unfold_more</span>
                                </div>
                            </div>
                        </th>
                        <th class="svcr-list-header-item">
                            <div class="flex">Area
                                <div class="ml-1 sort-icons">
                                    <span class="material-symbols-outlined sort-icon " data-field="courseNames" data-direction="asc">unfold_more</span>
                                </div>
                            </div>
                        </th>
                        <th class="svcr-list-header-item">
                            <div class="flex">
                                Description
                                <div class="ml-1 sort-icons">
                                    <span class="material-symbols-outlined sort-icon " data-field="courseNames" data-direction="asc">unfold_more</span>
                                </div>
                            </div>
                        </th>
                        <th class="svcr-list-header-item">
                            <div class="flex">
                                Instructors
                                <div class="ml-1 sort-icons">
                                    <span class="material-symbols-outlined sort-icon " data-field="courseNames" data-direction="asc">unfold_more</span>
                                </div>
                            </div>
                        </th>
                        <th class="svcr-list-header-item">
                            <div class="flex">
                                Hours
                                {{-- <div class="ml-1 sort-icons">
                                    <span class="material-symbols-outlined sort-icon " data-field="courseNames" data-direction="asc">unfold_more</span>
                                </div> --}}
                            </div>
                        </th>
                        <th class="svcr-list-header-item">
                            <div class="flex">
                                Manage
                                <div class="ml-1 sort-icons">
                                    <span class="material-symbols-outlined sort-icon " data-field="courseNames" data-direction="asc">unfold_more</span>
                                </div>
                            </div>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($serviceRoles as $svcr)
                        <livewire:templates.svcrole-list-item :serviceRole="$svcr" :key="'serviceRoleListI-'.$svcr->id" />
                    @empty
                        <tr>
                            <td colspan="5" class="empty-list">
                                <span>No service roles found.</span>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <div class="svcr-list" x-show="$wire.viewMode === 'card'">
                @forelse ($serviceRoles as $serviceRole)
                    <livewire:templates.svcrole-card-item :serviceRole="$serviceRole" :key="'serviceRoleCardI-'.$serviceRole->id" />
                @empty
                    <div class="empty-list">
                        <span>No service roles found.</span>
                    </div>
                @endforelse
            </div>

            @if ($pageMode == 'pagination')
                {!! $serviceRoles->links() !!}
            @endif
        </section>
    </div>

    @include('components.link-bar', ['links' => $links])
    <livewire:extra-hour-form :serviceRoleId="$serviceRoleIdForModal" :key="'extraHourForm-'.$serviceRoleIdForModal"  x-show="showExtraHourFor" :showExtraHourForm="$showExtraHourForm" :serviceRoleId="$serviceRoleIdForModal" />
</div>
<script type="text/javascript">
    document.addEventListener('DOMContentLoaded', initializeToolbar);
    document.addEventListener('livewire:init', initializeToolbar);
    document.addEventListener('livewire:load', initializeToolbar);
    document.addEventListener('livewire:update', initializeToolbar);

    function initializeToolbar() {
        if (document.querySelector('.toolbar-initialized')) return;
        const toolbar = document.getElementById('svcr-toolbar');
        if (!toolbar) return;
        toolbar.classList.add('toolbar-initialized');

        const viewModeDropdown = toolbar.querySelector('#viewModeDropdown');
        const pageModeDropdown = toolbar.querySelector('#pageModeDropdown');
        const search = toolbar.querySelector('#toolbar-search');
        const searchCategory = toolbar.querySelector('#searchCategoryDropdown');
        const filter = toolbar.querySelector('#filterDropdown');
        const filterValueElement = toolbar.querySelector('#toolbar-filter-value');
        const sort = toolbar.querySelector('#sortDropdown');
        const sortOrder = toolbar.querySelector('#sortOrderDropdown');
        const group = toolbar.querySelector('#groupDropdown');
        const actions = toolbar.querySelector('#actionsDropdown');
        const checkAll = document.getElementById('svcr-select-all');
        let checkboxes = document.querySelectorAll('.svcr-list-item-select');
        let rows = document.querySelectorAll('.svcr-list-item');
        const selectedItems = {};

        const updateSelectAll = () => {
            checkboxes = document.querySelectorAll('.svcr-list-item-select');
            const totalCheckboxes = checkboxes.length;
            const checkedCheckboxes = document.querySelectorAll('.svcr-list-item-select:checked').length;

            checkAll.checked = checkedCheckboxes === totalCheckboxes;
            checkAll.indeterminate = checkedCheckboxes > 0 && checkedCheckboxes < totalCheckboxes;

            checkboxes.forEach(function (checkbox) {
                selectedItems[checkbox.value] = checkbox.checked;
            });

            // Dispatch updateSelectedItems to Livewire for each checkbox
            checkboxes.forEach(function (checkbox) {
                Livewire.dispatch('handleItemSelected', checkbox.value, checkbox.checked);
            });
        }

        updateSelectAll();

        if (checkAll) {
            checkAll.addEventListener('change', function (e) {
                const isChecked = e.target.checked;
                checkboxes.forEach(function (checkbox) {
                    checkbox.checked = isChecked;
                    selectedItems[checkbox.value] = isChecked;
                    @this.dispatch('handleItemSelected', checkbox.value, isChecked);
                });
                updateSelectAll();
            });

            checkboxes.forEach(function (checkbox) {
                checkbox.addEventListener('change', function () {
                    selectedItems[checkbox.value] = this.checked;
                    updateSelectAll();
                });
                selectedItems[checkbox.value] = checkbox.checked;
            });

            updateSelectAll();
        }

        rows.forEach(function(row) {
            row.addEventListener('dblclick', function(e) {
                const checkbox = row.querySelector('.svcr-list-item-select');
                checkbox.checked = !checkbox.checked;
                selectedItems[checkbox.value] = checkbox.checked;
                updateSelectAll();
            });
        });

        if (viewModeDropdown) {
            viewModeDropdown.addEventListener('change', function(e) {
                console.log(`viewMode: ${this.value}${e.target}`);
                @this.set('viewMode', this.value);
            });
        }

        if (pageModeDropdown) {
            pageModeDropdown.addEventListener('change', function(e) {
                console.log(e, this.value);
                // @this.set('pageMode', this.value);
                @this.dispatch('changePageMode', {
                    'mode': this.value
                })
            });
        }

        if (search) {
            search.addEventListener('input', function(e) {
                const value = this.value;
                // @this.set('searchQuery', value);
                @this.dispatch('changeSearchQuery', {
                    'query': value
                });
            });
        }


        if (searchCategory) {
            searchCategory.addEventListener('change', function(e) {
                const value = this.value;
                // @this.set('searchCategory', value);
                @this.dispatch('changeSearchCategory', {
                    'category': value
                });
            });
        }

        if (filterValueElement) {
            filterValueElement.addEventListener('input', function(e) {
                // @this.set('filterValue', this.value);
                @this.dispatch('changeFilter', {
                    'value': this.value,
                    'filter': filter?.value ?? null
                });
            });
        }

        if (filter) {
            filter.addEventListener('change', function(e) {
                // @this.set('selectedFilter', this.value);
                @this.dispatch('changeFilter', {
                    'filter': this.value,
                    'value': filterValueElement?.value ?? null
                });
            });
        }

        if (sortOrder) {
            sortOrder.addEventListener('change', function(e) {
                const order = (sortOrder) ? sortOrder.value : null;
                @this.set('selectedSortOrder', order);
            })
        }

        if (sort) {
            sort.addEventListener('change', function(e) {
                // @this.set('selectedSort', this.value);
                @this.dispatch('changeSort', {
                    'sort': this.value,
                    'order': sortOrder?.value ?? 'asc'
                });
            });
        }

        if (group) {
            group.addEventListener('change', function(e) {
                // @this.set('selectedGroup', this.value);
                @this.dispatch('changeGroup', {
                    'group': this.value
                });
            });
        }

        if (actions) {
            actions.addEventListener('change', function(e) {
                let sendItems = Object.keys(selectedItems).reduce((acc, key) => {
                    acc[key] = selectedItems[key];
                    return acc;
                }, {});
                // console.log(sendItems);
                @this.dispatch('performAction', {
                    'action': this.value,
                    'items': sendItems
                });
            });
        }
    }
</script>
<script type="text/javascript">
    document.addEventListener('DOMContentLoaded', initElementActions);
    document.addEventListener('livewire:init', initElementActions);
    document.addEventListener('livewire:load', initElementActions);
    document.addEventListener('livewire:update', initElementActions);

    function initElementActions() {
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
                @this.dispatch('changeSearchQuery', {
                    'query': null,
                });
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
                @this.dispatch('changeFilter', {
                    'value': null,
                    'filter': filter?.value ?? null
                });
            });
        }

    }
</script>
