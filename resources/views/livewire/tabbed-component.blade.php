<div>
    <x-tabs for="{{ $groupId }}">
        @foreach($tabs as $tab)
            <x-tab :tab="$tab" :active="$selectedTab === $tab['id']"
                wire:click="selectTab('{{ $tab['id'] }}')"
                wire:key="{{ $tab['id'] }}"
                wire:loading.attr="disabled"
                />
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
