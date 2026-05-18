@extends('layout')

@section('content')

    <div class="hero-card">
        <div>
            <h1>EJ6 Inspection Map</h1>

            <p class="hero-subtitle">
                Move around your 3D Civic, create custom inspection points, and connect them to maintenance categories.
            </p>

            <div class="badge-row">
                <span class="badge">Editor Mode</span>
                <span class="badge">Rust Tracking</span>
                <span class="badge">Lighting Presets</span>
                <span class="badge">Maintenance Linked</span>
            </div>
        </div>
    </div>

    <div class="inspection-toolbar card">
        <button id="editor-toggle" type="button">Editor Mode: OFF</button>

        <button type="button" onclick="setLightingMode('garage')">Garage</button>
        <button type="button" onclick="setLightingMode('night')">Night</button>
        <button type="button" onclick="setLightingMode('inspection')">Inspection</button>
        <button type="button" onclick="setLightingMode('majin')">Majin</button>
    </div>

    <div class="card inspection-card">

        <div id="car-viewer"></div>

        <div class="inspection-info">
            <h2>Inspection Notes</h2>

            <p>
                Click glowing points to inspect issues. Turn editor mode ON, then click the car to create your own points.
            </p>

            <div id="inspection-output">
                No inspection point selected.
            </div>

            <div id="editor-panel" class="editor-panel hidden">
                <h3>Create Inspection Point</h3>

                <input id="point-name" type="text" placeholder="Point name">

                <select id="point-category">
                    <option value="Body">Body</option>
                    <option value="Rust">Rust</option>
                    <option value="Under Hood">Under Hood</option>
                    <option value="Fuel System">Fuel System</option>
                    <option value="Exhaust">Exhaust</option>
                    <option value="Suspension">Suspension</option>
                    <option value="Brakes">Brakes</option>
                    <option value="Interior">Interior</option>
                </select>

                <select id="point-priority">
                    <option value="Low">Low Priority</option>
                    <option value="Medium">Medium Priority</option>
                    <option value="High">High Priority</option>
                </select>

                <select id="point-status">
                    <option value="Open">Open</option>
                    <option value="Watching">Watching</option>
                    <option value="Fixed">Fixed</option>
                </select>

                <textarea id="point-description" placeholder="Description"></textarea>

                <button id="save-point" type="button">Save Point</button>
            </div>
        </div>

    </div>

    <script>
        window.savedInspectionPoints = @json($points);
        window.maintenanceByCategory = @json($maintenances);
        window.csrfToken = "{{ csrf_token() }}";
    </script>

    <script type="importmap">
        {
            "imports": {
                "three": "https://cdn.jsdelivr.net/npm/three@0.161.0/build/three.module.js",
                "three/addons/": "https://cdn.jsdelivr.net/npm/three@0.161.0/examples/jsm/"
            }
        }
    </script>

    <script type="module" src="{{ asset('js/inspection.js') }}"></script>

@endsection
