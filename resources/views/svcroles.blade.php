<x-app-layout>
    @php
        $user = auth()->user();
        $userRoles = $user->roles()->pluck('role')->toArray();
        $links = [
            ['href' => route('svcroles'), 'title' => __('Dashboard'), 'icon' => 'group', 'active' => request()->is('svcroles')],
            ['href' => route('svcroles.add'), 'title' => __('Add Service Role'), 'icon' => 'add', 'active' => request()->is('svcroles/add')],
            ['href' => route('svcroles.manage'), 'title' => __('Manage Service Roles'), 'icon' => 'visibility', 'active' => request()->is('svcroles/manage')],
            ['href' => route('svcroles.requests'), 'title' => __('Requests'), 'icon' => 'request_page', 'active' => request()->is('svcroles/requests')],
            ['href' => route('svcroles.logs'), 'title' => __('Audit Logs'), 'icon' => 'description', 'active' => request()->is('svcroles/audit-logs')],
        ];
    @endphp

    {{-- disabled access control for testing --}}

    @if ($user->hasRoles(['dept_staff', 'dept_head', 'admin']))
        @if(request()->is('svcroles/add'))
            @include('components.svcrole.add-svcrole')
        @elseif(request()->is('svcroles/manage'))
            {{-- needs id --}}
            @php
                $svcrId = 1;
            @endphp
            <livewire:manage-service-role
                :links="$links"
                :serviceRoleId="$svcrId"
            />
        @elseif (request()->is('svcroles/manage/*'))
            @php
                $svcrId = request()->route('id');
            @endphp
            <livewire:manage-service-role
                :links="$links"
                :serviceRoleId="$svcrId"
            />
        @elseif(request()->is('svcroles/requests'))
            @include('components.svcrole.requests')
        @elseif(request()->is('svcroles/audit-logs'))
            {{-- @include('components.svcrole.logs') --}}
            <livewire:audit-logs />
        @else
            <livewire:service-roles-list :links="$links"/>
        @endif
    @else
        @php
            $url = route('dashboard');
            header("Location: $url");
            exit();
        @endphp
    @endif
</x-app-layout>
