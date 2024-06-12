<a class="{{ $class }}" href="{{ $href }}">
    @if(!empty($icon))
        <span class="material-symbols-outlined icon">{{ $icon }}</span>
    @endif
    @if(!empty($title))
        @if (!empty($class) && $class == 'menu-item')
            <span class="menu-item-title">{{ $title }}</span>
        @else
            <span class="link-title">{{ $title }}</span>
        @endif
    @else
        <span class="link-title"></span>
    @endif
</a>
