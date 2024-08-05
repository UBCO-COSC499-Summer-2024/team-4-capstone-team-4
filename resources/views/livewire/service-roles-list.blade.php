@php
    $viewModes = ['table' => 'Table', 'card' => 'Card'];
    $pageModes = ['pagination' => 'Pagination', 'infinite' => 'Infinite Scroll'];
    $filterBy = ['' => 'Filter By', 'area' => 'Area', 'name' => 'Name', 'year' => 'Year'];
    $sortBy = ['name' => 'Name', 'area' => 'Area', 'created_at' => 'Created', 'year' => 'Year'];
    $searchCategories = ['*' => 'All', 'name' => 'Name', 'area_id' => 'Area', 'description' => 'Description', 'year' => 'Year'];
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
    $user = Auth::user();
@endphp
<div class="content" x-data="{
    showExtraHourForm: @entangle('showExtraHourForm'),
    showAssignInstructorModal: @entangle('showAssignInstructorModal'),
}">
    <h1 class="nos content-title">
        <span class="content-title-text">Service Roles</span>
        <div class="flex gap-2 right content-title-btn-holder">
            @if(!$user->hasOnlyRole('instructor'))
                <button class="content-title-btn" onClick="window.location.href='{{ route('svcroles.add') }}'">
                    <span class="material-symbols-outlined">work_history</span>
                    <span class="button-title btn-title">Create New</span>
                </button>
                <button class="content-title-btn" id="svcr-extra-hours-add"
                        title="Add Extra Hours"
                        x-on:click="
                            $dispatch('open-modal', {
                                'component': 'extra-hour-form'
                            });
                        ">
                    <span class="material-symbols-outlined icon">more_time</span>
                    <span class="button-title btn-title">Add Service Time</span>
                </button>

                {{-- assign instructor --}}
                <button class="content-title-btn" id="svcr-assign-instructor"
                        title="Assign Instructor"
                        x-on:click="
                            $dispatch('open-modal', {
                                'component': 'assign-instructor'
                            });
                        ">
                    <span class="material-symbols-outlined icon">people</span>
                    <span>Assign</span>
                </button>
            @endif

            <x-dropdown align="right" width="32">
                <x-slot name="trigger">
                    <button class="content-title-btn">
                        <span class="material-symbols-outlined icon">
                            view_module
                        </span>
                        <span class="btn-title">
                            View Mode
                        </span>
                        <span class="material-symbols-outlined icon">
                            expand_more
                        </span>
                    </button>
                </x-slot>
                <x-slot name="content">
                    @foreach ($viewModes as $value => $name)
                        <x-dropdown-link
                            wire:click="changeViewMode('{{ $value }}')"
                            class="{{
                                $viewMode == $value ? 'active' : ''
                            }}">
                            {{ $name }}
                        </x-dropdown-link>
                    @endforeach
                </x-slot>
            </x-dropdown>

            <x-dropdown align="right" width="48" align="right">
                <x-slot name="trigger">
                    <button class="content-title-btn">
                        <span class="material-symbols-outlined icon">
                            view_list
                        </span>
                        <span class="btn-title">
                            Page Mode
                        </span>
                        <span class="material-symbols-outlined icon">
                            expand_more
                        </span>
                    </button>
                </x-slot>
                <x-slot name="content">
                    @foreach ($pageModes as $value => $name)
                        <x-dropdown-link
                            wire:click="changePageMode('{{ $value }}')"
                            class="{{
                                $pageMode == $value ? 'active' : ''
                            }}">
                            {{ $name }}
                        </x-dropdown-link>
                    @endforeach
                </x-slot>
            </x-dropdown>
        </div>
    </h1>

    <div class="svcr-container">
        <section class="grid w-full grid-cols-1 toolbar md:grid-cols-2 grid-sticky" id="svcr-toolbar" wire:key='toolbar'>
            <section class="w-full toolbar-section left">
                <div class="flex-grow toolbar-search-container">
                    <span class="material-symbols-outlined icon toolbar-search-icon">search</span>
                    <input type="text" id="toolbar-search" placeholder="Search..." class="flex-grow toolbar-search" wire:model="searchQuery" />
                    <span class="material-symbols-outlined icon toolbar-clear-search">close</span>
                </div>
            </section>

            <section class="toolbar-section right">
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

                @if(!$user->hasOnlyRole('instructor'))
                    <select id="actionsDropdown" class="toolbar-dropdown">
                        <option>Bulk Actions</option>
                        @foreach ($actions as $value => $name)
                            <option value="{{ $value }}">{{ $name }}</option>
                        @endforeach
                    </select>
                @endif

                <button class="toolbar-button"
                    x-on:click="window.location.href = window.location.href;">
                    <span class="material-symbols-outlined">refresh</span>
                </button>
            </section>
        </section>

        <section class="svcr-items">
            <table id="svcr-table" x-show="$wire.viewMode === 'table'" class="svcrprose-table:">
                <thead>
                    <tr class="svcr-list-header">
                        @if(!$user->hasOnlyRole('instructor'))
                            <th class="svcr-list-header-item">
                                <input type="checkbox" class="svcr-list-item-select" id="svcr-select-all" />
                            </th>
                        @endif
                        {{-- id --}}
                        <th class="svcr-list-header-item w-fit">
                            <div class="flex">
                                Id
                                <div class="ml-1 sort-icons">
                                    <span class="material-symbols-outlined sort-icon " data-field="id" data-direction="asc">unfold_more</span>
                                </div>
                            </div>
                        </th>
                        <th class="svcr-list-header-item">
                            <div class="flex">
                                    Role
                                    <div class="ml-1 sort-icons">
                                    <span class="material-symbols-outlined sort-icon " data-field="name" data-direction="asc">unfold_more</span>
                                </div>
                            </div>
                        </th>
                        <th class="svcr-list-header-item">
                            <div class="flex">
                                Area
                                <div class="ml-1 sort-icons">
                                    <span class="material-symbols-outlined sort-icon " data-field="area_id" data-direction="asc">unfold_more</span>
                                </div>
                            </div>
                        </th>
                        <th class="svcr-list-header-item">
                            <div class="flex">
                                Year
                                <div class="ml-1 sort-icons">
                                    <span class="material-symbols-outlined sort-icon " data-field="year" data-direction="asc">unfold_more</span>
                                </div>
                            </div>
                        </th>
                        <th class="svcr-list-header-item">
                            <div class="flex">
                                Description
                                <div class="ml-1 sort-icons">
                                    <span class="material-symbols-outlined sort-icon " data-field="description" data-direction="asc">unfold_more</span>
                                </div>
                            </div>
                        </th>
                        <th class="svcr-list-header-item">
                            <div class="flex">
                                Instructors
                                <div class="ml-1 sort-icons">
                                    <span class="material-symbols-outlined sort-icon " data-field="instructors" data-direction="asc">unfold_more</span>
                                </div>
                            </div>
                        </th>
                        @if(!$user->hasOnlyRole('instructor'))
                            <th class="svcr-list-header-item">
                                <div class="flex">
                                    Manage
                                </div>
                            </th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @forelse ($serviceRoles as $svcr)
                        @php
                            $svcrId = $svcr->id;
                        @endphp
                        <livewire:templates.svcrole-list-item :serviceRoleId="$svcrId"
                        :key="'svcrli-'.$svcrId"
                        />
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
                    <livewire:templates.svcrole-card-item :serviceRole="$serviceRole" :key="'svcrci-'.$serviceRole->id" />
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
    <livewire:extra-hour-form :key="'extraHourForm'.time()" :showExtraHourForm="$showExtraHourForm" x-show="showExtraHourForm" x-cloak/>
    {{-- assign instructor --}}
    <livewire:components.assign-instructor-modal :key="'assignInstructorModal'.time()" :showAssignInstructorModal="$showAssignInstructorModal" x-show="showAssignInstructorModal" x-cloak/>
