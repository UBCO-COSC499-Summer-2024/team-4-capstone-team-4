<x-auth-layout>
    <!-- <x-validation-errors class="mb-4" /> -->
    <section id="auth-reset-password" class="auth-section active glass">
        <h1>Reset Password</h1>
        <form id="reset-password-form" class="form" method="POST" action="{{ route('password.update') }}">
            @csrf

            <x-form-item>
                <x-form-icon icon="Password"/>
                <x-form-input type="password" id="password" name="password" placeholder="New Password..."/>
            </x-form-item>
            <x-form-item>
                <x-form-icon icon="Password"/>
                <x-form-input type="password" id="password_confirmation" name="password_confirmation" placeholder="Confirm New Password..."/>
            </x-form-item>
            <x-form-item>
                <x-form-input type="submit" name="submit" value="Reset Password" />
            </x-form-item>
            <x-form-item>
                <span class="input-label">Back to <a href="/login" data-type="tab" data-pg="login">Login</a></span>
            </x-form-item>
        </form>
    </section>
</x-auth-layout>
