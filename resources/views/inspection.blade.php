@extends('layout')

@section('content')
    @php
        $car = $carProfile ?? $currentCarProfile;
        $carName = trim((($car?->year) ? $car->year.' ' : '').($car?->make ?? __('ui.mods.your_car')).' '.($car?->model ?? ''));
        $bodyType = $carProfile->body_type ?? 'coupe';
        $customModelPath = $carProfile->model_path ? '/'.ltrim($carProfile->model_path, '/') : null;
        $inspectionModelConfig = [
            'bodyType' => $bodyType,
            'customModelPath' => $customModelPath,
            'genericModelPath' => "/models/generic/{$bodyType}.glb",
            'fallbackStlPath' => "/models/generic/{$bodyType}.stl",
        ];
        $inspectionUiText = [
            'customModelLoaded' => __('ui.inspection.custom_model_loaded'),
            'genericModelLoaded' => __('ui.inspection.generic_model_loaded', ['type' => ':type']),
            'placeholderLoaded' => __('ui.inspection.placeholder_loaded'),
            'editorOn' => __('ui.inspection.editor_on'),
            'editorOff' => __('ui.inspection.editor_off'),
            'pointReadyTitle' => __('ui.inspection.point_ready_title'),
            'pointReadyCopy' => __('ui.inspection.point_ready_copy'),
            'noPositionTitle' => __('ui.inspection.no_position_title'),
            'noPositionCopy' => __('ui.inspection.no_position_copy'),
            'saveFailedTitle' => __('ui.inspection.save_failed_title'),
            'saveFailedCopy' => __('ui.inspection.save_failed_copy'),
            'categoryLabel' => __('ui.inspection.category_label'),
            'statusLabel' => __('ui.inspection.status_label'),
            'priorityLabel' => __('ui.inspection.priority_label'),
            'unsorted' => __('ui.common.unsorted'),
            'open' => __('ui.categories.open'),
            'medium' => __('ui.categories.medium'),
            'noNotes' => __('ui.common.no_notes'),
            'selectedFromList' => __('ui.inspection.selected_from_list'),
            'resetPosition' => __('ui.inspection.reset_position'),
            'confirmDeletePoint' => __('ui.inspection.confirm_delete_point'),
            'delete' => __('ui.common.delete'),
        ];
        $selectedPointId = request('point');
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

            <div class="inspection-point-list">
                <div class="panel-title compact-title">
                    <div>
                        <p class="eyebrow">{{ __('ui.inspection.saved_points') }}</p>
                        <h3>{{ __('ui.inspection.point_management') }}</h3>
                    </div>
                </div>

                @forelse ($points as $point)
                    <article class="inspection-point-row" id="inspection-point-{{ $point->id }}" data-inspection-point-row="{{ $point->id }}">
                        <button type="button" class="inspection-point-select" data-inspection-select="{{ $point->id }}">
                            <strong>{{ $point->name }}</strong>
                            <span>{{ $point->category ?: __('ui.common.unsorted') }} • {{ $point->status ?: __('ui.categories.open') }} • {{ $point->priority ?: __('ui.categories.medium') }}</span>
                        </button>

                        <details class="inspection-point-edit">
                            <summary>{{ __('ui.cars.edit') }}</summary>
                            <form action="{{ route('inspection-points.update', $point) }}" method="POST" class="setup-form">
                                @csrf
                                @method('PUT')

                                <input type="hidden" name="x" value="{{ $point->x }}">
                                <input type="hidden" name="y" value="{{ $point->y }}">
                                <input type="hidden" name="z" value="{{ $point->z }}">
                                <input type="hidden" name="normalized_x" value="{{ $point->normalized_x }}">
                                <input type="hidden" name="normalized_y" value="{{ $point->normalized_y }}">
                                <input type="hidden" name="normalized_z" value="{{ $point->normalized_z }}">

                                <label>{{ __('ui.inspection.point_name') }}</label>
                                <input name="name" value="{{ old('name', $point->name) }}" required>

                                <div class="form-grid">
                                    <div>
                                        <label>{{ __('ui.inspection.category_label') }}</label>
                                        <input name="category" value="{{ old('category', $point->category) }}">
                                    </div>
                                    <div>
                                        <label>{{ __('ui.inspection.status_label') }}</label>
                                        <input name="status" value="{{ old('status', $point->status) }}">
                                    </div>
                                    <div>
                                        <label>{{ __('ui.inspection.priority_label') }}</label>
                                        <input name="priority" value="{{ old('priority', $point->priority) }}">
                                    </div>
                                </div>

                                <label>{{ __('ui.inspection.description') }}</label>
                                <textarea name="description">{{ old('description', $point->description) }}</textarea>

                                <button type="submit">{{ __('ui.inspection.update_point') }}</button>
                            </form>
                        </details>

                        <div class="inspection-point-actions">
                            <form action="{{ route('inspection-points.reset-position', $point) }}" method="POST">
                                @csrf
                                <button type="submit" class="ghost-inline-button">{{ __('ui.inspection.reset_position') }}</button>
                            </form>

                            <form action="{{ route('inspection-points.destroy', $point) }}" method="POST" onsubmit="return confirm('{{ __('ui.inspection.confirm_delete_point') }}');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="danger-btn">{{ __('ui.common.delete') }}</button>
                            </form>
                        </div>
                    </article>
                @empty
                    <div class="empty-state">
                        <strong>{{ __('ui.inspection.no_points_title') }}</strong>
                        <p>{{ __('ui.inspection.no_points_copy') }}</p>
                    </div>
                @endforelse
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
        window.inspectionModelConfig = @json($inspectionModelConfig);
        window.inspectionUiText = @json($inspectionUiText);
        window.selectedInspectionPointId = @json($selectedPointId);
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
