@extends('layout')

@section('content')

    @if (!empty($dbError))
        <div class="card error-card">
            <h2>Database Error</h2>
            <p>{{ $dbError }}</p>
        </div>
    @endif

    @if (session('error'))
        <div class="card error-card">
            <h2>Save/Delete Error</h2>
            <p>{{ session('error') }}</p>
        </div>
    @endif

    <div class="card">
        <h2>Add Maintenance Entry</h2>

        <form action="/maintenance" method="POST">
            @csrf

            <label>Title</label>
            <input type="text" name="title" required>

            <label>Category</label>
            <select name="category">
                <option value="Body">Body</option>
                <option value="Rust">Rust</option>
                <option value="Under Hood">Under Hood</option>
                <option value="Fuel System">Fuel System</option>
                <option value="Exhaust">Exhaust</option>
                <option value="Suspension">Suspension</option>
                <option value="Brakes">Brakes</option>
                <option value="Interior">Interior</option>
            </select>

            <label>Mileage</label>
            <input type="number" name="mileage">

            <label>Cost (€)</label>
            <input type="number" step="0.01" name="cost">

            <label>Date</label>
            <input type="date" name="service_date">

            <label>Next Due Date</label>
            <input type="date" name="next_due_date">

            <label>Next Due Mileage</label>
            <input type="number" name="next_due_mileage">

            <label>Notes</label>
            <textarea name="notes"></textarea>

            <button type="submit">Save Entry</button>
        </form>
    </div>

    <div class="card">
        <h2>Maintenance History</h2>

        @forelse ($maintenances as $maintenance)
            <div class="entry">
                <h3>{{ $maintenance->title }}</h3>

                <p><strong>Category:</strong> {{ $maintenance->category ?? 'N/A' }}</p>
                <p><strong>Mileage:</strong> {{ $maintenance->mileage ?? 'N/A' }} km</p>
                <p><strong>Cost:</strong> €{{ $maintenance->cost ?? '0.00' }}</p>
                <p><strong>Date:</strong> {{ $maintenance->service_date ?? 'No date' }}</p>

                @if ($maintenance->next_due_date || $maintenance->next_due_mileage)
                    <p class="reminder-text">
                        <strong>Reminder:</strong>
                        {{ $maintenance->next_due_date ?? 'No date set' }}
                        @if ($maintenance->next_due_mileage)
                            / {{ $maintenance->next_due_mileage }} km
                        @endif
                    </p>
                @endif

                <p>{{ $maintenance->notes }}</p>

                <form
                    action="/maintenance/{{ $maintenance->id }}"
                    method="POST"
                    onsubmit="return confirm('Delete this maintenance entry?');"
                >
                    @csrf
                    @method('DELETE')

                    <button type="submit" class="delete-btn">Delete</button>
                </form>
            </div>
        @empty
            <p>No maintenance entries yet.</p>
        @endforelse
    </div>

@endsection
