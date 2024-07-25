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
        <livewire:help.main-topics />
        <livewire:help.f-a-q />
    </div>
</x-app-layout>
