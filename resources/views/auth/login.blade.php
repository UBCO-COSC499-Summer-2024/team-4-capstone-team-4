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
            <x-validation-errors name="password" />
            <x-form-item>
                <span class="input-label"><a href="/forgot-password" data-type="tab" data-pg="forgot-password">Forgot Password</a></span>
            </x-form-item>
            <x-form-item>
                <x-form-input type="checkbox" name="rememberme" value="Remember Me"/><span class="input-label">Remember Me</span>
            </x-form-item>
            <x-form-item>
                <x-form-input type="submit" name="submit" value="Login" />
            </x-form-item>
            <x-form-item>
                <span class="input-label">Don't have an account? <a href="/register" data-type="tab" data-pg="register">Register</a></span>
            </x-form-item>
        </form>
    </section>
</x-auth-layout>
