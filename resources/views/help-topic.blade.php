@php
    use App\Helpers\HtmlHelpers;
    use App\Helpers\LivewireHelpers;
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

        @php
            $currentTopic = request()->route('topic');
            $topics = json_decode(file_get_contents(resource_path('/json/help/index.json')), true);
        @endphp

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
</x-app-layout>
