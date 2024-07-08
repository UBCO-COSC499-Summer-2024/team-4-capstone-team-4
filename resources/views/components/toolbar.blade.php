{{-- options to enable/disable sections/features, order of items, groups, etc --}}
@props([
    'id' => null,
    'class' => null, // additional classes
    'features' => [
        'viewMode' => true,
        'pageMode' => true,
        'search' => true,
        'searchCategory' => true,
        'filter' => true,
        'filterValue' => true,
        'sort' => true,
        'group' => true,
        'actions' => true,
        'export' => true,
        'import' => true,
        'settings' => false,
    ],
    'order' => [
        ['viewMode', 'pageMode'],
        ['search', 'searchCategory'],
        ['filter', 'filterValue'],
        ['sort'],
        ['group'],
        ['actions', 'export', 'import', 'settings'],
    ],
    'viewModes' => [
        'card' => 'Card',
        'list' => 'List',
        'table' => 'Table',
    ],
    'pageModes' => [
        'pagination' => 'Pagination',
        'infinite' => 'Infinite Scroll',
    ],
    'filterBy' => [
        'filter1' => 'Filter 1',
        'filter2' => 'Filter 2',
        'filter3' => 'Filter 3',
    ],
    'sortBy' => [
        'sort1' => 'Sort 1',
        'sort2' => 'Sort 2',
        'sort3' => 'Sort 3',
    ],
    'sortOrder' => [
        'asc' => 'Ascending',
        'desc' => 'Descending',
    ],
    'groupBy' => [
        'group1' => 'Group 1',
        'group2' => 'Group 2',
        'group3' => 'Group 3',
    ],
    'actions' => [
        'edit' => 'Edit',
        'delete' => 'Delete',
        'duplicate' => 'Duplicate',
        'archive' => 'Archive',
        'restore' => 'Restore',
    ],
    'exports' => [
        'csv' => 'CSV',
        'pdf' => 'PDF',
        'json' => 'JSON',
    ],
    'imports' => [
        'csv' => 'CSV',
        'json' => 'JSON',
    ]
])
<section id="{{ $id }}" class="toolbar {{ $class }}">
    @foreach ($order as $section)
        <section class="toolbar-section">
            @foreach ($section as $feature)
                @if ($features[$feature] ?? false)
                    @switch($feature)
                        @case('viewMode')
                            <x-dropdown-element
                                id="viewModeDropdown"
                                title="View Mode" 
                                :values="$viewModes" 
                                preIcon="view_comfy" />
                            @break

                        @case('pageMode')
                            <x-dropdown-element
                                id="pageModeDropdown"
                                title="Page Mode" 
                                :values="$pageModes" 
                                preIcon="view_list" />
                            @break

                        @case('searchCategory')
                            <x-dropdown-element 
                                id="searchCategoryDropdown"
                                title="Category" 
                                :values="$filterBy" 
                                preIcon="category" />
                            @break

                        @case('search')
                            <input type="text" id="toolbar-search" placeholder="Search..." class="toolbar-search"/>
                            @break

                        @case('filter')
                            <x-dropdown-element 
                                id="filterDropdown"
                                title="Filter" 
                                :values="$filterBy" 
                                preIcon="filter_list" />
                            @break

                        @case('filterValue')
                            <input type="text" placeholder="Filter..." class="toolbar-filter-value" id="toolbar-filter-value"/>
                            @break

                        @case('sort')
                            <x-dropdown-element 
                                id="sortByDropdown"
                                title="Sort" 
                                :values="$sortBy" 
                                preIcon="sort" />
                            <x-dropdown-element 
                                id="sortOrderDropdown"
                                title="Sort Order" 
                                :values="$sortOrder" 
                                preIcon="sort_by_alpha" />
                            @break

                        @case('group')
                            <x-dropdown-element 
                                id="groupByDropdown"
                                title="Group" 
                                :values="$groupBy" 
                                preIcon="group" />
                            @break

                        @case('actions')
                            <x-dropdown-element 
                                id="actionsDropdown"
                                title="Actions" 
                                :values="$actions" 
                                preIcon="list_alt" />
                            <button>
                                <span class="material-symbols-outlined icon">done</span>
                                <span class="button-title">Apply</span>
                            </button>
                            @break

                        @case('export')
                            <x-dropdown-element 
                                id="exportDropdown"
                                title="Export As" 
                                :values="$exports" 
                                preIcon="save_alt" />
                            @break

                        @case('import')
                            <x-dropdown-element 
                                id="importDropdown"
                                title="Import From" 
                                :values="$imports" 
                                preIcon="file_upload" />
                            @break

                        @case('settings')
                            <button class="toolbar-settings" id="toolbar-settings">
                                <span class="material-symbols-outlined icon">settings</span>
                                <span class="button-title">Settings</span>
                            </button>
                            @break
                    @endswitch
                @endif
            @endforeach
        </section>
    @endforeach
</section>