@php
    $classes = 'tab nos' . ($active ? ' active' : '');
@endphp
<button class="{{ $classes }}" id="{{ $tab['id'] }}">
    @if(isset($tab['icon']))
        <span class="material-symbols-outlined icon tab-item">{{ $tab['icon'] }}</span>
    @endif
    @if(isset($tab['title']))
        <span class="tab-item tab-item-title">{{ $tab['title'] }}</span>
    @endif
</button>

