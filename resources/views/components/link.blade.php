<a class="{{ $class }}" href="{{ $href }}">
    @if(isset($icon))
        <span class="material-symbols-outlined">{{ $icon }}</span>
    @endif
    @if(isset($title))
        @if(isset($class))
            <span class="{{ $class }}-title">{{ $title }}</span>
        @else
            <span class="link-title">{{ $title }}</span>
        @endif
    @endif
    
</a>
