@extends('layout')

@section('content')

    <div class="hero-card">
        <div>
            <h1>EJ6 Parts Encyclopedia</h1>
            <p class="hero-subtitle">
                Learn what each system does, what symptoms to watch for, and what upgrades make sense for your build.
            </p>

            <div class="badge-row">
                <span class="badge">D16Y7</span>
                <span class="badge">EJ6 Coupe</span>
                <span class="badge">Maintenance First</span>
                <span class="badge">Clean JDM Build</span>
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

            <h3>Your EJ6 Notes</h3>
            <p>
                You already had concern about a possible fuel tank leak, so this should be treated as high priority before mods.
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

            <h3>Your EJ6 Notes</h3>
            <p>
                Your Magnaflow/exhaust alignment and hanger issue should be fixed properly before chasing sound upgrades.
            </p>
        </div>

        <div class="part-card">
            <h2>Rear Arches</h2>
            <p class="part-category">Body / Rust</p>

            <p>
                The rear wheel arch area is a common rust point on older Civics.
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

            <h3>Your EJ6 Notes</h3>
            <p>
                This should be checked together with rear arches before spending big money on cosmetics.
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
                For your clean JDM vibe, suspension should come after rust and safety fixes.
            </p>
        </div>

        <div class="part-card">
            <h2>D16Y7 Engine</h2>
            <p class="part-category">Engine</p>

            <p>
                The D16Y7 is your stock single-cam non-VTEC engine. Good for reliability, not huge power.
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
                Keep it healthy for now. Bigger power would make more sense later with a B-series or K-series plan.
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

            <h3>Your EJ6 Notes</h3>
            <p>
                You mentioned the front low area/bumper feels wobbly and misaligned with the headlights.
            </p>
        </div>

        <div class="part-card">
            <h2>Wheels / Tires</h2>
            <p class="part-category">Fitment / Style</p>

            <p>
                Wheels change the car’s look massively, but wrong specs can rub or feel bad.
            </p>

            <h3>Clean EJ6 Direction</h3>
            <ul>
                <li>15 or 16 inch wheels</li>
                <li>Good tire fitment</li>
                <li>No extreme stance first</li>
                <li>Match dark green body color</li>
            </ul>

            <h3>Vibe</h3>
            <p>
                Dark green body, subtle midnight-purple accents, clean JDM wheel choice.
            </p>
        </div>

    </div>

@endsection
