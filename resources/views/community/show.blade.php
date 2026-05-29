@extends('layout')

@section('content')
    <div class="page-head">
        <div>
            <p class="eyebrow">{{ __('ui.community.post_detail') }}</p>
            <h1>{{ $post->title }}</h1>
            <p>{{ __('ui.community.post_detail_copy') }}</p>
        </div>

        <a class="ghost-button" href="{{ route('community.index') }}">{{ __('ui.community.back_to_feed') }}</a>
    </div>

    <section class="post-detail-shell">
        @include('community.partials.post-card', ['post' => $post, 'variant' => 'detail'])
    </section>
@endsection
