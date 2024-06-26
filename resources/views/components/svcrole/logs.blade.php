<div class="content">
    <h1 class="flex nos content-title">
        <span class="content-title-text">Audit Logs</span>
        <span class="content-title-item">
            <x-dropdown-element 
                id="viewModeDropdown"
                class="right"
                title="View Mode"
                :values="['card' => 'Card View', 'list' => 'List View']"
                preIcon="view_module"/>
            <x-dropdown-element 
                id="pageModeDropdown"
                title="Display As"
                :values="['pagination' => 'Pagination', 'infinite' => 'Infinite Scroll']"
                preIcon="first_page"/>
        </span>
    </h1>
    {{-- Toolbar --}}
    <div class="toolbar glass">
        <section class="toolbar-section">

            {{-- Search category (dropdown) --}}
            <x-dropdown-element 
                id="searchCategoryDropdown"
                class="toolbar-dropdown"
                title="Category"
                :values="['category1' => 'Category 1', 'category2' => 'Category 2', 'category3' => 'Category 3']"
                preIcon="category"/>
            {{-- Search --}}
            <input type="text" placeholder="Search...">
        </section>

        <section class="toolbar-section">
            {{-- Sort --}}
            <x-dropdown-element 
                id="sortDropdown"
                class="toolbar-dropdown"
                title="Sort"
                :values="['sort1' => 'Sort 1', 'sort2' => 'Sort 2', 'sort3' => 'Sort 3']"
                preIcon="sort"/>

            {{-- Sort order --}}
            <x-dropdown-element 
                id="sortOrderDropdown"
                class="toolbar-dropdown"
                title="Sort Order"
                :values="['asc' => 'Ascending', 'desc' => 'Descending']"
                preIcon="sort_by_alpha"/>
        </section>

        <section class="toolbar-section">
            <x-dropdown-element 
                id="filterValueDropdown"
                class="toolbar-dropdown"
                title="Filter Value"
                :values="['filter1' => 'Filter 1', 'filter2' => 'Filter 2', 'filter3' => 'Filter 3']"
                preIcon="filter_list"/>

            <input type="text" placeholder="Filter...">

            <x-dropdown-element 
                id="groupDropdown"
                class="toolbar-dropdown"
                title="Group"
                :values="['group1' => 'Group 1', 'group2' => 'Group 2', 'group3' => 'Group 3']"
                preIcon="group"/>
        </section>

        <section class="toolbar-section">
            {{-- Group --}}
        </section>

        <section class="toolbar-section">
            <x-dropdown-element 
                id="bulkActionsDropdown"
                class="toolbar-dropdown"
                title="Actions"
                :values="['action1' => 'Action 1', 'action2' => 'Action 2', 'action3' => 'Action 3']"
                preIcon="list_alt"/>

            <button>
                <span class="material-symbols-outlined icon">done</span>
                <span class="button-title">Apply</span>
            </button>
        </section>

        <section class="toolbar-section">
            {{-- export and create report --}}
            <button class="right">
                <span class="material-symbols-outlined icon">save_alt</span>
                <span class="button-title">Export</span>
            </button>

            <button>
                <span class="material-symbols-outlined icon">description</span>
                <span class="button-title">Report</span>
            </button>
        </section>
    </div>

    {{-- View (based on toolbar settings, card/table) --}}
    <section class="view">
        @if ($viewMode == 'card')
            {{-- @include('templates.audit-log-card', ['auditLogs' => $auditLogs]) --}}
            <x-templates.audit-log-card :auditLogs="$auditLogs" />
        @else
            {{-- @include('templates.audit-log-list-item', ['auditLogs' => $auditLogs]) --}}
            <x-templates.audit-log-list-item :auditLogs="$auditLogs" />
        @endif
    </section>
    <x-link-bar :links="$links" />
</div>
