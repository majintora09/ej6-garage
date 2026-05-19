<x-guest-layout>
    @php
        $authErrors = $errors ?? new \Illuminate\Support\ViewErrorBag;
    @endphp

    <div class="auth-form-head">
        <p class="auth-eyebrow">{{ __('ui.auth.new_garage_eyebrow') }}</p>
        <h2>{{ __('ui.auth.create_account') }}</h2>
        <p>{{ __('ui.auth.register_copy') }}</p>
    </div>

    <form method="POST" action="{{ route('register') }}" class="auth-form">
        @csrf

        <div>
            <label for="name">{{ __('ui.auth.name') }}</label>
            <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name">
            <x-input-error :messages="$authErrors->get('name')" class="auth-error" />
        </div>

        <div>
            <label for="email">{{ __('ui.auth.email') }}</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="username">
            <x-input-error :messages="$authErrors->get('email')" class="auth-error" />
        </div>

        <div>
            <label for="password">{{ __('ui.auth.password') }}</label>
            <input id="password" type="password" name="password" required autocomplete="new-password">
            <x-input-error :messages="$authErrors->get('password')" class="auth-error" />
        </div>

        <div>
            <label for="password_confirmation">{{ __('ui.auth.confirm_password') }}</label>
            <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password">
            <x-input-error :messages="$authErrors->get('password_confirmation')" class="auth-error" />
        </div>

        <div class="auth-actions">
            <a href="{{ route('login') }}">{{ __('ui.auth.already_registered') }}</a>
            <button type="submit">{{ __('ui.auth.build_garage') }}</button>
        </div>
    </form>
</x-guest-layout>
