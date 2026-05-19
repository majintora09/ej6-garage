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
            <h1>Parts Encyclopedia</h1>
            <p class="hero-subtitle">
                Learn what each system does, what symptoms to watch for, and what upgrades make sense for {{ $carName }}.
            </p>

            <div class="badge-row">
                <span class="badge">{{ $engine }}</span>
                <span class="badge">{{ $chassis }}</span>
                <span class="badge">Maintenance First</span>
                <span class="badge">{{ __('ui.dashboard.personal_garage') }}</span>
            </div>
        </div>
    </div>

    <div class="parts-grid">

        <div class="part-card">
            <h2>Fuel Tank</h2>
            <p class="part-category">Fuel System</p>

            <p>
                Stores fuel and sends it toward the engine through the fuel pump and lines.
            </p>

            <h3>Symptoms</h3>
            <ul>
                <li>Fuel smell around the car</li>
                <li>Visible leak under rear area</li>
                <li>Bad fuel economy</li>
                <li>Rust around tank seams</li>
            </ul>

            <h3>Your Car Notes</h3>
            <p>
                Use this card as a general fuel-system reference for {{ $carName }}. Add actual fuel-system issues in Garage Details or the maintenance tracker.
            </p>
        </div>

        <div class="part-card">
            <h2>Exhaust System</h2>
            <p class="part-category">Engine / Sound</p>

            <p>
                Moves exhaust gases away from the engine and controls sound, emissions, and flow.
            </p>

            <h3>Symptoms</h3>
            <ul>
                <li>Rattling under the car</li>
                <li>Exhaust hanger slipping out</li>
                <li>Bad alignment</li>
                <li>Leaks or loud blowing sound</li>
            </ul>

            <h3>Your Car Notes</h3>
            <p>
                Use this as a general exhaust reference. Add the exact exhaust brand, alignment issue, or hanger problem in your own maintenance or mods entries.
            </p>
        </div>

        <div class="part-card">
            <h2>Rear Arches</h2>
            <p class="part-category">Body / Rust</p>

            <p>
                Wheel arch areas are common body condition checkpoints on older project cars.
            </p>

            <h3>Symptoms</h3>
            <ul>
                <li>Bubbles under paint</li>
                <li>Soft/crunchy metal</li>
                <li>Rust spreading near quarter panel</li>
                <li>Failed inspection risk</li>
            </ul>

            <h3>Repair Direction</h3>
            <p>
                Proper repair usually means cutting bad metal, welding fresh metal, sealing, primer, paint, and cavity protection.
            </p>
        </div>

        <div class="part-card">
            <h2>Jacking Points / Rockers</h2>
            <p class="part-category">Chassis / Safety</p>

            <p>
                These areas support the car when lifted and are important for structural safety.
            </p>

            <h3>Symptoms</h3>
            <ul>
                <li>Crunching when jacking the car</li>
                <li>Visible rust underneath</li>
                <li>Weak or bent pinch welds</li>
                <li>Inspection failure risk</li>
            </ul>

            <h3>Your Car Notes</h3>
            <p>
                Check these areas based on the real condition of {{ $carName }} before spending big money on cosmetics.
            </p>
        </div>

        <div class="part-card">
            <h2>Suspension</h2>
            <p class="part-category">Handling / Fitment</p>

            <p>
                Controls ride height, comfort, grip, and how the car feels in corners.
            </p>

            <h3>Common Mods</h3>
            <ul>
                <li>Coilovers</li>
                <li>Lowering springs</li>
                <li>Bushings</li>
                <li>Rear sway bar</li>
            </ul>

            <h3>Build Advice</h3>
            <p>
                Match suspension choices to the real use case, body type, wheel fitment and build vibe for {{ $carName }}.
            </p>
        </div>

        <div class="part-card">
            <h2>{{ $engine }}</h2>
            <p class="part-category">Engine</p>

            <p>
                Your saved engine profile should guide service checks, reliability work, and power goals.
            </p>

            <h3>Good Priorities</h3>
            <ul>
                <li>Oil leak check</li>
                <li>Valve cover gasket</li>
                <li>Cooling system health</li>
                <li>Timing belt history</li>
            </ul>

            <h3>Upgrade Direction</h3>
            <p>
                Keep the saved engine profile accurate. Future power plans should match {{ $engine }}, budget, local inspection rules and the actual chassis.
            </p>
        </div>

        <div class="part-card">
            <h2>Front Bumper / Headlights</h2>
            <p class="part-category">Body Alignment</p>

            <p>
                The bumper, brackets, headlights, and clips all affect how clean the front end looks.
            </p>

            <h3>Symptoms</h3>
            <ul>
                <li>Wobbly bumper</li>
                <li>Panel gaps</li>
                <li>Headlights not sitting right</li>
                <li>Broken clips or brackets</li>
            </ul>

            <h3>Your Car Notes</h3>
            <p>
                Use this as a general body-alignment reference. Add exact bumper, bracket or headlight notes in your own garage content.
            </p>
        </div>

        <div class="part-card">
            <h2>Wheels / Tires</h2>
            <p class="part-category">Fitment / Style</p>

            <p>
                Wheels change the car’s look massively, but wrong specs can rub or feel bad.
            </p>

            <h3>Clean Fitment Direction</h3>
            <ul>
                <li>Choose size based on chassis clearance</li>
                <li>Keep tire fitment realistic</li>
                <li>Avoid rubbing before chasing stance</li>
                <li>Match wheel style to saved color and build vibe</li>
            </ul>

            <h3>Vibe</h3>
            <p>
                Current build vibe: {{ $buildVibe }}
            </p>
        </div>

    </div>

@endsection
