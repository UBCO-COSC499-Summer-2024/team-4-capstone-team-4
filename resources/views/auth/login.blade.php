<x-auth-layout>
    <section id="auth-login" class="auth-section active glass">
        <h1>Secure Login</h1>
        <form id="login-form" class="form" method="POST" action="{{ route('login') }}">
            @csrf

            <x-form-item>
                <x-form-icon icon="Email"/>
                <x-form-input type="text" id="email" name="email" placeholder="Email..." />
            </x-form-item>
            <x-form-item>
                <x-form-icon icon="Password"/>
                <x-form-input type="password" id="password" name="password" placeholder="Password..."/>
            </x-form-item>
            <x-form-item>
                <x-form-icon icon="key"/>
                <x-form-input type="text" id="access_code" name="access_code" placeholder="Access Code..."/>
            </x-form-item>

            <x-validation-errors/>
            
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
                <span class="input-label">Don't have an account? <x-link href="{{ route('register') }}" title="Register" /></span>
            </x-form-item>
        </form>
    </section>
    <section id="auth-provider">
        <h1>Or Login With</h1>
        <div class="provider">
            <x-link href="{{ route('login.provider', 'google') }}" title="Google" class="google"/>
            <!-- ubc cwl -->
            <x-link href="{{ route('login.provider', 'ubc') }}" title="UBC" class="ubc"/>
        </div>
    </section>
</x-auth-layout>
