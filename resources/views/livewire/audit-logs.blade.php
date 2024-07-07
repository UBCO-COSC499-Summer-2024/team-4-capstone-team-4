<div class="content">
    <h1 class="flex nos content-title">
        <span class="content-title-text">Audit Logs</span>

        <span class="content-title-item">
            {{-- <livewire:dropdown-element
                id="viewModeDropdown"
                class="right"
                title="View Mode"
                :values="['card' => 'Card View', 'list' => 'List View']"
                preIcon="view_module"/> --}}
            <livewire:dropdown-element
                id="pageModeDropdown"
                title="Display As"
                :values="['pagination' => 'Pagination', 'infinite' => 'Infinite Scroll']"
                preIcon="first_page"/>
        </span>
    </h1>
    {{-- Toolbar --}}
    <livewire:toolbar
        :searchCategoryValues="['category1' => 'Category 1', 'category2' => 'Category 2', 'category3' => 'Category 3']"
        :sortValues="['sort1' => 'Sort 1', 'sort2' => 'Sort 2', 'sort3' => 'Sort 3']"
        :sortOrderValues="['asc' => 'Ascending', 'desc' => 'Descending']"
        :filterValues="['filter1' => 'Filter 1', 'filter2' => 'Filter 2', 'filter3' => 'Filter 3']"/>
    {{-- Content --}}
    <section class="view">

    </section>
</div>
