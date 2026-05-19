<x-guest-layout>
    @php
        $authErrors = $errors ?? new \Illuminate\Support\ViewErrorBag;
    @endphp

    <div class="auth-form-head">
        <p class="auth-eyebrow">NEW PASSWORD</p>
        <h2>Update access</h2>
        <p>Set a new password for your garage account.</p>
    </div>

    <form method="POST" action="{{ route('password.store') }}" class="auth-form">
        @csrf

        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <div>
            <label for="email">Email</label>
            <input id="email" type="email" name="email" value="{{ old('email', $request->email) }}" required autofocus autocomplete="username">
            <x-input-error :messages="$authErrors->get('email')" class="auth-error" />
        </div>

        <div>
            <label for="password">New Password</label>
            <input id="password" type="password" name="password" required autocomplete="new-password">
            <x-input-error :messages="$authErrors->get('password')" class="auth-error" />
        </div>

        <div>
            <label for="password_confirmation">Confirm Password</label>
            <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password">
            <x-input-error :messages="$authErrors->get('password_confirmation')" class="auth-error" />
        </div>

        <div class="auth-actions">
            <a href="{{ route('login') }}">Back to login</a>
            <button type="submit">Reset Password</button>
        </div>
    </form>
</x-guest-layout>
