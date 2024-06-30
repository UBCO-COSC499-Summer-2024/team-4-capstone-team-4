@php
    $viewModes = ['table' => 'Table', 'card' => 'Card'];
    $pageModes = ['pagination' => 'Pagination', 'infinite' => 'Infinite Scroll'];
    $filterBy = ['area' => 'Area', 'name' => 'Name'];
    $sortBy = ['name' => 'Name', 'created_at' => 'Created'];
    $sortOrder = ['asc' => 'Ascending', 'desc' => 'Descending'];
    $actions = ['edit' => 'Edit', 'delete' => 'Delete', 'duplicate' => 'Duplicate', 'archive' => 'Archive', 'restore' => 'Restore'];
    $groupBy = ['area' => 'Area', 'name' => 'Name'];
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
<div class="content">
    <h1 class="nos content-title">
        <span class="content-title-text">Service Roles</span>
        <button class="right">
            <span class="button-title">Create New</span>
            <span class="material-symbols-outlined">add</span>
        </button>
    </h1>

    {{-- <livewire:toolbar
        :features="$features"
        :viewModes="$viewModes"
        :pageModes="$pageModes"
        :filterBy="$filterBy"
        :sortBy="$sortBy"
        :sortOrder="$sortOrder"
        :actions="$actions"
        :groupBy="$groupBy"
        :viewMode="$viewMode"
        :pageMode="$pageMode"
    /> --}}

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
                @foreach ($filterBy as $value => $name)
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

        <section class="toolbar-section">
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
        </section>

        <section class="toolbar-section">
            <select id="groupDropdown" class="toolbar-dropdown">
                @foreach ($groupBy as $value => $name)
                    <option value="{{ $value }}"
                            @if ($selectedGroup == $value) selected @endif
                        >{{ $name }}</option>
                @endforeach
            </select>

            <select id="actionsDropdown" class="toolbar-dropdown">
                <option>Actions</option>
                <option value="edit">Edit</option>
                <option value="delete">Delete</option>
                <option value="duplicate">Duplicate</option>
                <option value="archive">Archive</option>
                <option value="restore">Restore</option>
            </select>

            <button class="toolbar-button">
                <span class="material-symbols-outlined">more_vert</span>
            </button>

            <button class="toolbar-button">
                <span class="material-symbols-outlined">filter_list</span>
            </button>

            <button class="toolbar-button">
                <span class="material-symbols-outlined">refresh</span>
            </button>
        </section>
    </section>

    <div class="svcr-container">
        <div class="svcr-list" x-show="$wire.viewMode === 'card'">
            @forelse ($serviceRoles as $serviceRole)
                {{-- @livewire('templates.svcrole-card-item', ['serviceRole' => $serviceRole]) --}}
                <livewire:templates.svcrole-card-item :serviceRole="$serviceRole" :key="'serviceRoleCardI-'.$serviceRole->id" />
            @empty
                <div class="empty-list">
                    <span>No service roles found.</span>
                </div>
            @endforelse
        </div>

        <table id="svcr-table" x-show="$wire.viewMode === 'table'">
            <thead>
                <tr class="svcr-list-header">
                    <th class="svcr-list-header-item">
                        <input type="checkbox" class="svcr-list-item-select" id="svcr-select-all" />
                    </th>
                    <th class="svcr-list-header-item">Service Role</th>
                    <th class="svcr-list-header-item">Area</th>
                    <th class="svcr-list-header-item">Description</th>
                    <th class="svcr-list-header-item">Instructors</th>
                    <th class="svcr-list-header-item">Manage</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($serviceRoles as $serviceRole)
                    <livewire:templates.svcrole-list-item :serviceRole="$serviceRole" :key="'serviceRoleListI-'.$serviceRole->id" />
                @empty
                    <tr>
                        <td colspan="5" class="empty-list">
                            <span>No service roles found.</span>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        @if ($pageMode == 'pagination')
            {!! $serviceRoles->links() !!}
        @endif
    </div>

    @include('components.link-bar', ['links' => $links])
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
            viewModeDropdown.addEventListener('input', function(e) {
                console.log(`viewMode: ${this.value}${e.target}`);
                @this.set('viewMode', this.value);
                // @this.dispatch('changeViewMode', this.value);
            });
        }

        if (pageModeDropdown) {
            pageModeDropdown.addEventListener('input', function(e) {
                @this.set('pageMode', this.value);
            });
        }

        if (search) {
            search.addEventListener('input', function(e) {
                const value = this.value;
                if (searchCategory) {
                    searchCategory.addEventListener('input', function(e) {
                        const value = this.value;
                        // @this.set('searchCategory', value);
                        @this.set('searchCategory', value);
                        console.log(`searchCategory: ${value}`);
                    });
                }
                @this.set('searchQuery', value);
                // @this.dispatch('changeSearchQuery', value);
                // console.log(`searchQuery: ${value}`);
            });
        }

        if (filter) {
            filter.addEventListener('input', function(e) {
                @this.set('selectedFilter', this.value);
                // You might need to adjust how you handle filterValue if it's dynamic
                const filterValue = (filterValueElement) ? filterValueElement.value : null;
                @this.set('filterValue', filterValue);
            });
        }

        if (sort) {
            sort.addEventListener('input', function(e) {
                @this.set('selectedSort', this.value);
                // Similar to filterValue, handle sortOrder based on your implementation
                const order = (sortOrder) ? sortOrder.value : null;
                @this.set('selectedSortOrder', order);
            });
        }

        if (group) {
            group.addEventListener('input', function(e) {
                @this.set('selectedGroup', this.value);
            });
        }
    }
</script>
