<x-auth-layout>
    <section id="auth-login" class="auth-section active glass">
        <h1>Secure Login</h1>
        <form id="login-form" class="form" method="POST" action="{{ route('login') }}">
            @csrf
            @if (session('status'))
                <div class="alert alert-success text-[#2d3b6b]">
                    {{ session('status') }}
                </div>
            @endif
            <x-form-item class="flex flex-col">
                <div class="flex items-center">
                    <x-form-icon icon="Email" class="mr-2"/>
                    <x-form-input type="text" id="email" name="email" placeholder="Email..."/>
                </div>
                <x-input-error for="email" class="mt-2" />
            </x-form-item>
            <x-form-item class="flex flex-col">
                <div class="flex items-center">
                    <x-form-icon icon="Password" class="mr-2"/>
                    <x-form-input type="password" id="password" name="password" placeholder="Password..."/>
                </div>
                <x-input-error for="password" class="mt-2" />
            </x-form-item>
            <x-form-item class="flex flex-col">
                <x-validation-errors/>
            </x-form-item>

            <x-form-item>
                <span class="input-label"><x-link href="/forgot-password" title="{{ __('Forgot Password') }}" /></span>
            </x-form-item>
            <x-form-item>
                <x-form-input type="checkbox" name="rememberme" value="Remember Me"/><span class="input-label">Remember Me</span>
            </x-form-item>
            <x-form-item>
                <x-form-input type="submit" name="submit" value="Login" />
            </x-form-item>
            <x-form-item>
                <span class="input-label">Don't have an account? <x-link href="{{ route('register') }}" title="Register" style="text-decoration:underline;"/></span>
            </x-form-item>
        </form>
    </section>
    <section id="auth-provider" class="auth-section active glass">
        <h1>Or Login With</h1>
        @if (session('provider-error'))
            <div class="alert alert-danger">
                {{ session('provider-error') }}
            </div>
        @endif
        <div class="provider-list">
            <x-link
                href="{{ route('auth.provider', ['provider' => 'google']) }}"
                title="Google" class="auth-provider-link">
                <x-logos.google/>
            </x-link>
        </div>
    </section>
</x-auth-layout>
