<section class="help-results">
    @if (count($results) > 0)
        @foreach ($results as $index => $result)
            <livewire:templates.help-result-item :result="$result" :key="$index" />
        @endforeach
    @else
        <span class="empty">
            {{ __('No results found.') }}
        </span>
    @endif
</section>
