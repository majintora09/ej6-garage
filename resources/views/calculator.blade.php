@extends('layout')

@section('content')
    @php
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
            <p class="eyebrow">BUDGET CONTROL</p>
            <h1>Price Calculator</h1>
            <p>Connected to your mods planner so the build spend stays visible beside the maintenance-first route.</p>
        </div>

        <div class="mini-spec-card">
            <span>Current planned spend</span>
            <strong>€{{ number_format($plannedTotal, 2) }}</strong>
            <small>Based on saved mods with prices.</small>
        </div>
    </div>

    @if (!empty($dbError))
        <div class="alert-card">
            <strong>Database Error</strong>
            <p>{{ $dbError }}</p>
        </div>
    @endif

    <div class="dashboard-grid budget-grid">
        <div class="card metric-card">
            <span>Installed</span>
            <strong>€{{ number_format($installedTotal, 2) }}</strong>
            <p>Already fitted or marked complete.</p>
        </div>

        <div class="card metric-card">
            <span>Still Planned</span>
            <strong>€{{ number_format($wantedTotal, 2) }}</strong>
            <p>Wanted or ordered items still ahead.</p>
        </div>

        <div class="card metric-card">
            <span>High Priority</span>
            <strong>€{{ number_format($highPriorityTotal, 2) }}</strong>
            <p>Safety, reliability and urgent build items.</p>
        </div>
    </div>

    <section class="panel">
        <div class="panel-title">
            <div>
                <p class="eyebrow">MOD COST ROLLUP</p>
                <h2>Build Budget Items</h2>
            </div>
        </div>

        <div class="budget-list">
            @forelse ($mods as $mod)
                <article class="budget-row">
                    <div>
                        <h3>{{ $mod->name }}</h3>
                        <p>{{ $mod->category ?? 'No category' }} • {{ $mod->priority ?? 'No priority' }} • {{ $mod->status ?? 'Wanted' }}</p>
                    </div>

                    <strong>€{{ number_format((float) ($mod->price ?? 0), 2) }}</strong>
                </article>
            @empty
                <div class="calculator-empty">
                    <div>
                        <p class="eyebrow">NO MOD COSTS YET</p>
                        <h2>Add mods to activate the calculator</h2>
                        <p>Once the mods database is saving, this page will total your parts automatically.</p>
                    </div>
                </div>
            @endforelse
        </div>
    </section>

@endsection
