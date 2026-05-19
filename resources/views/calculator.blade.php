@extends('layout')

@section('content')
    @php
        $car = $currentCarProfile;
        $mods = $mods ?? collect();
        $plannedTotal = $mods->sum(fn ($mod) => (float) ($mod->price ?? 0));
        $installedTotal = $mods
            ->filter(fn ($mod) => ($mod->status ?? '') === 'Installed')
            ->sum(fn ($mod) => (float) ($mod->price ?? 0));
        $wantedTotal = $mods
            ->filter(fn ($mod) => ($mod->status ?? '') !== 'Installed')
            ->sum(fn ($mod) => (float) ($mod->price ?? 0));
        $highPriorityTotal = $mods
            ->filter(fn ($mod) => ($mod->priority ?? '') === 'High')
            ->sum(fn ($mod) => (float) ($mod->price ?? 0));
    @endphp

    <div class="page-head">
        <div>
            <p class="eyebrow">{{ __('ui.calculator.budget_control') }}</p>
            <h1>{{ __('ui.calculator.title') }}</h1>
            <p>{{ __('ui.calculator.intro', ['car' => $car->make.' '.$car->model]) }}</p>
        </div>

        <div class="mini-spec-card">
            <span>{{ __('ui.calculator.planned_spend') }}</span>
            <strong>€{{ number_format($plannedTotal, 2) }}</strong>
            <small>{{ __('ui.calculator.based_on_mods') }}</small>
        </div>
    </div>

    @if (!empty($dbError))
        <div class="alert-card">
            <strong>{{ __('ui.common.database_error') }}</strong>
            <p>{{ $dbError }}</p>
        </div>
    @endif

    <div class="dashboard-grid budget-grid">
        <div class="card metric-card">
            <span>{{ __('ui.calculator.installed') }}</span>
            <strong>€{{ number_format($installedTotal, 2) }}</strong>
            <p>{{ __('ui.calculator.installed_copy') }}</p>
        </div>

        <div class="card metric-card">
            <span>{{ __('ui.calculator.planned') }}</span>
            <strong>€{{ number_format($wantedTotal, 2) }}</strong>
            <p>{{ __('ui.calculator.planned_copy') }}</p>
        </div>

        <div class="card metric-card">
            <span>{{ __('ui.calculator.high_priority') }}</span>
            <strong>€{{ number_format($highPriorityTotal, 2) }}</strong>
            <p>{{ __('ui.calculator.high_priority_copy') }}</p>
        </div>
    </div>

    <section class="panel">
        <div class="panel-title">
            <div>
                <p class="eyebrow">{{ __('ui.calculator.rollup') }}</p>
                <h2>{{ __('ui.calculator.items') }}</h2>
            </div>
        </div>

        <div class="budget-list">
            @forelse ($mods as $mod)
                <article class="budget-row">
                    <div>
                        <h3>{{ $mod->name }}</h3>
                        <p>{{ $mod->category ?? __('ui.common.no_category') }} • {{ $mod->priority ?? __('ui.common.no_priority') }} • {{ $mod->status ?? __('ui.common.wanted') }}</p>
                    </div>

                    <strong>€{{ number_format((float) ($mod->price ?? 0), 2) }}</strong>
                </article>
            @empty
                <div class="calculator-empty">
                    <div>
                        <p class="eyebrow">{{ __('ui.calculator.no_costs') }}</p>
                        <h2>{{ __('ui.calculator.activate') }}</h2>
                        <p>{{ __('ui.calculator.auto_total') }}</p>
                    </div>
                </div>
            @endforelse
        </div>
    </section>

@endsection
