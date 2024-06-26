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

    <div class="svcr-container" wire.poll>
        <div class="svcr-list" x-show="$wire.viewMode === 'card'">
            @forelse ($serviceRoles as $svcrole)
                <x-templates.svcrole-card :svcrole="$svcrole" />
            @empty
                <div class="empty-list">
                    <span>No service roles found.</span>
                </div>
            @endforelse
        </div>

        <table id="svcr-table" x-show="$wire.viewMode === 'table'">
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
                @forelse ($serviceRoles as $svcrole)
                    <x-templates.svcrole-list-item :svcrole="$svcrole" />
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
            {!! $serviceroles->links() !!}
        @endif
    </div>
    
    <x-link-bar :links="$links" />
</div>

<script type="text/javascript">
    document.addEventListener('DOMContentLoaded', function() {
        const toolbar = document.getElementById('svcroleToolbar');
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

            if (viewModeDropdown) {
                viewModeDropdown.addEventListener('change', function(e) {
                    const value = e.detail.value;
                    Livewire.emit('changeViewMode', value);
                });
            }

            if (pageModeDropdown) {
                pageModeDropdown.addEventListener('change', function(e) {
                    const value = e.detail.value;
                    Livewire.emit('changePageMode', value);
                });
            }

            if (search) {
                if (searchCategory) {
                    searchCategory.addEventListener('change', function(e) {
                        const value = e.detail.value;
                        Livewire.emit('changeSearchCategory', value);
                    });
                }
                search.addEventListener('input', function(e) {
                    const value = e.target.value;
                    Livewire.emit('changeSearchQuery', value);
                });
            }

            if (filter) {
                filter.addEventListener('change', function(e) {
                    const value = e.detail.value;
                    const filterValue = (filterValue) ? filterValue.value : null;
                    Livewire.emit('changeFilter', value, filterValue);
                });
            }

            if (sort) {
                sort.addEventListener('change', function(e) {
                    const value = e.detail.value;
                    const order = (sortOrder) ? sortOrder.value : null;
                    Livewire.emit('changeSort', value, order);
                });
            }

            if (group) {
                group.addEventListener('change', function(e) {
                    const value = e.detail.value;
                    Livewire.emit('changeGroup', value);
                });
            }
        }
    });
</script>
