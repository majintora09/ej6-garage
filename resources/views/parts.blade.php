@extends('layout')

@section('content')
    @php
        $car = $currentCarProfile;
        $carName = trim(($car?->year ? $car->year.' ' : '').($car?->make ?? __('ui.mods.your_car')).' '.($car?->model ?? ''));
        $chassis = $car?->chassis ?: __('ui.common.unknown_chassis');
        $engine = $car?->engine ?: __('ui.common.unknown_engine');
        $buildVibe = $car?->build_vibe ?: __('ui.common.empty_profile_text');
    @endphp

    <div class="hero-card">
        <div>
            <h1>{{ __('ui.parts.title') }}</h1>
            <p class="hero-subtitle">
                {{ __('ui.parts.intro', ['car' => $carName]) }}
            </p>

            <div class="badge-row">
                <span class="badge">{{ $engine }}</span>
                <span class="badge">{{ $chassis }}</span>
                <span class="badge">{{ __('ui.parts.maintenance_first') }}</span>
                <span class="badge">{{ __('ui.dashboard.personal_garage') }}</span>
            </div>
        </div>
    </div>

    <div class="parts-grid">

        <div class="part-card">
            <h2>{{ __('ui.parts.fuel_tank') }}</h2>
            <p class="part-category">{{ __('ui.parts.fuel_system') }}</p>

            <p>{{ __('ui.parts.fuel_tank_copy') }}</p>

            <h3>{{ __('ui.parts.symptoms') }}</h3>
            <ul>
                @foreach (__('ui.parts.fuel_symptoms') as $symptom)
                    <li>{{ $symptom }}</li>
                @endforeach
            </ul>

            <h3>{{ __('ui.parts.your_car_notes') }}</h3>
            <p>{{ __('ui.parts.fuel_notes', ['car' => $carName]) }}</p>
        </div>

        <div class="part-card">
            <h2>{{ __('ui.parts.exhaust_system') }}</h2>
            <p class="part-category">{{ __('ui.parts.engine_sound') }}</p>

            <p>{{ __('ui.parts.exhaust_copy') }}</p>

            <h3>{{ __('ui.parts.symptoms') }}</h3>
            <ul>
                @foreach (__('ui.parts.exhaust_symptoms') as $symptom)
                    <li>{{ $symptom }}</li>
                @endforeach
            </ul>

            <h3>{{ __('ui.parts.your_car_notes') }}</h3>
            <p>{{ __('ui.parts.exhaust_notes') }}</p>
        </div>

        <div class="part-card">
            <h2>{{ __('ui.parts.rear_arches') }}</h2>
            <p class="part-category">{{ __('ui.parts.body_rust') }}</p>

            <p>{{ __('ui.parts.rear_arches_copy') }}</p>

            <h3>{{ __('ui.parts.symptoms') }}</h3>
            <ul>
                @foreach (__('ui.parts.rear_arches_symptoms') as $symptom)
                    <li>{{ $symptom }}</li>
                @endforeach
            </ul>

            <h3>{{ __('ui.parts.repair_direction') }}</h3>
            <p>{{ __('ui.parts.rear_arches_repair') }}</p>
        </div>

        <div class="part-card">
            <h2>{{ __('ui.parts.jacking_points') }}</h2>
            <p class="part-category">{{ __('ui.parts.chassis_safety') }}</p>

            <p>{{ __('ui.parts.jacking_points_copy') }}</p>

            <h3>{{ __('ui.parts.symptoms') }}</h3>
            <ul>
                @foreach (__('ui.parts.jacking_points_symptoms') as $symptom)
                    <li>{{ $symptom }}</li>
                @endforeach
            </ul>

            <h3>{{ __('ui.parts.your_car_notes') }}</h3>
            <p>{{ __('ui.parts.jacking_points_notes', ['car' => $carName]) }}</p>
        </div>

        <div class="part-card">
            <h2>{{ __('ui.categories.suspension') }}</h2>
            <p class="part-category">{{ __('ui.parts.handling_fitment') }}</p>

            <p>{{ __('ui.parts.suspension_copy') }}</p>

            <h3>{{ __('ui.parts.common_mods') }}</h3>
            <ul>
                @foreach (__('ui.parts.suspension_mods') as $mod)
                    <li>{{ $mod }}</li>
                @endforeach
            </ul>

            <h3>{{ __('ui.parts.build_advice') }}</h3>
            <p>{{ __('ui.parts.suspension_advice', ['car' => $carName]) }}</p>
        </div>

        <div class="part-card">
            <h2>{{ $engine }}</h2>
            <p class="part-category">{{ __('ui.dashboard.engine') }}</p>

            <p>{{ __('ui.parts.engine_copy') }}</p>

            <h3>{{ __('ui.parts.good_priorities') }}</h3>
            <ul>
                @foreach (__('ui.parts.engine_priorities') as $priority)
                    <li>{{ $priority }}</li>
                @endforeach
            </ul>

            <h3>{{ __('ui.parts.upgrade_direction') }}</h3>
            <p>{{ __('ui.parts.engine_upgrade', ['engine' => $engine]) }}</p>
        </div>

        <div class="part-card">
            <h2>{{ __('ui.parts.front_bumper') }}</h2>
            <p class="part-category">{{ __('ui.parts.body_alignment') }}</p>

            <p>{{ __('ui.parts.front_bumper_copy') }}</p>

            <h3>{{ __('ui.parts.symptoms') }}</h3>
            <ul>
                @foreach (__('ui.parts.front_bumper_symptoms') as $symptom)
                    <li>{{ $symptom }}</li>
                @endforeach
            </ul>

            <h3>{{ __('ui.parts.your_car_notes') }}</h3>
            <p>{{ __('ui.parts.front_bumper_notes') }}</p>
        </div>

        <div class="part-card">
            <h2>{{ __('ui.parts.wheels_tires') }}</h2>
            <p class="part-category">{{ __('ui.parts.fitment_style') }}</p>

            <p>{{ __('ui.parts.wheels_copy') }}</p>

            <h3>{{ __('ui.parts.clean_fitment_direction') }}</h3>
            <ul>
                @foreach (__('ui.parts.wheels_direction') as $direction)
                    <li>{{ $direction }}</li>
                @endforeach
            </ul>

            <h3>{{ __('ui.parts.vibe') }}</h3>
            <p>{{ __('ui.parts.current_build_vibe', ['vibe' => $buildVibe]) }}</p>
        </div>

    </div>

@endsection
