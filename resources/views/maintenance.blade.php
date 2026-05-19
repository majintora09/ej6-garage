@extends('layout')

@section('content')
    @php
        $car = $currentCarProfile;
        $carName = trim(($car->year ? $car->year.' ' : '').$car->make.' '.$car->model);
        $buildVibe = $car->build_vibe ?: __('ui.common.personal_garage_build_profile');
    @endphp

    <div class="page-head">
        <div>
            <p class="eyebrow">{{ __('ui.maintenance.service_log') }}</p>
            <h1>{{ __('ui.maintenance.title') }}</h1>
            <p>{{ __('ui.maintenance.intro', ['car' => $carName]) }}</p>
        </div>

        <div class="mini-spec-card">
            <span>{{ __('ui.maintenance.priority') }}</span>
            <strong>{{ __('ui.maintenance.priority_copy') }}</strong>
            <small>{{ $buildVibe }}</small>
        </div>
    </div>

    @if (!empty($dbError))
        <div class="card error-card">
            <h2>{{ __('ui.common.database_error') }}</h2>
            <p>{{ $dbError }}</p>
        </div>
    @endif

    @if (session('error'))
        <div class="card error-card">
            <h2>{{ __('ui.common.save_delete_error') }}</h2>
            <p>{{ session('error') }}</p>
        </div>
    @endif

    <div class="mods-layout">
        <section class="panel">
            <div class="panel-title">
                <div>
                    <p class="eyebrow">{{ __('ui.maintenance.new_record') }}</p>
                    <h2>{{ __('ui.maintenance.add_entry') }}</h2>
                </div>
            </div>

            <form action="/maintenance" method="POST">
                @csrf

                <label>{{ __('ui.maintenance.field_title') }}</label>
                <input type="text" name="title" required>

                <label>{{ __('ui.maintenance.category') }}</label>
                <select name="category">
                    <option value="Body">{{ __('ui.categories.body') }}</option>
                    <option value="Rust">{{ __('ui.categories.rust') }}</option>
                    <option value="Under Hood">{{ __('ui.categories.under_hood') }}</option>
                    <option value="Fuel System">{{ __('ui.categories.fuel_system') }}</option>
                    <option value="Exhaust">{{ __('ui.categories.exhaust') }}</option>
                    <option value="Suspension">{{ __('ui.categories.suspension') }}</option>
                    <option value="Brakes">{{ __('ui.categories.brakes') }}</option>
                    <option value="Interior">{{ __('ui.categories.interior') }}</option>
                </select>

                <label>{{ __('ui.maintenance.mileage') }}</label>
                <input type="number" name="mileage">

                <label>{{ __('ui.maintenance.cost') }}</label>
                <input type="number" step="0.01" name="cost">

                <label>{{ __('ui.maintenance.date') }}</label>
                <input type="date" name="service_date">

                <label>{{ __('ui.maintenance.next_due_date') }}</label>
                <input type="date" name="next_due_date">

                <label>{{ __('ui.maintenance.next_due_mileage') }}</label>
                <input type="number" name="next_due_mileage">

                <label>{{ __('ui.maintenance.notes') }}</label>
                <textarea name="notes"></textarea>

                <button type="submit">{{ __('ui.maintenance.save_entry') }}</button>
            </form>
        </section>

        <section class="panel">
            <div class="panel-title">
                <div>
                    <p class="eyebrow">{{ __('ui.maintenance.timeline') }}</p>
                    <h2>{{ __('ui.maintenance.history') }}</h2>
                </div>
            </div>

            @forelse ($maintenances as $maintenance)
                <div class="entry">
                    <h3>{{ $maintenance->title }}</h3>

                    <p><strong>{{ __('ui.maintenance.category') }}:</strong> {{ $maintenance->category ?? __('ui.common.na') }}</p>
                    <p><strong>{{ __('ui.maintenance.mileage') }}:</strong> {{ $maintenance->mileage ?? __('ui.common.na') }} km</p>
                    <p><strong>{{ __('ui.maintenance.cost') }}:</strong> €{{ $maintenance->cost ?? '0.00' }}</p>
                    <p><strong>{{ __('ui.maintenance.date') }}:</strong> {{ $maintenance->service_date ?? __('ui.common.no_date') }}</p>

                    @if ($maintenance->next_due_date || $maintenance->next_due_mileage)
                        <p class="reminder-text">
                            <strong>{{ __('ui.maintenance.reminder') }}:</strong>
                            {{ $maintenance->next_due_date ?? __('ui.common.no_date') }}
                            @if ($maintenance->next_due_mileage)
                                / {{ $maintenance->next_due_mileage }} km
                            @endif
                        </p>
                    @endif

                    <p>{{ $maintenance->notes }}</p>

                    <form
                        action="/maintenance/{{ $maintenance->id }}"
                        method="POST"
                        onsubmit="return confirm('{{ __('ui.maintenance.confirm_delete') }}');"
                    >
                        @csrf
                        @method('DELETE')

                        <button type="submit" class="delete-btn">{{ __('ui.common.delete') }}</button>
                    </form>
                </div>
            @empty
                <div class="gallery-empty">
                    <p>{{ __('ui.maintenance.no_entries') }}</p>
                </div>
            @endforelse
        </section>
    </div>

@endsection
