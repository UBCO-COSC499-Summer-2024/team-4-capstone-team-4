@php
    $serviceroles = App\Models\ServiceRole::all();
@endphp
<div class="content">
    {{-- <livewire:tabbed-component :group-id="'svcrole'" :wire:key="'svcrole-' . now()->timestamp" /> --}}
    <h1 class="nos content-title">
        <span class="content-title-text">Service Roles</span>
        <button class="right">
            <span class="button-title">Create New</span>
            <span class="material-symbols-outlined">add</span>
        </button>
    </h1>
    <div class="toolbar">
        <section class="toolbar-section right">
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
            {{-- get selected mode and store in variable --}}
            @php
                $viewMode = 'list';
                $pageMode = 'pagination';
            @endphp
        </section>
    </div>
    <section class="svcr-list">
        @foreach ($serviceroles as $svcrole)
             <x-templates.svcrole-card :svcrole="$svcrole" />
        @endforeach
    </section>
    <x-link-bar :links="$links" />
</div>
