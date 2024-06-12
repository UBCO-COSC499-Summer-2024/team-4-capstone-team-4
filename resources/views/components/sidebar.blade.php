@php
$sidebarItems = [
    ['icon' => 'dashboard', 'route' => 'dashboard', 'title' => 'Dashboard'],
    ['icon' => 'person', 'route' => 'profile', 'title' => 'Profile'],
    ['icon' => 'groups', 'route' => 'staff', 'title' => 'Staff'],
    ['icon' => 'bar_chart', 'route' => 'performance', 'title' => 'Performance'],
    ['icon' => 'notifications', 'route' => 'notifications', 'title' => 'Notifications'],
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
