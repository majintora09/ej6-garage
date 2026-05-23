<x-guest-layout>
    <div class="auth-form-head">
        <p class="auth-eyebrow">{{ __('ui.auth.system_access') }}</p>
        <h2>{{ __('ui.auth.confirm') }}</h2>
        <p>{{ __('ui.auth.secure_area') }}</p>
    </div>

    <form method="POST" action="{{ route('password.confirm') }}" class="auth-form">
        @csrf

        <div>
            <label for="password">{{ __('ui.auth.password') }}</label>
            <input id="password" type="password" name="password" required autocomplete="current-password">
            <x-input-error :messages="$errors->get('password')" class="auth-error" />
        </div>

        <div class="auth-actions">
            <button type="submit">{{ __('ui.auth.confirm') }}</button>
        </div>
    </form>
</x-guest-layout>
