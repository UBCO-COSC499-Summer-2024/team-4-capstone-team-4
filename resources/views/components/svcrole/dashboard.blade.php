@php
    $viewMode = request()->input('view_mode', 'list');
    $pageMode = request()->input('page_mode', 'infinite');
    $page = request()->input('page', 1);
    $pgn_size = request()->input('pgn_size', 20);
    $data = App\Models\ServiceRole::all();
    $serviceroles = App\Models\ServiceRole::paginate($pgn_size);
@endphp
<div class="content">
    <h1 class="nos content-title">
        <span class="content-title-text">Service Roles</span>
        <button class="right">
            <span class="button-title">Create New</span>
            <span class="material-symbols-outlined">add</span>
        </button>
    </h1>
    
    <x-toolbar 
        id="serviceRoleToolbar" 
        :view-modes="['list' => 'List View', 'card' => 'Card View']"  
        :page-modes="['pagination' => 'Pagination', 'infinite' => 'Infinite Scroll']"
        :filter-by="['area' => 'Area', 'role' => 'Role']"
        :sort-by="['name' => 'Name', 'created_at' => 'Created At']"
        :group-by="['area' => 'Area']"
        :features="['viewMode' => true, 'pageMode' => true, 'search' => true, 
                   'filter' => true, 'sort' => true, 'actions' => true,
                   'searchCategory' => true]" 
    />

    <div id="svcr-list" class="svcr-list" style="display: {{ $viewMode == 'card' ? 'grid' : 'none' }};">
        @foreach ($serviceroles as $svcrole)
            <x-templates.svcrole-card :svcrole="$svcrole" />
        @endforeach
    </div>

    <table id="svcr-table" style="display: {{ $viewMode == 'list' ? 'table' : 'none' }};">
        <thead>
            <tr class="svcr-list-header">
                <th class="svcr-list-header-item">Role</th>
                <th class="svcr-list-header-item">Area</th>
                <th class="svcr-list-header-item">Description</th>
                <th class="svcr-list-header-item">Instructors</th>
                <th class="svcr-list-header-item">Manage</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($serviceroles as $svcrole)
                <x-templates.svcrole-list-item :svcrole="$svcrole" />
            @endforeach
        </tbody>
    </table>

    @if ($pageMode == 'pagination')
        <div class="pagination">
            {!! $serviceroles->links() !!}
        </div>
    @endif
    
    <x-link-bar :links="$links" />
</div>

@php
    $first = $data->first();
@endphp
<template id="card-view">
{{-- first item --}}
    <x-templates.svcrole-card :svcrole="$first" />
</template>

<template id="list-view">
    <x-templates.svcrole-list-item :svcrole="$first" />
</template>

<script type="text/javascript">
    document.addEventListener('DOMContentLoaded', function() {
        const toolbar = document.getElementById('svcroleToolbar');
        let page = {{ $page }};
        let per_page = {{ $pgn_size }};
        let loading = false;
        let endOfData = false;
        let options = {
            'viewMode': '{{ $viewMode }}',
            'pageMode': '{{ $pageMode }}',
            'search': '',
            'searchCategory': '',
            'filter': '',
            'filterValue': '',
            'sort': '',
            'group': '',
            'mode': 'view', // view, edit, create
        }

        const svcrList = document.getElementById('svcr-list');
        const svcrTable = document.getElementById('svcr-table');
        const pagination = document.querySelector('.pagination');
        const apiUrl = `/api/service-roles`;

        const updateDisplayMode = () => {
            svcrList.style.display = options.viewMode === 'card' ? 'grid' : 'none';
            svcrTable.style.display = options.viewMode === 'list' ? 'table' : 'none';
            pagination.style.display = options.pageMode === 'pagination' ? 'block' : 'none';
        };

        const reloadData = (data) => {
            const listContainer = options.viewMode === 'card' ? svcrList : svcrTable.querySelector('tbody');
            listContainer.innerHTML = ''; // Clear existing data
            data.forEach(svcrole => {
                const template = document.createElement('template');
                template.innerHTML = options.viewMode === 'card'
                    ? document.getElementById('card-view').innerHTML
                    : document.getElementById('list-view').innerHTML;
                listContainer.appendChild(template.content.firstChild);
            });
        };

        const updateData = () => {
            const url = new URL(apiUrl);
            url.searchParams.append('page', page);
            url.searchParams.append('per_page', per_page);
            if (options.search) url.searchParams.append('search', options.search);
            if (options.filter) url.searchParams.append('filter', options.filter);
            if (options.filterValue) url.searchParams.append('filterValue', options.filterValue);
            if (options.sort) url.searchParams.append('sort', options.sort);
            if (options.group) url.searchParams.append('group', options.group);
            
            fetch(url)
                .then(response => response.json())
                .then(_data => {
                    if (_data.length < per_page) endOfData = true;
                    reloadData(_data);
                    loading = false;
                });
        };

        const loadMoreOnScroll = () => {
            if (loading || endOfData) return;
            const scrollHeight = document.documentElement.scrollHeight;
            const clientHeight = document.documentElement.clientHeight;
            const scrollTop = document.documentElement.scrollTop;
            if (scrollHeight - scrollTop === clientHeight) {
                loading = true;
                page++;
                updateData();
            }
        };

        if (toolbar) {
            const viewModeDropdown = toolbar.querySelector('#viewModeDropdown');
            const pageModeDropdown = toolbar.querySelector('#pageModeDropdown');
            const search = toolbar.querySelector('#toolbar-search');
            const searchCategory = toolbar.querySelector('#searchCategoryDropdown');
            const filter = toolbar.querySelector('#filterDropdown');
            const filterValue = toolbar.querySelector('#toolbar-filter-value');
            const sort = toolbar.querySelector('#sortDropdown');
            const sortOrder = toolbar.querySelector('#sortOrderDropdown');
            const group = toolbar.querySelector('#groupDropdown');
            const actions = toolbar.querySelector('#actionsDropdown');

            viewModeDropdown.addEventListener('change', (e) => {
                options.viewMode = e.target.value;
                updateDisplayMode();
            });

            pageModeDropdown.addEventListener('change', (e) => {
                options.pageMode = e.target.value;
                updateDisplayMode();
                if (options.pageMode === 'infinite') {
                    window.addEventListener('scroll', loadMoreOnScroll);
                } else {
                    window.removeEventListener('scroll', loadMoreOnScroll);
                }
            });

            // Add more event listeners for search, filter, sort, and group
            // For instance:
            search.addEventListener('input', (e) => {
                options.search = e.target.value;
                updateData();
            });

            filter.addEventListener('change', (e) => {
                options.filter = e.target.value;
                updateData();
            });

            filterValue.addEventListener('input', (e) => {
                options.filterValue = e.target.value;
                updateData();
            });

            sort.addEventListener('change', (e) => {
                options.sort = e.target.value;
                updateData();
            });

            group.addEventListener('change', (e) => {
                options.group = e.target.value;
                updateData();
            });

            // Add functionality for edit mode
            document.addEventListener('click', (e) => {
                if (options.mode === 'edit') {
                    const row = e.target.closest('.svcr-list-item');
                    if (row) {
                        row.classList.toggle('list-item-selected');
                    }
                }
            });

            updateDisplayMode();
            updateData();
        }
    });
</script>
