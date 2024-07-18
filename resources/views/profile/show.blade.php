<x-app-layout>
    <div class="content">
        {{-- <h2 class="mx-auto text-xl font-semibold leading-tight text-gray-800 max-w-7xl sm:px-6 lg:px-8">
            {{ __('Profile') }}
        </h2> --}}
        <div class="py-10 mx-auto max-w-7xl sm:px-6 lg:px-8">
            @if (Laravel\Fortify\Features::canUpdateProfileInformation())
                @livewire('profile.update-profile-information-form')

                <x-section-border />
            @endif

            @if (Laravel\Fortify\Features::enabled(Laravel\Fortify\Features::updatePasswords()))
                <div class="mt-10 sm:mt-0">
                    @livewire('profile.update-password-form')
                </div>

                <x-section-border />
            @endif

            {{-- <div class="mt-10 sm:mt-0">
                @livewire('profile.auth-methods-update')
                <x-profile.auth-methods-update :authMethods="$authMethods" />
            </div> --}}

            <div class="mt-10 sm:mt-0">
                @livewire('profile.preferences')
            </div>

            <x-section-border />

            @if (Laravel\Fortify\Features::canManageTwoFactorAuthentication())
                <div class="mt-10 sm:mt-0">
                    @livewire('profile.two-factor-authentication-form')
                </div>

                <x-section-border />
            @endif

            <div class="mt-10 sm:mt-0">
                @livewire('profile.logout-other-browser-sessions-form')
            </div>

            @if (Laravel\Jetstream\Jetstream::hasAccountDeletionFeatures())
                <x-section-border />

                <div class="mt-10 sm:mt-0">
                    @livewire('profile.delete-user-form')
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
