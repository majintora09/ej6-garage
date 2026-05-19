@extends('layout')

@section('content')
    @php
        $car = $currentCarProfile;
        $carName = trim(($car->year ? $car->year.' ' : '').$car->make.' '.$car->model);
        $priorityAlbum = $car->chassis ?: 'Profile';
        $photos = $car->photos ?? collect();
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
            <strong>{{ $car->color_code ?: 'Custom' }}</strong>
            <p>{{ $car->color_name ?: 'Themeable' }} {{ __('ui.gallery.reference_board') }}</p>
        </div>
    </div>

    <section class="panel color-reference-panel">
        <div class="panel-title">
            <div>
                <p class="eyebrow">{{ __('ui.gallery.color_reference') }}</p>
                <h2>{{ $car->color_name ?: __('ui.common.unknown_color') }} {{ __('ui.gallery.checkpoint') }}</h2>
            </div>

            <div class="color-chip" style="--chip-color: {{ $car->theme_color ?: '#76ff9f' }}">
                <span></span>
                <strong>{{ $car->color_code ?: 'Theme' }}</strong>
            </div>
        </div>

        <div class="reference-console">
            <p>
                {{ __('ui.gallery.console') }}
            </p>

            <a class="text-link" href="{{ route('garage.profile.edit') }}">{{ __('ui.gallery.manage') }}</a>
        </div>

        @if ($photos->isNotEmpty())
            <div class="car-photo-grid">
                @foreach ($photos as $photo)
                    <figure class="car-photo-card">
                        <img src="{{ route('car-photos.show', $photo) }}" alt="{{ $carName }} color reference photo">
                        <figcaption>
                            <span>{{ __('ui.gallery.paint_ref') }} {{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}</span>
                            <strong>{{ $photo->original_name ?: 'Color reference' }}</strong>
                        </figcaption>
                    </figure>
                @endforeach
            </div>
        @else
            <div class="gallery-empty color-empty">
                <div>
                    <strong>{{ __('ui.gallery.no_photos') }}</strong>
                    <p>{{ __('ui.gallery.empty_copy') }}</p>
                    <a class="text-link" href="{{ route('garage.profile.edit') }}">{{ __('ui.gallery.add_photos') }}</a>
                </div>
            </div>
        @endif
    </section>

    <section class="panel">
        <div class="panel-title">
            <div>
                <p class="eyebrow">{{ __('ui.gallery.archive_map') }}</p>
                <h2>{{ __('ui.gallery.photo_bays_title') }}</h2>
            </div>
        </div>

        <div class="gallery-grid">
            <article class="gallery-bay priority-bay">
                <span>01</span>
                <h3>{{ __('ui.gallery.bodywork') }}</h3>
                <p>{{ __('ui.gallery.bodywork_copy') }}</p>
                <small>{{ __('ui.gallery.high_priority') }}</small>
            </article>

            <article class="gallery-bay">
                <span>02</span>
                <h3>{{ __('ui.gallery.maintenance_receipts') }}</h3>
                <p>{{ __('ui.gallery.maintenance_copy') }}</p>
                <small>{{ __('ui.gallery.service_proof') }}</small>
            </article>

            <article class="gallery-bay">
                <span>03</span>
                <h3>{{ __('ui.gallery.parts_mods') }}</h3>
                <p>{{ __('ui.gallery.parts_copy') }}</p>
                <small>{{ __('ui.gallery.build_planner_link') }}</small>
            </article>

            <article class="gallery-bay">
                <span>04</span>
                <h3>{{ __('ui.gallery.paint_styling') }}</h3>
                <p>{{ $car->color_code ?: 'Color' }} {{ $car->color_name ?: 'style' }} references, lighting, wheels and exterior details.</p>
                <small>{{ __('ui.gallery.visual_direction') }}</small>
            </article>

            <article class="gallery-bay">
                <span>05</span>
                <h3>{{ __('ui.gallery.interior') }}</h3>
                <p>{{ $car->interior ?: 'Cabin' }} photos, trim condition, seats, plastics and subtle accent plans.</p>
                <small>{{ __('ui.gallery.cabin_archive') }}</small>
            </article>

            <article class="gallery-bay">
                <span>06</span>
                <h3>{{ __('ui.gallery.inspection_captures') }}</h3>
                <p>{{ __('ui.gallery.inspection_copy') }}</p>
                <small>{{ __('ui.gallery.linked_3d') }}</small>
            </article>
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
                <strong>Rear arches</strong>
                <span>Wide shot, close-up, underside angle</span>
            </div>

            <div class="shot-row">
                <strong>Jacking points</strong>
                <span>Both sides, pinch welds, rocker condition</span>
            </div>

            <div class="shot-row">
                <strong>Fuel tank area</strong>
                <span>Leak evidence, straps, corrosion, line condition</span>
            </div>

            <div class="shot-row">
                <strong>Exhaust alignment</strong>
                <span>Hanger position, rear fitment, underside route</span>
            </div>
        </div>
    </section>

@endsection
