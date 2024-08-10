<a class="{{ $class }}" href="{{ $href }}" {{$attributes}}>
    {{ $slot }}
    @if(isset($icon))
        <span class="material-symbols-outlined">{{ $icon }}</span>
    @endif
    @if(isset($title))
        @if(isset($class))
            <span class="{{ $class }}-title">{!! str_replace('&amp;', '&', $title) !!}</span>
        @else
            <span class="link-title">{!! str_replace('&amp;', '&', $title) !!}</span>
        @endif
    @endif
</a>
