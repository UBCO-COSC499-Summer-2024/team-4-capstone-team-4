<x-auth-layout>
    <!-- <x-validation-errors class="mb-4" /> -->
    <section id="auth-forgot-password" class="auth-section active glass">
        <h1>Forgot Password</h1>
        <form id="forgot-password-form" class="form" method="POST" action="{{ route('password.email') }}">
            <div class="form-item">
                <span class="material-symbols-outlined icon">Email</span>
                <input class="form-input" type="text" id="email" name="email" placeholder="Email...">
            </div>
            <div class="form-item">
                <input class="form-input" type="submit" name="submit" value="Send Reset Link" />
            </div>
            <div class="form-item">
                <span class="input-label">Remember your password? <a href="/login" data-type="tab" data-pg="login">Login</a></span>
            </div>
        </form>
    </section>
</x-auth-layout>
