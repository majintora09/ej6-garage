<x-guest-layout>
    @php
        $authErrors = $errors ?? new \Illuminate\Support\ViewErrorBag;
    @endphp

    <div class="auth-form-head">
        <p class="auth-eyebrow">PASSWORD RECOVERY</p>
        <h2>Reset access</h2>
        <p>Enter your account email and the garage will generate a password reset link.</p>
    </div>

    @if (config('mail.default') === 'log')
        <div class="auth-note">
            Local mail is set to log mode. Reset links are written to <strong>storage/logs/laravel.log</strong>.
        </div>
    @endif

    <x-auth-session-status class="auth-status" :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}" class="auth-form">
        @csrf

        <div>
            <label for="email">Email</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username">
            <x-input-error :messages="$authErrors->get('email')" class="auth-error" />
        </div>

        <div class="auth-actions">
            <a href="{{ route('login') }}">Back to login</a>
            <button type="submit">Generate Reset Link</button>
        </div>
    </form>
</x-guest-layout>
