<!DOCTYPE HTML>
<html>
    <head>
        <title>Authentication | Insight</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />
        <link href="{{ asset('css/auth.css') }}" rel="stylesheet">
    </head>
    <body>
        <header class="auth">
            <section id="header-brand">
                <div id="header-img">
                    <img src="https://iconape.com/wp-content/files/sf/192229/svg/192229.svg" alt="UBC Logo">
                </div>
                <h1 class="nos">Insighttt</h1>
            </section>
            <section id="header-auth">
                <nav class="menu nos">
                    <div class="menu-item" data-type="tab" data-pg="login">
                        <span class="menu-item-title">Login</span>
                        <span class="material-symbols-outlined">login</span>
                    </div>
                    <div class="menu-item" data-type="tab" data-pg="register">
                        <span class="menu-item-title">Register</span>
                        <span class="material-symbols-outlined">person_add</span>
                    </div>
            </section>
        </header>
        <main>
            <div class="auth-container">
                <section id="auth-login" class="auth-section active glass">
                    <h1>Secure Login</h1>
                    <form id="login-form" class="form">
                        @csrf
                        <div class="form-item">
                            <span class="material-symbols-outlined icon">Email</span>
                            <input class="form-input" type="text" name="email" placeholder="Email...">
                        </div>
                        <div class="form-item">
                            <span class="material-symbols-outlined icon">Password</span>
                            <input class="form-input" type="password" name="password" placeholder="Password...">
                        </div>
                        <div class="form-item">
                            <span class="material-symbols-outlined icon">key</span>
                            <input class="form-input" type="text" name="access_code" placeholder="Access Code...">
                        </div>
                        <div class="form-item">
                            <span class="input-label"><a href="#forgotpassword" data-type="tab" data-pg="forgot-password">Forgot Password</a></span>
                        </div>
                        <div class="form-item">
                            <input class="form-input" type="checkbox" name="rememberme" value="Remember Me"><span class="input-label">Remember Me</span>
                        </div>
                        <div class="form-item">
                            <input class="form-input" type="submit" name="submit" value="Login" />
                        </div>
                        <div class="form-item">
                            <span class="input-label">Don't have an account? <a href="#register" data-type="tab" data-pg="register">Register</a></span>
                        </div>
                    </form>
                </section>
                <section id="auth-register" class="auth-section glass">
                    <h1>Secure Register</h1>
                    <form id="register-form" class="form">
                        <div class="form-item">
                            <span class="material-symbols-outlined icon">Email</span>
                            <input class="form-input" type="text" name="email" placeholder="Email...">
                        </div>
                        <div class="form-group">
                            <span class="material-symbols-outlined icon">id_card</span>
                            <div class="grouped">
                                <div class="form-item">
                                    <input class="form-input" type="text" name="firstname" placeholder="Firstname...">
                                </div>
                                <div class="form-item">
                                    <input class="form-input" type="text" name="lastname" placeholder="Lastname...">
                                </div>
                            </div>
                        </div>
                        <div class="form-item">
                            <span class="material-symbols-outlined icon">Password</span>
                            <input class="form-input" type="password" name="password" placeholder="Password...">
                        </div>
                        <div class="form-item">
                            <span class="material-symbols-outlined icon">Password</span>
                            <input class="form-input" type="password" name="password-confirm" placeholder="Confirm Password...">
                        </div>
                        <div class="form-item">
                            <input class="form-input" type="submit" name="submit" value="Register" />
                        </div>
                        <div class="form-item">
                            <span class="input-label">Already have an account? <a href="#login" data-type="tab" data-pg="login">Login</a></span>
                        </div>
                    </form>
                </section>
                <section id="auth-forgot-password" class="auth-section glass">
                    <h1>Forgot Password</h1>
                    <form id="forgot-password-form" class="form">
                        <div class="form-item">
                            <span class="material-symbols-outlined icon">Email</span>
                            <input class="form-input" type="text" name="email" placeholder="Email...">
                        </div>
                        <div class="form-item">
                            <input class="form-input" type="submit" name="submit" value="Send Reset Link" />
                        </div>
                        <div class="form-item">
                            <span class="input-label">Remember your password? <a href="#login" data-type="tab" data-pg="login">Login</a></span>
                        </div>
                    </form>
                </section>
                <section id="auth-reset-password" class="auth-section glass">
                    <h1>Reset Password</h1>
                    <form id="reset-password-form" class="form">
                        <div class="form-item">
                            <span class="material-symbols-outlined icon">Password</span>
                            <input class="form-input" type="password" name="new-password" placeholder="New Password...">
                        </div>
                        <div class="form-item">
                            <span class="material-symbols-outlined icon">Password</span>
                            <input class="form-input" type="password" name="confirm-new-password" placeholder="Confirm New Password...">
                        </div>
                        <div class="form-item">
                            <input class="form-input" type="submit" name="submit" value="Reset Password" />
                        </div>
                        <div class="form-item">
                            <span class="input-label">Back to <a href="#login" data-type="tab" data-pg="login">Login</a></span>
                        </div>
                    </form>
                </section>
            </div>
        </main>
        <script src="./js/auth.js"></script>
    </body>
</html>
