@props([
    'title' => 'Dropdown',
    'multiple' => false,
    'searchable' => false,
    'externalSource' => null,
    'regex' => 'i',
    'values' => [],
    'value' => null,
    'preIcon' => 'list',
    'name' => null,
])
<dropdown-element
    title="{{ $title }}" 
    role="listbox" 
    tabindex="0"
    {{ $attributes->merge(['class' => 'dropdown-element']) }}
    @if($multiple) multiple @endif 
    @if ($name) name="{{ $name }}" @endif
    @if($searchable) searchable @endif 
    @if($externalSource) external="{{ $externalSource }}" @endif 
    @if($regex) regex="{{ $regex }}" @endif 
    values="{{ json_encode($values) }}"
    @if($preIcon) pre-icon="{{ $preIcon }}" @endif
    @if($value) value="{{ $value }}" @endif>
    <dropdown-content>
        @foreach ($values as $vname => $value)
            <dropdown-item class="dropdown-item" value="{{ $vname }}">{{ $value }}</dropdown-item>
        @endforeach
    </dropdown-content>
</dropdown-element>