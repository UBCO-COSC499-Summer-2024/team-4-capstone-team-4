<x-auth-layout>
    <!-- <x-validation-errors class="mb-4" /> -->
    <section id="auth-forgot-password" class="auth-section active glass">
        <h1>Forgot Password</h1>
        <form id="forgot-password-form" class="form" method="POST" action="{{ route('password.email') }}">
            @csrf

           <x-form-item>
                <x-form-icon icon="Email" />
                <input class="form-input" type="email" id="email" name="email" placeholder="Email...">
            </x-form-item>
           <x-form-item>
                <input class="form-input" type="submit" name="submit" value="Send Reset Link" />
            </x-form-item>
           <x-form-item>
                <span class="input-label">Remember your password? <a href="/login" data-type="tab" data-pg="login">Login</a></span>
            </x-form-item>
        </form>
    </section>
</x-auth-layout>
