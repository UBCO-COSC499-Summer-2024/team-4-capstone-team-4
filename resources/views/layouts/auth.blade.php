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
                <h1 class="nos">Insight</h1>
            </section>
            <section id="header-auth">
                <nav class="menu nos">
                    <div class="menu-item" data-type="tab" data-pg="login">
                        <span class="menu-item-title"><a href="/login">Login</a></span>
                        <span class="material-symbols-outlined">login</span>
                    </div>
                    <div class="menu-item" data-type="tab" data-pg="register">
                        <span class="menu-item-title"><a href="/register">Register</a></span>
                        <span class="material-symbols-outlined">person_add</span>
                    </div>
            </section>
        </header>
        <main>
            <div class="auth-container">
               {{ $slot }}
            </div>
        </main>
    </body>
</html>
