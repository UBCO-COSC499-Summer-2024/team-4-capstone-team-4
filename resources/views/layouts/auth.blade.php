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
        <x-auth-header />
        <main>
            <div class="auth-container">
               {{ $slot }}
            </div>
        </main>
    </body>
</html>
