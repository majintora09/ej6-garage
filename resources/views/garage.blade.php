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
        $buildVibe = $carProfile->build_vibe;
        $knownIssues = collect(preg_split('/\r\n|\r|\n/', (string) ($carProfile->known_issues ?? '')))
            ->map(fn ($issue) => trim($issue))
            ->filter();
        $futurePlans = collect(preg_split('/\r\n|\r|\n/', (string) ($carProfile->future_plans ?? '')))
            ->map(fn ($plan) => trim($plan))
            ->filter();
        $progress = $carProfile->restoration_progress;
    @endphp

    @if (session('status'))
        <div class="alert-card success-card">
            <strong>{{ session('status') }}</strong>
        </div>
    @endif

    <div class="hero-card">
        <div>
            <h1>{{ $year }} {{ $make }} {{ $model }}</h1>
            <p class="hero-subtitle">
                {{ $colorCode }} {{ $colorName }} • {{ $interior }} • {{ $engine }}
            </p>

            <div class="badge-row">
                <span class="badge">{{ $colorName }} {{ __('ui.nav.build_hub') }}</span>
                <span class="badge">{{ $chassis }}</span>
                <span class="badge">{{ $engine }}</span>
                <span class="badge">{{ __('ui.dashboard.personal_garage') }}</span>
            </div>
        </div>
    </div>

    <div class="dashboard-grid">

        <div class="card">
            <h2>{{ __('ui.dashboard.car_identity') }}</h2>

            <div class="spec-list">
                <div><strong>{{ __('ui.dashboard.make') }}:</strong> {{ $make }}</div>
                <div><strong>{{ __('ui.dashboard.model') }}:</strong> {{ $model }}</div>
                <div><strong>{{ __('ui.dashboard.chassis') }}:</strong> {{ $chassis }}</div>
                <div><strong>{{ __('ui.dashboard.year') }}:</strong> {{ $year }}</div>
                <div><strong>{{ __('ui.dashboard.engine') }}:</strong> {{ $engine }}</div>
                <div><strong>{{ __('ui.dashboard.color_code') }}:</strong> {{ $colorCode }}</div>
                <div><strong>{{ __('ui.dashboard.interior') }}:</strong> {{ $interior }}</div>
            </div>
        </div>

        <div class="card">
            <h2>{{ __('ui.dashboard.known_issues') }}</h2>

            @if ($knownIssues->isNotEmpty())
                <ul class="issues-list">
                    @foreach ($knownIssues as $issue)
                        <li>{{ $issue }}</li>
                    @endforeach
                </ul>
            @else
                <p class="muted">{{ __('ui.dashboard.add_content') }}</p>
            @endif
        </div>

        <div class="card">
            <h2>{{ __('ui.dashboard.build_direction') }}</h2>

            <p>{{ $buildVibe ?: __('ui.common.empty_profile_text') }}</p>

            @if (! is_null($progress))
                <div class="progress-section">
                    <div class="progress-label">
                        {{ __('ui.dashboard.restoration_progress') }}
                    </div>

                    <div class="progress-bar">
                        <div class="progress-fill" style="width: {{ max(0, min(100, $progress)) }}%;"></div>
                    </div>

                    <p class="progress-text">{{ $progress }}%</p>
                </div>
            @endif
        </div>

        <div class="card">
            <h2>{{ __('ui.dashboard.future_plans') }}</h2>

            @if ($futurePlans->isNotEmpty())
                <ul class="issues-list">
                    @foreach ($futurePlans as $plan)
                        <li>{{ $plan }}</li>
                    @endforeach
                </ul>
            @else
                <p class="muted">{{ __('ui.dashboard.add_content') }}</p>
            @endif
        </div>

    </div>

@endsection
