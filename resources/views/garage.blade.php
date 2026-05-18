@extends('layout')

@section('content')

    <div class="hero-card">
        <div>
            <h1>1997 Honda Civic EJ6 Coupe</h1>
            <p class="hero-subtitle">
                G-82P-5 Dark Green • TYPE-K DK.GRAY • D16Y7
            </p>

            <div class="badge-row">
                <span class="badge">Dark Green Build</span>
                <span class="badge">Majin Vibe</span>
                <span class="badge">Clean JDM</span>
                <span class="badge">Restoration Project</span>
            </div>
        </div>
    </div>

    <div class="dashboard-grid">

        <div class="card">
            <h2>Car Identity</h2>

            <div class="spec-list">
                <div><strong>Chassis:</strong> EJ6 Coupe</div>
                <div><strong>Year:</strong> 1997</div>
                <div><strong>Engine:</strong> D16Y7</div>
                <div><strong>Drive:</strong> FWD</div>
                <div><strong>Color Code:</strong> G-82P-5</div>
                <div><strong>Interior:</strong> TYPE-K, DK.GRAY</div>
            </div>
        </div>

        <div class="card">
            <h2>Current Known Issues</h2>

            <ul class="issues-list">
                <li>Exhaust alignment / hanger issue</li>
                <li>Possible oil leak underneath</li>
                <li>Fuel tank leak concern</li>
                <li>Rust around arches / jacking points</li>
                <li>Front bumper/headlight alignment</li>
            </ul>
        </div>

        <div class="card">
            <h2>Current Build Direction</h2>

            <p>
                Dark green clean-JDM street build with subtle
                Majin / underground garage aesthetics.
            </p>

            <div class="progress-section">
                <div class="progress-label">
                    Restoration Progress
                </div>

                <div class="progress-bar">
                    <div class="progress-fill"></div>
                </div>

                <p class="progress-text">35% Complete</p>
            </div>
        </div>

        <div class="card">
            <h2>Future Plans</h2>

            <ul class="issues-list">
                <li>Fix rust correctly</li>
                <li>Proper exhaust setup</li>
                <li>Suspension setup</li>
                <li>Wheel + tire setup</li>
                <li>Possible B-series future</li>
                <li>Interactive 3D inspection map</li>
            </ul>
        </div>

    </div>

@endsection
