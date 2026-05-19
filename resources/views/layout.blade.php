<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EJ6 Garage</title>

    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body>

<div class="background-orb orb-one"></div>
<div class="background-orb orb-two"></div>

<header>
    <div class="header-top">
        <div>
            <h1>EJ6 Garage</h1>
            <p>G-82P-5 Dark Green Civic Build Hub</p>
        </div>

        <div class="status-pill">
            SYSTEM ONLINE
        </div>
    </div>

    <nav>
        <a href="/">Dashboard</a>
        <a href="/maintenance">Maintenance</a>
        <a href="/mods">Mods</a>
        <a href="/parts">Learn Parts</a>
        <a href="/gallery">Gallery</a>
        <a href="/calculator">Calculator</a>
        <a href="/inspection">Inspection Map</a>
    </nav>
</header>

<div class="page-transition"></div>

<main class="container page-content">
    @yield('content')
</main>

<script src="{{ asset('js/site.js') }}"></script>

</body>
</html>
