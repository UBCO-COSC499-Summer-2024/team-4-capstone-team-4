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

        <div id="help-search-results">
            @livewire('help.results', key(time()))
        </div>

        <livewire:help.main-topics />
        <livewire:help.faq />
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Function to display search results from localStorage
            function displaySearchResults() {
                const searchResults = localStorage.getItem('searchResults');
                const container = document.getElementById('search-results-container');

                if (searchResults) {
                    container.style.display = 'block';
                } else {
                    container.style.display = 'none';
                }
            }

            // Display search results if available in localStorage
            displaySearchResults();
        });
    </script>
    @endpush
</x-app-layout>
