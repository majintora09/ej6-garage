@extends('layout')

@section('content')

    <div class="hero-card">
        <div>
            <h1>EJ6 Inspection Map</h1>

            <p class="hero-subtitle">
                Interactive rust and issue tracking system.
            </p>

            <div class="badge-row">
                <span class="badge">Rust Tracking</span>
                <span class="badge">3D Inspection</span>
                <span class="badge">Restoration Planner</span>
            </div>
        </div>
    </div>

    <div class="card inspection-card">

        <div id="car-viewer"></div>

        <div class="inspection-info">
            <h2>Inspection Notes</h2>

            <p>
                Click glowing points around the car to inspect common EJ6 problem areas.
            </p>

            <div id="inspection-output">
                No inspection point selected.
            </div>
        </div>

    </div>

    <script type="module" src="{{ asset('js/inspection.js') }}"></script>

@endsection
