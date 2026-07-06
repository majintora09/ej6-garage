@extends('layout')

@section('content')
    @php
        $mainPhoto = $car->photos->first();
        $mainPhotoUrl = \App\Support\UploadedMedia::url($mainPhoto?->path);
        $carName = trim(($car->year ? $car->year.' ' : '').$car->make.' '.$car->model);
    @endphp

    <section class="public-hero garage-public-hero" style="--theme: {{ $car->theme_color ?: '#8b5cf6' }}; @if($mainPhotoUrl) background-image: linear-gradient(180deg, rgba(0,0,0,0.2), rgba(0,0,0,0.8)), url('{{ $mainPhotoUrl }}') @endif">
        <p class="eyebrow">{{ __('ui.public.public_garage') }}</p>
        <h1>{{ $carName }}</h1>
        <p>{{ $car->build_vibe ?: __('ui.common.empty_profile_text') }}</p>
        <div class="public-actions">
            <a class="ghost-button" href="{{ route('public.profile', $user->profile_slug) }}">{{ __('ui.public.owner_profile') }}</a>
            <button type="button" data-share-url="{{ route('public.garage', [$user->profile_slug, $car->slug]) }}" data-copied-label="{{ __('ui.public.copied') }}" data-copy-prompt-label="{{ __('ui.public.copy_prompt') }}">{{ __('ui.public.share_garage') }}</button>
        </div>
    </section>

    <div class="public-grid">
        <section class="panel">
            <div class="panel-title">
                <div>
                    <p class="eyebrow">{{ __('ui.dashboard.car_identity') }}</p>
                    <h2>{{ __('ui.public.specs') }}</h2>
                </div>
            </div>

            <div class="spec-list">
                <p><strong>{{ __('ui.dashboard.chassis') }}:</strong> {{ $car->chassis ?: __('ui.common.unknown_chassis') }}</p>
                <p><strong>{{ __('ui.dashboard.engine') }}:</strong> {{ $car->engine ?: __('ui.common.unknown_engine') }}</p>
                <p><strong>{{ __('ui.setup.body_type') }}:</strong> {{ __("ui.body_types.".($car->body_type ?: 'other')) }}</p>
                <p><strong>{{ __('ui.common.color') }}:</strong> {{ $car->color_name ?: __('ui.common.unknown_color') }}</p>
            </div>
        </section>

        <section class="panel">
            <div class="panel-title">
                <div>
                    <p class="eyebrow">{{ __('ui.nav.mods') }}</p>
                    <h2>{{ __('ui.public.mods_summary') }}</h2>
                </div>
            </div>
            <div class="compact-feed">
                @forelse ($car->mods as $mod)
                    <article>
                        <span>{{ $mod->category ?: __('ui.common.no_category') }}</span>
                        <h3>{{ $mod->name }}</h3>
                        <p>{{ $mod->notes ?: __('ui.common.no_notes') }}</p>
                    </article>
                @empty
                    <div class="empty-state">
                        <strong>{{ __('ui.mods.no_mods') }}</strong>
                        <p>{{ __('ui.mods.no_mods_copy') }}</p>
                    </div>
                @endforelse
            </div>
        </section>
    </div>

    <section class="panel">
        <div class="panel-title">
            <div>
                <p class="eyebrow">{{ __('ui.public.recent_posts') }}</p>
                <h2>{{ __('ui.community.title') }}</h2>
            </div>
        </div>

        <div class="compact-feed">
            @forelse ($car->communityPosts as $post)
                @include('community.partials.post-card', ['post' => $post, 'variant' => 'compact'])
            @empty
                <div class="empty-state">
                    <strong>{{ __('ui.community.empty_title') }}</strong>
                    <p>{{ __('ui.community.empty_copy') }}</p>
                </div>
            @endforelse
        </div>
    </section>

    <section class="panel">
        <div class="panel-title">
            <div>
                <p class="eyebrow">{{ __('ui.nav.gallery') }}</p>
                <h2>{{ __('ui.public.public_gallery') }}</h2>
            </div>
        </div>

        <div class="public-gallery-grid">
            @forelse ($car->photos as $photo)
                <figure>
                    @if ($photoUrl = \App\Support\UploadedMedia::url($photo->path))
                        <img src="{{ $photoUrl }}" alt="{{ $photo->caption ?: $carName }}" loading="lazy" style="object-position: {{ \App\Support\UploadedMedia::position($photo->image_position) }};">
                    @else
                        <div class="missing-media">{{ __('ui.common.media_missing') }}</div>
                    @endif
                    <figcaption>{{ $photo->caption ?: __("ui.gallery.categories.".($photo->category ?: 'exterior')) }}</figcaption>
                </figure>
            @empty
                <div class="empty-state">
                    <strong>{{ __('ui.gallery.no_photos') }}</strong>
                    <p>{{ __('ui.gallery.empty_copy') }}</p>
                </div>
            @endforelse
        </div>
    </section>
@endsection
