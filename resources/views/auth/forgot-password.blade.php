<x-guest-layout>
    @php
        $authErrors = $errors ?? new \Illuminate\Support\ViewErrorBag;
    @endphp

    <div class="auth-form-head">
        <p class="auth-eyebrow">{{ __('ui.auth.password_recovery') }}</p>
        <h2>{{ __('ui.auth.reset_access') }}</h2>
        <p>{{ __('ui.auth.reset_copy') }}</p>
    </div>

    @if (config('mail.default') === 'log')
        <div class="auth-note">
            {!! __('ui.auth.local_mail_log', ['path' => '<strong>storage/logs/laravel.log</strong>']) !!}
        </div>
    @endif

    <x-auth-session-status class="auth-status" :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}" class="auth-form">
        @csrf

        <div>
            <label for="email">{{ __('ui.auth.email') }}</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username">
            <x-input-error :messages="$authErrors->get('email')" class="auth-error" />
        </div>

        <div class="auth-actions">
            <a href="{{ route('login') }}">{{ __('ui.auth.back_to_login') }}</a>
            <button type="submit">{{ __('ui.auth.generate_reset_link') }}</button>
        </div>
    </form>
</x-guest-layout>
