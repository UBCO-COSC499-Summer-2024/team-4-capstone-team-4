@php
    $serviceroles = App\Models\ServiceRole::all();
@endphp
<div class="content">
    <livewire:tabbed-component :group-id="'svcrole'" :wire:key="'svcrole-' . now()->timestamp" />
    @foreach ($serviceroles as $svcrole)
        <div class="card">
            <div class="card-header">
                <h2>{{ $svcrole->role }}</h2>
                <span class="material-symbols-outlined icon">more_vert</span>
            </div>
            <div class="card-content">
                <div class="card-section">
                    <div class="card-section-item">
                        <span class="material-symbols-outlined icon">category</span>
                        <span>{{ $svcrole->area }}</span>
                    </div>
                    <div class="card-section-item">
                        <span class="material-symbols-outlined icon">description</span>
                        <span>{{ $svcrole->description }}</span>
                    </div>
                    <div class="card-section-item">
                        <span class="material-symbols-outlined icon">schedule</span>
                        <span>{{ $svcrole->created_at }}</span>
                    </div>
                </div>
                <div class="card-section">
                    <button>
                        <span class="material-symbols-outlined icon">edit</span>
                        <span>Edit</span>
                    </button>
                    <button>
                        <span class="material-symbols-outlined icon">delete</span>
                        <span>Delete</span>
                    </button>
                </div>
            </div>
        </div>
    @endforeach
    <section class="link-bar">
        <x-link href="{{ route('svcroles') }}" title="{{ __('Dashboard') }}" :active="request()->is('svcroles')" />
        <x-link href="{{ route('svcroles.add') }}" title="{{ __('Add Service Role') }}" :active="request()->is('svcroles/add')" />
        <x-link href="{{ route('svcroles.manage') }}" title="{{ __('Manage Service Roles') }}" :active="request()->is('svcroles/manage')" />
        <x-link href="{{ route('svcroles.requests') }}" title="{{ __('Requests') }}" :active="request()->is('svcroles/requests')" />
        <x-link href="{{ route('svcroles.logs') }}" title="{{ __('Audit Logs') }}" :active="request()->is('svcroles/audit-logs')" />
    </section>
</div>
