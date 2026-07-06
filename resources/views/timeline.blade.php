@extends('layout')

@section('content')
    @php
        $timelineErrors = $errors ?? new \Illuminate\Support\ViewErrorBag;
        $carName = trim(($carProfile->year ? $carProfile->year.' ' : '').$carProfile->make.' '.$carProfile->model);
    @endphp

    @if (session('status'))
        <div class="alert-card success-card">
            <strong>{{ session('status') }}</strong>
        </div>
    @endif

    <div class="page-head">
        <div>
            <p class="eyebrow">{{ __('ui.timeline.eyebrow') }}</p>
            <h1>{{ __('ui.timeline.title') }}</h1>
            <p>{{ __('ui.timeline.intro', ['car' => $carName]) }}</p>
        </div>

        <div class="mini-spec-card">
            <span>{{ __('ui.timeline.entries') }}</span>
            <strong>{{ $entries->count() }}</strong>
            <small>{{ __('ui.timeline.entries_copy') }}</small>
        </div>
    </div>

    <div class="timeline-layout">
        <section class="panel">
            <div class="panel-title">
                <div>
                    <p class="eyebrow">{{ __('ui.timeline.new_entry') }}</p>
                    <h2>{{ __('ui.timeline.log_moment') }}</h2>
                </div>
            </div>

            <form action="{{ route('timeline.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <label>{{ __('ui.timeline.field_title') }}</label>
                <input type="text" name="title" value="{{ old('title') }}" placeholder="{{ __('ui.timeline.placeholder_title') }}" required>
                <x-input-error :messages="$timelineErrors->get('title')" class="auth-error" />

                <label>{{ __('ui.timeline.category') }}</label>
                <select name="category">
                    @foreach (['maintenance', 'mod', 'inspection', 'bodywork', 'paint', 'milestone', 'note'] as $category)
                        <option value="{{ $category }}" @selected(old('category') === $category)>{{ __("ui.timeline.categories.{$category}") }}</option>
                    @endforeach
                </select>

                <label>{{ __('ui.timeline.description') }}</label>
                <textarea name="description" placeholder="{{ __('ui.timeline.placeholder_description') }}">{{ old('description') }}</textarea>
                <x-input-error :messages="$timelineErrors->get('description')" class="auth-error" />

                <div class="form-grid">
                    <div>
                        <label>{{ __('ui.timeline.event_date') }}</label>
                        <input type="date" name="event_date" value="{{ old('event_date') }}">
                        <x-input-error :messages="$timelineErrors->get('event_date')" class="auth-error" />
                    </div>

                    <div>
                        <label>{{ __('ui.timeline.mileage') }}</label>
                        <input type="number" name="mileage" min="0" value="{{ old('mileage') }}">
                        <x-input-error :messages="$timelineErrors->get('mileage')" class="auth-error" />
                    </div>

                    <div>
                        <label>{{ __('ui.timeline.cost') }}</label>
                        <input type="number" name="cost" min="0" step="0.01" value="{{ old('cost') }}">
                        <x-input-error :messages="$timelineErrors->get('cost')" class="auth-error" />
                    </div>

                    <div>
                        <label>{{ __('ui.timeline.image') }}</label>
                        <input type="file" name="image" accept="image/*">
                        <x-input-error :messages="$timelineErrors->get('image')" class="auth-error" />
                    </div>
                </div>

                <button type="submit">{{ __('ui.timeline.save_entry') }}</button>
            </form>
        </section>

        <section class="panel">
            <div class="panel-title">
                <div>
                    <p class="eyebrow">{{ __('ui.timeline.archive') }}</p>
                    <h2>{{ __('ui.timeline.build_story') }}</h2>
                </div>
            </div>

            <div class="build-timeline">
                @forelse ($entries as $entry)
                    <article class="build-timeline-entry">
                        @if ($entry->image_path)
                            @if ($entryImageUrl = \App\Support\UploadedMedia::url($entry->image_path))
                                <img src="{{ $entryImageUrl }}" alt="{{ $entry->title }}" loading="lazy">
                            @else
                                <div class="missing-media">{{ __('ui.common.media_missing') }}</div>
                            @endif
                        @endif

                        <div>
                            <span>{{ $entry->event_date?->format('Y-m-d') ?: __('ui.common.no_date') }}</span>
                            <h3>{{ $entry->title }}</h3>
                            <p>{{ $entry->description ?: __('ui.common.no_notes') }}</p>

                            <div class="mod-meta">
                                <span>{{ __("ui.timeline.categories.".($entry->category ?: 'note')) }}</span>
                                @if ($entry->mileage)
                                    <span>{{ number_format($entry->mileage) }} km</span>
                                @endif
                                @if ($entry->cost)
                                    <span>€{{ number_format((float) $entry->cost, 2) }}</span>
                                @endif
                            </div>

                            <form action="{{ route('timeline.destroy', $entry) }}" method="POST" onsubmit="return confirm('{{ __('ui.timeline.confirm_delete') }}');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="danger-btn">{{ __('ui.common.delete') }}</button>
                            </form>
                        </div>
                    </article>
                @empty
                    <div class="empty-state timeline-empty">
                        <strong>{{ __('ui.timeline.empty_title') }}</strong>
                        <p>{{ __('ui.timeline.empty_copy') }}</p>
                    </div>
                @endforelse
            </div>
        </section>
    </div>
@endsection
