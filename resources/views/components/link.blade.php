<a class="material-symbols-outlined {{ $class }}"
    data-type="{{ $type }}"
    data-pg="{{ $page }}"
    @if(isset($clickAction))
        onclick="{{ $clickAction }}"
    @endif>
    @if(isset($icon))
        <span class="material-symbols-outlined">{{ $icon }}</span>
    @endif
    {{ $slot }}
</a>
