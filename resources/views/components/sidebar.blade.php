@php
$sidebarItems = [
    ['icon' => 'dashboard', 'href' => '/dashboard', 'title' => 'Dashboard'],
    ['icon' => 'notifications', 'href' => 'notifications', 'title' => 'Notifications'],
    ['icon' => 'leaderboard', 'href' => 'leaderboard', 'title' => 'Leaderboard'],
    ['icon' => 'person', 'href' => '/user/profile', 'title' => 'Profile'],
    ['icon' => 'groups', 'href' => 'staff', 'title' => 'Staff'],
    ['icon' => 'bar_chart', 'href' => 'performance', 'title' => 'Performance'],
    ['icon' => 'notifications', 'href' => 'notifications', 'title' => 'Notifications'],
    ['icon' => 'settings', 'href' => '/user/profile', 'title' => 'Settings'],
    ['icon' => 'logout', 'href' => '/logout', 'title' => 'Logout'],
];

// add to $items
if (isset($items)) {
    $sidebarItems = array_merge($sidebarItems, $items);
}
@endphp

<aside class="sidebar glass">
    @foreach($sidebarItems as $item)
        <x-sidebar-item icon="{{ $item['icon'] }}" href="{{ $item['href'] }}" title="{{ $item['title'] }}" />
    @endforeach
</aside>
