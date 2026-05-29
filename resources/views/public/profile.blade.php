@extends('layout')

@section('content')
    @php
        $avatarUrl = $user->avatar_path ? \Illuminate\Support\Facades\Storage::url($user->avatar_path) : null;
        $bannerUrl = $user->banner_path ? \Illuminate\Support\Facades\Storage::url($user->banner_path) : null;
    @endphp

    <section class="public-hero" @if($bannerUrl) style="background-image: linear-gradient(180deg, rgba(0,0,0,0.18), rgba(0,0,0,0.78)), url('{{ $bannerUrl }}')" @endif>
        <div class="profile-avatar xl">
            @if ($avatarUrl)
                <img src="{{ $avatarUrl }}" alt="{{ $user->displayHandle() }}" loading="lazy">
            @else
                <span>{{ strtoupper(substr($user->displayHandle(), 0, 2)) }}</span>
            @endif
        </div>
        <p class="eyebrow">{{ __('ui.public.driver_profile') }}</p>
        <h1>{{ $user->displayHandle() }}</h1>
        <p>{{ $user->bio ?: __('ui.public.no_bio') }}</p>
        @if ($user->location)
            <span class="feed-category">{{ $user->location }}</span>
        @endif
    </section>

    <div class="public-grid">
        <section class="panel">
            <div class="panel-title">
                <div>
                    <p class="eyebrow">{{ __('ui.public.public_cars') }}</p>
                    <h2>{{ __('ui.public.garages') }}</h2>
                </div>
            </div>

            <div class="car-roster">
                @forelse ($user->carProfiles as $car)
                    <article class="car-roster-card">
                        <div class="car-roster-head">
                            <div>
                                <span>{{ $car->chassis ?: __('ui.common.unknown_chassis') }}</span>
                                <h3>{{ trim(($car->year ? $car->year.' ' : '').$car->make.' '.$car->model) }}</h3>
                                <p>{{ $car->build_vibe ?: __('ui.common.empty_profile_text') }}</p>
                            </div>
                        </div>
                        <div class="mod-meta">
                            <span>{{ $car->photos_count }} {{ __('ui.nav.gallery') }}</span>
                            <span>{{ $car->mods_count }} {{ __('ui.nav.mods') }}</span>
                            <span>{{ $car->community_posts_count }} {{ __('ui.community.posts') }}</span>
                        </div>
                        @if ($car->slug)
                            <a class="ghost-button" href="{{ route('public.garage', [$user->profile_slug, $car->slug]) }}">{{ __('ui.public.view_garage') }}</a>
                        @endif
                    </article>
                @empty
                    <div class="empty-state">
                        <strong>{{ __('ui.public.no_public_cars') }}</strong>
                        <p>{{ __('ui.public.no_public_cars_copy') }}</p>
                    </div>
                @endforelse
            </div>
        </section>

        <section class="panel">
            <div class="panel-title">
                <div>
                    <p class="eyebrow">{{ __('ui.public.recent_posts') }}</p>
                    <h2>{{ __('ui.community.title') }}</h2>
                </div>
            </div>

            <div class="compact-feed">
                @forelse ($user->communityPosts as $post)
                    <article>
                        <span>{{ __("ui.community.categories.{$post->category}") }}</span>
                        <h3>{{ $post->title }}</h3>
                        <p>{{ $post->body ? \Illuminate\Support\Str::limit($post->body, 130) : __('ui.public.no_post_body') }}</p>
                        <small>{{ $post->likes_count }} {{ __('ui.community.likes') }} • {{ $post->comments_count }} {{ __('ui.community.comments') }}</small>
                    </article>
                @empty
                    <div class="empty-state">
                        <strong>{{ __('ui.community.empty_title') }}</strong>
                        <p>{{ __('ui.community.empty_copy') }}</p>
                    </div>
                @endforelse
            </div>
        </section>
    </div>
@endsection
