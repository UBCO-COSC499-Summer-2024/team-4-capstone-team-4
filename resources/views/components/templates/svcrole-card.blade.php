@props(['svcrole'])
<div class="card">
    <div class="card-header">
        <h2>{{ $svcrole->name }}</h2>
        <button>
            <span class="material-symbols-outlined icon">more_vert</span>
        </button>
    </div>
    <div class="card-content">
        <div class="card-section">
            <section class="card-section-item">
                <span>Area</span>
                <span>{{ $svcrole->area->name }}</span>
            </section>
            <section class="card-section-item">
                <p>{{ $svcrole->description }}</p>
            </section>
            <section class="card-section-item svcr-card-instructors">
                <h4>Instructors</h4>
                <ul>
                    @foreach ($svcrole->users as $instructor)
                        <li>{{ $instructor->name }}</li>
                    @endforeach
                </ul>
            </section>
        </div>
    </div>
    <div class="card-footer">
        <button>
            {{-- <span class="material-symbols-outlined icon">edit</span> --}}
            <span>Manage</span>
        </button>
    </div>
</div>