<div class="content">
    <h1 class="flex nos content-title">
        <span class="content-title-text">Audit Logs</span>
        <span class="content-title-item">
            <x-dropdown-element 
                id="viewModeDropdown"
                class="right"
                title="View Mode"
                :values="['Card View' => 'card', 'List View' => 'list']"
                preIcon="view_module"/>
            <x-dropdown-element 
                id="pageModeDropdown"
                title="Display As"
                :values="['Pagination' => 'pagination', 'Infinite Scroll' => 'infinite']"
                preIcon="first_page"/>
        </span>
    </h1>
    {{-- Toolbar --}}
    <div class="toolbar glass">
        <section class="toolbar-section">

            {{-- Search category (dropdown) --}}
            <dropdown-element id="searchCategoryDropdown" class="toolbar-dropdown" title="Category">
                <span class="material-symbols-outlined dropdown-pre-icon">category</span>
                <span class="dropdown-title">Category</span>
                <span class="material-symbols-outlined dropdown-button">arrow_drop_down</span>
                <dropdown-content class="dropdown-content">
                    <dropdown-item>Category 1</dropdown-item>
                    <dropdown-item>Category 2</dropdown-item>
                    <dropdown-item>Category 3</dropdown-item>
                </dropdown-content>
            </dropdown-element>
            {{-- Search --}}
            <input type="text" placeholder="Search...">
        </section>

        <section class="toolbar-section">
            {{-- Sort --}}
            <dropdown-element id="sortDropdown" class="toolbar-dropdown" title="Sort">
                <span class="material-symbols-outlined dropdown-pre-icon">sort</span>
                <span class="dropdown-title">Sort</span>
                <span class="material-symbols-outlined dropdown-button">arrow_drop_down</span>
                <dropdown-content class="dropdown-content">
                    <dropdown-item>Sort 1</dropdown-item>
                    <dropdown-item>Sort 2</dropdown-item>
                    <dropdown-item>Sort 3</dropdown-item>
                </dropdown-content>
            </dropdown-element>

            {{-- Sort order --}}
            <dropdown-element id="sortOrderDropdown" class="toolbar-dropdown" title="Sort Order">
                <span class="material-symbols-outlined dropdown-pre-icon">sort_by_alpha</span>
                <span class="dropdown-title">Sort Order</span>
                <span class="material-symbols-outlined dropdown-button">arrow_drop_down</span>
                <dropdown-content class="dropdown-content">
                    <dropdown-item>Ascending</dropdown-item>
                    <dropdown-item>Descending</dropdown-item>
                </dropdown-content>
            </dropdown-element>
        </section>

        <section class="toolbar-section">
            <dropdown-element id="filterValueDropdown" class="toolbar-dropdown" title="Filter Value">
                <span class="material-symbols-outlined dropdown-pre-icon">filter_list</span>
                <span class="dropdown-title">Filter By</span>
                <span class="material-symbols-outlined dropdown-button">arrow_drop_down</span>
                <dropdown-content class="dropdown-content">
                    <dropdown-item>Filter 1</dropdown-item>
                    <dropdown-item>Filter 2</dropdown-item>
                    <dropdown-item>Filter 3</dropdown-item>
                </dropdown-content>
            </dropdown-element>

            <input type="text" placeholder="Filter...">

            <dropdown-element id="groupDropdown" class="toolbar-dropdown" title="Group">
                <span class="material-symbols-outlined dropdown-pre-icon">group</span>
                <span class="dropdown-title">Group</span>
                <span class="material-symbols-outlined dropdown-button">arrow_drop_down</span>
                <dropdown-content class="dropdown-content">
                    <dropdown-item>Group 1</dropdown-item>
                    <dropdown-item>Group 2</dropdown-item>
                    <dropdown-item>Group 3</dropdown-item>
                </dropdown-content>
            </dropdown-element>
        </section>

        <section class="toolbar-section">
            {{-- Group --}}
        </section>

        <section class="toolbar-section">
            {{-- Bulk actions dropdown (rollback, etc) --}}
            <dropdown-element id="bulkActionsDropdown" class="toolbar-dropdown" title="Actions">
                <span class="material-symbols-outlined dropdown-pre-icon">list_alt</span>
                <span class="dropdown-title">Actions</span>
                <span class="material-symbols-outlined dropdown-button">arrow_drop_down</span>
                <dropdown-content class="dropdown-content">
                    <dropdown-item>Rollback 1</dropdown-item>
                    <dropdown-item>Bulk Action 2</dropdown-item>
                    <dropdown-item>Bulk Action 3</dropdown-item>
                </dropdown-content>
            </dropdown-element>

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
