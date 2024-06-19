<div class="content">
    <livewire:tabbed-component :group-id="'svcrole'" :wire:key="'svcrole-' . now()->timestamp" />
    
    <section class="link-bar">
        <x-link href="{{ route('svcroles') }}" title="{{ __('Dashboard') }}" :active="request()->is('svcroles')" />
        <x-link href="{{ route('svcroles.add') }}" title="{{ __('Add Service Role') }}" :active="request()->is('svcroles/add')" />
        <x-link href="{{ route('svcroles.manage') }}" title="{{ __('Manage Service Roles') }}" :active="request()->is('svcroles/manage')" />
        <x-link href="{{ route('svcroles.requests') }}" title="{{ __('Requests') }}" :active="request()->is('svcroles/requests')" />
        <x-link href="{{ route('svcroles.logs') }}" title="{{ __('Audit Logs') }}" :active="request()->is('svcroles/audit-logs')" />
    </section>
</div>
