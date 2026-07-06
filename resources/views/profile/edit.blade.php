@extends('layout')

@section('content')
    @php
        $avatarUrl = \App\Support\UploadedMedia::url($user->avatar_path);
        $bannerUrl = \App\Support\UploadedMedia::url($user->banner_path);
        $initials = strtoupper(substr($user->displayHandle(), 0, 2));
    @endphp

    <div class="page-head">
        <div>
            <p class="eyebrow">{{ __('ui.profile.eyebrow') }}</p>
            <h1>{{ __('ui.profile.title') }}</h1>
            <p>{{ __('ui.profile.social_copy') }}</p>
        </div>

        @if ($user->profile_slug)
            <a class="ghost-button" href="{{ route('public.profile', $user->profile_slug) }}">{{ __('ui.profile.view_public') }}</a>
        @endif
    </div>

    @if (session('status'))
        <div class="alert-card success-card">
            <strong>{{ __('ui.profile.saved') }}</strong>
        </div>
    @endif

    <div class="profile-shell">
        <section class="panel profile-editor">
            <div class="profile-banner" @if($bannerUrl) style="background-image: linear-gradient(180deg, rgba(0,0,0,0.18), rgba(0,0,0,0.72)), url('{{ $bannerUrl }}')" @endif>
                <div class="profile-avatar xl">
                    @if ($avatarUrl)
                        <img src="{{ $avatarUrl }}" alt="{{ $user->displayHandle() }}" loading="lazy">
                    @else
                        <span>{{ $initials }}</span>
                    @endif
                </div>
            </div>

            <form method="post" action="{{ route('profile.update') }}" enctype="multipart/form-data" class="setup-form">
                @csrf
                @method('patch')

                <div class="form-grid">
                    <div>
                        <label for="name">{{ __('ui.auth.name') }}</label>
                        <input id="name" name="name" type="text" value="{{ old('name', $user->name) }}" required autocomplete="name">
                        <x-input-error :messages="$errors->get('name')" class="auth-error" />
                    </div>

                    <div>
                        <label for="display_name">{{ __('ui.profile.display_name') }}</label>
                        <input id="display_name" name="display_name" type="text" value="{{ old('display_name', $user->display_name) }}" autocomplete="nickname">
                        <x-input-error :messages="$errors->get('display_name')" class="auth-error" />
                    </div>

                    <div>
                        <label for="email">{{ __('ui.auth.email') }}</label>
                        <input id="email" name="email" type="email" value="{{ old('email', $user->email) }}" required autocomplete="username">
                        <x-input-error :messages="$errors->get('email')" class="auth-error" />
                    </div>

                    <div>
                        <label for="location">{{ __('ui.profile.location') }}</label>
                        <input id="location" name="location" type="text" value="{{ old('location', $user->location) }}" placeholder="{{ __('ui.profile.location_placeholder') }}">
                        <x-input-error :messages="$errors->get('location')" class="auth-error" />
                    </div>

                    <div>
                        <label for="avatar">{{ __('ui.profile.avatar') }}</label>
                        <input id="avatar" name="avatar" type="file" accept="image/*">
                        <x-input-error :messages="$errors->get('avatar')" class="auth-error" />
                    </div>

                    <div>
                        <label for="banner">{{ __('ui.profile.banner') }}</label>
                        <input id="banner" name="banner" type="file" accept="image/*">
                        <x-input-error :messages="$errors->get('banner')" class="auth-error" />
                    </div>
                </div>

                <label for="bio">{{ __('ui.profile.bio') }}</label>
                <textarea id="bio" name="bio" rows="5" placeholder="{{ __('ui.profile.bio_placeholder') }}">{{ old('bio', $user->bio) }}</textarea>
                <x-input-error :messages="$errors->get('bio')" class="auth-error" />

                <button type="submit">{{ __('ui.profile.save_profile') }}</button>
            </form>
        </section>

        <aside class="profile-side">
            <section class="panel">
                <div class="panel-title">
                    <div>
                        <p class="eyebrow">{{ __('ui.profile.security') }}</p>
                        <h2>{{ __('ui.profile.update_password') }}</h2>
                    </div>
                </div>

                <form method="post" action="{{ route('password.update') }}" class="setup-form">
                    @csrf
                    @method('put')

                    <label for="update_password_current_password">{{ __('ui.profile.current_password') }}</label>
                    <input id="update_password_current_password" name="current_password" type="password" autocomplete="current-password">
                    <x-input-error :messages="$errors->updatePassword->get('current_password')" class="auth-error" />

                    <label for="update_password_password">{{ __('ui.auth.new_password') }}</label>
                    <input id="update_password_password" name="password" type="password" autocomplete="new-password">
                    <x-input-error :messages="$errors->updatePassword->get('password')" class="auth-error" />

                    <label for="update_password_password_confirmation">{{ __('ui.auth.confirm_password') }}</label>
                    <input id="update_password_password_confirmation" name="password_confirmation" type="password" autocomplete="new-password">
                    <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="auth-error" />

                    <button type="submit">{{ __('ui.profile.save_password') }}</button>
                </form>
            </section>

            <section class="panel danger-panel">
                <div class="panel-title">
                    <div>
                        <p class="eyebrow">{{ __('ui.profile.danger_zone') }}</p>
                        <h2>{{ __('ui.profile.delete_account') }}</h2>
                    </div>
                </div>

                <p>{{ __('ui.profile.delete_copy') }}</p>

                <form method="post" action="{{ route('profile.destroy') }}" class="setup-form" onsubmit="return confirm('{{ __('ui.profile.delete_confirm_title') }}');">
                    @csrf
                    @method('delete')

                    <label for="delete_password">{{ __('ui.auth.password') }}</label>
                    <input id="delete_password" name="password" type="password" autocomplete="current-password">
                    <x-input-error :messages="$errors->userDeletion->get('password')" class="auth-error" />

                    <button type="submit" class="danger-btn">{{ __('ui.profile.delete_account') }}</button>
                </form>
            </section>
        </aside>
    </div>
@endsection
