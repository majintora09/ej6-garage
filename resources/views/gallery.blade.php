@extends('layout')

@section('content')
    @php
        $car = $currentCarProfile;
        $carName = trim(($car->year ? $car->year.' ' : '').$car->make.' '.$car->model);
        $priorityAlbum = $car->chassis ?: 'Profile';
        $photos = $car->photos ?? collect();
        $galleryErrors = $errors ?? new \Illuminate\Support\ViewErrorBag;
        $albumCategories = ['exterior', 'interior', 'rust', 'maintenance', 'mods', 'receipts', 'inspiration'];
        $activeAlbum = in_array(request('album', 'exterior'), $albumCategories, true) ? request('album', 'exterior') : 'exterior';
        $albumPhotos = $photos->where('category', $activeAlbum);
    @endphp

    <div class="page-head">
        <div>
            <p class="eyebrow">{{ __('ui.gallery.archive') }}</p>
            <h1>{{ __('ui.gallery.title') }}</h1>
            <p>{{ __('ui.gallery.intro', ['car' => $carName]) }}</p>
        </div>

        <div class="mini-spec-card">
            <span>{{ __('ui.gallery.layout') }}</span>
            <strong>{{ __('ui.gallery.ready') }}</strong>
            <small>{{ __('ui.gallery.sorted') }}</small>
        </div>
    </div>

    @if (session('status'))
        <div class="alert-card success-card">
            <strong>{{ session('status') }}</strong>
        </div>
    @endif

    <div class="gallery-stats">
        <div class="card metric-card">
            <span>{{ __('ui.gallery.photo_bays') }}</span>
            <strong>6</strong>
            <p>{{ __('ui.gallery.prepared') }}</p>
        </div>

        <div class="card metric-card">
            <span>{{ __('ui.gallery.priority_album') }}</span>
            <strong>{{ $priorityAlbum }}</strong>
            <p>{{ __('ui.gallery.core_docs') }}</p>
        </div>

        <div class="card metric-card">
            <span>{{ __('ui.gallery.build_tone') }}</span>
            <strong>{{ $car->color_code ?: __('ui.common.custom') }}</strong>
            <p>{{ $car->color_name ?: __('ui.common.theme') }} {{ __('ui.gallery.reference_board') }}</p>
        </div>
    </div>

    <section class="panel color-reference-panel">
        <div class="panel-title">
            <div>
                <p class="eyebrow">{{ __('ui.gallery.color_reference') }}</p>
                <h2>{{ $car->color_name ?: __('ui.common.unknown_color') }} {{ __('ui.gallery.checkpoint') }}</h2>
            </div>

            <div class="color-chip" style="--chip-color: {{ $car->theme_color && strtolower($car->theme_color) !== '#76ff9f' ? $car->theme_color : '#8b5cf6' }}">
                <span></span>
                <strong>{{ $car->color_code ?: __('ui.common.theme') }}</strong>
            </div>
        </div>

        <div class="reference-console">
            <p>
                {{ __('ui.gallery.console') }}
            </p>

            <a class="text-link" href="{{ route('garage.profile.edit') }}">{{ __('ui.gallery.manage') }}</a>
        </div>

        @if ($albumPhotos->isNotEmpty())
            <div class="car-photo-grid">
                @foreach ($albumPhotos as $photo)
                    <figure class="car-photo-card">
                        @if (\Illuminate\Support\Facades\Storage::disk('public')->exists($photo->path))
                            <img src="{{ route('media.show', ['path' => $photo->path]) }}" alt="{{ $carName }} {{ __('ui.gallery.color_reference') }}" loading="lazy" style="object-position: {{ in_array($photo->image_position, ['center', 'top', 'bottom', 'left', 'right'], true) ? $photo->image_position : 'center' }};">
                        @else
                            <div class="missing-media">{{ __('ui.common.media_missing') }}</div>
                        @endif
                        <figcaption>
                            <span>{{ __("ui.gallery.categories.".($photo->category ?: 'exterior')) }} • {{ $photo->visibility ?: 'private' }}</span>
                            <strong>{{ $photo->caption ?: ($photo->original_name ?: __('ui.setup.color_reference_fallback')) }}</strong>
                            @if ($photo->notes)
                                <small>{{ $photo->notes }}</small>
                            @endif
                        </figcaption>
                        <form action="{{ route('car-photos.destroy', $photo) }}" method="POST" onsubmit="return confirm('{{ __('ui.gallery.confirm_delete') }}');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="danger-btn">{{ __('ui.common.delete') }}</button>
                        </form>
                    </figure>
                @endforeach
            </div>
        @else
            <div class="gallery-empty color-empty">
                <div>
                    <strong>{{ __('ui.gallery.empty_album_title', ['album' => __("ui.gallery.categories.{$activeAlbum}")]) }}</strong>
                    <p>{{ __('ui.gallery.empty_album_copy') }}</p>
                </div>
            </div>
        @endif
    </section>

    <section class="panel">
        <div class="panel-title">
            <div>
                <p class="eyebrow">{{ __('ui.gallery.upload_queue') }}</p>
                <h2>{{ __('ui.gallery.upload_title') }}</h2>
            </div>
        </div>

        <form action="{{ route('car-photos.store') }}" method="POST" enctype="multipart/form-data" class="gallery-upload-form">
            @csrf

            <label class="photo-dropzone">
                <input type="file" name="photos[]" accept="image/*" multiple required data-image-preview-input data-preview-target="gallery-image-preview">
                <span class="dropzone-icon">+</span>
                <strong>{{ __('ui.gallery.upload_images') }}</strong>
                <small>{{ __('ui.gallery.upload_hint') }}</small>
                <x-input-error :messages="$galleryErrors->get('photos')" class="auth-error" />
                <x-input-error :messages="$galleryErrors->get('photos.*')" class="auth-error" />
            </label>

            <div class="form-grid">
                <div>
                    <label>{{ __('ui.gallery.category') }}</label>
                    <select name="category" required>
                        @foreach (['exterior', 'interior', 'rust', 'maintenance', 'mods', 'receipts', 'inspiration'] as $category)
                            <option value="{{ $category }}">{{ __("ui.gallery.categories.{$category}") }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label>{{ __('ui.cars.visibility') }}</label>
                    <select name="visibility">
                        @foreach (['private', 'unlisted', 'public'] as $visibility)
                            <option value="{{ $visibility }}">{{ __("ui.cars.visibility_{$visibility}") }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="gallery_image_position">{{ __('ui.common.image_position') }}</label>
                    <select id="gallery_image_position" name="image_position" data-image-position-select data-preview-target="gallery-image-preview">
                        @foreach (['center', 'top', 'bottom', 'left', 'right'] as $position)
                            <option value="{{ $position }}" @selected(old('image_position', 'center') === $position)>{{ __("ui.common.positions.{$position}") }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label>{{ __('ui.gallery.caption') }}</label>
                    <input type="text" name="caption" placeholder="{{ __('ui.gallery.caption_placeholder') }}">
                </div>

                <div>
                    <label>{{ __('ui.gallery.notes') }}</label>
                    <input type="text" name="notes" placeholder="{{ __('ui.gallery.notes_placeholder') }}">
                </div>
            </div>

            <div id="gallery-image-preview" class="image-position-preview wide" hidden>
                <span>{{ __('ui.gallery.image_preview') }}</span>
                <img alt="{{ __('ui.gallery.image_preview') }}">
            </div>

            <button type="submit">{{ __('ui.gallery.upload_button') }}</button>
        </form>
    </section>

    <section class="panel">
        <div class="panel-title">
            <div>
                <p class="eyebrow">{{ __('ui.gallery.archive_map') }}</p>
                <h2>{{ __('ui.gallery.photo_bays_title') }}</h2>
            </div>
        </div>

        <div class="gallery-grid album-grid">
            @foreach ($albumCategories as $index => $category)
                @php
                    $cover = $photos->firstWhere('category', $category);
                    $count = $photos->where('category', $category)->count();
                @endphp
                <a class="gallery-bay album-folder {{ $activeAlbum === $category ? 'active' : '' }}" href="{{ url('/gallery?album='.$category) }}">
                    <span>{{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}</span>
                    @if ($cover && \Illuminate\Support\Facades\Storage::disk('public')->exists($cover->path))
                        <img src="{{ route('media.show', ['path' => $cover->path]) }}" alt="{{ __("ui.gallery.categories.{$category}") }}" loading="lazy" style="object-position: {{ in_array($cover->image_position, ['center', 'top', 'bottom', 'left', 'right'], true) ? $cover->image_position : 'center' }};">
                    @else
                        <div class="album-folder-placeholder">{{ strtoupper(substr(__("ui.gallery.categories.{$category}"), 0, 2)) }}</div>
                    @endif
                    <h3>{{ __("ui.gallery.categories.{$category}") }}</h3>
                    <p>{{ trans_choice('ui.gallery.album_count', $count, ['count' => $count]) }}</p>
                    <small>{{ $activeAlbum === $category ? __('ui.gallery.active_album') : __('ui.gallery.open_album') }}</small>
                </a>
            @endforeach
        </div>
    </section>

    <section class="panel">
        <div class="panel-title">
            <div>
                <p class="eyebrow">{{ __('ui.gallery.upload_queue') }}</p>
                <h2>{{ __('ui.gallery.first_shots') }}</h2>
            </div>
        </div>

        <div class="shot-list">
            <div class="shot-row">
                <strong>{{ __('ui.gallery.shot_rear_arches_title') }}</strong>
                <span>{{ __('ui.gallery.shot_rear_arches_copy') }}</span>
            </div>

            <div class="shot-row">
                <strong>{{ __('ui.gallery.shot_jacking_points_title') }}</strong>
                <span>{{ __('ui.gallery.shot_jacking_points_copy') }}</span>
            </div>

            <div class="shot-row">
                <strong>{{ __('ui.gallery.shot_fuel_tank_title') }}</strong>
                <span>{{ __('ui.gallery.shot_fuel_tank_copy') }}</span>
            </div>

            <div class="shot-row">
                <strong>{{ __('ui.gallery.shot_exhaust_title') }}</strong>
                <span>{{ __('ui.gallery.shot_exhaust_copy') }}</span>
            </div>
        </div>
    </section>

@endsection
