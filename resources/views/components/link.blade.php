<a class="material-symbols-outlined {{ $class }}"
    href="{{ $href }}"
    data-type="{{ $type }}"
    data-pg="{{ $page }}"
    @if(isset($clickAction))
        onclick="{{ $clickAction }}"
    @endif>
    {{ $slot }}
</a>
