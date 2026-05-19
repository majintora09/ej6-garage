@extends('layout')

@section('content')
    @php
        $car = $carProfile ?? $currentCarProfile;
        $carName = trim((($car?->year) ? $car->year.' ' : '').($car?->make ?? __('ui.mods.your_car')).' '.($car?->model ?? ''));
        $carDetails = collect([$car?->engine, $car?->color_name, $car?->build_vibe])->filter()->join(' • ');
        $uiText = [
            'yourCar' => __('ui.mods.your_car'),
            'engineNotSet' => __('ui.mods.engine_not_set'),
            'colorNotSet' => __('ui.mods.color_not_set'),
            'interiorNotSet' => __('ui.mods.interior_not_set'),
            'personalBuild' => __('ui.mods.personal_build'),
            'selectMode' => __('ui.mods.select_mode'),
            'alreadyPlanned' => __('ui.mods.already_planned'),
        ];
    @endphp

    <div class="page-head">
        <div>
            <p class="eyebrow">{{ __('ui.mods.build_system') }}</p>
            <h1>{{ __('ui.mods.title') }}</h1>
            <p>{{ __('ui.mods.intro') }}</p>
        </div>

        <div class="mini-spec-card">
            <span>{{ __('ui.mods.car') }}</span>
            <strong>{{ $carName }}</strong>
            <small>{{ $carDetails }}</small>
        </div>
    </div>

    @if (!empty($dbError))
        <div class="alert-card">
            <strong>{{ __('ui.common.database_error') }}</strong>
            <p>{{ $dbError }}</p>
        </div>
    @endif

    @if (session('error'))
        <div class="alert-card">
            <strong>{{ __('ui.common.save_delete_error') }}</strong>
            <p>{{ session('error') }}</p>
        </div>
    @endif

    <div class="mods-layout">

        <section class="panel ai-panel">
            <div class="panel-title">
                <div>
                    <p class="eyebrow">{{ __('ui.mods.smart_assistant') }}</p>
                    <h2>{{ __('ui.mods.advisor') }}</h2>
                </div>
            </div>

            <p class="muted">
                {{ __('ui.mods.placeholder') }}
            </p>

            <div class="ai-controls">
                <button type="button" onclick="generateBuildAdvice('priority')">{{ __('ui.mods.priority_plan') }}</button>
                <button type="button" onclick="generateBuildAdvice('reliability')">{{ __('ui.mods.reliability') }}</button>
                <button type="button" onclick="generateBuildAdvice('visual')">{{ __('ui.mods.visual_style') }}</button>
                <button type="button" onclick="generateBuildAdvice('performance')">{{ __('ui.mods.performance') }}</button>
                <button type="button" onclick="generateBuildAdvice('budget')">{{ __('ui.mods.budget_route') }}</button>
            </div>

            <div id="ai-output" class="ai-output">
                {{ __('ui.mods.select_mode') }}
            </div>
        </section>

        <section class="panel">
            <div class="panel-title">
                <div>
                    <p class="eyebrow">{{ __('ui.mods.add_part') }}</p>
                    <h2>{{ __('ui.mods.new_mod') }}</h2>
                </div>
            </div>

            <form action="/mods" method="POST">
                @csrf

                <label>{{ __('ui.mods.mod_name') }}</label>
                <input type="text" name="name" placeholder="Example: EK front lip" required>

                <label>{{ __('ui.mods.category') }}</label>
                <input type="text" name="category" placeholder="Rust, Exhaust, Suspension, Wheels...">

                <label>{{ __('ui.mods.price') }}</label>
                <input type="number" step="0.01" name="price">

                <label>{{ __('ui.mods.priority') }}</label>
                <select name="priority">
                    <option value="Low">{{ __('ui.categories.low') }}</option>
                    <option value="Medium">{{ __('ui.categories.medium') }}</option>
                    <option value="High">{{ __('ui.categories.high') }}</option>
                </select>

                <label>{{ __('ui.mods.status') }}</label>
                <select name="status">
                    <option value="Wanted">{{ __('ui.common.wanted') }}</option>
                    <option value="Ordered">{{ __('ui.categories.ordered') }}</option>
                    <option value="Installed">{{ __('ui.categories.installed') }}</option>
                </select>

                <label>{{ __('ui.mods.link') }}</label>
                <input type="text" name="link" placeholder="Part link">

                <label>{{ __('ui.mods.notes') }}</label>
                <textarea name="notes" placeholder="Why this part? Fitment? Install notes?"></textarea>

                <button type="submit">{{ __('ui.mods.save_mod') }}</button>
            </form>
        </section>

    </div>

    <section class="panel">
        <div class="panel-title">
            <div>
                <p class="eyebrow">{{ __('ui.mods.wishlist') }}</p>
                <h2>{{ __('ui.mods.current_build_list') }}</h2>
            </div>

            @php $totalCost = 0; @endphp

            @foreach ($mods as $mod)
                @php $totalCost += $mod->price ?? 0; @endphp
            @endforeach

            <div class="price-pill">
                €{{ number_format($totalCost, 2) }}
            </div>
        </div>

        <div class="mod-list">
            @forelse ($mods as $mod)
                <article class="mod-item">
                    <div class="mod-main">
                        <div>
                            <h3>{{ $mod->name }}</h3>
                            <p>{{ $mod->notes ?: __('ui.common.no_notes') }}</p>
                        </div>

                        <span class="status-badge">{{ $mod->status ?? __('ui.common.wanted') }}</span>
                    </div>

                    <div class="mod-meta">
                        <span>{{ $mod->category ?? __('ui.common.no_category') }}</span>
                        <span>€{{ $mod->price ?? '0.00' }}</span>
                        <span>{{ $mod->priority ?? __('ui.common.no_priority') }}</span>
                    </div>

                    <div class="mod-actions">
                        @if ($mod->link)
                            <a href="{{ $mod->link }}" target="_blank">{{ __('ui.mods.open_part') }}</a>
                        @endif

                        <form action="/mods/{{ $mod->id }}" method="POST" onsubmit="return confirm('{{ __('ui.mods.confirm_delete') }}');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="danger-btn">{{ __('ui.common.delete') }}</button>
                        </form>
                    </div>
                </article>
            @empty
                <p class="muted">{{ __('ui.mods.no_mods') }}</p>
            @endforelse
        </div>
    </section>

    <script>
        window.currentMods = @json($mods);
        window.carProfile = @json($car ?? null);
        window.uiText = @json($uiText);
    </script>

    <script src="{{ asset('js/mods.js') }}"></script>

@endsection
