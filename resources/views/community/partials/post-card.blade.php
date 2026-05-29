@php
    $variant = $variant ?? 'feed';
    $isPreview = $variant !== 'detail';
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
    $likedByUser = (bool) ($post->liked_by_user ?? false);
    $postBody = (string) $post->body;
    $longPost = \Illuminate\Support\Str::length($postBody) > ($isPreview ? 320 : 700);
@endphp

<article class="feed-card {{ $imageUrl || $post->image_path ? 'has-media' : 'no-media' }} {{ $variant === 'detail' ? 'post-detail-card' : 'post-preview-card' }}">
    @if ($imageUrl)
        <a class="feed-media" href="{{ route('community.show', $post) }}">
            <img src="{{ $imageUrl }}" alt="{{ $post->title }}" loading="lazy">
        </a>
    @elseif ($post->image_path)
        <div class="missing-media feed-media">{{ __('ui.common.media_missing') }}</div>
    @endif

    <div class="feed-content">
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
            <h2><a href="{{ route('community.show', $post) }}">{{ $post->title }}</a></h2>
            @if ($postBody !== '')
                <div class="post-copy {{ $longPost && $isPreview ? 'is-collapsed' : '' }}" data-expandable-post>
                    {!! nl2br(e($isPreview ? \Illuminate\Support\Str::limit($postBody, 900) : $postBody)) !!}
                </div>
                @if ($longPost && $isPreview)
                    <button type="button" class="read-more-button" data-expand-button data-more-label="{{ __('ui.community.read_more') }}" data-less-label="{{ __('ui.community.show_less') }}">{{ __('ui.community.read_more') }}</button>
                @endif
            @endif
        </div>

        <div class="feed-actions">
            @auth
                <form action="{{ route('community.like', $post) }}" method="POST">
                    @csrf
                    <button type="submit" class="{{ $likedByUser ? 'active' : '' }}">
                        {{ $likedByUser ? __('ui.community.liked') : __('ui.community.like') }}
                        <span>{{ $post->likes_count }}</span>
                    </button>
                </form>
            @else
                <a class="ghost-button compact-action" href="{{ route('login') }}">{{ __('ui.community.login_to_like') }}</a>
            @endauth

            <a class="comment-link" href="{{ route('community.show', $post) }}#comments">
                {{ trans_choice('ui.community.comment_count', $post->comments_count, ['count' => $post->comments_count]) }}
            </a>

            @if ($isPreview)
                <a class="ghost-button compact-action" href="{{ route('community.show', $post) }}">{{ __('ui.community.open_post') }}</a>
            @endif

            @auth
                @if ($post->user_id === auth()->id())
                    <form action="{{ route('community.destroy', $post) }}" method="POST" onsubmit="return confirm('{{ __('ui.community.confirm_delete') }}');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="danger-btn">{{ __('ui.common.delete') }}</button>
                    </form>
                @endif
            @endauth
        </div>

        <div id="comments" class="comment-stack {{ $isPreview ? 'compact-comments' : '' }}">
            @foreach ($post->comments->sortBy('created_at') as $comment)
                <div class="comment-row">
                    <strong>{{ $comment->user->displayHandle() }}</strong>
                    <p>{!! nl2br(e($comment->body)) !!}</p>
                </div>
            @endforeach

            @auth
                <form action="{{ route('community.comments.store', $post) }}" method="POST" class="comment-form">
                    @csrf
                    <input name="body" type="text" maxlength="1200" placeholder="{{ __('ui.community.comment_placeholder') }}" required>
                    <button type="submit">{{ __('ui.community.comment') }}</button>
                </form>
            @else
                <a class="ghost-button compact-action" href="{{ route('login') }}">{{ __('ui.community.login_to_comment') }}</a>
            @endauth
        </div>
    </div>
</article>