</div>
<script type="text/javascript">
    document.addEventListener('DOMContentLoaded', function() {
        initializeToolbar();
        initElementActions();
    });
    document.addEventListener('livewire:init', function() {
        initializeToolbar();
        initElementActions();
    });
    document.addEventListener('livewire:load', function() {
        initializeToolbar();
        initElementActions();
    });
    document.addEventListener('livewire:update', function() {
        initializeToolbar();
        initElementActions();
    });

    window.addEventListener('resize', function() {
        if (window.innerWidth < 768) {
            viewModeDropdown.value = 'card';
            @this.set('viewMode', 'card');
        } else {
            viewModeDropdown.value = 'table';
            @this.set('viewMode', 'table');
        }
    });

    function calculatePaginationItems(screenWidth, screenHeight, elementWidth, elementHeight, elementMargin) {
        const itemsPerRow = Math.floor(screenWidth / (elementWidth + 2 * elementMargin));
        const rowsPerScreen = Math.floor(screenHeight / (elementHeight + 2 * elementMargin));
        const itemsPerScreen = itemsPerRow * rowsPerScreen;

        return itemsPerScreen;
    }

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

        if (checkAll) {
            const updateSelectAll = () => {
                checkboxes = document.querySelectorAll('.svcr-list-item-select');
                const totalCheckboxes = checkboxes.length;
                const checkedCheckboxes = document.querySelectorAll('.svcr-list-item-select:checked').length;

                checkAll.checked = checkedCheckboxes === totalCheckboxes;
                checkAll.indeterminate = checkedCheckboxes > 0 && checkedCheckboxes < totalCheckboxes;

                checkboxes.forEach(function (checkbox) {
                    selectedItems[checkbox.value] = checkbox.checked;
                });
            }

            updateSelectAll();

            checkAll.addEventListener('change', function (e) {
                const isChecked = e.target.checked;
                checkboxes.forEach(function (checkbox) {
                    checkbox.checked = isChecked;
                    selectedItems[checkbox.value] = isChecked;
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
                @this.dispatch('performAction', { 'action': this.value });
            });
        }
    }

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
