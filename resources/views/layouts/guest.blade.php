<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="theme-color" content="#050807">

        <title>{{ __('ui.auth.title') }}</title>

        <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    </head>
    <body class="auth-body">
        <main class="auth-shell">
            <section class="auth-intro" aria-label="{{ __('ui.dashboard.personal_garage') }}">
                <a class="auth-brand" href="/">
                    <span>CAR</span>
                    <strong>{{ __('ui.nav.garage') }}</strong>
                </a>

                <div class="auth-hero-copy">
                    <p class="auth-eyebrow">{{ __('ui.auth.platform') }}</p>
                    <h1>{{ __('ui.auth.hero') }}</h1>
                    <p>
                        {{ __('ui.auth.copy') }}
                    </p>
                </div>

                <div class="auth-spec-grid">
                    <div>
                        <span>{{ __('ui.auth.profile') }}</span>
                        <strong>{{ __('ui.auth.car_id') }}</strong>
                    </div>
                    <div>
                        <span>{{ __('ui.auth.theme') }}</span>
                        <strong>{{ __('ui.auth.color') }}</strong>
                    </div>
                    <div>
                        <span>{{ __('ui.auth.data') }}</span>
                        <strong>{{ __('ui.nav.garage') }}</strong>
                    </div>
                </div>
            </section>

            <section class="auth-panel" aria-label="{{ __('ui.auth.form') }}">
                {{ $slot }}
            </section>
        </main>
        @include('partials.language-switcher')
    </body>
</html>
