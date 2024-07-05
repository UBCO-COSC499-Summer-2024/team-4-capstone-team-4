<dropdown-element
    id="{{ $id }}"
    role="listbox"
    tabindex="0"
    title="{{ $title }}"
    class="dropdown-element {{ $class }}"
    values="{{ json_encode($values) }}"
    @if(isset($attributes))
        {{ $attributes }}
    @endif
    {{-- x-data="{ open: false }"
    x-on:click.away="open = false"
    wire:click="toggleDropdown" --}}
    >
    @if($preIcon)
        <span class="material-symbols-outlined icon dropdown-pre-icon noselect">{{ $preIcon }}</span>
    @endif
    <span class="dropdown-title noselect">{{ $title }}
    </span>
    <span class="material-symbols-outlined dropdown-button icon noselect" x-on:click="open = !open">arrow_drop_down</span>

    <dropdown-content class="dropdown-content">
        @foreach($values as $value => $name)
            <dropdown-item class="dropdown-item"
                value="{{ json_encode($value) }}"
                wire:key="ddi-{{ json_encode($value) }}-{{ time() }}"
                {{-- x-on:click="$wire.dispatch('dropdown-item-selected', {{ json_encode($value) }})" --}}>{{ $name }}</dropdown-item>
        @endforeach
    </dropdown-content>

</dropdown-element>
