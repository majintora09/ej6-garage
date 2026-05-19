@extends('layout')

@section('content')
    @php
        $setupErrors = $errors ?? new \Illuminate\Support\ViewErrorBag;
        $mode = $mode ?? ($carProfile ? 'edit' : 'create');
        $isEdit = $mode === 'edit';
        $headline = $isEdit ? __('ui.setup.edit_headline') : __('ui.setup.create_headline');
        $intro = $isEdit
            ? __('ui.setup.edit_intro')
            : __('ui.setup.create_intro');
    @endphp

    <div class="setup-shell">
        <section class="hero-card setup-hero">
            <div>
                <p class="eyebrow">{{ $isEdit ? __('ui.setup.details') : __('ui.setup.create') }}</p>
                <h1>{{ $headline }}</h1>
                <p class="hero-subtitle">
                    {{ $intro }}
                </p>
            </div>
        </section>

        <section class="panel setup-panel">
            <div class="panel-title">
                <div>
                    <p class="eyebrow">{{ __('ui.setup.car_identity') }}</p>
                    <h2>{{ __('ui.setup.profile') }}</h2>
                </div>
            </div>

            <form action="{{ $isEdit ? route('garage.profile.update') : route('garage.setup.store') }}" method="POST" enctype="multipart/form-data" class="setup-form">
                @csrf
                @if ($isEdit)
                    @method('PUT')
                @endif

                <div class="form-grid">
                    <div>
                        <label>{{ __('ui.dashboard.make') }}</label>
                        <input type="text" name="make" value="{{ old('make', $carProfile->make ?? '') }}" placeholder="Toyota, BMW, Nissan..." required>
                        <x-input-error :messages="$setupErrors->get('make')" class="auth-error" />
                    </div>

                    <div>
                        <label>{{ __('ui.dashboard.model') }}</label>
                        <input type="text" name="model" value="{{ old('model', $carProfile->model ?? '') }}" placeholder="Supra, E36, Silvia..." required>
                        <x-input-error :messages="$setupErrors->get('model')" class="auth-error" />
                    </div>

                    <div>
                        <label>{{ __('ui.dashboard.chassis') }}</label>
                        <input type="text" name="chassis" value="{{ old('chassis', $carProfile->chassis ?? '') }}" placeholder="JZA80, E36, S14...">
                    </div>

                    <div>
                        <label>{{ __('ui.dashboard.year') }}</label>
                        <input type="number" name="year" value="{{ old('year', $carProfile->year ?? '') }}" placeholder="1997">
                    </div>

                    <div>
                        <label>{{ __('ui.dashboard.engine') }}</label>
                        <input type="text" name="engine" value="{{ old('engine', $carProfile->engine ?? '') }}" placeholder="2JZ-GTE, M50, SR20DET...">
                    </div>

                    <div>
                        <label>{{ __('ui.dashboard.interior') }}</label>
                        <input type="text" name="interior" value="{{ old('interior', $carProfile->interior ?? '') }}" placeholder="Black cloth, DK.GRAY, leather...">
                    </div>

                    <div>
                        <label>{{ __('ui.setup.body_type') }}</label>
                        <select name="body_type" required>
                            @foreach (['coupe' => 'Coupe', 'hatchback' => 'Hatchback', 'sedan' => 'Sedan', 'suv' => 'SUV', 'pickup' => 'Pickup'] as $value => $label)
                                <option value="{{ $value }}" @selected(old('body_type', $carProfile->body_type ?? 'coupe') === $value)>{{ $label }}</option>
                            @endforeach
                        </select>
                        <p class="field-hint">{{ __('ui.setup.body_type_hint') }}</p>
                    </div>

                    <div>
                        <label>{{ __('ui.setup.model_path') }}</label>
                        <input type="text" name="model_path" value="{{ old('model_path', $carProfile->model_path ?? '') }}" placeholder="/models/my-car/model.glb">
                        <x-input-error :messages="$setupErrors->get('model_path')" class="auth-error" />
                        <p class="field-hint">{{ __('ui.setup.model_path_hint') }}</p>
                    </div>

                    <div>
                        <label>{{ __('ui.setup.color_name') }}</label>
                        <input type="text" name="color_name" value="{{ old('color_name', $carProfile->color_name ?? '') }}" placeholder="Black, Midnight Purple, Championship White...">
                    </div>

                    <div>
                        <label>{{ __('ui.dashboard.color_code') }}</label>
                        <input type="text" name="color_code" value="{{ old('color_code', $carProfile->color_code ?? '') }}" placeholder="202, LP2, NH-0...">
                    </div>

                    <div>
                        <label>{{ __('ui.setup.theme_color') }}</label>
                        <input type="color" name="theme_color" value="{{ old('theme_color', $carProfile->theme_color ?? '#76ff9f') }}">
                        <p class="field-hint">{{ __('ui.setup.theme_hint') }}</p>
                    </div>
                </div>

                <label>{{ __('ui.setup.build_vibe') }}</label>
                <textarea name="build_vibe" placeholder="Clean street build, track prep, OEM+ restoration...">{{ old('build_vibe', $carProfile->build_vibe ?? '') }}</textarea>

                <div class="photo-upload-panel">
                    <div class="photo-upload-head">
                        <div>
                            <p class="eyebrow">{{ __('ui.setup.paint_scan') }}</p>
                            <h3>{{ __('ui.setup.photo_title') }}</h3>
                            <p class="field-hint">
                                {{ __('ui.setup.photo_hint') }}
                            </p>
                        </div>

                        <span class="scanner-pill">{{ __('ui.setup.theme_linked') }}</span>
                    </div>

                    <label class="photo-dropzone">
                        <input type="file" name="car_photos[]" accept="image/*" multiple>
                        <span class="dropzone-icon">+</span>
                        <strong>{{ __('ui.setup.upload_photos') }}</strong>
                        <small>{{ __('ui.setup.upload_small') }}</small>
                        <x-input-error :messages="$setupErrors->get('car_photos')" class="auth-error" />
                        <x-input-error :messages="$setupErrors->get('car_photos.*')" class="auth-error" />
                    </label>

                    @if ($isEdit && $carProfile?->photos?->isNotEmpty())
                        <div class="profile-photo-grid">
                            @foreach ($carProfile->photos as $photo)
                                <figure class="profile-photo-card">
                                    <img src="{{ route('car-photos.show', $photo) }}" alt="{{ $carProfile->make }} {{ $carProfile->model }} color reference">
                                    <figcaption>
                                        <span>{{ __('ui.setup.reference') }} {{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}</span>
                                        <strong>{{ $photo->original_name ?: 'Color reference' }}</strong>
                                    </figcaption>
                                    <form action="{{ route('car-photos.destroy', $photo) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="danger-btn">{{ __('ui.setup.remove') }}</button>
                                    </form>
                                </figure>
                            @endforeach
                        </div>
                    @endif
                </div>

                <button type="submit">{{ $isEdit ? __('ui.setup.save') : __('ui.setup.create_button') }}</button>
            </form>
        </section>
    </div>

@endsection
