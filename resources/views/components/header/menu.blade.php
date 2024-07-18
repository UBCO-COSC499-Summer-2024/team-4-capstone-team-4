<section id="header-menu">
    <nav class="menu nos">
        <x-link icon="notifications" type="modal" page="sync" />
        <x-link icon="brightness_4" type="toggle" page="dark-mode" />
        <x-link icon="settings" type="page" page="settings" href="{{ route('profile.show') }}" />
    </nav>
    <x-header.user />
</section>
