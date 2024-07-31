<div class="flex flex-col w-full gap-4 px-2 service-requests-container">
    <!-- Toolbar Section -->
    <section class="z-50 toolbar" id="approval-toolbar">
        <section class="left toolbar-section">
            <!-- Add content for the left toolbar section if needed -->
        </section>
        <section class="right toolbar-section">
            <!-- Switch View Mode Dropdown -->
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
                    <x-dropdown-link title="All" wire:click="changeViewMode('all')">All</x-dropdown-link>
                    <x-dropdown-link title="Grid" wire:click="changeViewMode('grid')">Grid</x-dropdown-link>
                    @foreach ($types as $type)
                        <x-dropdown-link title="{{ $type->name }}" wire:click="changeViewMode('{{ $type->name }}')">{{ ucfirst($type->name) }}</x-dropdown-link>
                    @endforeach
                </x-slot>
            </x-dropdown>
        </section>
    </section>

    <div class="grid gap-4 {{ $viewMode === 'all' ? 'grid-cols-1' : ($viewMode === 'grid' ? 'grid-cols-2' : 'grid-cols-1') }} sm:grid-cols-1">
        @foreach ($types as $type)
            @if ($viewMode === $type->name)
                <section class="service-requests-section">
                    <livewire:approval-list :type="$type->name" :key="$type->id" />
                </section>
            @elseif ($viewMode === 'all')
                <section class="service-requests-section">
                    <livewire:approval-list :type="'all'" :key="$type->id" />
                </section>
            @endif
        @endforeach
    </div>
</div>
