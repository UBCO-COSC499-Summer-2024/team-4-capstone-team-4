<x-auth-layout>
    <section id="auth-two-factor" class="auth-section active glass">
    <h1>Two Factor Authentication</h1>
    <form id="two-factor-form" class="form" method="POST" action="{{ route('two-factor.login') }}">
        @csrf
        <x-form-item>
            <x-validation-errors class="mb-4 form-text" />
        </x-form-item>
        <div class="form-container" x-data="{ recovery: false }">
            <x-form-group>
                <x-form-item class="mb-4 text-sm text-gray-600" x-show="! recovery">
                    <p class="form-text">{{ __('Please confirm access to your account by entering the authentication code provided by your authenticator application.') }}</p>
                </x-form-item>

                <x-form-item class="mb-4 text-sm text-gray-600" x-cloak x-show="recovery">
                    <p class="form-text">{{ __('Please confirm access to your account by entering one of your emergency recovery codes.') }}</p>
                </x-form-item>
            </x-form-group>

            <x-form-group>
                <x-form-item x-show="!recovery">
                    <x-form-icon icon="{{ __('Key') }}"/>
                    <x-input class="form-input" type="text" id="code" name="code" x-ref="code" inputmode="numeric" autocomplete="one-time-code" autofocus placeholder="{{ __('Code...') }}" />
                </x-form-item>

                <x-form-item x-cloak x-show="recovery">
                    <x-form-icon icon="{{ __('Key') }}"/>
                    <x-input class="form-input" type="text" id="recovery_code" name="recovery_code" x-ref="recovery_code" autocomplete="one-time-code" placeholder="{{ __('Recovery Code...') }}" />
                </x-form-item>
            </x-form-group>
            <x-form-group>
                <x-form-group class="flex items-center">
                    <button type="button" class="text-sm text-gray-600 hover:text-gray-900 underline cursor-pointer"
                        x-show="! recovery"
                        x-on:click="
                            recovery = true;
                            $nextTick(() => {
                                $refs.recovery_code.focus()
                            })
                        ">
                        {{ __('Use a recovery code') }}
                    </button>
                    <button type="button" class="text-sm text-gray-600 hover:text-gray-900 underline cursor-pointer"
                        x-show="recovery"
                        x-cloak
                        x-on:click="
                            recovery = false;
                            $nextTick(() => {
                                $refs.code.focus()
                            })
                        ">
                        {{ __('Use an authentication code') }}
                    </button>
                </x-form-group>

                <x-form-item>
                    <x-form-input type="submit" name="submit" value="{{ __('Log in') }}" />
                </x-form-item>
            </x-form-group>
        </div>
    </form>
</x-auth-layout>