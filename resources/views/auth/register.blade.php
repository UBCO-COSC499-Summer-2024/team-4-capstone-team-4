<x-auth-layout>
    <section id="auth-register" class="auth-section active glass">
        <h1>Secure Register</h1>
        <form id="register-form" class="form" method="POST" action="{{ route('register') }}">
            @csrf
            
            <x-form-item>
                <x-form-icon icon="Email"/>
                <x-form-input type="text" id="email" name="email" placeholder="Email..."/>
            </x-form-item>
            <div class="form-group">
                <x-form-icon icon="id_card"/>
                <div class="grouped">
                    <x-form-item>
                        <x-form-input type="text" id="firstname" name="firstname" placeholder="Firstname..."/>
                    </x-form-item>
                    <x-form-item>
                        <x-form-input type="text" id="lastname" name="lastname" placeholder="Lastname..."/>
                    </x-form-item>
                </div>
            </div>
            <x-form-item>
                <x-form-icon icon="Password"/>
                <x-form-input type="password" id="password" name="password" placeholder="Password..."/>
            </x-form-item>
            <x-form-item>
                <x-form-icon icon="Password"/>
                <x-form-input type="password" id="password_confirmation" name="password_confirmation" placeholder="Confirm Password..."/>
            </x-form-item>
            <x-validation-errors name="password" />
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
