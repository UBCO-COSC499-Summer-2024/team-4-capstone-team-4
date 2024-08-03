<div class="content place-items-center"
    x-data="{
        viewMode: @entangle('viewMode'),
        numItems: @entangle('numItems'),
        showImportModal: @entangle('showImportModal'),
        forceViewMode: @entangle('forceViewMode'),
        formattedData: @entangle('formattedData'),
    }"
    x-init="$nextTick(() => { showImportModal = false; })">
    <h1 class="flex justify-between m-auto nos content-title">
        <span class="content-title-text w-fit">Add Service Role</span>
        <div class="content-title-btn-holder w-fit">
            <button class="content-title-btn" @click="$dispatch('svcr-table-add-row');"
                x-show="viewMode === 'table'"
                x-cloak
            >
                <span class="button-title">Add Row</span>
                <span class="material-symbols-outlined">add</span>
            </button>
            <button class="content-title-btn" @click="
                viewMode = viewMode === 'form' ? 'table' : 'form';
                forceViewMode = viewMode;
            ">
                <span class="button-title">Toggle View</span>
                <span class="material-symbols-outlined">swap_horiz</span>
            </button>
            <button class="content-title-btn" @click="showImportModal = true">
                <span class="button-title">Import</span>
                <span class="material-symbols-outlined">cloud_upload</span>
            </button>
            {{-- <button class="content-title-btn" onclick="window.location.href='{{ route('svcroles') }}'">
                <span class="button-title">See All</span>
                <span class="material-symbols-outlined">visibility</span>
            </button> --}}
            {{-- save button --}}
            <button class="content-title-btn" @click="$dispatch('svcr-table-save');" x-show="viewMode === 'table'" x-cloak>
                <span class="button-title">Save</span>
                <span class="material-symbols-outlined">save</span>
            </button>
        </div>
    </h1>
    <section
        id="import-data"
        x-show="forceViewMode === 'form' || (forceViewMode !== 'table' && (viewMode === 'form' || (numItems < 2 && formattedData.length === 0)))"
        x-cloak
        class="flex-col items-center justify-center add-svcrole-section-form add-svcrole-section">
        <livewire:service-role-form />
    </section>
    <section
        id="view-uploaded-data"
        x-show="forceViewMode === 'table' || (forceViewMode !== 'form' && (viewMode === 'table' || numItems > 1))"
        x-cloak
        class="add-svcrole-section-table add-svcrole-section">
        <livewire:service-role-table :svcroles="$formattedData"/>
    </section>
    <x-link-bar :links="$links" />

    <x-dialog-modal x-show="showImportModal" @close="showImportModal = false" x-cloak wire:model="showImportModal" class="glass" style="background: rgba(255,255,255, 0.9)">
        <x-slot name="title">
            Import Data
        </x-slot>

        <x-slot name="content">
            <livewire:drag-and-drop :action="'upload.svcroles'" :accept="'.csv, .xlsx, .xls, .json'" :multiple="true" :externalHandler="false" />
        </x-slot>

        <x-slot name="footer">
            <x-button @click="showImportModal = false">
                {{ __('Close') }}
            </x-button>
        </x-slot>
    </x-dialog-modal>
</div>
