@php

$user = auth()->user();
$sidebarItems = [
    ['icon' => 'dashboard', 'href' => route('dashboard'), 'title' => 'Dashboard'],
    // ['icon' => 'bar_chart', 'href' => '/performance', 'title' => 'Performance'],
    ['icon' => 'list', 'href' => route('courses.details.id', ['user' => $user->id]), 'title' => 'Courses']
    //['icon' => 'leaderboard', 'href' => 'leaderboard', 'title' => 'Leaderboard'],
    //['icon' => 'groups', 'href' => '/staff', 'title' => 'Staff'],

];

// add to $items
if (isset($items)) {
    $sidebarItems = array_merge($sidebarItems, $items);
}
@endphp

<aside class="sidebar glass" id="sidebar">
    @foreach($sidebarItems as $item)
        <x-sidebar-item icon="{{ $item['icon'] }}" href="{{ $item['href'] }}" title="{{ $item['title'] }}" />
    @endforeach

    <!-- bottom -->
    <div class="sidebar-item-group bottom">
        <hr>
        <x-sidebar-item icon="notifications" href="{{ route('notifications') }}" title="{{ __('Notifications') }}" />
        <x-sidebar-item icon="help" href="{{ route('help') }}" title="{{ __('Help') }}" />
        <x-sidebar-item icon="settings" href="{{ route('profile.show') }}" title="{{ __('Settings') }}" />
        <form method="POST" action="{{ route('logout') }}" x-data>
            @csrf
            <div class="sidebar-item">
                <x-link class="text-xl sidebar-link" href="{{ route('logout') }}" icon="logout" title="{{ __('Log Out') }}" @click.prevent="$root.submit();" />
            </div>
        </form>
    </div>
</aside>
