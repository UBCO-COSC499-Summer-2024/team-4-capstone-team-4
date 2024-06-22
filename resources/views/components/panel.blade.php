@php
    $classes = 'panel' . ($active ? ' active' : '');
@endphp
<div class="{{ $classes }}" for="{{ $panel['for'] }}" id="{{ $panel['id'] }}">
    {{ $slot }}
</div>
