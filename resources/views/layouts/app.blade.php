<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'WorshipSync' }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    <div class="shell">
        <header class="topbar">
            <a class="brand" href="{{ route('welcome') }}">WorshipSync</a>

            <nav class="topnav">
                @auth
                    <a href="{{ route('dashboard') }}">Dashboard</a>
                    <a href="{{ route('songs.index') }}">Songs</a>
                    <a class="button ghost" href="{{ route('songs.create') }}">New Song</a>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="button subtle">Logout</button>
                    </form>
                @else
                    <a class="button" href="{{ route('google.redirect') }}">Sign in with Google</a>
                @endauth
            </nav>
        </header>

        @if (session('status'))
            <div class="flash">{{ session('status') }}</div>
        @endif

        <main class="content">
            @yield('content')
        </main>
    </div>
</body>
</html>
