<x-app-layout>
    @vite(['resources/css/help.css'])
    <div class="content">
        <h1 class="content-title">
            <div class="content-title-btn-holder">
                <button class="content-title-btn hover:!border-2" onclick="window.history.back()" style="border-width: 2px !important;">
                    <span class="material-symbols-outlined">arrow_back</span>
                    <span class="content-title-btn-text">{{ __('Back') }}</span>
                </button>
            </div>
        </h1>

        <livewire:help.hero />

        @php
            $searchResults = Session::get('searchResults');
        @endphp

        @if (!empty($searchResults))
            <div id="help-search-results">
                <h2 class="help-search-results-title">{{ __('Search Results') }}</h2>
                @livewire('help.results', ['results' => $searchResults], key(time()))
                {{-- <livewire:help.results :results="{{ json_encode($searchResults) }}" wire:key="{{time()}}" /> --}}
            </div>
        @endif

        <livewire:help.main-topics />
        <livewire:help.faq />
    </div>
</x-app-layout>
