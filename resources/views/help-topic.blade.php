@php
    use App\Helpers\HtmlHelpers;
    use App\Helpers\LivewireHelpers;

    $topics = json_decode(file_get_contents(resource_path('/json/help/index.json')), true);
    $currentTopic = request()->route('topic');
    $links = [];

    foreach ($topics as $index => $topic) {
        $links[] = [
            'href' => route('help.topic', ['topic' => $topic['url']]),
            'icon' => $topic['icon'],
            'title' => $topic['title'],
            'active' => $currentTopic === $topic['url'],
        ];
    }
@endphp

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
        {{-- <div id="help-search-results">
            @livewire('help.results', key(time()))
        </div> --}}

        @forelse ($topics as $index => $topic)
            @php
                $path = resource_path('/json/help/pages/'.$topic['url'].'.json');
                if (!file_exists($path)) {
                    continue;
                }
                $data = json_decode(file_get_contents($path), true);
                $componentName = 'help.' . $topic['url'];
            @endphp

            @if ($currentTopic === $topic['url'])
                @if (LivewireHelpers::componentExists($componentName))
                    @livewire($componentName, ['topic' => $topic, 'data' => $data], key($index))
                @else
                    {!! HtmlHelpers::convertToJsonToHtml($data, $topic['title'] ?? 'No Title') !!}
                @endif
            @endif
        @empty
            <div class="p-4 bg-gray-100 rounded-lg shadow-md content-main-topic">
                <h2 class="text-xl font-semibold content-main-topic-title">{{ __('No topics found') }}</h2>
            </div>
        @endforelse
    </div>

    <x-link-bar :links="$links" />
    {{-- @push('scripts') --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const headings = document.querySelectorAll('a[href*="subtopic-"]'); // Select all headings with IDs starting with "subtopic-"
            console.log(headings);
            headings.forEach(heading => {
                heading.addEventListener('click', function(event) {
                    event.preventDefault(); // Prevent the default jump behavior

                    // const targetId = this.getAttribute('href'); // Get the href attribute (e.g., "#subtopic-my-heading")
                    // const targetElement = document.getElementById(this.getAttribute('href').replace('#', '')); // Select the target element
                    // const targetElement = document.querySelector(targetId); // Select the target element

                    if (heading) {
                        // add transition 1s ease-in-out
                        heading.style.transition = 'all 1s ease-in-out';
                        // smooth scroll to element
                        heading.scrollIntoView({
                            behavior: 'smooth',
                            block: 'start',
                            inline: 'nearest'
                        });
                    }
                });
            });
            // Function to display search results from localStorage
            function displaySearchResults() {
                const searchResults = localStorage.getItem('searchResults');
                const container = document.getElementById('search-results-container');

                if (!container) {
                    return;
                }
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
    {{-- @endpush --}}
</x-app-layout>
