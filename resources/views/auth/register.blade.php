<x-auth-layout>
    <section id="auth-register" class="auth-section active glass">
        <h1>Secure Register</h1>
        <form id="register-form" class="form" method="POST" action="{{ route('register') }}">
            @csrf
            
            <x-form-item class="flex flex-col">
                <div class="flex items-center">
                    <x-form-icon icon="Email" class="mr-2"/>
                    <x-form-input type="text" id="email" name="email" placeholder="Email..." value="{{ old('email') }}"/>
                </div>
                <x-input-error for="email" class="mt-2" />
            </x-form-item>
            <div class="form-group">
                <x-form-icon icon="id_card"/>
                <div class="grouped">
                    <x-form-item class="flex flex-col">
                        <div class="flex items-center">
                            <x-form-input type="text" id="firstname" name="firstname" placeholder="Firstname..." value="{{ old('firstname') }}"/>
                        </div>
                        <x-input-error for="firstname" class="mt-2" />
                    </x-form-item>
                    <x-form-item class="flex flex-col">
                        <div class="flex items-center">
                            <x-form-input type="text" id="lastname" name="lastname" placeholder="Lastname..." value="{{ old('lastname') }}"/>
                        </div>
                        <x-input-error for="lastname" class="mt-2" />
                    </x-form-item>
                </div>
            </div>
            <x-form-item class="flex flex-col">
                <div class="flex items-center">
                    <x-form-icon icon="Password" class="mr-2"/>
                    <x-form-input type="password" id="password" name="password" placeholder="Password..."/>
                </div>
                <x-input-error for="password" class="mt-2" />
            </x-form-item>
            <x-form-item class="flex flex-col">
                <div class="flex items-center">
                    <x-form-icon icon="Password" class="mr-2"/>
                    <x-form-input type="password" id="password_confirmation" name="password_confirmation" placeholder="Confirm Password..."/>
                </div>
                <x-input-error for="password_confirmation" class="mt-2" />
            </x-form-item>
           
            <x-form-item>
                <x-form-input type="submit" name="submit" value="Register" />
            </x-form-item>
            <x-form-item>
                <span class="input-label">Already have an account?<x-link href="{{ route('login') }}" title='Login' /></span>
            </x-form-item>
        </form>
    </section>
    <section id="auth-provider" class="auth-section active glass">
        <h1>Or Register With</h1>
        @if (session('provider-error'))
            <div class="alert alert-danger">
                {{ session('provider-error') }}
            </div>
        @endif
        <div class="provider-list">
            <x-link href="{{ route('auth.provider', 'google') }}" title="Google" class="auth-provider-link">
                <x-logos.google/>
            </x-link>
        </div>
    </section>
</x-auth-layout>
