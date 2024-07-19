<x-app-layout>
    @php
        $user = auth()->user();
        $userRoles = $user->roles()->pluck('role')->toArray();
        $links = [
            ['href' => route('svcroles'), 'title' => __('Dashboard'), 'icon' => 'group', 'active' => request()->is('svcroles')],
            ['href' => route('svcroles.add'), 'title' => __('Add Service Role'), 'icon' => 'add', 'active' => request()->is('svcroles/add')],
            ['href' => route('svcroles.manage'), 'title' => __('Manage Service Roles'), 'icon' => 'visibility', 'active' => request()->is('svcroles/manage')]
        ];
    @endphp

    @if ($user->hasRoles(['dept_staff', 'dept_head', 'admin']))
        @if(request()->is('svcroles/add'))
            @include('components.svcrole.add-svcrole')
        @elseif(request()->is('svcroles/manage'))
            @php
                $svcrId = 1;
                $nextId = \App\Models\ServiceRole::where('id', '>', $svcrId)->min('id') ?? \App\Models\ServiceRole::min('id');
                $prevId = \App\Models\ServiceRole::where('id', '<', $svcrId)->max('id') ?? \App\Models\ServiceRole::max('id');

                $links = array_merge($links, [
                    ['href' => route('svcroles.manage.id', ['id' => $prevId]), 'title' => __('Previous Service Role'), 'icon' => 'chevron_left', 'active' => false],
                    ['href' => route('svcroles.manage.id', ['id' => $nextId]), 'title' => __('Next Service Role'), 'icon' => 'chevron_right', 'active' => false],
                ]);
            @endphp
            <livewire:manage-service-role
                :links="$links"
                :serviceRoleId="$svcrId"
            />
        @elseif (request()->is('svcroles/manage/*'))
            @php
                $svcrId = request()->route('id');
                $nextId = \App\Models\ServiceRole::where('id', '>', $svcrId)->min('id') ?? \App\Models\ServiceRole::min('id');
                $prevId = \App\Models\ServiceRole::where('id', '<', $svcrId)->max('id') ?? \App\Models\ServiceRole::max('id');

                $links = array_merge($links, [
                    ['href' => route('svcroles.manage.id', ['id' => $prevId]), 'title' => __('Previous Service Role'), 'icon' => 'chevron_left', 'active' => false],
                    ['href' => route('svcroles.manage.id', ['id' => $nextId]), 'title' => __('Next Service Role'), 'icon' => 'chevron_right', 'active' => false],
                ]);
            @endphp
            <livewire:manage-service-role
                :links="$links"
                :serviceRoleId="$svcrId"
            />
        @elseif(request()->is('svcroles/requests'))
            @include('components.svcrole.requests')
        @elseif(request()->is('svcroles/audit-logs'))
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
