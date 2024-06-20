@props([
    'title' => 'Dropdown',
    'multiple' => false,
    'searchable' => false,
    'externalSource' => null,
    'regex' => 'i',
    'values' => [],
    'preIcon' => 'list'
])

<dropdown-element
    class="dropdown-element" 
    title="{{ $title }}" 
    role="listbox" 
    tabindex="0" 
    @if($multiple) multiple @endif 
    @if($searchable) searchable @endif 
    @if($externalSource) external="{{ $externalSource }}" @endif 
    @if($regex) regex="{{ $regex }}" @endif 
    values="{{ json_encode($values) }}"
>
    <span class="material-symbols-outlined dropdown-pre-icon noselect">
        {{ $preIcon }}
    </span>
    <span class="dropdown-title noselect">
        {{ $title }}
    </span>
    <span class="material-symbols-outlined dropdown-button noselect">
        arrow_drop_down
    </span>
    {{-- @if($searchable)
    <input 
        type="text" 
        placeholder="Search..." 
        class="dropdown-search" 
        autocomplete="off" 
        autocorrect="off" 
        autocapitalize="off" 
        spellcheck="false" 
        tabindex="-1"
    >
    @endif --}}
    <dropdown-content>
        @foreach ($values as $name => $value)
            <dropdown-item class="dropdown-item" value="{{ $name }}">{{ $name }}</dropdown-item>
        @endforeach
    </dropdown-content>
</dropdown-element>