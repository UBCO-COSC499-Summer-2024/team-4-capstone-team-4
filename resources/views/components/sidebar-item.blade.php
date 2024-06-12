<div class="sidebar-item flex items-center">
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
    <a href="#" class="text-xl mx-4 my-4">{{$title}}</a>
</div>
