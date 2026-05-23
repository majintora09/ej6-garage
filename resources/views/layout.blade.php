<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#050807">
    <title>{{ __('ui.dashboard.personal_garage') }}</title>

    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
@php
    $activeCarProfile = $carProfile ?? $currentCarProfile ?? null;

    if (! $activeCarProfile && auth()->check()) {
        try {
            if (\Illuminate\Support\Facades\Schema::hasTable('car_profiles')) {
                $activeCarProfile = auth()->user()->carProfile;
            }
        } catch (\Throwable $e) {
            $activeCarProfile = null;
        }
    }

    $defaultGarageTheme = '#8b5cf6';
    $themeColor = data_get($activeCarProfile, 'theme_color') ?: $defaultGarageTheme;
    $themeColor = strtolower($themeColor) === '#76ff9f' ? $defaultGarageTheme : $themeColor;
    $themeColor = preg_match('/^#[0-9A-Fa-f]{6}$/', $themeColor) ? $themeColor : $defaultGarageTheme;
    $secondaryThemeColor = data_get($activeCarProfile, 'secondary_theme_color') ?: '#38bdf8';
    $secondaryThemeColor = preg_match('/^#[0-9A-Fa-f]{6}$/', $secondaryThemeColor) ? $secondaryThemeColor : '#38bdf8';
    [$themeRed, $themeGreen, $themeBlue] = sscanf($themeColor, '#%02x%02x%02x');
    $profileColorName = data_get($activeCarProfile, 'color_name') ?: __('ui.common.unknown_color');
    $profileColorCode = data_get($activeCarProfile, 'color_code') ?: __('ui.common.no_color_code');
    $profileChassis = data_get($activeCarProfile, 'chassis') ?: 'GARAGE';
@endphp
<body
    style="--theme: {{ $themeColor }}; --theme-rgb: {{ $themeRed }}, {{ $themeGreen }}, {{ $themeBlue }}; --secondary-theme: {{ $secondaryThemeColor }};"
>

<header class="site-header">
    <div class="header-shell">
        <div class="brand-block">
            <a class="brand-mark" href="/" aria-label="{{ __('ui.nav.dashboard') }}">
                <span class="brand-code">{{ strtoupper(substr($profileChassis, 0, 4)) }}</span>
                <span>
                    <strong>{{ __('ui.nav.garage') }}</strong>
                    <small>{{ $profileColorCode }} {{ $profileColorName }} {{ __('ui.nav.build_hub') }}</small>
                </span>
            </a>
        </div>

        <div class="status-pill">
            {{ __('ui.nav.system_online') }}
        </div>

        @auth
            <form class="logout-form" action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="logout-button">{{ __('ui.nav.logout') }}</button>
            </form>
        @endauth

        <nav aria-label="{{ __('ui.nav.primary') }}">
            <a href="/" class="{{ request()->is('/') ? 'active' : '' }}">{{ __('ui.nav.dashboard') }}</a>
            <a href="/cars" class="{{ request()->is('cars') ? 'active' : '' }}">{{ __('ui.nav.manage_cars') }}</a>
            <a href="/garage/details" class="{{ request()->is('garage/details') || request()->is('garage/setup') ? 'active' : '' }}">{{ __('ui.nav.garage_details') }}</a>
            <a href="/maintenance" class="{{ request()->is('maintenance') ? 'active' : '' }}">{{ __('ui.nav.maintenance') }}</a>
            <a href="/mods" class="{{ request()->is('mods') ? 'active' : '' }}">{{ __('ui.nav.mods') }}</a>
            <a href="/parts" class="{{ request()->is('parts') ? 'active' : '' }}">{{ __('ui.nav.learn_parts') }}</a>
            <a href="/gallery" class="{{ request()->is('gallery') ? 'active' : '' }}">{{ __('ui.nav.gallery') }}</a>
            <a href="/timeline" class="{{ request()->is('timeline') ? 'active' : '' }}">{{ __('ui.nav.timeline') }}</a>
            <a href="/calculator" class="{{ request()->is('calculator') ? 'active' : '' }}">{{ __('ui.nav.calculator') }}</a>
            <a href="/inspection" class="{{ request()->is('inspection') ? 'active' : '' }}">{{ __('ui.nav.inspection_map') }}</a>
        </nav>
    </div>
</header>

<div class="page-transition"></div>

<main class="container page-content">
    @yield('content')
</main>

<script src="{{ asset('js/site.js') }}"></script>
@include('partials.language-switcher')

</body>
</html>
