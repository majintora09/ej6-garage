@extends('layout')

@section('content')
    @php
        $categories = ['update', 'question', 'showcase', 'poll', 'trip', 'repair', 'mod'];
    @endphp

    <div class="page-head">
        <div>
            <p class="eyebrow">{{ __('ui.community.eyebrow') }}</p>
            <h1>{{ __('ui.community.title') }}</h1>
            <p>{{ __('ui.community.intro') }}</p>
        </div>

        <div class="mini-spec-card">
            <span>{{ __('ui.community.posting_as') }}</span>
            <strong>{{ $activeCar ? trim($activeCar->make.' '.$activeCar->model) : __('ui.common.not_set') }}</strong>
            <small>{{ __('ui.community.public_hint') }}</small>
        </div>
    </div>

    @if (session('status'))
        <div class="alert-card success-card">
            <strong>{{ session('status') }}</strong>
        </div>
    @endif

    <div class="community-layout">
        <aside class="panel composer-panel">
            <div class="panel-title">
                <div>
                    <p class="eyebrow">{{ __('ui.community.composer') }}</p>
                    <h2>{{ __('ui.community.create_post') }}</h2>
                </div>
            </div>

            <form action="{{ route('community.store') }}" method="POST" enctype="multipart/form-data" class="setup-form">
                @csrf

                <label for="title">{{ __('ui.community.field_title') }}</label>
                <input id="title" name="title" type="text" value="{{ old('title') }}" required maxlength="140" placeholder="{{ __('ui.community.title_placeholder') }}">
                <x-input-error :messages="$errors->get('title')" class="auth-error" />

                <div class="form-grid">
                    <div>
                        <label for="category">{{ __('ui.community.category') }}</label>
                        <select id="category" name="category">
                            @foreach ($categories as $category)
                                <option value="{{ $category }}" @selected(old('category', 'update') === $category)>{{ __("ui.community.categories.{$category}") }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="visibility">{{ __('ui.cars.visibility') }}</label>
                        <select id="visibility" name="visibility">
                            @foreach (['public', 'unlisted', 'private'] as $visibility)
                                <option value="{{ $visibility }}" @selected(old('visibility', 'public') === $visibility)>{{ __("ui.cars.visibility_{$visibility}") }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <label for="body">{{ __('ui.community.body') }}</label>
                <textarea id="body" name="body" rows="5" placeholder="{{ __('ui.community.body_placeholder') }}">{{ old('body') }}</textarea>
                <x-input-error :messages="$errors->get('body')" class="auth-error" />

                <label for="image">{{ __('ui.community.image') }}</label>
                <input id="image" name="image" type="file" accept="image/*">
                <x-input-error :messages="$errors->get('image')" class="auth-error" />

                <button type="submit">{{ __('ui.community.publish') }}</button>
            </form>
        </aside>

        <section class="feed-stack">
            @forelse ($posts as $post)
                @php
                    $avatarUrl = $post->user->avatar_path && \Illuminate\Support\Facades\Storage::disk('public')->exists($post->user->avatar_path)
                        ? route('media.show', ['path' => $post->user->avatar_path])
                        : null;
                    $imageUrl = $post->image_path && \Illuminate\Support\Facades\Storage::disk('public')->exists($post->image_path)
                        ? route('media.show', ['path' => $post->image_path])
                        : null;
                    $car = $post->carProfile;
                    $profileUrl = $post->user->profile_slug ? route('public.profile', $post->user->profile_slug) : null;
                    $garageUrl = $profileUrl && $car && $car->slug && in_array($car->visibility, ['public', 'unlisted'], true)
                        ? route('public.garage', [$post->user->profile_slug, $car->slug])
                        : null;
                @endphp

                <article class="feed-card">
                    <div class="feed-author">
                        <a class="profile-avatar" @if($profileUrl) href="{{ $profileUrl }}" @endif>
                            @if ($avatarUrl)
                                <img src="{{ $avatarUrl }}" alt="{{ $post->user->displayHandle() }}" loading="lazy">
                            @else
                                <span>{{ strtoupper(substr($post->user->displayHandle(), 0, 2)) }}</span>
                            @endif
                        </a>

                        <div>
                            <strong>
                                @if ($profileUrl)
                                    <a href="{{ $profileUrl }}">{{ $post->user->displayHandle() }}</a>
                                @else
                                    {{ $post->user->displayHandle() }}
                                @endif
                            </strong>
                            <p>
                                @if ($garageUrl)
                                    <a href="{{ $garageUrl }}">{{ trim(($car->year ? $car->year.' ' : '').$car->make.' '.$car->model) }}</a>
                                @elseif ($car)
                                    {{ trim(($car->year ? $car->year.' ' : '').$car->make.' '.$car->model) }}
                                @else
                                    {{ __('ui.community.no_car') }}
                                @endif
                                <span>{{ $post->created_at->diffForHumans() }}</span>
                            </p>
                        </div>

                        <span class="feed-category">{{ __("ui.community.categories.{$post->category}") }}</span>
                    </div>

                    <div class="feed-body">
                        <h2>{{ $post->title }}</h2>
                        @if ($post->body)
                            <div class="post-copy {{ \Illuminate\Support\Str::length($post->body) > 420 ? 'is-collapsed' : '' }}" data-expandable-post>
                                {!! nl2br(e($post->body)) !!}
                            </div>
                            @if (\Illuminate\Support\Str::length($post->body) > 420)
                                <button type="button" class="read-more-button" data-expand-button data-more-label="{{ __('ui.community.read_more') }}" data-less-label="{{ __('ui.community.show_less') }}">{{ __('ui.community.read_more') }}</button>
                            @endif
                        @endif
                    </div>

                    @if ($imageUrl)
                        <img class="feed-image" src="{{ $imageUrl }}" alt="{{ $post->title }}" loading="lazy">
                    @elseif ($post->image_path)
                        <div class="missing-media feed-missing-media">{{ __('ui.common.media_missing') }}</div>
                    @endif

                    <div class="feed-actions">
                        <form action="{{ route('community.like', $post) }}" method="POST">
                            @csrf
                            <button type="submit" class="{{ $post->liked_by_user ? 'active' : '' }}">
                                {{ $post->liked_by_user ? __('ui.community.liked') : __('ui.community.like') }}
                                <span>{{ $post->likes_count }}</span>
                            </button>
                        </form>

                        <span>{{ trans_choice('ui.community.comment_count', $post->comments_count, ['count' => $post->comments_count]) }}</span>

                        @if ($post->user_id === auth()->id())
                            <form action="{{ route('community.destroy', $post) }}" method="POST" onsubmit="return confirm('{{ __('ui.community.confirm_delete') }}');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="danger-btn">{{ __('ui.common.delete') }}</button>
                            </form>
                        @endif
                    </div>

                    <div class="comment-stack">
                        @foreach ($post->comments->sortBy('created_at') as $comment)
                            <div class="comment-row">
                                <strong>{{ $comment->user->displayHandle() }}</strong>
                                <p>{{ $comment->body }}</p>
                            </div>
                        @endforeach

                        <form action="{{ route('community.comments.store', $post) }}" method="POST" class="comment-form">
                            @csrf
                            <input name="body" type="text" maxlength="1200" placeholder="{{ __('ui.community.comment_placeholder') }}" required>
                            <button type="submit">{{ __('ui.community.comment') }}</button>
                        </form>
                    </div>
                </article>
            @empty
                <div class="empty-state">
                    <strong>{{ __('ui.community.empty_title') }}</strong>
                    <p>{{ __('ui.community.empty_copy') }}</p>
                </div>
            @endforelse

            {{ $posts->links() }}
        </section>
    </div>
@endsection
