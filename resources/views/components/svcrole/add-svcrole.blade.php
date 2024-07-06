<div class="content">
    <h1 class="nos flex">
        <span class="content-title-text">Add Service Role</span>
        {{-- <section class="mini-toolbar">
            <div class="toolbar-section-item">
            </div>
        </section> --}}
        <button onclick="window.location.href='{{ route('svcroles') }}'">
            <span class="button-title">See All</span>
            <span class="material-symbols-outlined">visibility</span>
        </button>
    </h1>
    <section id="import-data" class="">
        <livewire:service-role-form />
    </section>

    <x-link-bar :links="$links" />
</div>
