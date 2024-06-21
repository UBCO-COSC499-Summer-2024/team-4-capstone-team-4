<div class="content">
    <h1 class="flex">Audit Logs
        <div class="mini-toolbar right">
            <section class="toolbar-section">
                {{-- View switcher (card/list) --}}
                <span class="toolbar-section-title">View Mode:</span>
                <div class="toolbar-section-item">
                    <span for="viewSwitch" class="material-symbols-outlined switch-label-icon">
                        view_module
                    </span>
                    <label class="switch" cv="card" ucv="list", title="Item View Mode">
                        <input type="checkbox" id="viewSwitch" name="viewSwitch">
                        <span class="slider round"></span>
                    </label>
                    <span for="viewSwitch" class="material-symbols-outlined switch-label-icon">
                        view_list
                    </span>
                </div>
            </section>
        </div>
        <div class="mini-toolbar">
            <section class="toolbar-section">
                {{-- Mode switcher (pagination/infinite scroll) --}}
                <span class="toolbar-section-title">Page Mode:</span>
                <div class="toolbar-section-item">
                    <span class="material-symbols-outlined switch-label-icon">first_page</span>
                    <label class="switch" cv="pagination" ucv="infinite" title="Page Mode">
                        <input type="checkbox" id="modeSwitch" name="modeSwitch">
                        <span class="slider round"></span>
                    </label>
                    <span class="material-symbols-outlined switch-label-icon">refresh</span>
                </div>
            </section>
        </div>
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
            {{-- Filter --}}
            <input type="text" placeholder="Filter...">

            {{-- Filter value --}}
            <dropdown-element id="filterValueDropdown" class="toolbar-dropdown" title="Filter Value">
                <span class="material-symbols-outlined dropdown-pre-icon">filter_list</span>
                <span class="dropdown-title">Filter Value</span>
                <span class="material-symbols-outlined dropdown-button">arrow_drop_down</span>
                <dropdown-content class="dropdown-content">
                    <dropdown-item>Filter 1</dropdown-item>
                    <dropdown-item>Filter 2</dropdown-item>
                    <dropdown-item>Filter 3</dropdown-item>
                </dropdown-content>
            </dropdown-element>

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
            <button>
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
    
    <section class="link-bar">
        <x-link href="{{ route('svcroles') }}" title="{{ __('Dashboard') }}" :active="request()->is('svcroles')" icon="{{ __('group') }}"/>
        <x-link href="{{ route('svcroles.add') }}" title="{{ __('Add Service Role') }}" :active="request()->is('svcroles/add')" icon="{{ __('add') }}"/>
        <x-link href="{{ route('svcroles.manage') }}" title="{{ __('Manage Service Roles') }}" :active="request()->is('svcroles/manage')" icon="{{ __('visibility') }}"/>
        <x-link href="{{ route('svcroles.requests') }}" title="{{ __('Requests') }}" :active="request()->is('svcroles/requests')" icon="{{ __('request_page') }}"/>
        <x-link href="{{ route('svcroles.logs') }}" title="{{ __('Audit Logs') }}" :active="request()->is('svcroles/audit-logs')" icon="{{ __('description') }}"/>
    </section>
</div>
