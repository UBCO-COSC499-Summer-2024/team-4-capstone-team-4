@php
    $viewModes = ['table' => 'Table', 'card' => 'Card'];
    $pageModes = ['pagination' => 'Pagination', 'infinite' => 'Infinite Scroll'];
    $filterBy = ['area' => 'Area', 'name' => 'Name'];
    $sortBy = ['name' => 'Name', 'created_at' => 'Created'];
    $sortOrder = ['asc' => 'Ascending', 'desc' => 'Descending'];
    $actions = ['edit' => 'Edit', 'delete' => 'Delete'];
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
                    @foreach ($actions as $value => $name)
                        <option value="{{ $value }}">{{ $name }}</option>
                    @endforeach
                </select>

                <button class="toolbar-button" wire:click="refresh">
                    <span class="material-symbols-outlined">refresh</span>
                </button>
            </section>
        </section>
        <section class="svcr-items">
            <div class="svcr-list" x-show="$wire.viewMode === 'card'">
                @forelse ($serviceRoles as $serviceRole)
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
                        <th class="svcr-list-header-item">Extra Hours</th>
                        <th class="svcr-list-header-item">Manage</th>
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
                @this.set('pageMode', this.value);
            });
        }

        if (search) {
            search.addEventListener('input', function(e) {
                const value = this.value;
                @this.set('searchQuery', value);
            });
        }


        if (searchCategory) {
            searchCategory.addEventListener('change', function(e) {
                const value = this.value;
                @this.set('searchCategory', value);
            });
        }

        if (filter) {
            filter.addEventListener('change', function(e) {
                @this.set('selectedFilter', this.value);
            });
        }

        if (filterValueElement) {
            filterValueElement.addEventListener('input', function(e) {
                @this.set('filterValue', this.value);
            });
        }

        if (sort) {
            sort.addEventListener('change', function(e) {
                @this.set('selectedSort', this.value);
            });
        }

        if (sortOrder) {
            sortOrder.addEventListener('change', function(e) {
                const order = (sortOrder) ? sortOrder.value : null;
                @this.set('selectedSortOrder', order);
            })
        }

        if (group) {
            group.addEventListener('change', function(e) {
                @this.set('selectedGroup', this.value);
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
