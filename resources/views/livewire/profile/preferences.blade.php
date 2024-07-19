@php
    function timezones() {
        return timezone_identifiers_list();
    }
    function locales() {
        return [
            'en' => 'English',
            'en_GB' => 'English (United Kingdom)',
            'en_US' => 'English (United States)',
            'es_MX' => 'Spanish (Mexico)',
            'es_ES' => 'Spanish (Spain)',
            'fr_FR' => 'French (France)'
    ];
    }

    function languages() {
        return [
            'en' => 'English',
            'es' => 'Spanish',
            'fr' => 'French'
        ];
    }
@endphp
<x-action-section class="settings-section">
    <x-slot name="title">
        {{ __('Preferences') }}
    </x-slot>

    <x-slot name="description">
        {{ __('Manage your preferences.') }}
    </x-slot>

    <x-slot name="content">
        <div class="text-lg font-medium text-gray-900">
            {{ __('You can manage your preferences here.') }}
        </div>

        {{-- user.settings --}}
        <div class="mt-5 space-y-6">
            <div class="flex justify-between items center">
                <div class="flex items center">
                    <div class="ml-4">
                        <div class="text-sm font-medium text-gray-900">
                            {{ __('Dark Mode') }}
                        </div>

                        <div class="text-sm text-gray-500">
                            {{ __('Select your theme.') }}
                        </div>
                    </div>
                </div>

                <div>
                    <x-dropdown :align="'right'">
                        <x-slot name="trigger">
                            <button class="flex items-center">
                                {{-- <span>{{ __('Select theme') }}</span> --}}
                                {{-- default from db --}}
                                <span>{{ Str::ucfirst($theme) }}</span>
                                <span >
                                    <span class="flex justify-center w-5 h-5 p-0 m-0 items center material-symbols-outlined icon">expand_more</span>
                                </span>
                            </button>
                        </x-slot>

                        <x-slot name="content">
                            @php
                                $themes = [
                                    'light' => 'Light',
                                    'dark' => 'Dark'
                                ];
                                $selectedTheme = 'light';
                            @endphp

                            @foreach ($themes as $value => $label)
                                <button
                                wire:click="setTheme('{{ $value }}')" class="block w-full px-4 py-2 text-sm text-left text-gray-700 hover:bg-gray-100 hover:text-gray-900" role="menuitem"
                                >
                                    {{ $label }}
                                </button>
                            @endforeach
                        </x-slot>
                    </x-dropdown>
                </div>
            </div>

            {{-- <div class="flex justify-between items center">
                <div class="flex items center">
                    <div class="ml-4">
                        <div class="text-sm font-medium text-gray-900">
                            {{ __('Notifications') }}
                        </div>

                        <div class="text-sm text-gray-500">
                            {{ __('Toggle notifications.') }}
                        </div>
                    </div>
                </div>

                <div>
                    <x-button wire:click="toggleNotifications" wire:loading.attr="disabled">
                        {{ __('Toggle') }}
                    </x-button>
                </div>
            </div> --}}

            {{-- timezone, locale, language --}}
            <div class="flex justify-between items center">
                <div class="flex items center">
                    <div class="ml-4">
                        <div class="text-sm font-medium text-gray-900">
                            {{ __('Timezone') }}
                        </div>

                        <div class="text-sm text-gray-500">
                            {{ __('Select your timezone.') }}
                        </div>
                    </div>
                </div>
                <div>
                    <x-dropdown :align="'right'" width="64">
                        <x-slot name="trigger">
                            <button class="flex items-center">
                                {{-- <span>{{ __('Select your timezone.') }}</span> --}}
                                <span>{{ $timezone }}</span>
                                <span >
                                    <span class="flex items-center justify-center w-5 h-5 p-0 m-0 material-symbols-outlined icon">expand_more</span>
                                </span>
                            </button>
                        </x-slot>

                        <x-slot name="content">
                            @foreach (timezones() as $value => $label)
                                <button
                                wire:click="setTimezone('{{ $value }}')" class="block w-full px-4 py-2 text-sm text-left text-gray-700 hover:bg-gray-100 hover:text-gray-900" role="menuitem"
                                >
                                    {{ $label }}
                                </button>
                            @endforeach
                        </x-slot>
                    </x-dropdown>
                </div>
            </div>


            <div class="flex justify-between items center">
                <div class="flex items center">
                    <div class="ml-4">
                        <div class="text-sm font-medium text-gray-900">
                            {{ __('Locale') }}
                        </div>

                        <div class="text-sm text-gray-500">
                            {{ __('Select your locale.') }}
                        </div>
                    </div>
                </div>

                <div>
                    <x-dropdown :align="'right'">
                        <x-slot name="trigger">
                            <button class="flex items-center">
                                {{-- <span>{{ __('Select your locale.') }}</span> --}}
                                <span>{{ locales()[$locale] }}</span>
                                <span >
                                    <span class="flex items-center justify-center w-5 h-5 p-0 m-0 material-symbols-outlined icon">expand_more</span>
                                </span>
                            </button>
                        </x-slot>

                        <x-slot name="content">
                            @foreach (locales() as $value => $label)
                                <button
                                wire:click="setLocale('{{ $value }}')" class="block w-full px-4 py-2 text-sm text-left text-gray-700 hover:bg-gray-100 hover:text-gray-900" role="menuitem"
                                >
                                    {{ $label }}
                                </button>
                            @endforeach
                        </x-slot>
                    </x-dropdown>
                </div>
            </div>

            <div class="flex justify-between items center">
                <div class="flex items center">
                    <div class="ml-4">
                        <div class="text-sm font-medium text-gray-900">
                            {{ __('Language') }}
                        </div>

                        <div class="text-sm text-gray-500">
                            {{ __('Select your language.') }}
                        </div>
                    </div>
                </div>

                <div>
                    <x-dropdown :align="'right'">
                        <x-slot name="trigger">
                            <button class="flex items-center">
                                {{-- <span>{{ __('Select your Language.') }}</span> --}}
                                <span>{{ languages()[$language] }}</span>
                                <span >
                                    <span class="flex items-center justify-center w-5 h-5 p-0 m-0 material-symbols-outlined icon">expand_more</span>
                                </span>
                            </button>
                        </x-slot>

                        <x-slot name="content">
                            @foreach (languages() as $value => $label)
                                <button
                                wire:click="setLanguage('{{ $value }}')" class="block w-full px-4 py-2 text-sm text-left text-gray-700 hover:bg-gray-100 hover:text-gray-900" role="menuitem"
                                >
                                    {{ $label }}
                                </button>
                            @endforeach
                        </x-slot>
                    </x-dropdown>
                </div>
            </div>

            <div>
                {{-- custom use a foreach loop as key=> value --}}
                <div class="flex flex-col space-y-6">
                    <div class="flex items center">
                        <div class="ml-4">
                            <div class="text-sm font-medium text-gray-900">
                                {{ __('Custom') }}
                            </div>

                            <div class="text-sm text-gray-500">
                                {{ __('Select your custom preference.') }}
                            </div>
                        </div>
                    </div>

                    <div>
                        @forelse ($custom as $key => $value)
                            <div class="flex justify-between items center">
                                <div class="flex items center">
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900">
                                            {{ $key }}
                                        </div>

                                        <div class="text-sm text-gray-500">
                                            {{ $value }}
                                        </div>
                                    </div>
                                </div>

                                <div>
                                    <x-dropdown :align="'right'">
                                        <x-slot name="trigger">
                                            <button class="flex items-center">
                                                <span>{{ __('Select your custom preference.') }}</span>
                                                <span >
                                                    <span class="flex justify-center w-5 h-5 p-0 m-0 items center material-symbols-outlined icon">expand_more</span>
                                                </span>
                                            </button>
                                        </x-slot>

                                        <x-slot name="content">
                                            @foreach ($custom as $value => $label)
                                                <button
                                                wire:click="setCustom('{{ $value }}')" class="block w-full px-4 py-2 text-sm text-left text-gray-700 hover:bg-gray-100 hover:text-gray-900" role="menuitem"
                                                >
                                                    {{ $label }}
                                                </button>
                                            @endforeach
                                        </x-slot>
                                    </x-dropdown>
                                </div>
                            </div>
                        @empty
                            <div class="text-sm text-gray-600">
                                {{ __('No custom preferences.') }}
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <div>
                <x-button wire:click="savePreferences" wire:loading.attr="disabled">
                    {{ __('Save') }}
                </x-button>
            </div>
        </div>

    </x-slot>
</x-action-section>

