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
                @include('community.partials.post-card', ['post' => $post, 'variant' => 'feed'])
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
