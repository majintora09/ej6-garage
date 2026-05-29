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
    $authUser = auth()->user();
    $authAvatar = $authUser?->avatar_path && \Illuminate\Support\Facades\Storage::disk('public')->exists($authUser->avatar_path)
        ? route('media.show', ['path' => $authUser->avatar_path])
        : null;
    $activePublicGarageUrl = $authUser && $activeCarProfile && $authUser->profile_slug && data_get($activeCarProfile, 'slug') && in_array(data_get($activeCarProfile, 'visibility'), ['public', 'unlisted'], true)
        ? route('public.garage', [$authUser->profile_slug, data_get($activeCarProfile, 'slug')])
        : null;
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

        @auth
            <div class="profile-menu-wrap">
                <div class="status-pill">{{ __('ui.nav.system_online') }}</div>
                <details class="profile-menu">
                    <summary aria-label="{{ __('ui.profile.menu') }}">
                        <span class="profile-avatar">
                            @if ($authAvatar)
                                <img src="{{ $authAvatar }}" alt="{{ $authUser->displayHandle() }}" loading="lazy">
                            @else
                                <span>{{ strtoupper(substr($authUser->displayHandle(), 0, 2)) }}</span>
                            @endif
                        </span>
                        <span class="profile-menu-name">{{ $authUser->displayHandle() }}</span>
                    </summary>
                    <div class="profile-dropdown">
                        <div class="profile-dropdown-head">
                            <span class="profile-avatar">
                                @if ($authAvatar)
                                    <img src="{{ $authAvatar }}" alt="{{ $authUser->displayHandle() }}" loading="lazy">
                                @else
                                    <span>{{ strtoupper(substr($authUser->displayHandle(), 0, 2)) }}</span>
                                @endif
                            </span>
                            <div>
                                <strong>{{ $authUser->displayHandle() }}</strong>
                                <small>{{ $activeCarProfile ? trim(data_get($activeCarProfile, 'make').' '.data_get($activeCarProfile, 'model')) : __('ui.common.not_set') }}</small>
                            </div>
                        </div>

                        <div class="profile-dropdown-list">
                            <a href="{{ route('dashboard') }}">{{ __('ui.profile.my_garage') }}</a>
                            @if ($authUser->profile_slug)
                                <a href="{{ route('public.profile', $authUser->profile_slug) }}">{{ __('ui.profile.public_profile') }}</a>
                            @endif
                            @if ($activePublicGarageUrl)
                                <a href="{{ $activePublicGarageUrl }}">{{ __('ui.public.view_public_garage') }}</a>
                                <button type="button" data-share-url="{{ $activePublicGarageUrl }}" data-copied-label="{{ __('ui.public.copied') }}" data-copy-prompt-label="{{ __('ui.public.copy_prompt') }}">{{ __('ui.public.copy_public_link') }}</button>
                            @elseif ($activeCarProfile)
                                <a href="{{ route('cars.index') }}">{{ __('ui.public.make_public_to_share') }}</a>
                            @endif
                            <a href="{{ route('cars.index') }}">{{ __('ui.nav.manage_cars') }}</a>
                            <a href="{{ route('profile.settings') }}">{{ __('ui.profile.settings') }}</a>
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit">{{ __('ui.nav.logout') }}</button>
                            </form>
                        </div>
                    </div>
                </details>
            </div>
        @endauth

        <nav aria-label="{{ __('ui.nav.primary') }}">
            <a href="/" class="{{ request()->is('/') ? 'active' : '' }}">{{ __('ui.nav.dashboard') }}</a>
            <a href="/community" class="{{ request()->is('community') ? 'active' : '' }}">{{ __('ui.nav.community') }}</a>
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
