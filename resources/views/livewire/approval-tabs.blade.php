@php
    use App\Helpers\LivewireHelpers;
@endphp
<div class="relative text-sm font-medium text-gray-500 min-w-fit" x-data="{
        activeTab: @entangle('activeTab'),
        setActiveTab(tab) {
            this.activeTab = tab;
        }
    }">
    <nav class="flex flex-wrap -mb-px list-none border-b border-gray-200">
        @foreach ($tabs as $tab => $tabItem)
            <li class="me-2">
                <a id="tab-{{$tab}}" class="import-nav-link @if($activeTab === $tab) active text-[#3b4779] hover:text-[#3b4779] border-solid border-b-[#3b4779] hover:border-b-[#3b4779] @endif" href="?tab={{$tab}}"
                x-on:click.stop="setActiveTab('{{$tab}}')">{{$tabItem['label']}}</a>
            </li>
        @endforeach
    </nav>

    <div class="mt-5 ">
        @foreach ($tabs as $tab => $tabItem)
            @if ($activeTab === $tab)
                @if (LivewireHelpers::componentExists($tabItem['component']))
                    @php
                        $hasOptions = $tabItem['options'] !== [] || count($tabItem['options']) > 0 || !empty($tabItem['options']);
                    @endphp
                    @if($hasOptions)
                        @livewire($tabItem['component'], $tabItem['options'], key('panel_'.$tabItem['component']))
                    @else
                        @livewire($tabItem['component'])
                    @endif
                @else
                    {{-- throw 404. We couldn't find what you were looking --}}
                    <div class="text-center text-gray-500">
                        <span class="text-6xl material-symbols-outlined icon">sentiment_very_dissatisfied</span>
                        <p class="text-lg">We couldn't find what you were looking for.</p>
                    </div>
            @endif
            @endif
        @endforeach
    </div>
</div>

