<div class="flex flex-col w-full gap-4 px-2 service-requests-container" x-data="{
    viewMode: @entangle('viewMode'),
}">
    <section class="z-50 toolbar" id="approval-toolbar">
        <section class="left toolbar-section">
        </section>
        <section class="right toolbar-section">
            <x-dropdown title="View Mode" class="toolbar-dropdown">
                <x-slot name="trigger">
                    <button class="toolbar-dropdown-trigger">
                        <span class="material-symbols-outlined icon toolbar-dropdown-icon">
                            view_module
                        </span>
                        <span class="toolbar-dropdown-title">
                            {{ ucfirst($viewMode) }}
                        </span>
                        <span class="material-symbols-outlined icon toolbar-dropdown-arrow">
                            arrow_drop_down
                        </span>
                    </button>
                </x-slot>
                <x-slot name="content">
                    <x-dropdown-link title="All" wire:click="$dispatch('change-view-mode', ['all'])"
                        class="{{ $viewMode === 'all' ? 'active' : '' }}">All</x-dropdown-link>
                    @foreach ($types as $type)
                        <x-dropdown-link title="{{ $type->name }}" wire:click="$dispatch('change-view-mode', ['{{ $type->name }}'])"
                            :key="'view_mode_{{ $type->name }}'"
                            class="{{ $viewMode === $type->name ? 'active' : '' }}">{{ ucfirst($type->name) }}</x-dropdown-link>
                    @endforeach
                </x-slot>
            </x-dropdown>
        </section>
    </section>

    <div class="grid grid-cols-1 gap-4">
        <section class="service-requests-section">
            <livewire:approval-list :type="$viewMode" :key="'approval_list_' . $viewMode" />
        </section>
    </div>
</div>
