<div>
    <x-tabs for="{{ $groupId }}">
        @foreach($tabs as $tab)
            <x-tab :tab="$tab" wire:click="selectTab('{{ $tab['id'] }}')" />
        @endforeach
    </x-tabs>

    <x-panels for="{{ $groupId }}">
        @foreach($panels as $tabId => $panel)
            <x-panel :panel="$panel" :active="$selectedTab === $tabId">
                {{ $panel['content'] }}
            </x-panel>
        @endforeach
    </x-panels>
</div>
