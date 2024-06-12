@php
$sidebarItems = [
    ['icon' => 'dashboard', 'route' => 'dashboard', 'title' => 'Dashboard'],
    ['icon' => 'notifications', 'route' => 'notifications', 'title' => 'Notifications'],
    ['icon' => 'leaderboard', 'route' => 'leaderboard', 'title' => 'Leaderboard'],
    ['icon' => 'settings', 'route' => 'settings', 'title' => 'Settings'],
    ['icon' => 'logout', 'route' => 'logout', 'title' => 'Logout'],
];

// add to $items
if (isset($items)) {
    $sidebarItems = array_merge($sidebarItems, $items);
}
@endphp

<aside class="sidebar glass">
    @foreach($sidebarItems as $item)
        <x-sidebar-item icon="{{ $item['icon'] }}" route="{{ $item['route'] }}" title="{{ $item['title'] }}" />
    @endforeach
</aside>
