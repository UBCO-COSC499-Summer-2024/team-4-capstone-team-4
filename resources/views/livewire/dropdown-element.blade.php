@php
    // if values is array or object, convert to value => name array, else if string json_decode
    if(is_array($values) || is_object($values)) {
        // check if it already follows the required format
        if(!isset($values[0]) && !isset($values['value'])) {
            $values = collect($values)->mapWithKeys(function($item, $key) {
                return [$key => $item];
            })->toArray();
        } else {
            $values = collect($values)->mapWithKeys(function($item) {
                return [$item['value'] => $item['name']];
            })->toArray();
        }
    } else {
        $values = json_decode($values, true);
    }
@endphp
<dropdown-element
    id="{{ $id }}"
    role="listbox"
    tabindex="0"
    title="{{ $title }}"
    class="dropdown-element {{ $class }}"
    values="{{ json_encode($values) }}"
    @if ($multiple) multiple @endif
    {{-- @if ($disabled) disabled @endif --}}
    {{-- @if ($required) required @endif --}}
    {{-- @if ($readonly) readonly @endif --}}
    {{-- @if ($autofocus) autofocus @endif --}}
    @if ($searchable) searchable="{{ $searchable }}" @endif
    @if ($useExternal) external="{{ $useExternal }}" @endif
    @if ($source) src="{{ $source }}" @endif
    @if ($value) value="{{ $value }}" @endif
    @if ($preIcon) preIcon="{{ $preIcon }}" @endif
    @if ($regex) regex="{{ $regex }}" @endif
    >
    @if($preIcon)
        <span class="material-symbols-outlined icon dropdown-pre-icon noselect">{{ $preIcon }}</span>
    @endif
    <span class="dropdown-title noselect">{{ $title }}
    </span>
    <span class="material-symbols-outlined dropdown-button icon noselect" x-on:click="open = !open">arrow_drop_down</span>

    <dropdown-content class="dropdown-content">
    </dropdown-content>

</dropdown-element>
@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', initThisDropdown);
        document.addEventListener('Liveiwre:init', initThisDropdown);
        document.addEventListener('Livewire:load', initThisDropdown);
        document.addEventListener('Liveiwre:update', initThisDropdown);

        const thisDropdown = document.querySelector('#{{ $id }}"]');

        function initThisDropdown(thisDropdown) {
            if (document.querySelector('#{{ $id }}.initialized')) return;
            const dropdown = thisDropdown;
            if (!dropdown) return;
            dropdown.classList.add('initialized');
            const dropdownContent = dropdown.querySelector('.dropdown-content');
            const dropdownItems = dropdownContent.querySelectorAll('.dropdown-item');
            const dropdownButton = dropdown.querySelector('.dropdown-button');
            const dropdownTitle = dropdown.querySelector('.dropdown-title');
            const dropdownPreIcon = dropdown.querySelector('.dropdown-pre-icon');

            dropdown.addEventListener('change', function(e) {
                console.log('change', e);
                console.log('value', e.target.value);
                console.log('value', e.value);
                console.log('value', dropdown.getSelected())
                @this.set('value', e.target.value);
            });
        }
    </script>
@endpush
