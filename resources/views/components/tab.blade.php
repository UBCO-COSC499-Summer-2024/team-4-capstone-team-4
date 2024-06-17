@php
    $classes = 'tab' . ($tab['active'] ? ' active' : '');
@endphp
<div class="{{ $classes }}" id="{{ $attributes['id'] }}" id="{{ $tab['id'] }}">
    @if(isset($attributes['tab']['icon']))
        <span class="material-symbols-outlined icon tab-item">{{ $attributes['tab']['icon'] }}</span>
    @endif
    @if(isset($attributes['tab']['title']))
        <span class="tab-item tab-item-title">{{ $attributes['tab']['title'] }}</span>
    @endif
</div>

