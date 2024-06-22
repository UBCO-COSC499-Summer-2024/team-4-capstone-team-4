@props(['svcrole'])
{{-- list item (tabular) version of service role --}}
<div class="list-item">
    <section class="list-item-section">
        <span>{{ $svcrole->name }}</span>
    </section>
    <section class="list-item-section">
        <span>{{ $svcrole->area->name }}</span>
    </section>
    <section class="list-item-section">
        <span>{{ $svcrole->description }}</span>
    </section>
    <section class="list-item-section">
        <span>{{ $svcrole->users->count() }}</span>
    </section>
    <section class="list-item-section">
        <button>
            <span class="material-symbols-outlined icon">more_vert</span>
        </button>
    </section>
</div>