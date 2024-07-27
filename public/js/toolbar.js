document.addEventListener('DOMContentLoaded', function() {
    function initializeToolbar() {
        const toolbar = document.getElementById('serviceRoleToolbar');
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
            viewModeDropdown.addEventListener('dropdown-item-selected', function(e) {
                const value = e.detail.value;
                // TODO: Add logic to handle view mode change
            });
        }

        if (pageModeDropdown) {
            pageModeDropdown.addEventListener('dropdown-item-selected', function(e) {
                const value = e.detail.value;
                // TODO: Add logic to handle page mode change
            });
        }

        if (search) {
            if (searchCategory) {
                searchCategory.addEventListener('dropdown-item-selected', function(e) {
                    const value = e.detail.value;
                    // TODO: Add logic to handle search category change
                });
            }
            search.addEventListener('input', function(e) {
                const value = e.target.value;
                // TODO: Add logic to handle search input change
            });
        }

        if (filter) {
            filter.addEventListener('dropdown-item-selected', function(e) {
                const value = e.detail.value;
                const filterValue = (filterValueElement) ? filterValueElement.value : null;
                // @this.set('selectedFilter', value);
                // @this.set('filterValue', filterValue);
                // TODO: Add logic to handle filter change
            });
        }

        if (sort) {
            sort.addEventListener('dropdown-item-selected', function(e) {
                const value = e.detail.value;
                const order = (sortOrder) ? sortOrder.value : null;
                // @this.set('selectedSort', value);
                // @this.set('selectedSortOrder', order);
                // TODO: Add logic to handle sort change
            });
        }

        if (group) {
            group.addEventListener('dropdown-item-selected', function(e) {
                const value = e.detail.value;
                // @this.set('selectedGroup', value);
                // TODO: Add logic to handle group change
            });
        }
    }

    const handleSort = function (val, order) {
        // sort by val and order
        
    }

    function waitForToolbar() {
        const observer = new MutationObserver(function(mutations, me) {
            const toolbar = document.getElementById('serviceRoleToolbar');
            if (toolbar) {
                initializeToolbar(toolbar);
                me.disconnect(); // stop observing
            }
        });

        observer.observe(document, {
            childList: true,
            subtree: true
        });
    }

    waitForToolbar();
});