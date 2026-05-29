@extends('layout')

@section('content')
    @php
        $carProfile = $carProfile ?? $currentCarProfile;
        $make = $carProfile->make;
        $model = $carProfile->model;
        $chassis = $carProfile->chassis ?? __('ui.common.unknown_chassis');
        $year = $carProfile->year ?? __('ui.common.unknown_year');
        $engine = $carProfile->engine ?? __('ui.common.unknown_engine');
        $colorName = $carProfile->color_name ?? __('ui.common.unknown_color');
        $colorCode = $carProfile->color_code ?? __('ui.common.no_color_code');
        $interior = $carProfile->interior ?? __('ui.common.not_set');
        $bodyType = $carProfile->body_type ? __("ui.body_types.{$carProfile->body_type}") : __('ui.common.not_set');
        $buildVibe = $carProfile->build_vibe ?: __('ui.common.empty_profile_text');
        $progress = $carProfile->restoration_progress ?? $garageHealth['score'];
        $formatMoney = fn ($value) => '€'.number_format((float) $value, 2);
        $publicGarageUrl = auth()->user()->profile_slug && $carProfile->slug && in_array($carProfile->visibility, ['public', 'unlisted'], true)
            ? route('public.garage', [auth()->user()->profile_slug, $carProfile->slug])
            : null;
    @endphp

    @if (session('status'))
        <div class="alert-card success-card">
            <strong>{{ session('status') }}</strong>
        </div>
    @endif

    <section class="garage-os-hero">
        <div class="garage-os-copy">
            <p class="eyebrow">{{ __('ui.dashboard.garage_os') }}</p>
            <h1>{{ $year }} {{ $make }} {{ $model }}</h1>
            <p>
                {{ __('ui.dashboard.workspace_intro', ['car' => trim($make.' '.$model)]) }}
            </p>

            <div class="badge-row">
                <span class="badge">{{ $colorCode }} {{ $colorName }}</span>
                <span class="badge">{{ $bodyType }}</span>
                <span class="badge">{{ $engine }}</span>
                <span class="badge">{{ $chassis }}</span>
            </div>

            <div class="hero-action-row">
                @if ($publicGarageUrl)
                    <a class="ghost-button" href="{{ $publicGarageUrl }}">{{ __('ui.public.view_my_garage') }}</a>
                    <button type="button" data-share-url="{{ $publicGarageUrl }}" data-copied-label="{{ __('ui.public.copied') }}">{{ __('ui.public.copy_public_link') }}</button>
                @else
                    <a class="ghost-button" href="{{ route('cars.index') }}">{{ __('ui.public.make_public_to_share') }}</a>
                @endif
            </div>
        </div>

        <div class="garage-health-card">
            <span>{{ __('ui.dashboard.garage_health') }}</span>
            <strong>{{ $garageHealth['score'] }}%</strong>
            <p>{{ $garageHealth['label'] }}</p>

            <div class="progress-bar">
                <div class="progress-fill" style="width: {{ $garageHealth['score'] }}%;"></div>
            </div>
        </div>
    </section>

    <section class="dashboard-grid product-metrics">
        <article class="card metric-card">
            <span>{{ __('ui.dashboard.total_spent') }}</span>
            <strong>{{ $formatMoney($costSummary['total_spent']) }}</strong>
            <p>{{ __('ui.dashboard.total_spent_copy') }}</p>
        </article>

        <article class="card metric-card">
            <span>{{ __('ui.dashboard.planned_spend') }}</span>
            <strong>{{ $formatMoney($costSummary['mods_planned']) }}</strong>
            <p>{{ __('ui.dashboard.planned_spend_copy') }}</p>
        </article>

        <article class="card metric-card">
            <span>{{ __('ui.dashboard.profile_completion') }}</span>
            <strong>{{ $progress }}%</strong>
            <p>{{ __('ui.dashboard.profile_completion_copy') }}</p>
        </article>
    </section>

    <div class="dashboard-command-grid">
        <section class="panel car-summary-panel">
            <div class="panel-title">
                <div>
                    <p class="eyebrow">{{ __('ui.dashboard.active_car') }}</p>
                    <h2>{{ __('ui.dashboard.car_identity') }}</h2>
                </div>
                <a class="text-link" href="{{ route('garage.profile.edit') }}">{{ __('ui.dashboard.edit_profile') }}</a>
            </div>

            <div class="spec-list compact-spec-list">
                <div><strong>{{ __('ui.dashboard.make') }}:</strong> {{ $make }}</div>
                <div><strong>{{ __('ui.dashboard.model') }}:</strong> {{ $model }}</div>
                <div><strong>{{ __('ui.dashboard.year') }}:</strong> {{ $year }}</div>
                <div><strong>{{ __('ui.dashboard.engine') }}:</strong> {{ $engine }}</div>
                <div><strong>{{ __('ui.setup.body_type') }}:</strong> {{ $bodyType }}</div>
                <div><strong>{{ __('ui.dashboard.interior') }}:</strong> {{ $interior }}</div>
            </div>

            <div class="dashboard-note">
                <strong>{{ __('ui.dashboard.build_direction') }}</strong>
                <p>{{ $buildVibe }}</p>
            </div>
        </section>

        <section class="panel">
            <div class="panel-title">
                <div>
                    <p class="eyebrow">{{ __('ui.dashboard.next_reminders') }}</p>
                    <h2>{{ __('ui.dashboard.maintenance_radar') }}</h2>
                </div>
                <a class="text-link" href="/maintenance">{{ __('ui.nav.maintenance') }}</a>
            </div>

            <div class="stack-list">
                @forelse ($nextReminders as $reminder)
                    <article class="signal-row {{ $reminder['status'] }}">
                        <div>
                            <strong>{{ $reminder['title'] }}</strong>
                            <span>
                                {{ $reminder['date'] ?: __('ui.common.no_date') }}
                                @if ($reminder['mileage'])
                                    / {{ $reminder['mileage'] }} km
                                @endif
                            </span>
                        </div>
                        <em>{{ __("ui.dashboard.reminder_{$reminder['status']}") }}</em>
                    </article>
                @empty
                    <div class="empty-state">
                        <strong>{{ __('ui.dashboard.no_reminders') }}</strong>
                        <p>{{ __('ui.dashboard.no_reminders_copy') }}</p>
                    </div>
                @endforelse
            </div>
        </section>
    </div>

    <div class="dashboard-command-grid">
        <section class="panel">
            <div class="panel-title">
                <div>
                    <p class="eyebrow">{{ __('ui.dashboard.current_projects') }}</p>
                    <h2>{{ __('ui.dashboard.work_queue') }}</h2>
                </div>
            </div>

            <div class="stack-list">
                @forelse ($currentProjects as $project)
                    <article class="timeline-row">
                        <div>
                            <strong>{{ $project['title'] }}</strong>
                            <span>{{ $project['meta'] }}</span>
                        </div>
                    </article>
                @empty
                    <div class="empty-state">
                        <strong>{{ __('ui.dashboard.no_projects') }}</strong>
                        <p>{{ __('ui.dashboard.no_projects_copy') }}</p>
                    </div>
                @endforelse
            </div>
        </section>

        <section class="panel ai-panel">
            <div class="panel-title">
                <div>
                    <p class="eyebrow">{{ __('ui.dashboard.ai_preview') }}</p>
                    <h2>{{ $recommendationPreview['title'] }}</h2>
                </div>
                <span class="status-badge">{{ $recommendationPreview['mode'] }}</span>
            </div>

            <p>{{ $recommendationPreview['copy'] }}</p>
            <a class="text-link" href="/mods">{{ __('ui.dashboard.open_recommendations') }}</a>
        </section>
    </div>

    <section class="dashboard-grid dashboard-preview-grid">
        <article class="panel">
            <div class="panel-title">
                <div>
                    <p class="eyebrow">{{ __('ui.dashboard.recent_maintenance') }}</p>
                    <h2>{{ __('ui.maintenance.history') }}</h2>
                </div>
            </div>

            <div class="stack-list">
                @forelse ($recentMaintenance as $maintenance)
                    <div class="timeline-row">
                        <div>
                            <strong>{{ $maintenance->title }}</strong>
                            <span>{{ $maintenance->service_date ?: __('ui.common.no_date') }} • {{ $maintenance->category ?: __('ui.common.no_category') }}</span>
                        </div>
                        <em>{{ $formatMoney($maintenance->cost ?? 0) }}</em>
                    </div>
                @empty
                    <div class="empty-state">
                        <strong>{{ __('ui.dashboard.no_maintenance') }}</strong>
                        <p>{{ __('ui.dashboard.no_maintenance_copy') }}</p>
                    </div>
                @endforelse
            </div>
        </article>

        <article class="panel">
            <div class="panel-title">
                <div>
                    <p class="eyebrow">{{ __('ui.dashboard.recent_mods') }}</p>
                    <h2>{{ __('ui.mods.current_build_list') }}</h2>
                </div>
            </div>

            <div class="stack-list">
                @forelse ($recentMods as $mod)
                    <div class="timeline-row">
                        <div>
                            <strong>{{ $mod->name }}</strong>
                            <span>{{ $mod->category ?: __('ui.common.no_category') }} • {{ $mod->status ?: __('ui.common.wanted') }}</span>
                        </div>
                        <em>{{ $formatMoney($mod->price ?? 0) }}</em>
                    </div>
                @empty
                    <div class="empty-state">
                        <strong>{{ __('ui.dashboard.no_mods') }}</strong>
                        <p>{{ __('ui.dashboard.no_mods_copy') }}</p>
                    </div>
                @endforelse
            </div>
        </article>

        <article class="panel">
            <div class="panel-title">
                <div>
                    <p class="eyebrow">{{ __('ui.dashboard.inspection_issues') }}</p>
                    <h2>{{ __('ui.inspection.notes') }}</h2>
                </div>
            </div>

            <div class="stack-list">
                @forelse ($latestInspectionPoints as $point)
                    <div class="timeline-row">
                        <div>
                            <strong>{{ $point->name }}</strong>
                            <span>{{ $point->category ?: __('ui.common.unsorted') }} • {{ $point->status ?: __('ui.categories.open') }}</span>
                        </div>
                        <em>{{ $point->priority ?: __('ui.common.no_priority') }}</em>
                    </div>
                @empty
                    <div class="empty-state">
                        <strong>{{ __('ui.dashboard.no_inspection') }}</strong>
                        <p>{{ __('ui.dashboard.no_inspection_copy') }}</p>
                    </div>
                @endforelse
            </div>
        </article>
    </section>

    <div class="dashboard-command-grid">
        <section class="panel">
            <div class="panel-title">
                <div>
                    <p class="eyebrow">{{ __('ui.dashboard.timeline_preview') }}</p>
                    <h2>{{ __('ui.timeline.title') }}</h2>
                </div>
                <a class="text-link" href="{{ route('timeline.index') }}">{{ __('ui.dashboard.open_timeline') }}</a>
            </div>

            <div class="stack-list">
                @forelse ($timelineEntries as $entry)
                    <article class="timeline-row">
                        <div>
                            <strong>{{ $entry->title }}</strong>
                            <span>{{ $entry->event_date?->format('Y-m-d') ?: __('ui.common.no_date') }} • {{ $entry->category ?: __('ui.common.no_category') }}</span>
                        </div>
                        @if ($entry->cost)
                            <em>{{ $formatMoney($entry->cost) }}</em>
                        @endif
                    </article>
                @empty
                    <div class="empty-state">
                        <strong>{{ __('ui.dashboard.no_timeline') }}</strong>
                        <p>{{ __('ui.dashboard.no_timeline_copy') }}</p>
                    </div>
                @endforelse
            </div>
        </section>

        <section class="panel">
            <div class="panel-title">
                <div>
                    <p class="eyebrow">{{ __('ui.dashboard.recent_gallery') }}</p>
                    <h2>{{ __('ui.gallery.title') }}</h2>
                </div>
                <a class="text-link" href="/gallery">{{ __('ui.nav.gallery') }}</a>
            </div>

            @if ($recentPhotos->isNotEmpty())
                <div class="dashboard-photo-strip">
                    @foreach ($recentPhotos as $photo)
                        @if (\Illuminate\Support\Facades\Storage::disk('public')->exists($photo->path))
                            <img src="{{ route('media.show', ['path' => $photo->path]) }}" alt="{{ $make }} {{ $model }} {{ __('ui.gallery.color_reference') }}" loading="lazy">
                        @else
                            <div class="missing-media">{{ __('ui.common.media_missing') }}</div>
                        @endif
                    @endforeach
                </div>
            @else
                <div class="empty-state">
                    <strong>{{ __('ui.gallery.no_photos') }}</strong>
                    <p>{{ __('ui.gallery.empty_copy') }}</p>
                </div>
            @endif
        </section>
    </div>
@endsection
