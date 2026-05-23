<x-guest-layout>
    @php
        $authErrors = $errors ?? new \Illuminate\Support\ViewErrorBag;
    @endphp

    <div class="auth-form-head">
        <p class="auth-eyebrow">{{ __('ui.auth.new_password') }}</p>
        <h2>{{ __('ui.auth.update_access') }}</h2>
        <p>{{ __('ui.auth.new_password_copy') }}</p>
    </div>

    <form method="POST" action="{{ route('password.store') }}" class="auth-form">
        @csrf

        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <div>
            <label for="email">{{ __('ui.auth.email') }}</label>
            <input id="email" type="email" name="email" value="{{ old('email', $request->email) }}" required autofocus autocomplete="username">
            <x-input-error :messages="$authErrors->get('email')" class="auth-error" />
        </div>

        <div>
            <label for="password">{{ __('ui.auth.new_password') }}</label>
            <input id="password" type="password" name="password" required autocomplete="new-password">
            <x-input-error :messages="$authErrors->get('password')" class="auth-error" />
        </div>

        <div>
            <label for="password_confirmation">{{ __('ui.auth.confirm_password') }}</label>
            <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password">
            <x-input-error :messages="$authErrors->get('password_confirmation')" class="auth-error" />
        </div>

        <div class="auth-actions">
            <a href="{{ route('login') }}">{{ __('ui.auth.back_to_login') }}</a>
            <button type="submit">{{ __('ui.auth.reset_password') }}</button>
        </div>
    </form>
</x-guest-layout>
