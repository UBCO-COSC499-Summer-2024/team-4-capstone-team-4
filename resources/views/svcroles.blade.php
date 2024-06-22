<x-app-layout>
    @php
        $links = [
            ['href' => route('svcroles'), 'title' => __('Dashboard'), 'icon' => 'group', 'active' => request()->is('svcroles')],
            ['href' => route('svcroles.add'), 'title' => __('Add Service Role'), 'icon' => 'add', 'active' => request()->is('svcroles/add')],
            ['href' => route('svcroles.manage'), 'title' => __('Manage Service Roles'), 'icon' => 'visibility', 'active' => request()->is('svcroles/manage')],
            ['href' => route('svcroles.requests'), 'title' => __('Requests'), 'icon' => 'request_page', 'active' => request()->is('svcroles/requests')],
            ['href' => route('svcroles.logs'), 'title' => __('Audit Logs'), 'icon' => 'description', 'active' => request()->is('svcroles/audit-logs')],
        ];
    @endphp
    @if(request()->is('svcroles/add'))
        @include('components.svcrole.add-svcrole')
    @elseif(request()->is('svcroles/manage'))
        @include('components.svcrole.manage-svcroles')
    @elseif(request()->is('svcroles/requests'))
        @include('components.svcrole.requests')
    @elseif(request()->is('svcroles/audit-logs'))
        @include('components.svcrole.logs')
    @else
        @include('components.svcrole.dashboard')
    @endif
</x-app-layout>