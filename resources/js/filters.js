document.addEventListener('DOMContentLoaded', () => initToolbar());
document.addEventListener('livewire:init', () => initToolbar());
document.addEventListener('livewire:update', () => initToolbar());
document.addEventListener('livewire:load', () => initToolbar());

function initToolbar() {
    if (document.querySelector('.toolbar.init')) return;
    const toolbar = document.querySelector('.toolbar');
    if (!toolbar) return;
    toolbar.classList.add('init');
    const search = document.getElementById('toolbar-search');
    const clearSearch = document.querySelector('.toolbar-clear-search');
    const filterBtn = document.querySelector('.filter-btn');
    // const filterClear = document.querySelector('.filter-clear');

    if (clearSearch) {
        clearSearch.addEventListener('click', () => {
            search.value = '';
            // @this.dispatch('change-search-query', { 'query':  });
        });
    }

    if (filterBtn) {
        filterBtn.addEventListener('click', () => {
            const filterItemsHolder = document.querySelector('.filter-items-holder');
            filterItemsHolder.classList.toggle('open');
        });
    }

    document.addEventListener('click', (e) => {
        const filterItemsHolder = document.querySelector('.filter-items-holder');
        if (!filterItemsHolder) return;
        if (!filterItemsHolder.contains(e.target) && !filterBtn.contains(e.target)) {
            filterItemsHolder.classList.remove('open');
        }
    });
}
