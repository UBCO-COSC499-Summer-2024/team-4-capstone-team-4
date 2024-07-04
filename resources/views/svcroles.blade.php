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

    {{-- @if ($user->hasRoles(['dept_staff', 'dept_head', 'admin'])) --}}
        @if(request()->is('svcroles/add'))
            @include('components.svcrole.add-svcrole')
        @elseif(request()->is('svcroles/manage'))
            {{-- needs id --}}
            @php
                $serviceRole = \App\Models\ServiceRole::first();
            @endphp
            <livewire:manage-service-role
                :links="$links"
                :serviceRole="$serviceRole"
            >
        @elseif (request()->is('svcroles/manage/*'))
            @php
                $svcId = request()->route('id');
                $serviceRole = \App\Models\ServiceRole::find($svcId);
                if (!$serviceRole) {
                    return redirect()->route('svcroles');
                }
            @endphp
            <livewire:manage-service-role
                :links="$links"
                :serviceRole="$serviceRole"
            >
        @elseif(request()->is('svcroles/requests'))
            @include('components.svcrole.requests')
        @elseif(request()->is('svcroles/audit-logs'))
            @include('components.svcrole.logs')
        @else
            {{-- @php
                $pageMode = request()->input('page_mode', 'pagination');
                $viewMode = request()->input('view_mode', 'list');
                $page = request()->input('page', 1);
                $pgnSize = request()->input('pgn_size', 10);
            @endphp
            @include('components.svcrole.dashboard', ['view_mode' => $viewMode, 'page_mode' => $pageMode, 'page' => $page, 'pgn_size' => $pgnSize]); --}}
            <livewire:service-roles-list :links="$links"/>
            {{-- @include('components.svcrole.dashboard') --}}
        @endif
    {{-- @else
        @php
            return redirect()->route('dashboard');
        @endphp
    @endif --}}
</x-app-layout>
