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
    <livewire:toolbar />
    {{-- Content --}}
    <section class="view">

    </section>
</div>
