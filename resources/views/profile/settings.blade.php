@extends('layout')

@section('content')
    <div class="page-head">
        <div>
            <p class="eyebrow">{{ __('ui.profile.settings_eyebrow') }}</p>
            <h1>{{ __('ui.profile.settings') }}</h1>
            <p>{{ __('ui.profile.settings_copy') }}</p>
        </div>
    </div>

    <div class="settings-grid">
        <section class="panel">
            <div class="panel-title">
                <div>
                    <p class="eyebrow">{{ __('ui.language.choose') }}</p>
                    <h2>{{ __('ui.profile.language_region') }}</h2>
                </div>
            </div>

            <div class="settings-language-grid">
                @foreach (['en' => 'English', 'de' => 'Deutsch', 'fr' => 'Français', 'pt' => 'Português', 'lb' => 'Lëtzebuergesch'] as $locale => $label)
                    <a class="{{ app()->getLocale() === $locale ? 'active' : '' }}" href="{{ route('language.switch', $locale) }}">
                        <strong>{{ strtoupper($locale) }}</strong>
                        <span>{{ $label }}</span>
                    </a>
                @endforeach
            </div>
        </section>

        <section class="panel">
            <div class="panel-title">
                <div>
                    <p class="eyebrow">{{ __('ui.profile.account') }}</p>
                    <h2>{{ __('ui.profile.account_settings') }}</h2>
                </div>
            </div>

            <div class="spec-list">
                <p><strong>{{ __('ui.auth.email') }}:</strong> {{ $user->email }}</p>
                <p><strong>{{ __('ui.profile.public_handle') }}:</strong> {{ $user->profile_slug ?: __('ui.common.not_set') }}</p>
            </div>

            <div class="profile-settings-actions">
                <a class="ghost-button" href="{{ route('profile.edit') }}">{{ __('ui.profile.edit_driver_profile') }}</a>
                <a class="ghost-button" href="{{ route('cars.index') }}">{{ __('ui.nav.manage_cars') }}</a>
            </div>
        </section>
    </div>
@endsection
