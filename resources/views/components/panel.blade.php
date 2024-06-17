@php
    $classes = 'panel' . ($active ? ' active' : '');
@endphp
<div class="{{ $classes }}" id="{{ $panel['id'] }}">
    {{ $slot }}
</div>
