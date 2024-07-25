<section class="help-results">
    @if (isset($results) && (count($results) > 0 || !empty($searchQuery)))
        <h2 class="help-search-results-title">{{ __('Search Results') }}</h2>
        @foreach ($results as $index => $result)
            <livewire:templates.help-result-item :result="$result" :key="$index" />
        @endforeach
    @endif
</section>
