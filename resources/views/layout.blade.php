<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#050807">
    <title>EJ6 Garage</title>

    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body>

<header class="site-header">
    <div class="header-shell">
        <div class="brand-block">
            <a class="brand-mark" href="/" aria-label="EJ6 Garage dashboard">
                <span class="brand-code">EJ6</span>
                <span>
                    <strong>Garage</strong>
                    <small>G-82P-5 Civic Build Hub</small>
                </span>
            </a>
        </div>

        <div class="status-pill">
            SYSTEM ONLINE
        </div>

        <nav aria-label="Primary navigation">
            <a href="/" class="{{ request()->is('/') ? 'active' : '' }}">Dashboard</a>
            <a href="/maintenance" class="{{ request()->is('maintenance') ? 'active' : '' }}">Maintenance</a>
            <a href="/mods" class="{{ request()->is('mods') ? 'active' : '' }}">Mods</a>
            <a href="/parts" class="{{ request()->is('parts') ? 'active' : '' }}">Learn Parts</a>
            <a href="/gallery" class="{{ request()->is('gallery') ? 'active' : '' }}">Gallery</a>
            <a href="/calculator" class="{{ request()->is('calculator') ? 'active' : '' }}">Calculator</a>
            <a href="/inspection" class="{{ request()->is('inspection') ? 'active' : '' }}">Inspection Map</a>
        </nav>
    </div>
</header>

<div class="page-transition"></div>

<main class="container page-content">
    @yield('content')
</main>

<script src="{{ asset('js/site.js') }}"></script>

</body>
</html>
