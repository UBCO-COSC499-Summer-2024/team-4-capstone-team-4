<div class="content place-items-center">
    <div></div>
    <section id="import-data" class="flex flex-col items-center justify-center object-center w-max">
        <h1 class="flex justify-between m-auto nos content-title">
            <span class="content-title-text">Add Service Role</span>
            {{-- <section class="mini-toolbar">
                <div class="toolbar-section-item">
                </div>
            </section> --}}
            <div class="content-title-btn-holder">
                <button class="content-title-btn" onclick="window.location.href='{{ route('svcroles') }}'">
                    <span class="button-title">See All</span>
                    <span class="material-symbols-outlined">visibility</span>
                </button>
            </div>
        </h1>
        <livewire:service-role-form />
    </section>

    <x-link-bar :links="$links" />
</div>
