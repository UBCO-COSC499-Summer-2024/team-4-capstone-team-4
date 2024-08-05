@php

$user = auth()->user();
$sidebarItems = [
    ['icon' => 'dashboard', 'href' => route('dashboard'), 'title' => 'Dashboard'],
    // ['icon' => 'bar_chart', 'href' => '/performance', 'title' => 'Performance'],
    ['icon' => 'list', 'href' => route('courses.details.id', ['user' => $user->id]), 'title' => 'Courses & TAs'],
    ['icon' => 'work_history', 'href' => '/svcroles', 'title' => 'Service Roles'],
    //['icon' => 'leaderboard', 'href' => 'leaderboard', 'title' => 'Leaderboard'],
    //['icon' => 'groups', 'href' => '/staff', 'title' => 'Staff'],
];

// add to $items
if (isset($items)) {
    $sidebarItems = array_merge($sidebarItems, $items);
}

//leaderboard shows for all roles except for instructor, if a user is only admin, it's not visible
if($user->hasRoles(['dept_head', 'dept_staff'])) {
    $sidebarItems = array_merge($sidebarItems, [
        ['icon' => 'leaderboard', 'href' => route('leaderboard'), 'title' => 'Leaderboard'],
    ]);
}

if($user->hasRoles(['dept_head', 'admin'])) {
    $sidebarItems = array_merge($sidebarItems, [
        ['icon' => 'browse_activity', 'href' => '/audits', 'title' => 'Audit Logs'],
    ]);
}
if($user->hasRoles(['dept_head', 'admin'])) {
    $sidebarItems = array_merge($sidebarItems, [
        ['icon' => 'priority', 'href' => '/requests', 'title' => 'Requests'],
        ['icon' => 'database', 'href' => 'http://localhost:5050', 'title' => 'Admin', 'target' => '_blank'],
    ]);
}
@endphp

<aside class="sidebar glass" id="sidebar">
    @foreach($sidebarItems as $item)
        <x-sidebar-item icon="{{ $item['icon'] }}" href="{{ $item['href'] }}" title="{{ $item['title'] }}" target="{{ $item['target'] ?? '' }}" />
    @endforeach

    <!-- bottom -->
    <div class="sidebar-item-group bottom">
        <hr>
        <x-sidebar-item icon="help" href="{{ route('help') }}" title="{{ __('Help') }}" />
        <x-sidebar-item icon="settings" href="{{ route('profile.show') }}" title="{{ __('Settings') }}" />
        <form method="POST" action="{{ route('logout') }}" x-data>
            @csrf
            <div class="sidebar-item">
                <x-link class="sidebar-link" href="{{ route('logout') }}" icon="logout" title="{{ __('Log Out') }}" @click.prevent="$root.submit();" />
            </div>
        </form>
    </div>
</aside>
