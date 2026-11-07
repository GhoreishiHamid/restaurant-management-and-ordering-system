<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Restaurant') | Restaurant Management</title>
    <link rel="stylesheet" href="{{ asset('css/restaurant.css') }}">
</head>
<body>
    <header class="site-header">
        <div class="container nav">
            <a class="brand" href="{{ route('menu.index') }}">Table &amp; Thyme<span>.</span></a>
            <nav aria-label="Main navigation" class="nav-links">
                <a href="{{ route('menu.index') }}">Menu</a>
                @auth
                    <a href="{{ route('orders.index') }}">My orders</a>
                    <span class="hello">Hi, {{ auth()->user()->name }}</span>
                    <form method="post" action="{{ route('logout') }}">@csrf <button class="nav-button" type="submit">Log out</button></form>
                @else
                    <a href="{{ route('login') }}">Log in</a>
                    <a class="pill" href="{{ route('register') }}">Sign up</a>
                @endauth
            </nav>
        </div>
    </header>
    <main class="container">
        @if(session('success')) <div class="alert success" role="status">{{ session('success') }}</div> @endif
        @if($errors->any())
            <div class="alert error" role="alert"><strong>Please review:</strong>
                <ul>@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
            </div>
        @endif
        @yield('content')
    </main>
    <footer class="footer container">Restaurant Management &amp; Ordering System · Laravel &amp; MariaDB · Student research portfolio</footer>
</body>
</html>
