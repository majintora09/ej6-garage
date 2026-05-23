<x-guest-layout>
    <div class="auth-form-head">
        <p class="auth-eyebrow">{{ __('ui.auth.system_access') }}</p>
        <h2>{{ __('ui.auth.create_account') }}</h2>
        <p>{{ __('ui.auth.verify_intro') }}</p>
    </div>

    @if (session('status') == 'verification-link-sent')
        <div class="auth-status">
            {{ __('ui.auth.verification_sent') }}
        </div>
    @endif

    <div class="auth-actions">
        <form method="POST" action="{{ route('verification.send') }}">
            @csrf

            <div>
                <button type="submit">{{ __('ui.auth.resend_verification') }}</button>
            </div>
        </form>

        <form method="POST" action="{{ route('logout') }}">
            @csrf

            <button type="submit" class="logout-button">
                {{ __('ui.nav.logout') }}
            </button>
        </form>
    </div>
</x-guest-layout>
