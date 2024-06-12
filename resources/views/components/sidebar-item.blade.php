<div class="sidebar-item">
    {{-- link component --}}

    {{-- *note: changed $type to $title --}}
    <x-link
        class="sidebar-link"
        type="{{ $title }}" 
        icon="{{ $icon }}"
        page="{{ $route }}"
        >
    </x-link>
    {{-- add name beside icon --}}
    <a href="#">{{$title}}</a>
</div>
