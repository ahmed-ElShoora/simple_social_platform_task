<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-100">
            @include('layouts.navigation')

            <!-- Page Heading -->
            @isset($header)
                <header class="bg-white shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <!-- Page Content -->
            <main>
                {{ $slot }}
            </main>
            <div id="flash-message" style="position: absolute;width:25%;top: 10px;right: 10px;z-index: 9999">
                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ session('error') }}
                    </div>
                @endif
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                    </div>
                @endif
            </div>
        </div>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
        @if(session('error') || session('success'))
            <script>
                setTimeout(() => {
                    const flash = document.getElementById('flash-message');
                    if (flash) {
                        flash.style.display = 'none';
                    }
                }, 5000); 
            </script>
        @endif
        @if(auth()->check())
            <script>
                document.addEventListener('DOMContentLoaded', () => {
                    const userId = {{ auth()->id() }};
                    window.Echo.private(`users.${userId}`)
                    .listen('.like', (e) => {
                        console.log(e.payload);
                        showNotification(e.payload,'like');
                    });
                });
                document.addEventListener('DOMContentLoaded', () => {
                    const userId = {{ auth()->id() }};
                    window.Echo.private(`users.${userId}`)
                    .listen('.comment', (e) => {
                        console.log(e.payload);
                        showNotification(e.payload,'comment');
                    });
                });
                document.addEventListener('DOMContentLoaded', () => {
                    const userId = {{ auth()->id() }};
                    window.Echo.private(`users.${userId}`)
                    .listen('.friend', (e) => {
                        console.log(e.payload);
                        showNotification(e.payload,'friend');
                    });
                });
                function showNotification(payload,from) {
                    const container = document.createElement('div');
                    container.className = 'alert alert-info shadow';
                    container.style.position = 'absolute';
                    container.style.top = '10px';
                    container.style.right = '10px';
                    container.style.zIndex = 9999;
                    container.style.minWidth = '25%';
                    if(from === 'like'){
                        container.innerHTML = payload.actor_name + " liked your post";
                    }else if(from === 'comment'){
                        container.innerHTML = payload.actor_name + " commented on your post: " + payload.comment;
                    }else if(from === 'friend'){
                        container.innerHTML = payload.actor_name + " sent you a friend request";
                    }

                    document.body.appendChild(container);

                    setTimeout(() => {
                        container.classList.add('fade');
                        container.style.opacity = '0';
                        setTimeout(() => container.remove(), 500);
                    }, 5000);
                }
            </script>
        @endif

    </body>
</html>
