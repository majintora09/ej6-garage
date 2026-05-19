<x-guest-layout>
    @php
        $authErrors = $errors ?? new \Illuminate\Support\ViewErrorBag;
    @endphp

    <div class="auth-form-head">
        <p class="auth-eyebrow">{{ __('ui.auth.system_access') }}</p>
        <h2>{{ __('ui.auth.login') }}</h2>
        <p>{{ __('ui.auth.login_copy') }}</p>
    </div>

    <x-auth-session-status class="auth-status" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="auth-form">
        @csrf

        <div>
            <label for="email">{{ __('ui.auth.email') }}</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username">
            <x-input-error :messages="$authErrors->get('email')" class="auth-error" />
        </div>

        <div>
            <label for="password">{{ __('ui.auth.password') }}</label>
            <input id="password" type="password" name="password" required autocomplete="current-password">
            <x-input-error :messages="$authErrors->get('password')" class="auth-error" />
        </div>

        <label class="auth-check" for="remember_me">
            <input id="remember_me" type="checkbox" name="remember">
            <span>{{ __('ui.auth.remember') }}</span>
        </label>

        <div class="auth-actions">
            @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}">{{ __('ui.auth.forgot') }}</a>
            @endif

            <button type="submit">{{ __('ui.auth.enter') }}</button>
        </div>
    </form>

    <p class="auth-switch">
        {{ __('ui.auth.new_garage') }}
        <a href="{{ route('register') }}">{{ __('ui.auth.create_account_link') }}</a>
    </p>
</x-guest-layout>
