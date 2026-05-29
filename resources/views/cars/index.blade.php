@extends('layout')

@section('content')
    @php
        $carErrors = $errors ?? new \Illuminate\Support\ViewErrorBag;
    @endphp

    @if (session('status'))
        <div class="alert-card success-card">
            <strong>{{ session('status') }}</strong>
        </div>
    @endif

    <div class="page-head">
        <div>
            <p class="eyebrow">{{ __('ui.cars.eyebrow') }}</p>
            <h1>{{ __('ui.cars.title') }}</h1>
            <p>{{ __('ui.cars.intro') }}</p>
        </div>

        <div class="mini-spec-card">
            <span>{{ __('ui.cars.active_car') }}</span>
            <strong>{{ $activeCar ? trim($activeCar->make.' '.$activeCar->model) : __('ui.common.not_set') }}</strong>
            <small>{{ __('ui.cars.active_hint') }}</small>
        </div>
    </div>

    <div class="cars-layout">
        <section class="panel">
            <div class="panel-title">
                <div>
                    <p class="eyebrow">{{ __('ui.cars.new_car') }}</p>
                    <h2>{{ __('ui.cars.create_title') }}</h2>
                </div>
            </div>

            <form action="{{ route('cars.store') }}" method="POST" class="setup-form">
                @csrf

                <div class="form-grid">
                    <div>
                        <label>{{ __('ui.dashboard.make') }}</label>
                        <input type="text" name="make" value="{{ old('make') }}" required>
                        <x-input-error :messages="$carErrors->get('make')" class="auth-error" />
                    </div>

                    <div>
                        <label>{{ __('ui.dashboard.model') }}</label>
                        <input type="text" name="model" value="{{ old('model') }}" required>
                        <x-input-error :messages="$carErrors->get('model')" class="auth-error" />
                    </div>

                    <div>
                        <label>{{ __('ui.dashboard.year') }}</label>
                        <input type="number" name="year" value="{{ old('year') }}">
                    </div>

                    <div>
                        <label>{{ __('ui.dashboard.chassis') }}</label>
                        <input type="text" name="chassis" value="{{ old('chassis') }}">
                    </div>

                    <div>
                        <label>{{ __('ui.setup.body_type') }}</label>
                        <select name="body_type" required>
                            @foreach (['coupe', 'hatchback', 'sedan', 'wagon', 'suv', 'pickup', 'motorcycle', 'other'] as $value)
                                <option value="{{ $value }}" @selected(old('body_type', 'coupe') === $value)>{{ __("ui.body_types.{$value}") }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label>{{ __('ui.dashboard.engine') }}</label>
                        <input type="text" name="engine" value="{{ old('engine') }}">
                    </div>

                    <div>
                        <label>{{ __('ui.setup.theme_color') }}</label>
                        <input type="color" name="theme_color" value="{{ old('theme_color', '#8b5cf6') }}">
                    </div>

                    <div>
                        <label>{{ __('ui.cars.secondary_theme') }}</label>
                        <input type="color" name="secondary_theme_color" value="{{ old('secondary_theme_color', '#38bdf8') }}">
                    </div>
                </div>

                <label>{{ __('ui.setup.build_vibe') }}</label>
                <textarea name="build_vibe" placeholder="{{ __('ui.cars.vibe_placeholder') }}">{{ old('build_vibe') }}</textarea>

                <button type="submit">{{ __('ui.cars.create_button') }}</button>
            </form>
        </section>

        <section class="panel">
            <div class="panel-title">
                <div>
                    <p class="eyebrow">{{ __('ui.cars.roster') }}</p>
                    <h2>{{ __('ui.cars.saved_cars') }}</h2>
                </div>
            </div>

            <div class="car-roster">
                @forelse ($cars as $car)
                    <article class="car-roster-card {{ $activeCar?->id === $car->id ? 'active' : '' }}">
                        <div class="car-roster-head">
                            <div>
                                <span>{{ $activeCar?->id === $car->id ? __('ui.cars.active_badge') : __('ui.cars.saved_badge') }}</span>
                                <h3>{{ trim(($car->year ? $car->year.' ' : '').$car->make.' '.$car->model) }}</h3>
                                <p>{{ $car->chassis ?: __('ui.common.unknown_chassis') }} • {{ $car->engine ?: __('ui.common.unknown_engine') }}</p>
                            </div>

                            <div class="car-theme-dots">
                                <i style="--dot-color: {{ $car->theme_color ?: '#8b5cf6' }}"></i>
                                <i style="--dot-color: {{ $car->secondary_theme_color ?: '#38bdf8' }}"></i>
                            </div>
                        </div>

                        <div class="mod-meta">
                            <span>{{ $car->photos_count }} {{ __('ui.nav.gallery') }}</span>
                            <span>{{ $car->mods_count }} {{ __('ui.nav.mods') }}</span>
                            <span>{{ $car->maintenances_count }} {{ __('ui.nav.maintenance') }}</span>
                            <span>{{ $car->visibility ?: 'private' }}</span>
                        </div>

                        <div class="car-actions">
                            @if ($activeCar?->id !== $car->id)
                                <form action="{{ route('cars.select', $car) }}" method="POST">
                                    @csrf
                                    <button type="submit">{{ __('ui.cars.select') }}</button>
                                </form>
                            @endif

                            @if (auth()->user()->profile_slug && $car->slug && in_array($car->visibility, ['public', 'unlisted'], true))
                                @php $carPublicUrl = route('public.garage', [auth()->user()->profile_slug, $car->slug]); @endphp
                                <a class="ghost-button" href="{{ $carPublicUrl }}">{{ __('ui.public.view_garage') }}</a>
                                <button type="button" data-share-url="{{ $carPublicUrl }}" data-copied-label="{{ __('ui.public.copied') }}" data-copy-prompt-label="{{ __('ui.public.copy_prompt') }}">{{ __('ui.public.copy_public_link') }}</button>
                            @else
                                <a class="ghost-button muted-action" href="{{ route('cars.index') }}#car-{{ $car->id }}">{{ __('ui.public.make_public_to_share') }}</a>
                            @endif

                            <details id="car-{{ $car->id }}">
                                <summary>{{ __('ui.cars.edit') }}</summary>
                                <form action="{{ route('cars.update', $car) }}" method="POST" class="setup-form">
                                    @csrf
                                    @method('PUT')

                                    <div class="form-grid">
                                        <div>
                                            <label>{{ __('ui.dashboard.make') }}</label>
                                            <input type="text" name="make" value="{{ old('make', $car->make) }}" required>
                                        </div>

                                        <div>
                                            <label>{{ __('ui.dashboard.model') }}</label>
                                            <input type="text" name="model" value="{{ old('model', $car->model) }}" required>
                                        </div>

                                        <div>
                                            <label>{{ __('ui.dashboard.year') }}</label>
                                            <input type="number" name="year" value="{{ old('year', $car->year) }}">
                                        </div>

                                        <div>
                                            <label>{{ __('ui.dashboard.chassis') }}</label>
                                            <input type="text" name="chassis" value="{{ old('chassis', $car->chassis) }}">
                                        </div>

                                        <div>
                                            <label>{{ __('ui.setup.body_type') }}</label>
                                            <select name="body_type" required>
                                                @foreach (['coupe', 'hatchback', 'sedan', 'wagon', 'suv', 'pickup', 'motorcycle', 'other'] as $value)
                                                    <option value="{{ $value }}" @selected(old('body_type', $car->body_type) === $value)>{{ __("ui.body_types.{$value}") }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div>
                                            <label>{{ __('ui.dashboard.engine') }}</label>
                                            <input type="text" name="engine" value="{{ old('engine', $car->engine) }}">
                                        </div>

                                        <div>
                                            <label>{{ __('ui.setup.color_name') }}</label>
                                            <input type="text" name="color_name" value="{{ old('color_name', $car->color_name) }}">
                                        </div>

                                        <div>
                                            <label>{{ __('ui.dashboard.color_code') }}</label>
                                            <input type="text" name="color_code" value="{{ old('color_code', $car->color_code) }}">
                                        </div>

                                        <div>
                                            <label>{{ __('ui.setup.theme_color') }}</label>
                                            <input type="color" name="theme_color" value="{{ old('theme_color', $car->theme_color ?: '#8b5cf6') }}">
                                        </div>

                                        <div>
                                            <label>{{ __('ui.cars.secondary_theme') }}</label>
                                            <input type="color" name="secondary_theme_color" value="{{ old('secondary_theme_color', $car->secondary_theme_color ?: '#38bdf8') }}">
                                        </div>

                                        <div>
                                            <label>{{ __('ui.cars.visibility') }}</label>
                                            <select name="visibility">
                                                @foreach (['private', 'unlisted', 'public'] as $visibility)
                                                    <option value="{{ $visibility }}" @selected(old('visibility', $car->visibility ?: 'private') === $visibility)>{{ __("ui.cars.visibility_{$visibility}") }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div>
                                            <label>{{ __('ui.cars.slug') }}</label>
                                            <input type="text" name="slug" value="{{ old('slug', $car->slug) }}">
                                        </div>
                                    </div>

                                    <label>{{ __('ui.setup.build_vibe') }}</label>
                                    <textarea name="build_vibe">{{ old('build_vibe', $car->build_vibe) }}</textarea>

                                    <button type="submit">{{ __('ui.cars.save') }}</button>
                                </form>
                            </details>

                            <form action="{{ route('cars.destroy', $car) }}" method="POST" onsubmit="return confirm('{{ __('ui.cars.confirm_delete') }}');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="danger-btn">{{ __('ui.common.delete') }}</button>
                            </form>
                        </div>
                    </article>
                @empty
                    <div class="empty-state">
                        <strong>{{ __('ui.cars.empty_title') }}</strong>
                        <p>{{ __('ui.cars.empty_copy') }}</p>
                    </div>
                @endforelse
            </div>
        </section>
    </div>
@endsection
