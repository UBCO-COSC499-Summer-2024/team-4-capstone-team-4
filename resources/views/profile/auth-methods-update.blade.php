<x-action-section class="settings-section">
    <x-slot name="title">
        {{ __('Connected Accounts') }}
    </x-slot>

    <x-slot name="description">
        {{ __('Manage your connected accounts.') }}
    </x-slot>

    <x-slot name="content">
        <div class="max-w-xl text-sm text-gray-600">
            {{ __('You can connect your account with other services to make it easier to log in. If you no longer want to use a service, you can disconnect it from your account.') }}
        </div>

        {{-- user.authMethods --}}
        {{-- @if ($authMethods->isNotEmpty())
            <div class="mt-5 space-y-6">
                @foreach ($authMethods as $authMethod)
                    <div class="flex justify-between items center">
                        <div class="flex items center">
                            <div>
                                @if ($authMethod->provider === 'google')
                                    <x-google-icon class="w-8 h-8 text-gray-400" />
                                @elseif ($authMethod->provider === 'facebook')
                                    <x-facebook-icon class="w-8 h-8 text-gray-400" />
                                @endif
                            </div>

                            <div class="ml-4">
                                <div class="text-sm font-medium text-gray-900">
                                    {{ $authMethod->provider }}
                                </div>

                                <div class="text-sm text-gray-500">
                                    {{ $authMethod->email }}
                                </div>
                            </div>
                        </div>

                        <div>
                            <x-danger-button wire:click="confirmAuthMethodDeletion('{{ $authMethod->id }}')" wire:loading.attr="disabled">
                                {{ __('Disconnect') }}
                            </x-danger-button>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="mt-5">
                <div class="text-sm text-gray-600">
                    {{ __('No connected accounts.') }}
                </div>
            </div>
        @endif --}}
    </x-slot>
</x-action-section>
