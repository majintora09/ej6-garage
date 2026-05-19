@extends('layout')

@section('content')
    @php
        $car = $currentCarProfile;
        $carName = trim(($car->year ? $car->year.' ' : '').$car->make.' '.$car->model);
        $bodyType = $carProfile->body_type ?? 'coupe';
        $customModelPath = $carProfile->model_path ?? null;
    @endphp

    <div class="hero-card">
        <div>
            <h1>{{ __('ui.inspection.title') }}</h1>

            <p class="hero-subtitle">
                {{ __('ui.inspection.intro', ['car' => $carName]) }}
            </p>

            <div class="badge-row">
                <span class="badge">{{ __('ui.inspection.editor_mode') }}</span>
                <span class="badge">{{ __('ui.inspection.strategy', ['type' => ucfirst($bodyType)]) }}</span>
                <span class="badge">{{ __('ui.inspection.glb') }}</span>
                <span class="badge">{{ __('ui.inspection.lighting') }}</span>
                <span class="badge">{{ __('ui.inspection.linked') }}</span>
            </div>
        </div>
    </div>

    <div class="inspection-toolbar card">
        <button id="editor-toggle" type="button">{{ __('ui.inspection.editor_off') }}</button>

        <button type="button" onclick="setLightingMode('garage')">{{ __('ui.inspection.garage') }}</button>
        <button type="button" onclick="setLightingMode('night')">{{ __('ui.inspection.night') }}</button>
        <button type="button" onclick="setLightingMode('inspection')">{{ __('ui.inspection.inspection') }}</button>
        <button type="button" onclick="setLightingMode('majin')">{{ __('ui.inspection.accent') }}</button>
    </div>

    <div class="card inspection-card">

        <div id="car-viewer"></div>

        <div class="inspection-info">
            <h2>{{ __('ui.inspection.notes') }}</h2>

            <p>
                {{ __('ui.inspection.help') }}
            </p>

            <p id="model-status" class="model-status">
                {{ __('ui.inspection.loading') }}
            </p>

            <div id="inspection-output">
                {{ __('ui.inspection.none_selected') }}
            </div>

            <div id="editor-panel" class="editor-panel hidden">
                <h3>{{ __('ui.inspection.create_point') }}</h3>

                <input id="point-name" type="text" placeholder="{{ __('ui.inspection.point_name') }}">

                <select id="point-category">
                    <option value="Body">{{ __('ui.categories.body') }}</option>
                    <option value="Rust">{{ __('ui.categories.rust') }}</option>
                    <option value="Under Hood">{{ __('ui.categories.under_hood') }}</option>
                    <option value="Fuel System">{{ __('ui.categories.fuel_system') }}</option>
                    <option value="Exhaust">{{ __('ui.categories.exhaust') }}</option>
                    <option value="Suspension">{{ __('ui.categories.suspension') }}</option>
                    <option value="Brakes">{{ __('ui.categories.brakes') }}</option>
                    <option value="Interior">{{ __('ui.categories.interior') }}</option>
                </select>

                <select id="point-priority">
                    <option value="Low">{{ __('ui.categories.low') }}</option>
                    <option value="Medium">{{ __('ui.categories.medium') }}</option>
                    <option value="High">{{ __('ui.categories.high') }}</option>
                </select>

                <select id="point-status">
                    <option value="Open">{{ __('ui.categories.open') }}</option>
                    <option value="Watching">{{ __('ui.categories.watching') }}</option>
                    <option value="Fixed">{{ __('ui.categories.fixed') }}</option>
                </select>

                <textarea id="point-description" placeholder="{{ __('ui.inspection.description') }}"></textarea>

                <button id="save-point" type="button">{{ __('ui.inspection.save_point') }}</button>
            </div>
        </div>

    </div>

    <script>
        window.savedInspectionPoints = @json($points);
        window.maintenanceByCategory = @json($maintenances);
        window.csrfToken = "{{ csrf_token() }}";
        window.inspectionModelConfig = @json([
            'bodyType' => $bodyType,
            'customModelPath' => $customModelPath,
            'genericModelPath' => "/models/generic/{$bodyType}.glb",
            'fallbackStlPath' => "/models/generic/{$bodyType}.stl",
        ]);
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
