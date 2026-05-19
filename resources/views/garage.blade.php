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
        $buildVibe = $carProfile->build_vibe ?? __('ui.common.personal_garage_build_profile');
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

            <ul class="issues-list">
                <li>{{ __('ui.dashboard.issue_exhaust') }}</li>
                <li>{{ __('ui.dashboard.issue_oil') }}</li>
                <li>{{ __('ui.dashboard.issue_fuel') }}</li>
                <li>{{ __('ui.dashboard.issue_body') }}</li>
                <li>{{ __('ui.dashboard.issue_front') }}</li>
            </ul>
        </div>

        <div class="card">
            <h2>{{ __('ui.dashboard.build_direction') }}</h2>

            <p>
                {{ $buildVibe }}
            </p>

            <div class="progress-section">
                <div class="progress-label">
                    {{ __('ui.dashboard.restoration_progress') }}
                </div>

                <div class="progress-bar">
                    <div class="progress-fill"></div>
                </div>

                <p class="progress-text">{{ __('ui.dashboard.complete') }}</p>
            </div>
        </div>

        <div class="card">
            <h2>{{ __('ui.dashboard.future_plans') }}</h2>

            <ul class="issues-list">
                <li>{{ __('ui.dashboard.plan_body') }}</li>
                <li>{{ __('ui.dashboard.plan_exhaust') }}</li>
                <li>{{ __('ui.dashboard.plan_suspension') }}</li>
                <li>{{ __('ui.dashboard.plan_wheels') }}</li>
                <li>{{ __('ui.dashboard.plan_engine') }}</li>
                <li>{{ __('ui.dashboard.plan_inspection') }}</li>
            </ul>
        </div>

    </div>

@endsection
