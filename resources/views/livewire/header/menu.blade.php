<section id="header-menu">
    <nav class="menu nos">
        <x-link icon="notifications" type="modal" page="sync" />
        <x-link icon="{{ $darkMode ? 'light_mode' : 'dark_mode' }}" type="toggle" page="dark-mode" wire:click.prevent="toggleDarkMode" data-tippy-content="Toggle Dark Mode" />
        <x-link icon="settings" type="page" page="settings" href="{{ route('profile.show') }}" />
    </nav>
    <x-header.user />
</section>
