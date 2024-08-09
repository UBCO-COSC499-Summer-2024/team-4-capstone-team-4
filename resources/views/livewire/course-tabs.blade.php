@php
    use App\Helpers\LivewireHelpers;
@endphp
<div class="relative min-w-fit text-sm font-medium text-gray-500" x-data="{
    activeTab: @entangle('activeTab'),
    setActiveTab(tab) {
        this.activeTab = tab;
        $dispatch('tab-changed', {tab: tab});
    }
}">
    <nav class="flex flex-wrap mb-px-2 list-none border-b border-gray-200 w-full">
        {{-- <li class="me-2">
            <a id="tab-courses"
               class="import-nav-link @if($activeTab === 'courses') active @endif"
               href="#"
               wire:click.prevent="setActiveTab('courses')">Course Sections</a>
        </li>
        <li class="me-2">
            <a id="tab-tas"
               class="import-nav-link @if($activeTab === 'tas') active @endif"
               href="#"
               wire:click.prevent="setActiveTab('tas')">TAs</a>
        </li>
        <li class="me-2">
            <a id="tab-archived"
               class="import-nav-link @if($activeTab === 'archived') active @endif"
               href="#"
               wire:click.prevent="setActiveTab('archived')">Archived Courses</a>
        </li> --}}
        @foreach ($tabs as $tab => $tabItem)
            <li class="me-2" wire:key="tab-{{$tab}}">
                <a id="tab-{{$tab}}"
                class="import-nav-link @if($activeTab === $tab) active @endif"
                {{-- href="?tab={{$tab}}" --}}
                @click.prevent="setActiveTab('{{$tab}}')">{{$tabItem['label']}}</a>
            </li>
        @endforeach
    </nav>

    <div class="w-full mt-5">
        @foreach ($tabs as $tab => $tabItem)
            @if($activeTab === $tab)
                @if(LivewireHelpers::componentExists($tabItem['component']))
                    @livewire($tabItem['component'], key('panel_'.$tab))
                @else
                    <div class="text-center text-gray-500" wire:key="tab_panel-{{$tab}}">
                        <span class="text-6xl material-symbols-outlined icon">sentiment_very_dissatisfied</span>
                        <p class="text-lg"We couldn't find what you were looking for.></p>
                    </div>
                @endif
            @endif
        @endforeach
    </div>
</div>
